<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Traspaso as TraspasoModel;
use App\Models\Reposicion;
use App\Models\Personal;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class Traspaso extends Component
{
    public $modal = false;
    public $modalError = false;
    public $mensajeError = '';
    public $accion = 'create';
    public $codigo;
    public $fecha_traspaso;
    public $detalleModal = false;
    public $traspasoSeleccionado = null;
    public $traspaso_id;
    public $origen_id;
    public $destino_id;
    public $cantidad;
    public $personal_id;
    public $observaciones;
    public $search = '';
    public $traspasos;
    public $reposicionesOrigen;
    public $reposicionesDestino;
    public $personals;
    public $confirmingDeleteId = null;

    public function mount()
    {
        $usuario = auth()->user();
        $this->traspasos = collect();
        $this->reposicionesOrigen = collect();
        $this->reposicionesDestino = collect();
        $this->personals = $usuario->rol_id == 1 ? Personal::all() : collect([$usuario->personal]);
        $this->personal_id = $usuario->personal->id ?? null;
        $this->cargarReposiciones($usuario);
    }

    private function cargarReposiciones($usuario)
    {
        $sucursalId = null;
        if ($usuario->rol_id != 1) {
            $trabajo = $usuario->personal->trabajos()->where('estado', 1)->first();
            $sucursalId = $trabajo ? $trabajo->sucursal_id : null;
        }

        // Traemos solo lotes con estado_revision = true
        $repos = Reposicion::with('existencia', 'comprobantes')
            ->where('estado_revision', true)
            ->get();

        $this->reposicionesOrigen = $repos->filter(function ($r) use ($usuario, $sucursalId) {
            return $usuario->rol_id == 1 || ($r->existencia && $r->existencia->sucursal_id == $sucursalId);
        })->groupBy('existencia_id')->map(function ($lotes) {
            $ex = $lotes->first()->existencia;
            return (object)[
                'existencia' => $ex,
                'totalDisponible' => $lotes->sum('cantidad'),
                'reposicionesIds' => $lotes->pluck('id')->toArray(),
            ];
        })->values();

        $this->reposicionesDestino = $repos->filter(function ($r) use ($usuario, $sucursalId) {
            return $usuario->rol_id == 1 || ($r->existencia && $r->existencia->sucursal_id != $sucursalId);
        })->groupBy('existencia_id')->map(function ($lotes) {
            $ex = $lotes->first()->existencia;
            return (object)[
                'existencia' => $ex,
                'totalDisponible' => $lotes->sum('cantidad'),
                'reposicionesIds' => $lotes->pluck('id')->toArray(),
            ];
        })->values();
    }

    public function abrirModal($accion, $id = null)
    {
        $this->accion = $accion;
        $this->modal = true;
        $usuario = auth()->user();
        $this->cargarReposiciones($usuario);

        if ($accion === 'create') {
            $this->codigo = 'T-' . now()->format('Ymd-His');
            $this->fecha_traspaso = now()->toDateTimeString();
            $this->cantidad = null;
            $this->origen_id = null;
            $this->destino_id = null;
            $this->personal_id = $usuario->personal->id ?? null;
            $this->observaciones = null;
        }

        if ($accion === 'edit' && $id) {
            $traspaso = TraspasoModel::with(['reposicionesOrigen' => fn($q) => $q->where('estado_revision', true)])->findOrFail($id);
            $this->traspaso_id = $traspaso->id;
            $this->codigo = $traspaso->codigo;
            $this->fecha_traspaso = $traspaso->fecha_traspaso;
            $this->cantidad = $traspaso->cantidad;
            $this->destino_id = $traspaso->reposicion_destino_id;
            $this->personal_id = $traspaso->personal_id;
            $this->observaciones = $traspaso->observaciones;

            // Incluir la reposicion origen asociada al traspaso aunque no tenga stock
            if ($traspaso->reposicionesOrigen->count()) {
                $this->origen_id = $traspaso->reposicionesOrigen->first()->id;
                $origenExistenciaId = $traspaso->reposicionesOrigen->first()->existencia_id;
                if (!$this->reposicionesOrigen->pluck('existencia.id')->contains($origenExistenciaId)) {
                    $this->reposicionesOrigen->push((object)[
                        'existencia' => $traspaso->reposicionesOrigen->first()->existencia,
                        'totalDisponible' => $traspaso->reposicionesOrigen->sum('pivot->cantidad'),
                        'reposicionesIds' => $traspaso->reposicionesOrigen->pluck('id')->toArray(),
                    ]);
                }
            }
        }
    }

    public function guardar()
    {
        $usuario = auth()->user();
        $personal = $usuario->personal;

        if (!$personal) {
            $this->mensajeError = "No estás asignado a un personal válido.";
            $this->modalError = true;
            return;
        }

        $validator = Validator::make([
            'origen_id' => $this->origen_id,
            'destino_id' => $this->destino_id,
            'cantidad' => $this->cantidad,
        ], [
            'origen_id' => 'required|exists:reposicions,id',
            'destino_id' => 'required|exists:reposicions,id|different:origen_id',
            'cantidad' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            $this->mensajeError = implode("\n", $validator->errors()->all());
            $this->modalError = true;
            return;
        }

        try {
            $origenPrincipal = Reposicion::where('estado_revision', true)->findOrFail($this->origen_id);
            $destino = Reposicion::where('estado_revision', true)->findOrFail($this->destino_id);

            $lotes = Reposicion::where('existencia_id', $origenPrincipal->existencia_id)
                ->where('cantidad', '>', 0)
                ->where('estado_revision', true)
                ->orderBy('fecha')
                ->get();

            $cantidadRestante = $this->cantidad;
            $reposicionesSeleccionadas = [];

            foreach ($lotes as $lote) {
                if ($cantidadRestante <= 0) break;
                $usar = min($lote->cantidad, $cantidadRestante);
                $reposicionesSeleccionadas[$lote->id] = ['obj' => $lote, 'cantidad' => $usar];
                $cantidadRestante -= $usar;
            }

            if ($cantidadRestante > 0) {
                $this->mensajeError = "No hay suficiente stock en los lotes de origen para cubrir la cantidad solicitada.";
                $this->modalError = true;
                return;
            }

            DB::transaction(function () use ($reposicionesSeleccionadas, $personal, $destino) {
                $totalTraspasado = array_sum(array_map(fn($r) => $r['cantidad'], $reposicionesSeleccionadas));

                $traspaso = TraspasoModel::create([
                    'codigo' => 'T-' . now()->format('YmdHis'),
                    'reposicion_destino_id' => $destino->id,
                    'personal_id' => $personal->id,
                    'cantidad' => $totalTraspasado,
                    'fecha_traspaso' => now(),
                    'observaciones' => 'Traspaso automático de múltiples lotes',
                ]);

                foreach ($reposicionesSeleccionadas as $id => $data) {
                    $lote = $data['obj'];
                    $usar = $data['cantidad'];

                    // Ajustamos cantidad en origen
                    $lote->cantidad -= $usar;
                    $lote->cantidad_inicial -= $usar;
                    $lote->save();

                    $traspaso->reposicionesOrigen()->attach($lote->id, ['cantidad' => $usar]);

                    // Trasladamos los comprobantes proporcionalmente
                    foreach ($lote->comprobantes as $comprobante) {
                        $proporcion = $usar / ($lote->cantidad + $usar); // cantidad movida / cantidad original
                        $montoTraslado = round($comprobante->monto * $proporcion, 2);

                        if ($montoTraslado > 0) {
                            // Reducimos monto en origen
                            $comprobante->monto -= $montoTraslado;
                            $comprobante->save();

                            // Creamos comprobante en destino
                            \App\Models\ComprobantePago::create([
                                'reposicion_id' => $destino->id,
                                'codigo' => $comprobante->codigo . '-T',
                                'monto' => $montoTraslado,
                                'fecha' => now(),
                                'observaciones' => 'Traspaso de lote ' . $lote->id,
                            ]);
                        }
                    }
                }

                // Ajustamos cantidad en destino
                $destino->cantidad += $totalTraspasado;
                $destino->cantidad_inicial += $totalTraspasado;
                $destino->save();
            });

            $this->cerrarModal();
            session()->flash('message', 'Traspaso guardado correctamente con comprobantes actualizados!');
        } catch (\Throwable $e) {
            $this->mensajeError = "Ocurrió un error: " . $e->getMessage();
            $this->modalError = true;
        }
    }


    public function eliminarTraspaso($id)
{
    $traspaso = TraspasoModel::with('reposicionesOrigen.comprobantes', 'reposicionDestino.comprobantes')->findOrFail($id);

    DB::transaction(function () use ($traspaso) {
        $destino = Reposicion::find($traspaso->reposicion_destino_id);

        foreach ($traspaso->reposicionesOrigen as $reposicion) {
            $cant = $reposicion->pivot->cantidad;
            $origen = Reposicion::find($reposicion->id);

            // Restaurar cantidades
            $origen->cantidad += $cant;
            $origen->cantidad_inicial += $cant;
            $origen->save();

            $destino->cantidad -= $cant;
            $destino->cantidad_inicial -= $cant;
            $destino->save();

            // Restaurar montos de todos los comprobantes divididos en destino
            $comprobantesDestino = $destino->comprobantes()
                ->where('observaciones', 'like', '%Traspaso de lote ' . $reposicion->id . '%')
                ->get();

            foreach ($comprobantesDestino as $comprobanteDestino) {
                $origenComprobante = $reposicion->comprobantes()->first();
                if ($origenComprobante) {
                    $origenComprobante->monto += $comprobanteDestino->monto;
                    $origenComprobante->save();
                }
                $comprobanteDestino->delete();
            }
        }

        // Limpiar la relación y eliminar el traspaso
        $traspaso->reposicionesOrigen()->detach();
        $traspaso->delete();
    });

    $this->traspasos = $this->traspasos->filter(fn($t) => $t->id != $id);
    session()->flash('message', 'Traspaso y comprobantes asociados eliminados correctamente y montos restaurados!');
}


    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset(['traspaso_id', 'origen_id', 'destino_id', 'cantidad', 'observaciones']);
    }

    public function render()
    {
        $usuario = auth()->user();
        $rol = $usuario->rol_id;

        $this->cargarReposiciones($usuario);

        $query = TraspasoModel::with([
            'personal',
            'reposicionesOrigen.existencia',
            'reposicionDestino.existencia',
        ]);

        if ($rol == 2) {
            $query->where('personal_id', $usuario->personal->id);
        }

        if ($this->search) {
            $query->where(fn($q) => $q->where('codigo', 'like', "%{$this->search}%"));
        }

        $this->traspasos = $query->latest()->get();
        return view('livewire.traspaso');
    }

    public function confirmarEliminar($id)
    {
        $this->confirmingDeleteId = $id;
    }
    public function eliminarTraspasoConfirmado()
    {
        if (!$this->confirmingDeleteId) return;
        $this->eliminarTraspaso($this->confirmingDeleteId);
        $this->confirmingDeleteId = null;
    }

    public function verDetalle($id)
    {
        $this->traspasoSeleccionado = TraspasoModel::with([
            'personal',
            'reposicionesOrigen.existencia.sucursal',
            'reposicionDestino.existencia.sucursal'
        ])->findOrFail($id);

        $this->modal = false;
        $this->modalError = false;
        $this->detalleModal = true;
    }
}

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
        $repos = Reposicion::with('existencia', 'comprobantes')->get();
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
        $this->cargarReposiciones(auth()->user());
        $this->personals = Personal::all();

        if ($accion === 'create') {
            $this->codigo = 'T-' . now()->format('Ymd-His');
            $this->fecha_traspaso = now()->toDateTimeString();
            $this->cantidad = null;
            $this->origen_id = null;
            $this->destino_id = null;
            $this->personal_id = null;
            $this->observaciones = null;
        }
        if ($accion === 'edit' && $id) {
            $traspaso = TraspasoModel::findOrFail($id);
            $this->traspaso_id = $traspaso->id;
            $this->codigo = $traspaso->codigo;
            $this->fecha_traspaso = $traspaso->fecha_traspaso;
            $this->cantidad = $traspaso->cantidad;
            $this->destino_id = $traspaso->reposicion_destino_id;
            $this->personal_id = $traspaso->personal_id;
            $this->observaciones = $traspaso->observaciones;
            $this->origen_id = optional($traspaso->reposicionesOrigen->first())->id;
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
            $origenPrincipal = Reposicion::findOrFail($this->origen_id);
            $destino = Reposicion::findOrFail($this->destino_id);
            $lotes = Reposicion::where('existencia_id', $origenPrincipal->existencia_id)
                ->where('cantidad', '>', 0)
                ->orderBy('fecha')
                ->get();
            $cantidadRestante = $this->cantidad;
            $reposicionesSeleccionadas = [];
            foreach ($lotes as $lote) {
                if ($cantidadRestante <= 0) break;

                $usar = min($lote->cantidad, $cantidadRestante);
                $reposicionesSeleccionadas[$lote->id] = [
                    'obj' => $lote,
                    'cantidad' => $usar,
                ];
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
                    'observaciones' => 'Traspaso automático de lotes',
                ]);
                foreach ($reposicionesSeleccionadas as $id => $data) {
                    $lote = $data['obj'];
                    $usar = $data['cantidad'];
                    $lote->cantidad -= $usar;
                    $lote->cantidad_inicial -= $usar;
                    $lote->save();
                    $destino->cantidad += $usar;
                    $destino->cantidad_inicial += $usar;
                    $destino->save();
                    $traspaso->reposicionesOrigen()->attach($lote->id, ['cantidad' => $usar]);
                }
            });

            $this->cerrarModal();
            session()->flash('message', 'Traspaso guardado correctamente!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->mensajeError = "Error: no se encontró uno de los registros requeridos.";
            $this->modalError = true;
        } catch (\Throwable $e) {
            $this->mensajeError = "Ocurrió un error inesperado al guardar el traspaso: " . $e->getMessage();
            $this->modalError = true;
        }
    }



    public function eliminarTraspaso($id)
    {
        $traspaso = TraspasoModel::findOrFail($id);

        DB::transaction(function () use ($traspaso) {
            foreach ($traspaso->reposicionesOrigen as $reposicion) {
                $cant = $reposicion->pivot->cantidad;
                $origen = Reposicion::find($reposicion->id);
                $origen->cantidad += $cant;
                $origen->cantidad_inicial += $cant;
                $origen->save();

                $destino = Reposicion::find($traspaso->reposicion_destino_id);
                $destino->cantidad -= $cant;
                $destino->cantidad_inicial -= $cant;
                $destino->save();

                $comprobanteDestino = $destino->comprobantes()
                    ->where('observaciones', 'like', '%Generado automáticamente por traspaso%')
                    ->first();

                $comprobanteOrigen = $origen->comprobantes->first();
                if ($comprobanteOrigen && $comprobanteDestino) {
                    $comprobanteOrigen->monto += $comprobanteDestino->monto;
                    $comprobanteOrigen->save();
                    $comprobanteDestino->delete();
                }
            }

            $traspaso->reposicionesOrigen()->detach();
            $traspaso->delete();
        });

        $this->traspasos = $this->traspasos->filter(fn($t) => $t->id != $id);
        session()->flash('message', 'Traspaso y comprobantes asociados eliminados correctamente!');
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'traspaso_id',
            'origen_id',
            'destino_id',
            'cantidad',
            'observaciones',
        ]);
    }

    public function render()
    {
        $usuario = auth()->user();
        $rol = $usuario->rol_id;
        $sucursalId = null;
        if ($rol == 2) {
            $trabajo = $usuario->personal->trabajos()->where('estado', 1)->first();
            $sucursalId = $trabajo ? $trabajo->sucursal_id : null;
        }

        $this->cargarReposiciones($usuario);

        $query = TraspasoModel::with([
            'personal',
            'reposicionesOrigen.existencia',
            'reposicionesOrigen.comprobantes',
            'reposicionDestino.existencia',
            'reposicionDestino.comprobantes',
        ]);

        if ($rol == 2 && $sucursalId) {
            $query->where('personal_id', $usuario->personal->id);
        }

        if ($this->search) {
            $query->where(
                fn($q) =>
                $q->where('codigo', 'like', "%{$this->search}%")
                    ->orWhere('observaciones', 'like', "%{$this->search}%")
            );
        }

        $this->traspasos = $query->latest()->get();
        $this->personals = $rol == 1 ? Personal::all() : collect([$usuario->personal]);

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

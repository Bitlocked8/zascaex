<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Llenado;
use App\Models\Asignado;
use App\Models\ComprobantePago;
use App\Models\Reposicion;
use App\Models\Existencia;
use Illuminate\Support\Facades\DB;

class Llenados extends Component
{
    public $filtroSucursalElemento = null;
    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $llenado_id = null;

    public $asignado_base_id;
    public $asignado_tapa_id;
    public $existencia_destino_id;
    public $cantidad;
    public $merma_base = 0;
    public $merma_tapa = 0;
    public $estado = 0;
    public $observaciones = '';
    public $fecha;
    public $codigo;
    public $accion = 'create';
    public $confirmingDeleteLlenadoId = null;
    public $llenadoSeleccionado = null;
    public function filtrarSucursalElemento($sucursalId)
    {
        $this->filtroSucursalElemento = $sucursalId;
    }

    protected $rules = [
        'asignado_base_id' => 'required|exists:asignados,id',
        'asignado_tapa_id' => 'required|exists:asignados,id',
        'existencia_destino_id' => 'required|exists:existencias,id',
        'cantidad' => 'nullable|numeric|min:0',
        'estado' => 'required|in:0,1,2',
        'observaciones' => 'nullable|string|max:500',
    ];

    public function render()
    {

        $llenados = Llenado::with([
            'asignadoBase.existencia.existenciable',
            'asignadoTapa.existencia.existenciable',
            'existenciaDestino.existenciable',
            'reposicion'
        ])
            ->when(
                $this->search,
                fn($q) =>
                $q->where('codigo', 'like', "%{$this->search}%")
                    ->orWhereHas(
                        'existenciaDestino.existenciable',
                        fn($q) =>
                        $q->where('nombre', 'like', "%{$this->search}%")
                    )
            )
            ->get();

        $asignacionesUsadasBase = Llenado::pluck('asignado_base_id')->toArray();
        $asignacionesUsadasTapa = Llenado::pluck('asignado_tapa_id')->toArray();
        if ($this->llenado_id) {
            $llenadoActual = Llenado::find($this->llenado_id);
            if ($llenadoActual) {
                $asignacionesUsadasBase = array_diff($asignacionesUsadasBase, [$llenadoActual->asignado_base_id]);
                $asignacionesUsadasTapa = array_diff($asignacionesUsadasTapa, [$llenadoActual->asignado_tapa_id]);
            }
        }
        $asignacionesBase = Asignado::with('existencia.existenciable', 'existencia.sucursal')
            ->whereHas('existencia', function ($q) {
                $q->where('existenciable_type', \App\Models\Base::class);
                if ($this->filtroSucursalElemento) {
                    $q->where('sucursal_id', $this->filtroSucursalElemento);
                }
            })
            ->whereNotIn('id', $asignacionesUsadasBase)
            ->get();

        $asignacionesTapa = Asignado::with('existencia.existenciable', 'existencia.sucursal')
            ->whereHas('existencia', function ($q) {
                $q->where('existenciable_type', \App\Models\Tapa::class);
                if ($this->filtroSucursalElemento) {
                    $q->where('sucursal_id', $this->filtroSucursalElemento);
                }
            })
            ->whereNotIn('id', $asignacionesUsadasTapa)
            ->get();



        $existenciasDestino = Existencia::with('existenciable', 'sucursal')
            ->where('existenciable_type', \App\Models\Producto::class)
            ->when($this->filtroSucursalElemento, fn($q) => $q->where('sucursal_id', $this->filtroSucursalElemento))
            ->get();
        $sucursales = \App\Models\Sucursal::orderBy('nombre')->get();


        $personales = \App\Models\Personal::orderBy('nombres')->get();

        return view('livewire.llenados', compact(
            'llenados',
            'asignacionesBase',
            'asignacionesTapa',
            'existenciasDestino',
            'personales',
            'sucursales',
        ));
    }


    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'llenado_id',
            'asignado_base_id',
            'asignado_tapa_id',
            'existencia_destino_id',
            'cantidad',
            'merma_base',
            'merma_tapa',
            'estado',
            'observaciones',
            'fecha',
            'codigo',
            'llenadoSeleccionado'
        ]);

        $this->accion = $accion;

        if ($accion === 'create') {
            $this->codigo = $this->generarCodigo('L');
            $this->fecha = now();
        }

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }
        $this->modal = true;
    }

    protected function generarCodigo($prefijo)
    {
        $fechaHoy = now()->format('Ymd');
        $ultimoCodigo = Llenado::whereDate('created_at', now()->toDateString())
            ->latest('id')
            ->value('codigo');
        $contador = 1;
        if ($ultimoCodigo) {
            $partes = explode('-', $ultimoCodigo);
            $ultimoContador = intval(end($partes));
            $contador = $ultimoContador + 1;
        }
        $codigo = $prefijo . '-' . $fechaHoy . '-' . str_pad($contador, 3, '0', STR_PAD_LEFT);
        while (Llenado::where('codigo', $codigo)->exists()) {
            $contador++;
            $codigo = $prefijo . '-' . $fechaHoy . '-' . str_pad($contador, 3, '0', STR_PAD_LEFT);
        }

        return $codigo;
    }


    public function editar($id)
    {
        $llenado = Llenado::findOrFail($id);

        $this->llenado_id = $llenado->id;
        $this->asignado_base_id = $llenado->asignado_base_id;
        $this->asignado_tapa_id = $llenado->asignado_tapa_id;
        $this->existencia_destino_id = $llenado->existencia_id;
        $this->cantidad = $llenado->cantidad;
        $this->merma_base = $llenado->merma_base;
        $this->merma_tapa = $llenado->merma_tapa;
        $this->estado = $llenado->estado;
        $this->observaciones = $llenado->observaciones;
        $this->fecha = $llenado->fecha;
        $this->codigo = $llenado->codigo;
        $this->llenadoSeleccionado = $llenado;
    }

    public function guardar()
    {
        $this->validate();

        $usuario = auth()->user()->load('personal');
        if (!$usuario->personal) {
            $this->addError('personal_id', 'El usuario no tiene personal asignado.');
            return;
        }

        $personalId = $usuario->personal->id;
        $cantidad = $this->cantidad ?? 0;

        $asignadoBase = Asignado::findOrFail($this->asignado_base_id);
        $asignadoTapa = Asignado::findOrFail($this->asignado_tapa_id);
        $existenciaDestino = Existencia::findOrFail($this->existencia_destino_id);

        DB::transaction(function () use ($asignadoBase, $asignadoTapa, $existenciaDestino, $personalId, $cantidad) {
            $this->merma_base = max($asignadoBase->cantidad - $cantidad, 0);
            $this->merma_tapa = max($asignadoTapa->cantidad - $cantidad, 0);
            if ($this->estado == 2 && $cantidad > min($asignadoBase->cantidad, $asignadoTapa->cantidad)) {
                throw new \Exception("La cantidad producida no puede ser mayor que las asignadas.");
            }

            if ($this->accion === 'create') {
                $reposicionDestino = $this->crearReposicion($existenciaDestino, $personalId);

                $llenado = Llenado::create([
                    'codigo' => $this->codigo ?? $this->generarCodigo('L'),
                    'asignado_base_id' => $asignadoBase->id,
                    'asignado_tapa_id' => $asignadoTapa->id,
                    'existencia_id' => $existenciaDestino->id,
                    'cantidad' => $cantidad,
                    'merma_base' => $this->merma_base,
                    'merma_tapa' => $this->merma_tapa,
                    'estado' => $this->estado,
                    'observaciones' => $this->observaciones,
                    'fecha' => now(),
                    'personal_id' => $personalId,
                    'reposicion_id' => $reposicionDestino->id,
                ]);
            } else {
                $llenado = Llenado::findOrFail($this->llenado_id);
                $reposicionDestino = $llenado->reposicion;
                if ($llenado->estado == 2) {
                    $llenado->asignadoBase->cantidad += ($llenado->cantidad + $llenado->merma_base);
                    $llenado->asignadoTapa->cantidad += ($llenado->cantidad + $llenado->merma_tapa);
                    $llenado->existencia->cantidad -= $llenado->cantidad;
                    $llenado->asignadoBase->save();
                    $llenado->asignadoTapa->save();
                    $llenado->existencia->save();
                }
                $llenado->update([
                    'asignado_base_id' => $asignadoBase->id,
                    'asignado_tapa_id' => $asignadoTapa->id,
                    'existencia_id' => $existenciaDestino->id,
                    'cantidad' => $cantidad,
                    'merma_base' => $this->merma_base,
                    'merma_tapa' => $this->merma_tapa,
                    'estado' => $this->estado,
                    'observaciones' => $this->observaciones,
                    'fecha' => now(),
                ]);
            }
            if ($this->estado == 2 && $cantidad > 0) {
                $asignadoBase->cantidad -= ($cantidad + $this->merma_base);
                $asignadoTapa->cantidad -= ($cantidad + $this->merma_tapa);
                $asignadoBase->save();
                $asignadoTapa->save();
                $existenciaDestino->cantidad += $cantidad;
                $existenciaDestino->save();
                $reposicionDestino->update([
                    'cantidad' => $cantidad,
                    'cantidad_inicial' => $cantidad,
                    'estado_revision' => true,
                ]);
                $this->crearComprobante($reposicionDestino, $asignadoBase, $cantidad);
            }
            if ($this->estado != 2) {
                $reposicionDestino->update([
                    'cantidad' => 0,
                    'cantidad_inicial' => 0,
                    'estado_revision' => false,
                ]);
            }
        });

        $this->cerrarModal();
        session()->flash('mensaje', 'Llenado guardado correctamente.');
    }

    private function crearReposicion($existenciaDestino, $personalId)
    {
        $codigoReposicion = 'R-' . now()->format('Ymd') . '-' . str_pad(
            Reposicion::whereDate('created_at', now()->toDateString())->count() + 1,
            3,
            '0',
            STR_PAD_LEFT
        );

        return Reposicion::create([
            'fecha' => now(),
            'codigo' => $codigoReposicion,
            'cantidad' => 0,
            'cantidad_inicial' => 0,
            'existencia_id' => $existenciaDestino->id,
            'personal_id' => $personalId,
            'observaciones' => $this->observaciones ?? 'Reposición creada desde llenado',
            'estado_revision' => 0,
        ]);
    }

    private function crearComprobante($reposicion, $asignado, $cantidadUsada)
    {
        ComprobantePago::where('reposicion_id', $reposicion->id)->delete();

        $precioUnitario = $asignado->precio_unitario ?? 1;
        $monto = $precioUnitario * $cantidadUsada;

        $comprobante = ComprobantePago::create([
            'reposicion_id' => $reposicion->id,
            'codigo' => 'PAGO-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'monto' => $monto,
            'fecha' => now(),
            'observaciones' => 'Pago generado por llenado ' . ($this->codigo ?? ''),
        ]);

        $reposicion->update(['monto_usado' => $comprobante->monto]);
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset();
    }

    public function confirmarEliminarLlenado($id)
    {
        $this->confirmingDeleteLlenadoId = $id;
    }

    public function eliminarLlenadoConfirmado()
    {
        if (!$this->confirmingDeleteLlenadoId)
            return;

        $this->eliminar($this->confirmingDeleteLlenadoId);
        $this->confirmingDeleteLlenadoId = null;
    }

    public function eliminar($llenado_id)
    {
        $llenado = Llenado::find($llenado_id);
        if (!$llenado) {
            session()->flash('error', 'El llenado no existe.');
            return;
        }

        DB::transaction(function () use ($llenado) {
            $base = $llenado->asignadoBase;
            $tapa = $llenado->asignadoTapa;
            $producto = $llenado->existencia;

            if ($llenado->estado == 2) {
                $base->cantidad += ($llenado->cantidad + $llenado->merma_base);
                $tapa->cantidad += ($llenado->cantidad + $llenado->merma_tapa);
                $producto->cantidad -= $llenado->cantidad;
                $base->save();
                $tapa->save();
                $producto->save();
            }

            if ($llenado->reposicion_id) {
                $reposicion = Reposicion::find($llenado->reposicion_id);
                if ($reposicion)
                    $reposicion->delete();
            }

            $llenado->delete();
        });

        session()->flash('mensaje', 'Llenado eliminado correctamente.');
    }

    public function verDetalleLlenado($id)
    {
        $this->llenadoSeleccionado = Llenado::with([
            'asignadoBase.existencia.existenciable',
            'asignadoTapa.existencia.existenciable',
            'existenciaDestino.existenciable',
            'reposicion'
        ])->findOrFail($id);

        $this->modalDetalle = true;
    }
}

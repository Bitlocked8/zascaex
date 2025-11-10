<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Llenado;
use App\Models\Asignado;
use App\Models\ComprobantePago;
use App\Models\Reposicion;
use App\Models\Existencia;
use Illuminate\Support\Facades\DB;
use App\Models\Base;
use App\Models\Tapa;
use App\Models\Producto;

class Llenados extends Component
{
    public $modal = false;
    public $modalDetalle = false;
    public $llenado_id = null;
    public $asignado_id;
    public $existencia_destino_id;
    public $cantidad;
    public $merma = 0;
    public $estado = 0;
    public $observaciones = '';
    public $fecha;
    public $codigo;
    public $confirmingDeleteLlenadoId = null;
    public $accion = 'create';
    public $llenadoSeleccionado = null;
    public $existenciaSeleccionada = null;

    public $search = '';
    public $busquedaAsignacion = '';
    public $busquedaDestino = '';
    public $sucursalSeleccionada;
    public $filtroSucursalElemento;

    protected $rules = [
        'asignado_id' => 'required|exists:asignados,id',
        'existencia_destino_id' => 'required|exists:existencias,id',
        'cantidad' => 'nullable|numeric|min:0',
        'estado' => 'required|in:0,1,2',
        'observaciones' => 'nullable|string|max:500',
    ];

    public function render()
    {
        $llenados = Llenado::with(['asignado', 'reposicion', 'existencia'])
            ->when($this->search, function ($q) {
                $q->where('codigo', 'like', "%{$this->search}%")
                    ->orWhereHas('asignado', fn($q) => $q->where('codigo', 'like', "%{$this->search}%"));
            })
            ->get();

        $sucursales = \App\Models\Sucursal::all();

        $asignaciones = Asignado::with('reposiciones.existencia.existenciable', 'reposiciones.existencia.sucursal')
            ->where('cantidad', '>', 0)
            ->whereDoesntHave('llenados')
            ->whereHas('reposiciones.existencia', function ($q) {
                $q->whereIn('existenciable_type', [Base::class, Tapa::class]);
                if ($this->sucursalSeleccionada) {
                    $q->where('sucursal_id', $this->sucursalSeleccionada);
                }
            })
            ->when($this->filtroSucursalElemento, function ($q) {
                $q->whereHas(
                    'reposiciones.existencia',
                    fn($sub) => $sub->where('sucursal_id', $this->filtroSucursalElemento)
                );
            })
            ->when($this->busquedaAsignacion, function ($q) {
                $q->whereHas('reposiciones.existencia.existenciable', function ($sub) {
                    $sub->where('descripcion', 'like', "%{$this->busquedaAsignacion}%");
                });
            })
            ->get();

        $existenciasDestino = Existencia::with('existenciable', 'sucursal')
            ->where('existenciable_type', Producto::class)
            ->when($this->sucursalSeleccionada, function ($q) {
                $q->where('sucursal_id', $this->sucursalSeleccionada);
            })
            ->when($this->filtroSucursalElemento, function ($q) {
                $q->where('sucursal_id', $this->filtroSucursalElemento);
            })
            ->when($this->busquedaDestino, function ($q) {
                $q->whereHas('existenciable', function ($sub) {
                    $sub->where('descripcion', 'like', "%{$this->busquedaDestino}%");
                });
            })
            ->get();

        return view('livewire.llenados', compact('llenados', 'asignaciones', 'existenciasDestino', 'sucursales'));
    }

    public function seleccionarSucursal($id)
    {
        $this->sucursalSeleccionada = $id;
    }

    public function filtrarSucursalElemento($id)
    {
        $this->filtroSucursalElemento = $id;
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'llenado_id',
            'asignado_id',
            'existencia_destino_id',
            'cantidad',
            'merma',
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

    public function editar($id)
    {
        $llenado = Llenado::findOrFail($id);

        $this->llenado_id = $llenado->id;
        $this->asignado_id = $llenado->asignado_id;
        $this->existencia_destino_id = $llenado->existencia_id;
        $this->cantidad = $llenado->cantidad;
        $this->merma = $llenado->merma;
        $this->estado = $llenado->estado;
        $this->observaciones = $llenado->observaciones;
        $this->fecha = $llenado->fecha;
        $this->codigo = $llenado->codigo;
        $this->accion = 'edit';
        $this->llenadoSeleccionado = $llenado;
    }

    protected function generarCodigo($prefijo)
    {
        $fechaHoy = now()->format('Ymd');
        $ultimo = Llenado::whereDate('created_at', now()->toDateString())->latest('id')->first();
        $contador = $ultimo ? intval(substr($ultimo->codigo, -3)) + 1 : 1;
        return $prefijo . '-' . $fechaHoy . '-' . str_pad($contador, 3, '0', STR_PAD_LEFT);
    }

   public function guardar()
{
    $this->validate();

    $usuario = auth()->user()->load('personal');
    if (!$usuario->personal) {
        $this->addError('personal_id', 'El usuario autenticado no tiene un personal asignado.');
        return;
    }
    $personalId = $usuario->personal->id;

    $asignado = Asignado::findOrFail($this->asignado_id);
    $existenciaDestino = Existencia::findOrFail($this->existencia_destino_id);
    $cantidad = $this->cantidad ?? 0;

    // Calcular la merma individual según la cantidad disponible en la asignación
    $merma = max(0, $asignado->cantidad - $cantidad);

    // Si estamos editando, sumamos la cantidad y merma anterior
    $asignadoDisponible = $asignado->cantidad;
    if ($this->accion === 'edit' && $this->llenado_id) {
        $llenadoAnterior = Llenado::find($this->llenado_id);
        $asignadoDisponible += ($llenadoAnterior->cantidad + $llenadoAnterior->merma);
    }

    if ($cantidad > $asignadoDisponible) {
        $this->addError('cantidad', '⚠ No puedes usar más de la cantidad disponible en la asignación.');
        return;
    }

    DB::transaction(function () use ($asignado, $existenciaDestino, $cantidad, $merma, $personalId) {

        if ($this->accion === 'edit' && $this->llenado_id) {
            $llenado = Llenado::findOrFail($this->llenado_id);
            $reposicion = $llenado->reposicion ?? $this->crearReposicion($existenciaDestino, $personalId);
            $estadoAnterior = $llenado->estado;

            // Devolver cantidades si cambiamos de confirmado a otro estado
            if ($estadoAnterior == 2 && $this->estado != 2) {
                $asignado->cantidad += ($llenado->cantidad + $llenado->merma);
                $existenciaDestino->cantidad -= $llenado->cantidad;
            }

            // Si es confirmado, restamos y guardamos la merma
            if ($this->estado == 2) {
                $asignado->cantidad -= ($cantidad + $merma);
                $existenciaDestino->cantidad += $cantidad;
                $this->crearComprobante($reposicion, $asignado, $cantidad);
            }

            $asignado->save();
            $existenciaDestino->save();

            $reposicion->update([
                'cantidad' => $this->estado == 2 ? $cantidad : 0,
                'cantidad_inicial' => $this->estado == 2 ? $cantidad : 0,
                'estado_revision' => $this->estado == 2,
            ]);

            $llenado->update([
                'cantidad' => $cantidad,
                'merma' => $merma,
                'estado' => $this->estado,
                'observaciones' => $this->observaciones,
                'fecha' => $this->fecha ?? now(),
                'personal_id' => $personalId,
                'reposicion_id' => $reposicion->id,
            ]);

        } else {
            $reposicionDestino = $this->crearReposicion($existenciaDestino, $personalId);

            $llenado = Llenado::create([
                'codigo' => $this->codigo ?? $this->generarCodigo('L'),
                'asignado_id' => $asignado->id,
                'existencia_id' => $existenciaDestino->id,
                'cantidad' => $cantidad,
                'merma' => $merma,
                'estado' => $this->estado,
                'observaciones' => $this->observaciones,
                'fecha' => now(),
                'personal_id' => $personalId,
                'reposicion_id' => $reposicionDestino->id,
            ]);

            if ($this->estado == 2 && $cantidad > 0) {
                $asignado->cantidad -= ($cantidad + $merma);
                $existenciaDestino->cantidad += $cantidad;
                $asignado->save();
                $existenciaDestino->save();

                $reposicionDestino->update([
                    'cantidad' => $cantidad,
                    'cantidad_inicial' => $cantidad,
                    'estado_revision' => true,
                ]);

                $this->crearComprobante($reposicionDestino, $asignado, $cantidad);
            }
        }
    });

    $this->cerrarModal();
    session()->flash('mensaje', 'Llenado guardado correctamente.');
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

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'llenado_id',
            'asignado_id',
            'existencia_destino_id',
            'cantidad',
            'merma',
            'estado',
            'observaciones',
            'fecha',
            'codigo',
            'llenadoSeleccionado'
        ]);
        $this->resetErrorBag();
    }

    public function verDetalleLlenado($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->llenadoSeleccionado = Llenado::with([
            'asignado.existencia.existenciable',
            'existencia.existenciable',
            'personal',
            'reposicion'
        ])->findOrFail($id);
        $this->existenciaSeleccionada = $this->llenadoSeleccionado->existencia;
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->llenadoSeleccionado = null;
    }

    public static function booted()
    {
        Asignado::deleting(function ($asignado) {
            if ($asignado->llenados()->count() > 0) {
                throw new \Exception("No se puede eliminar la asignación porque ya tiene llenados registrados.");
            }
        });
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
            $asignado = $llenado->asignado;
            $existenciaDestino = $llenado->existencia;

            if ($llenado->estado == 2) {
                if ($asignado) {
                    $asignado->cantidad += ($llenado->cantidad + $llenado->merma);
                    $asignado->save();
                }

                if ($existenciaDestino) {
                    $existenciaDestino->cantidad -= $llenado->cantidad;
                    if ($existenciaDestino->cantidad < 0) {
                        $existenciaDestino->cantidad = 0;
                    }
                    $existenciaDestino->save();
                }
            }

            if ($llenado->reposicion_id) {
                $reposicion = Reposicion::find($llenado->reposicion_id);
                if ($reposicion) {
                    $reposicion->delete();
                }
            }

            $llenado->delete();
        });

        session()->flash('mensaje', 'Llenado eliminado correctamente.');
    }
}

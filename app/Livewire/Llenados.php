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
                $q->whereHas('reposiciones.existencia', fn($sub) => $sub->where('sucursal_id', $this->filtroSucursalElemento));
            })
            ->when($this->busquedaAsignacion, function ($q) {
                $q->whereHas('reposiciones.existencia.existenciable', fn($sub) => $sub->where('descripcion', 'like', "%{$this->busquedaAsignacion}%"));
            })
            ->get();

        $existenciasDestino = Existencia::with('existenciable', 'sucursal')
            ->where('existenciable_type', Producto::class)
            ->when($this->sucursalSeleccionada, fn($q) => $q->where('sucursal_id', $this->sucursalSeleccionada))
            ->when($this->filtroSucursalElemento, fn($q) => $q->where('sucursal_id', $this->filtroSucursalElemento))
            ->when($this->busquedaDestino, fn($q) => $q->whereHas('existenciable', fn($sub) => $sub->where('descripcion', 'like', "%{$this->busquedaDestino}%")))
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
        $this->reset(['llenado_id', 'asignado_id', 'existencia_destino_id', 'cantidad', 'merma', 'estado', 'observaciones', 'fecha', 'codigo', 'llenadoSeleccionado']);
        $this->accion = $accion;
        if ($accion === 'create')
            $this->fecha = now();
        if ($accion === 'edit' && $id)
            $this->editar($id);
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

    public function guardar()
    {
        $this->validate();

        $usuario = auth()->user()->load('personal');
        if (!$usuario->personal) {
            $this->addError('personal_id', 'El usuario autenticado no tiene un personal asignado.');
            return;
        }
        $personalId = $usuario->personal->id;

        $asignado = Asignado::with(['reposiciones.existencia.existenciable'])->findOrFail($this->asignado_id);
        $existenciaDestino = Existencia::findOrFail($this->existencia_destino_id);
        $cantidadProducida = $this->cantidad ?? 0;

        if ($asignado->reposiciones->isEmpty()) {
            $this->addError('asignado_id', 'La asignación no tiene materiales asignados.');
            return;
        }

        $diferencia = 0;
        $llenadoAnterior = null;

        if ($this->accion === 'edit' && $this->llenado_id) {
            $llenadoAnterior = Llenado::find($this->llenado_id);
            $diferencia = $cantidadProducida - $llenadoAnterior->cantidad;
            if ($llenadoAnterior && $llenadoAnterior->estado == 2) {
                $existenciaDestino->cantidad -= $llenadoAnterior->cantidad;
                $existenciaDestino->save();
            }
        }

        $materiales = $asignado->reposiciones->groupBy(function ($reposicion) {
            return class_basename($reposicion->existencia->existenciable);
        })->map(function ($group, $tipo) {
            return [
                'tipo' => $tipo,
                'cantidad_total' => $group->sum('pivot.cantidad_original'),
                'reposiciones' => $group
            ];
        });

        $cantidadMaximaProducible = $materiales->min('cantidad_total');

        if ($cantidadProducida > $cantidadMaximaProducible) {
            $this->addError('cantidad', "⚠ No puedes producir más de {$cantidadMaximaProducible} unidades.");
            return;
        }

        $mermaTotal = 0;
        $detalleMerma = '';

        foreach ($materiales as $tipo => $material) {
            $mermaMaterial = $material['cantidad_total'] - $cantidadProducida;
            $mermaTotal += $mermaMaterial;
            $detalleMerma .= ($detalleMerma ? ', ' : '') . "{$tipo}: {$mermaMaterial} unidades";
        }
        DB::transaction(function () use ($asignado, $existenciaDestino, $cantidadProducida, $personalId, $mermaTotal, $detalleMerma, $materiales, $diferencia, $llenadoAnterior) {
            foreach ($asignado->reposiciones as $reposicion) {
                $asignado->reposiciones()->updateExistingPivot($reposicion->id, [
                    'cantidad' => 0
                ]);
            }

            $reposicionDestino = null;

            if ($this->accion === 'edit' && $this->llenado_id) {
                $llenadoExistente = Llenado::find($this->llenado_id);
                $reposicionDestino = $llenadoExistente->reposicion;

                if ($reposicionDestino) {
                    $reposicionDestino->update([
                        'observaciones' => $this->observaciones ?? 'Reposición actualizada desde llenado',
                        'fecha' => now(),
                        'existencia_id' => $existenciaDestino->id,
                    ]);
                }
            }
            if (!$reposicionDestino) {
                $reposicionDestino = $this->crearReposicion($existenciaDestino, $personalId);
            }

            if ($this->accion === 'create') {
                $codigo = $this->generarCodigo('L');

                $llenado = Llenado::create([
                    'codigo' => $codigo,
                    'asignado_id' => $asignado->id,
                    'existencia_id' => $existenciaDestino->id,
                    'reposicion_id' => $reposicionDestino->id,
                    'personal_id' => $personalId,
                    'cantidad' => $cantidadProducida,
                    'merma' => $mermaTotal,
                    'estado' => $this->estado,
                    'observaciones' => $this->observaciones,
                    'fecha' => now(),
                ]);
            } else {
                $llenado = Llenado::findOrFail($this->llenado_id);
                $llenado->update([
                    'asignado_id' => $asignado->id,
                    'existencia_id' => $existenciaDestino->id,
                    'reposicion_id' => $reposicionDestino->id,
                    'personal_id' => $personalId,
                    'cantidad' => $cantidadProducida,
                    'merma' => $mermaTotal,
                    'estado' => $this->estado,
                    'observaciones' => $this->observaciones,
                    'fecha' => now(),
                ]);
            }
            $asignado->load('reposiciones');
            $asignado->cantidad = $asignado->reposiciones->sum('pivot.cantidad');
            $asignado->save();
            if ($this->estado == 2 && $cantidadProducida > 0) {
                $existenciaDestino->cantidad += $cantidadProducida;
                $existenciaDestino->save();

                $reposicionDestino->update([
                    'cantidad' => $cantidadProducida,
                    'cantidad_inicial' => $cantidadProducida,
                    'estado_revision' => true,
                ]);

                $this->crearComprobante($reposicionDestino, $asignado, $cantidadProducida);
            }
        });

        $this->cerrarModal();
        session()->flash('mensaje', 'Llenado ' . ($this->accion === 'create' ? 'guardado' : 'actualizado') . ' correctamente.');
        session()->flash('detalle_merma', $detalleMerma);
    }
    private function restaurarDesdeCantidadOriginal($asignado)
    {
        foreach ($asignado->reposiciones as $reposicion) {
            DB::table('asignado_reposicions')
                ->where('asignado_id', $asignado->id)
                ->where('reposicion_id', $reposicion->id)
                ->update([
                    'cantidad' => $reposicion->pivot->cantidad_original
                ]);
        }
        $asignado->cantidad = $asignado->reposiciones->sum('pivot.cantidad_original');
        $asignado->save();
    }

    protected function generarCodigo($prefijo)
    {
        $fechaHoy = now()->format('Ymd');
        $ultimo = Llenado::where('codigo', 'like', $prefijo . '-' . $fechaHoy . '%')
            ->lockForUpdate()
            ->orderBy('codigo', 'desc')
            ->first();

        $contador = $ultimo ? intval(substr($ultimo->codigo, -3)) + 1 : 1;
        return $prefijo . '-' . $fechaHoy . '-' . str_pad($contador, 3, '0', STR_PAD_LEFT);
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
        $fechaHoy = now()->format('Ymd');
        $ultimaReposicion = Reposicion::where('codigo', 'like', 'R-' . $fechaHoy . '%')
            ->lockForUpdate()
            ->orderBy('codigo', 'desc')
            ->first();

        $contador = $ultimaReposicion ? intval(substr($ultimaReposicion->codigo, -3)) + 1 : 1;
        $codigoReposicion = 'R-' . $fechaHoy . '-' . str_pad($contador, 3, '0', STR_PAD_LEFT);

        $existe = Reposicion::where('codigo', $codigoReposicion)->exists();
        if ($existe) {
            $codigoReposicion = 'R-' . $fechaHoy . '-' . str_pad($contador + 1, 3, '0', STR_PAD_LEFT);
        }

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
        $this->reset(['llenado_id', 'asignado_id', 'existencia_destino_id', 'cantidad', 'merma', 'estado', 'observaciones', 'fecha', 'codigo', 'llenadoSeleccionado']);
        $this->resetErrorBag();
    }

    public function verDetalleLlenado($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->llenadoSeleccionado = Llenado::with([
            'asignado.reposiciones.existencia.existenciable',
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
        Asignado::deleting(fn($asignado) => $asignado->llenados()->count() > 0 ? throw new \Exception("No se puede eliminar la asignación porque ya tiene llenados registrados.") : null);
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

            if ($asignado) {
                foreach ($asignado->reposiciones as $reposicion) {
                    DB::table('asignado_reposicions')
                        ->where('asignado_id', $asignado->id)
                        ->where('reposicion_id', $reposicion->id)
                        ->update([
                            'cantidad' => $reposicion->pivot->cantidad_original
                        ]);
                }
                $nuevaCantidad = DB::table('asignado_reposicions')
                    ->where('asignado_id', $asignado->id)
                    ->sum('cantidad');
                $asignado->cantidad = $nuevaCantidad;
                $asignado->save();
            }
            if ($llenado->estado == 2) {
                $existenciaDestino = $llenado->existencia;
                if ($existenciaDestino) {
                    $existenciaDestino->cantidad -= $llenado->cantidad;
                    if ($existenciaDestino->cantidad < 0)
                        $existenciaDestino->cantidad = 0;
                    $existenciaDestino->save();
                }
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
}

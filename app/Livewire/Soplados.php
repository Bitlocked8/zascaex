<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Soplado;
use App\Models\Asignado;
use App\Models\ComprobantePago;
use App\Models\Reposicion;
use App\Models\Existencia;
use Illuminate\Support\Facades\DB;
use App\Models\Base;
use App\Models\Preforma;

class Soplados extends Component
{
    public $modal = false;
    public $modalDetalle = false;
    public $soplado_id = null;
    public $asignado_id;
    public $existencia_destino_id;
    public $cantidad;
    public $merma = 0;
    public $estado = 0;
    public $observaciones = '';
    public $fecha;
    public $codigo;
    public $confirmingDeleteSopladoId = null;
    public $accion = 'create';
    public $sopladoSeleccionado = null;
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
        $soplados = Soplado::with(['asignado', 'reposicion', 'existencia'])
            ->when($this->search, function ($q) {
                $q->where('codigo', 'like', "%{$this->search}%")
                    ->orWhereHas('asignado', fn($q) => $q->where('codigo', 'like', "%{$this->search}%"));
            })
            ->get();

        $sucursales = \App\Models\Sucursal::all();

        $asignaciones = Asignado::with('reposiciones.existencia.existenciable', 'reposiciones.existencia.sucursal')
            ->where('cantidad', '>', 0)
            ->whereDoesntHave('soplados')
            ->whereHas('reposiciones.existencia', function ($q) {
                $q->whereIn('existenciable_type', [Preforma::class]);
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
            ->where('existenciable_type', Base::class)
            ->when($this->sucursalSeleccionada, fn($q) => $q->where('sucursal_id', $this->sucursalSeleccionada))
            ->when($this->filtroSucursalElemento, fn($q) => $q->where('sucursal_id', $this->filtroSucursalElemento))
            ->when($this->busquedaDestino, fn($q) => $q->whereHas('existenciable', fn($sub) => $sub->where('descripcion', 'like', "%{$this->busquedaDestino}%")))
            ->get();

        return view('livewire.soplados', compact('soplados', 'asignaciones', 'existenciasDestino', 'sucursales'));
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
        $this->reset(['soplado_id', 'asignado_id', 'existencia_destino_id', 'cantidad', 'merma', 'estado', 'observaciones', 'fecha', 'codigo', 'sopladoSeleccionado']);
        $this->accion = $accion;
        if ($accion === 'create')
            $this->fecha = now();
        if ($accion === 'edit' && $id)
            $this->editar($id);
        $this->modal = true;
    }

    public function editar($id)
    {
        $soplado = Soplado::findOrFail($id);
        $this->soplado_id = $soplado->id;
        $this->asignado_id = $soplado->asignado_id;
        $this->existencia_destino_id = $soplado->existencia_id;
        $this->cantidad = $soplado->cantidad;
        $this->merma = $soplado->merma;
        $this->estado = $soplado->estado;
        $this->observaciones = $soplado->observaciones;
        $this->fecha = $soplado->fecha;
        $this->codigo = $soplado->codigo;
        $this->accion = 'edit';
        $this->sopladoSeleccionado = $soplado;
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
        $sopladoAnterior = null;

        if ($this->accion === 'edit' && $this->soplado_id) {
            $sopladoAnterior = Soplado::find($this->soplado_id);
            $diferencia = $cantidadProducida - $sopladoAnterior->cantidad;
            if ($sopladoAnterior && $sopladoAnterior->estado == 2) {
                $existenciaDestino->cantidad -= $sopladoAnterior->cantidad;
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
        DB::transaction(function () use ($asignado, $existenciaDestino, $cantidadProducida, $personalId, $mermaTotal, $detalleMerma, $materiales, $diferencia, $sopladoAnterior) {
            foreach ($asignado->reposiciones as $reposicion) {
                $asignado->reposiciones()->updateExistingPivot($reposicion->id, [
                    'cantidad' => 0
                ]);
            }

            $reposicionDestino = null;

            if ($this->accion === 'edit' && $this->soplado_id) {
                $sopladoExistente = Soplado::find($this->soplado_id);
                $reposicionDestino = $sopladoExistente->reposicion;

                if ($reposicionDestino) {
                    $reposicionDestino->update([
                        'observaciones' => $this->observaciones ?? 'Reposición actualizada desde soplado',
                        'fecha' => now(),
                        'existencia_id' => $existenciaDestino->id,
                    ]);
                }
            }
            if (!$reposicionDestino) {
                $reposicionDestino = $this->crearReposicion($existenciaDestino, $personalId);
            }

            if ($this->accion === 'create') {
                $codigo = $this->generarCodigo('S');

                $soplado = Soplado::create([
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
                $soplado = Soplado::findOrFail($this->soplado_id);
                $soplado->update([
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
        session()->flash('mensaje', 'Soplado ' . ($this->accion === 'create' ? 'guardado' : 'actualizado') . ' correctamente.');
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
        $ultimo = Soplado::where('codigo', 'like', $prefijo . '-' . $fechaHoy . '%')
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
            'observaciones' => 'Pago generado por soplado ' . ($this->codigo ?? ''),
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
            'observaciones' => $this->observaciones ?? 'Reposición creada desde soplado',
            'estado_revision' => 0,
        ]);
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset(['soplado_id', 'asignado_id', 'existencia_destino_id', 'cantidad', 'merma', 'estado', 'observaciones', 'fecha', 'codigo', 'sopladoSeleccionado']);
        $this->resetErrorBag();
    }

    public function verDetalleSoplado($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->sopladoSeleccionado = Soplado::with([
            'asignado.reposiciones.existencia.existenciable',
            'existencia.existenciable',
            'personal',
            'reposicion'
        ])->findOrFail($id);
        $this->existenciaSeleccionada = $this->sopladoSeleccionado->existencia;
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->sopladoSeleccionado = null;
    }

    public static function booted()
    {
        Asignado::deleting(fn($asignado) => $asignado->soplados()->count() > 0 ? throw new \Exception("No se puede eliminar la asignación porque ya tiene soplados registrados.") : null);
    }

    public function confirmarEliminarSoplado($id)
    {
        $this->confirmingDeleteSopladoId = $id;
    }

    public function eliminarSopladoConfirmado()
    {
        if (!$this->confirmingDeleteSopladoId)
            return;
        $this->eliminar($this->confirmingDeleteSopladoId);
        $this->confirmingDeleteSopladoId = null;
    }

    public function eliminar($soplado_id)
    {
        $soplado = Soplado::find($soplado_id);
        if (!$soplado) {
            session()->flash('error', 'El soplado no existe.');
            return;
        }

        DB::transaction(function () use ($soplado) {
            $asignado = $soplado->asignado;

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
            if ($soplado->estado == 2) {
                $existenciaDestino = $soplado->existencia;
                if ($existenciaDestino) {
                    $existenciaDestino->cantidad -= $soplado->cantidad;
                    if ($existenciaDestino->cantidad < 0)
                        $existenciaDestino->cantidad = 0;
                    $existenciaDestino->save();
                }
            }
            if ($soplado->reposicion_id) {
                $reposicion = Reposicion::find($soplado->reposicion_id);
                if ($reposicion)
                    $reposicion->delete();
            }

            $soplado->delete();
        });

        session()->flash('mensaje', 'Soplado eliminado correctamente.');
    }
}
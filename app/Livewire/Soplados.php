<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Soplado;
use App\Models\Asignado;
use App\Models\ComprobantePago;
use App\Models\Reposicion;
use App\Models\Existencia;
use Illuminate\Support\Facades\DB;

class Soplados extends Component
{
    public $search = '';
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
            ->when(
                $this->search,
                fn($q) =>
                $q->where('codigo', 'like', "%{$this->search}%")
                    ->orWhereHas('asignado', fn($q) => $q->where('codigo', 'like', "%{$this->search}%"))
            )->get();

        $asignaciones = Asignado::with('existencia.existenciable')
            ->where('cantidad', '>', 0)
            ->whereHas('existencia', fn($q) => $q->where('existenciable_type', \App\Models\Preforma::class))
            ->get();

        $existenciasDestino = Existencia::with('existenciable')
            ->where('existenciable_type', \App\Models\Base::class)
            ->get();

        return view('livewire.soplados', compact('soplados', 'asignaciones', 'existenciasDestino'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'soplado_id',
            'asignado_id',
            'existencia_destino_id',
            'cantidad',
            'merma',
            'estado',
            'observaciones',
            'fecha',
            'codigo',
            'sopladoSeleccionado'
        ]);

        $this->accion = $accion;

        if ($accion === 'create') {
            $this->codigo = $this->generarCodigo('S');
            $this->fecha = now();
        }

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

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

    protected function generarCodigo($prefijo)
    {
        $fechaHoy = now()->format('Ymd');
        $ultimo = Soplado::whereDate('created_at', now()->toDateString())->latest('id')->first();
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
    $merma = $cantidad > 0 ? max(0, $asignado->cantidad - $cantidad) : 0;

    // Verificar cantidad disponible
    $asignadoDisponible = $asignado->cantidad;
    if ($this->accion === 'edit' && $this->soplado_id) {
        $sopladoAnterior = Soplado::find($this->soplado_id);
        $asignadoDisponible += ($sopladoAnterior->cantidad + $sopladoAnterior->merma);
    }

    if ($cantidad > $asignadoDisponible) {
        $this->addError('cantidad', '⚠ No puedes usar más de la cantidad disponible en la asignación.');
        return;
    }

    DB::transaction(function () use ($asignado, $existenciaDestino, $cantidad, $merma, $personalId) {

        if ($this->accion === 'edit' && $this->soplado_id) {

            $soplado = Soplado::findOrFail($this->soplado_id);
            $reposicion = $soplado->reposicion ?? $this->crearReposicion($existenciaDestino, $personalId);

            $estadoAnterior = $soplado->estado;

            // Revertir si antes estaba confirmado y ahora no
            if ($estadoAnterior == 2 && $this->estado != 2) {
                $asignado->cantidad += ($soplado->cantidad + $soplado->merma);
                $existenciaDestino->cantidad -= $soplado->cantidad;
                $asignado->save();
                $existenciaDestino->save();
            }

            // Aplicar movimiento si ahora está confirmado
            if ($this->estado == 2 && $estadoAnterior != 2) {
                $asignado->cantidad -= ($cantidad + $merma);
                $existenciaDestino->cantidad += $cantidad;
                $asignado->save();
                $existenciaDestino->save();
            }

            // Actualizar reposición
            $reposicion->cantidad = $this->estado == 2 ? $cantidad : 0;
            $reposicion->cantidad_inicial = $this->estado == 2 ? $cantidad : 0;
            $reposicion->estado_revision = $this->estado == 2;
            $reposicion->save();

            // Actualizar soplado
            $soplado->update([
                'cantidad' => $cantidad,
                'merma' => $merma,
                'estado' => $this->estado,
                'observaciones' => $this->observaciones,
                'fecha' => $this->fecha ?? now(),
                'personal_id' => $personalId,
                'reposicion_id' => $reposicion->id,
            ]);

            // Crear comprobante solo si está confirmado
            if ($this->estado == 2 && $cantidad > 0) {
                $this->crearComprobante($reposicion, $asignado, $cantidad);
            }

        } else {

            $reposicionDestino = $this->crearReposicion($existenciaDestino, $personalId);

            $soplado = Soplado::create([
                'codigo' => $this->codigo ?? $this->generarCodigo('S'),
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

                $reposicionDestino->cantidad = $cantidad;
                $reposicionDestino->cantidad_inicial = $cantidad;
                $reposicionDestino->estado_revision = true;
                $reposicionDestino->save();

                // Crear comprobante automáticamente
                $this->crearComprobante($reposicionDestino, $asignado, $cantidad);
            }
        }
    });

    $this->cerrarModal();
    session()->flash('mensaje', 'Soplado guardado correctamente.');
}

/**
 * Crear comprobante de pago basado en la cantidad usada
 */
private function crearComprobante($reposicion, $asignado, $cantidadUsada)
{
    $monto = 0;

    foreach ($asignado->reposiciones as $r) {
        $precioUnitario = $r->cantidad_inicial > 0
            ? $r->comprobantes->sum('monto') / $r->cantidad_inicial
            : 0;

        $monto += $precioUnitario * $cantidadUsada;
    }

    ComprobantePago::create([
        'reposicion_id' => $reposicion->id,
        'codigo' => 'PAGO-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
        'monto' => $monto,
        'fecha' => now(),
        'observaciones' => 'Pago generado por soplado ' . ($this->codigo ?? ''),
    ]);
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
            'observaciones' => $this->observaciones ?? 'Reposición creada desde soplado',
            'estado_revision' => 0,
        ]);
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'soplado_id',
            'asignado_id',
            'existencia_destino_id',
            'cantidad',
            'merma',
            'estado',
            'observaciones',
            'fecha',
            'codigo',
            'sopladoSeleccionado'
        ]);
        $this->resetErrorBag();
    }

    public function verDetalleSoplado($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->sopladoSeleccionado = Soplado::with([
            'asignado.existencia.existenciable',
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
        Asignado::deleting(function ($asignado) {
            if ($asignado->soplados()->count() > 0) {
                throw new \Exception("No se puede eliminar la asignación porque ya tiene soplados registrados.");
            }
        });
    }


    public function confirmarEliminarSoplado($id)
    {
        $this->confirmingDeleteSopladoId = $id;
    }

    public function eliminarSopladoConfirmado()
    {
        if (!$this->confirmingDeleteSopladoId) return;

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
            $existenciaDestino = $soplado->existencia;

            if ($soplado->estado == 2) {
                if ($asignado) {
                    $asignado->cantidad += ($soplado->cantidad + $soplado->merma);
                    $asignado->save();
                }

                if ($existenciaDestino) {
                    $existenciaDestino->cantidad -= $soplado->cantidad;
                    if ($existenciaDestino->cantidad < 0) {
                        $existenciaDestino->cantidad = 0;
                    }
                    $existenciaDestino->save();
                }
            }

            if ($soplado->reposicion_id) {
                $reposicion = Reposicion::find($soplado->reposicion_id);
                if ($reposicion) {
                    $reposicion->delete();
                }
            }

            $soplado->delete();
        });

        session()->flash('mensaje', 'Soplado eliminado correctamente.');
    }
}

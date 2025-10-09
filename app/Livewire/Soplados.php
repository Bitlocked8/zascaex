<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Soplado;
use App\Models\Asignado;
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
    public $existencia_destino_id; // existencia destino
    public $cantidad;
    public $merma = 0;
    public $estado = 0;
    public $observaciones = '';
    public $fecha;
    public $codigo;

    public $accion = 'create';
    public $sopladoSeleccionado = null;

    protected $rules = [
        'asignado_id' => 'required|exists:asignados,id',
        'existencia_destino_id' => 'required|exists:existencias,id',
        'cantidad' => 'required|numeric|min:1',
        'merma' => 'nullable|numeric|min:0',
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
            ->whereHas('existencia', function ($q) {
                $q->where('existenciable_type', \App\Models\Preforma::class);
            })
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

        DB::transaction(function () {
            $asignado = Asignado::findOrFail($this->asignado_id);
            $existenciaDestino = Existencia::findOrFail($this->existencia_destino_id);
            if ($this->cantidad + $this->merma > $asignado->cantidad) {
                $this->addError('cantidad', 'La cantidad + merma supera el disponible en la asignación.');
                return;
            }
            if ($this->accion === 'edit' && $this->soplado_id) {
                $soplado = Soplado::findOrFail($this->soplado_id);
                $soplado->update([
                    'cantidad' => $this->cantidad,
                    'merma' => $this->merma,
                    'estado' => $this->estado,
                    'observaciones' => $this->observaciones,
                    'fecha' => $this->fecha ?? now(),
                    'reposicion_id' => $soplado->reposicion_id,
                ]);
            } else {
                $codigoReposicion = 'R-' . now()->format('Ymd') . '-' . str_pad(
                    Reposicion::whereDate('created_at', now()->toDateString())->count() + 1,
                    3,
                    '0',
                    STR_PAD_LEFT
                );

                $reposicionDestino = Reposicion::create([
                    'fecha' => now(),
                    'codigo' => $codigoReposicion,
                    'cantidad' => $this->cantidad,
                    'cantidad_inicial' => $this->cantidad,
                    'existencia_id' => $existenciaDestino->id,
                    'personal_id' => $asignado->personal_id,
                    'observaciones' => $this->observaciones ?? 'Soplado desde asignación ' . $asignado->codigo,
                ]);
                Soplado::create([
                    'codigo' => $this->codigo,
                    'asignado_id' => $asignado->id,
                    'reposicion_id' => $reposicionDestino->id,
                    'existencia_id' => $existenciaDestino->id,
                    'cantidad' => $this->cantidad,
                    'merma' => $this->merma,
                    'estado' => 0,
                    'observaciones' => $this->observaciones,
                    'fecha' => now(),
                ]);
                $asignado->cantidad -= ($this->cantidad + $this->merma);
                $asignado->save();
            }
        });
        $this->cerrarModal();
        session()->flash('mensaje', 'Soplado registrado correctamente!');
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

    public function modalDetalle($id)
    {
        $soplado = Soplado::with(['asignado', 'reposicion', 'existencia'])->findOrFail($id);
        $this->sopladoSeleccionado = $soplado;
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

    public function eliminar($soplado_id)
    {
        $soplado = Soplado::find($soplado_id);

        if (!$soplado) {
            session()->flash('error', 'El soplado no existe.');
            return;
        }

        DB::transaction(function () use ($soplado) {
            $asignado = $soplado->asignado;

            // Devolver la cantidad + merma a la asignación
            if ($asignado) {
                $asignado->cantidad += ($soplado->cantidad + $soplado->merma);
                $asignado->save();
            }

            // Primero eliminamos la reposición asociada si existe
            if ($soplado->reposicion_id) {
                $reposicion = Reposicion::find($soplado->reposicion_id);
                if ($reposicion) {
                    // Actualizamos stock de la existencia si quieres revertirlo
                    $existencia = $reposicion->existencia;
                    if ($existencia) {
                        $existencia->cantidad -= $reposicion->cantidad;
                        if ($existencia->cantidad < 0) $existencia->cantidad = 0;
                        $existencia->save();
                    }
                    $reposicion->delete();
                }
            }

            // Finalmente eliminamos el soplado
            $soplado->delete();
        });

        session()->flash('mensaje', 'Soplado eliminado y cantidad devuelta a la asignación.');
    }
}

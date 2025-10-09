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
    public $existencia_destino_id;
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

        DB::transaction(function () use ($personalId) {
            $asignado = Asignado::findOrFail($this->asignado_id);
            $existenciaDestino = Existencia::findOrFail($this->existencia_destino_id);
            $cantidad = $this->cantidad ?? 0;
            $merma = $cantidad > 0 ? max(0, $asignado->cantidad - $cantidad) : 0;

            if ($cantidad > $asignado->cantidad) {
                $this->addError('cantidad', 'La cantidad no puede superar la asignada.');
                return;
            }

            if ($this->accion === 'edit' && $this->soplado_id) {
                $soplado = Soplado::findOrFail($this->soplado_id);
                $reposicion = $soplado->reposicion ?? $this->crearReposicion($existenciaDestino, $personalId);

                // Revertir cantidades si el soplado estaba confirmado antes
                if ($soplado->estado == 2) {
                    $existenciaDestino->cantidad -= $soplado->cantidad;
                    $asignado->cantidad += ($soplado->cantidad + $soplado->merma);
                    $existenciaDestino->save();
                    $asignado->save();
                }

                // Aplicar cantidades si ahora está confirmado
                if ($this->estado == 2) {
                    $existenciaDestino->cantidad += $cantidad;
                    $asignado->cantidad -= ($cantidad + $merma);
                    $existenciaDestino->save();
                    $asignado->save();

                    $reposicion->cantidad = $cantidad;
                    $reposicion->cantidad_inicial = $cantidad;
                    $reposicion->estado_revision = 2;
                    $reposicion->save();
                }

                $soplado->update([
                    'cantidad' => $cantidad,
                    'merma' => $merma,
                    'estado' => $this->estado,
                    'observaciones' => $this->observaciones,
                    'fecha' => $this->fecha ?? now(),
                    'personal_id' => $personalId,
                    'reposicion_id' => $reposicion->id,
                ]);
            } else {
                // Crear nuevo soplado
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

                // Aplicar cantidades si está confirmado
                if ($this->estado == 2 && $cantidad > 0) {
                    $existenciaDestino->cantidad += $cantidad;
                    $asignado->cantidad -= ($cantidad + $merma);
                    $existenciaDestino->save();
                    $asignado->save();

                    $reposicionDestino->cantidad = $cantidad;
                    $reposicionDestino->cantidad_inicial = $cantidad;
                    $reposicionDestino->estado_revision = 2;
                    $reposicionDestino->save();
                }
            }
        });

        $this->cerrarModal();
        session()->flash('mensaje', 'Soplado guardado correctamente.');
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

    public function modalDetalle($id)
    {
        $this->sopladoSeleccionado = Soplado::with(['asignado', 'reposicion', 'existencia'])->findOrFail($id);
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
            $existenciaDestino = $soplado->existencia;

            // Solo revertir cantidades si el soplado estaba confirmado
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

            // Eliminar reposición asociada si existe
            if ($soplado->reposicion_id) {
                $reposicion = Reposicion::find($soplado->reposicion_id);
                if ($reposicion) $reposicion->delete();
            }

            $soplado->delete();
        });

        session()->flash('mensaje', 'Soplado eliminado correctamente.');
    }
}

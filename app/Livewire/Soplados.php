<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Soplado;
use App\Models\Asignado;

use App\Models\Reposicion;
use App\Models\Existencia;
use App\Models\Base;
use App\Models\Preforma;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
    public $existenciasDestino = [];
    public $soloHoy = true;
    protected $rules = [
        'asignado_id' => 'required|exists:asignados,id',
        'existencia_destino_id' => 'required|exists:existencias,id',
        'cantidad' => 'nullable|numeric|min:0',
        'estado' => 'required|in:0,1,2',
        'observaciones' => 'nullable|string|max:500',
    ];

    public function render()
    {
        $usuario = auth()->user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        $sopladosQuery = Soplado::with(['asignado', 'reposicion', 'existencia'])
            ->when(
                $this->search,
                fn($q) => $q->where('codigo', 'like', "%{$this->search}%")
                    ->orWhereHas('asignado', fn($q) => $q->where('codigo', 'like', "%{$this->search}%"))
            );

        if ($this->soloHoy) {
            $sopladosQuery->whereDate('fecha', Carbon::today());
        }

        if ($rol === 4 && $personal) {
            $sucursalId = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $sopladosQuery->whereHas('existencia', fn($q) => $q->where('sucursal_id', $sucursalId));
        }

        $soplados = $sopladosQuery->get();

        $asignacionesQuery = Asignado::with('reposiciones.existencia.existenciable', 'reposiciones.existencia.sucursal')
            ->where('cantidad', '>', 0)
            ->whereDoesntHave('soplados')
            ->whereDoesntHave('traspasos')
            ->whereHas('reposiciones.existencia', fn($q) => $q->whereIn('existenciable_type', [Preforma::class]));

        if ($rol === 4 && $personal) {
            $sucursalId = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $asignacionesQuery->whereHas('reposiciones.existencia', fn($q) => $q->where('sucursal_id', $sucursalId));
        }

        if ($this->busquedaAsignacion) {
            $asignacionesQuery->whereHas('reposiciones.existencia.existenciable', fn($q) => $q->where('descripcion', 'like', "%{$this->busquedaAsignacion}%"));
        }

        $asignaciones = $asignacionesQuery->get();
        $sucursales = \App\Models\Sucursal::all();

        return view('livewire.soplados', compact('soplados', 'asignaciones', 'sucursales'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset(['soplado_id', 'asignado_id', 'existencia_destino_id', 'cantidad', 'merma', 'estado', 'observaciones', 'fecha', 'codigo', 'sopladoSeleccionado', 'existenciasDestino', 'sucursalSeleccionada']);
        $this->accion = $accion;
        $this->fecha = now();

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        } elseif ($accion === 'create' && $this->asignado_id) {
            $this->cargarBases(); // cargar bases según la asignación seleccionada
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

        $this->sucursalSeleccionada = $soplado->asignado->reposiciones->first()->existencia->sucursal_id ?? null;
        $this->cargarBases();
    }

    public function seleccionarPreforma($asignado_id)
    {
        $asignado = Asignado::with('reposiciones.existencia.sucursal')->find($asignado_id);
        if ($asignado) {
            $this->asignado_id = $asignado->id;
            $this->sucursalSeleccionada = $asignado->reposiciones->first()->existencia->sucursal_id ?? null;
            $this->cargarBases();
        }
    }

    public function seleccionarSucursal($id)
    {
        $this->sucursalSeleccionada = $id;
        $this->cargarBases();
    }

    public function cargarBases()
    {
        $query = Existencia::with('existenciable', 'sucursal')->where('existenciable_type', Base::class);
        if ($this->sucursalSeleccionada)
            $query->where('sucursal_id', $this->sucursalSeleccionada);
        if ($this->busquedaDestino)
            $query->whereHas('existenciable', fn($q) => $q->where('descripcion', 'like', "%{$this->busquedaDestino}%"));
        $this->existenciasDestino = $query->get();
    }



    public function filtrarSucursalElemento($id)
    {
        $this->filtroSucursalElemento = $id;
    }

    public function guardar()
    {
        $this->validate();

        $usuario = auth()->user();
        $personal = $usuario->personal;

        if (!$personal) {
            session()->flash('error', 'No tienes un registro de personal asociado.');
            return;
        }

        $personalId = $personal->id;

        $asignado = Asignado::with(['reposiciones.existencia.existenciable'])->findOrFail($this->asignado_id);
        $existenciaDestino = Existencia::findOrFail($this->existencia_destino_id);
        $cantidadProducida = $this->cantidad ?? 0;

        $materiales = $asignado->reposiciones->groupBy(fn($r) => class_basename($r->existencia->existenciable))
            ->map(fn($g, $tipo) => [
                'tipo' => $tipo,
                'cantidad_total' => $g->sum('pivot.cantidad_original'),
                'reposiciones' => $g
            ]);

        $cantidadMaximaProducible = $materiales->min('cantidad_total');
        if ($cantidadProducida > $cantidadMaximaProducible) {
            $this->addError('cantidad', "No puedes producir más de {$cantidadMaximaProducible} unidades.");
            return;
        }

        $mermaTotal = 0;
        foreach ($materiales as $m) {
            $mermaTotal += $m['cantidad_total'] - $cantidadProducida;
        }

        DB::transaction(function () use ($asignado, $existenciaDestino, $cantidadProducida, $personalId, $mermaTotal) {

            // Resetear cantidades en los pivots
            foreach ($asignado->reposiciones as $reposicion) {
                $asignado->reposiciones()->updateExistingPivot($reposicion->id, ['cantidad' => 0]);
            }

            // Crear o actualizar reposicion
            if ($this->accion === 'edit' && $this->sopladoSeleccionado && $this->sopladoSeleccionado->reposicion_id) {
                $reposicionDestino = Reposicion::find($this->sopladoSeleccionado->reposicion_id);
                $reposicionDestino->update([
                    'existencia_id' => $existenciaDestino->id,
                    'observaciones' => $this->observaciones ?? $reposicionDestino->observaciones,
                    'estado_revision' => 0
                ]);
            } else {
                $reposicionDestino = Reposicion::create([
                    'fecha' => now(),
                    'codigo' => 'R-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                    'cantidad' => 0,
                    'cantidad_inicial' => 0,
                    'existencia_id' => $existenciaDestino->id,
                    'personal_id' => $personalId,
                    'observaciones' => $this->observaciones ?? 'Reposición creada desde soplado',
                    'estado_revision' => 0
                ]);
            }

            // Datos del soplado
            $sopladoData = [
                'codigo' => $this->accion === 'create'
                    ? 'S-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)
                    : $this->codigo,
                'asignado_id' => $asignado->id,
                'existencia_id' => $existenciaDestino->id,
                'reposicion_id' => $reposicionDestino->id,
                'cantidad' => $cantidadProducida,
                'merma' => $mermaTotal,
                'estado' => $this->estado,
                'observaciones' => $this->observaciones,
                'fecha' => now(),
            ];

            if ($this->accion === 'create') {
                $sopladoData['personal_id'] = $personalId;
            }

            $soplado = Soplado::updateOrCreate(['id' => $this->soplado_id], $sopladoData);

            // Ajustar cantidad en existencia si estamos editando un soplado previamente confirmado
            if ($this->accion === 'edit' && $this->sopladoSeleccionado && $this->sopladoSeleccionado->estado == 2) {
                $existenciaDestino->cantidad -= $this->sopladoSeleccionado->cantidad;
            }

            // Sumar nueva cantidad solo si el estado actual es confirmado
            if ($this->estado == 2 && $cantidadProducida > 0) {
                $existenciaDestino->cantidad += $cantidadProducida;
                $reposicionDestino->update([
                    'cantidad' => $cantidadProducida,
                    'cantidad_inicial' => $cantidadProducida,
                    'estado_revision' => true
                ]);
            }

            $existenciaDestino->save();
        });

        $this->cerrarModal();
        session()->flash('mensaje', 'Soplado ' . ($this->accion === 'create' ? 'guardado' : 'actualizado') . ' correctamente.');
    }


    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset(['soplado_id', 'asignado_id', 'existencia_destino_id', 'cantidad', 'merma', 'estado', 'observaciones', 'fecha', 'codigo', 'sopladoSeleccionado']);
        $this->resetErrorBag();
    }

    public function verDetalleSoplado($id)
    {
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
        if (!$soplado)
            return;

        DB::transaction(function () use ($soplado) {
            $asignado = $soplado->asignado;
            if ($asignado) {
                foreach ($asignado->reposiciones as $reposicion) {
                    DB::table('asignado_reposicions')
                        ->where('asignado_id', $asignado->id)
                        ->where('reposicion_id', $reposicion->id)
                        ->update(['cantidad' => $reposicion->pivot->cantidad_original]);
                }
                $asignado->cantidad = DB::table('asignado_reposicions')->where('asignado_id', $asignado->id)->sum('cantidad');
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

    public static function booted()
    {
        Asignado::deleting(fn($asignado) => $asignado->soplados()->count() > 0 ? throw new \Exception("No se puede eliminar la asignación porque ya tiene soplados registrados.") : null);
    }
}

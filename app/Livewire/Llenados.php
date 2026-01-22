<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Llenado;
use App\Models\Asignado;
use App\Models\Reposicion;
use App\Models\Existencia;
use App\Models\Base;
use App\Models\Tapa;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        $llenadosQuery = Llenado::with(['asignado', 'reposicion', 'existencia'])
            ->when(
                $this->search,
                fn($q) => $q->where('codigo', 'like', "%{$this->search}%")
                    ->orWhereHas('asignado', fn($q2) => $q2->where('codigo', 'like', "%{$this->search}%"))
            );

        if ($this->soloHoy) {
            $llenadosQuery->whereDate('fecha', Carbon::today());
        }

        if ($rol === 4 && $personal) {
            $sucursalId = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $llenadosQuery->whereHas('existencia', fn($q) => $q->where('sucursal_id', $sucursalId));
        }

        $llenados = $llenadosQuery->get();

        $asignacionesQuery = Asignado::with('reposiciones.existencia.existenciable', 'reposiciones.existencia.sucursal')
            ->where('cantidad', '>', 0)
            ->whereDoesntHave('llenados')
            ->whereHas('reposiciones.existencia', fn($q) => $q->whereIn('existenciable_type', [Base::class, Tapa::class]));

        if ($rol === 4 && $personal) {
            $sucursalId = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $asignacionesQuery->whereHas('reposiciones.existencia', fn($q) => $q->where('sucursal_id', $sucursalId));
        }

        if ($this->busquedaAsignacion) {
            $asignacionesQuery->whereHas(
                'reposiciones.existencia.existenciable',
                fn($q) => $q->where('descripcion', 'like', "%{$this->busquedaAsignacion}%")
            );
        }

        $asignaciones = $asignacionesQuery->get();
        $sucursales = \App\Models\Sucursal::all();

        $this->cargarProductosDestino();

        return view('livewire.llenados', compact('llenados', 'asignaciones', 'sucursales'));
    }


    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset(['llenado_id', 'asignado_id', 'existencia_destino_id', 'cantidad', 'merma', 'estado', 'observaciones', 'fecha', 'codigo', 'llenadoSeleccionado', 'existenciasDestino', 'sucursalSeleccionada']);
        $this->accion = $accion;
        $this->fecha = now();

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

        $this->sucursalSeleccionada = $llenado->asignado->reposiciones->first()->existencia->sucursal_id ?? null;
        $this->cargarProductosDestino();
    }

    public function seleccionarAsignacion($asignado_id)
    {
        $this->asignado_id = $asignado_id;
        $asignado = Asignado::with('reposiciones.existencia.sucursal')->find($asignado_id);
        $this->sucursalSeleccionada = $asignado->reposiciones->first()->existencia->sucursal_id ?? null;
        $this->cargarProductosDestino();
    }

    public function seleccionarSucursal($id)
    {
        $this->sucursalSeleccionada = $id;
        $this->cargarProductosDestino();
    }

    public function cargarProductosDestino()
    {
        $this->existenciasDestino = collect();
        if (!$this->asignado_id)
            return;

        $asignado = Asignado::with('reposiciones.existencia.existenciable')->find($this->asignado_id);
        if (!$asignado)
            return;
        $sucursales = $asignado->reposiciones->map(
            fn($r) =>
            in_array(class_basename($r->existencia->existenciable), ['Base', 'Tapa'])
                ? $r->existencia->sucursal_id
                : null
        )->filter()->unique();

        if ($sucursales->isEmpty())
            return;

        $this->existenciasDestino = Existencia::with('existenciable', 'sucursal')
            ->where('existenciable_type', Producto::class)
            ->whereIn('sucursal_id', $sucursales)
            ->when(
                $this->busquedaDestino,
                fn($q) =>
                $q->whereHas(
                    'existenciable',
                    fn($sub) =>
                    $sub->where('descripcion', 'like', "%{$this->busquedaDestino}%")
                )
            )
            ->get();
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

            foreach ($asignado->reposiciones as $reposicion) {
                $asignado->reposiciones()->updateExistingPivot($reposicion->id, ['cantidad' => 0]);
            }

            if ($this->accion === 'edit' && $this->llenadoSeleccionado && $this->llenadoSeleccionado->reposicion_id) {
                $reposicionDestino = Reposicion::find($this->llenadoSeleccionado->reposicion_id);
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
                    'observaciones' => $this->observaciones ?? 'Reposición creada desde llenado',
                    'estado_revision' => 0
                ]);
            }
            $llenadoData = [
                'codigo' => $this->accion === 'create'
                    ? 'L-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)
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
                $llenadoData['personal_id'] = $personalId;
            }

            $llenado = Llenado::updateOrCreate(['id' => $this->llenado_id], $llenadoData);
            if ($this->accion === 'edit' && $this->llenadoSeleccionado && $this->llenadoSeleccionado->estado == 2) {
                $existenciaDestino->cantidad -= $this->llenadoSeleccionado->cantidad;
            }

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
        session()->flash('mensaje', 'Llenado ' . ($this->accion === 'create' ? 'guardado' : 'actualizado') . ' correctamente.');
    }
    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset(['llenado_id', 'asignado_id', 'existencia_destino_id', 'cantidad', 'merma', 'estado', 'observaciones', 'fecha', 'codigo', 'llenadoSeleccionado']);
        $this->resetErrorBag();
    }

    public function verDetalleLlenado($id)
    {
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
        if (!$llenado)
            return;

        DB::transaction(function () use ($llenado) {
            $asignado = $llenado->asignado;
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

            if ($llenado->estado == 2) {
                $existenciaDestino = $llenado->existencia;
                if ($existenciaDestino) {
                    $existenciaDestino->cantidad -= $llenado->cantidad;
                    $existenciaDestino->cantidad = max(0, $existenciaDestino->cantidad);
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

    public static function booted()
    {
        Asignado::deleting(fn($asignado) => $asignado->llenados()->count() > 0 ? throw new \Exception("No se puede eliminar la asignación porque ya tiene llenados registrados.") : null);
    }
}

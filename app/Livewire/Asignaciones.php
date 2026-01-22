<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asignado;
use App\Models\Existencia;
use App\Models\Reposicion;
use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Asignaciones extends Component
{
    public $modalEliminar = false;
    public $asignacionEliminarId;
    public $searchCodigo = '';
    public $modal = false;
    public $accion = 'create';
    public $asignacion_id;
    public $soloHoy = true;

    public $codigo;
    public $fecha;
    public $motivo;
    public $observaciones;
    public $personal_id;

    public $items = [];
    public $existencias = [];
    public $sucursales = [];
    public $filtroSucursalModal = null;
    public $searchExistencia = '';

    public $modalError = false;
    public $mensajeError = '';
    public $modalDetalle = false;
    public $asignacionSeleccionada;

    public function cargarExistencias()
    {
        $usuario = auth()->user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        $query = Existencia::with(['existenciable', 'sucursal'])
            ->whereHas('reposiciones', function ($q) {
                $q->where('estado_revision', true)
                    ->where('cantidad', '>', 0);
            });
        if ($rol === 4 && $personal) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            if ($sucursal_id)
                $query->where('sucursal_id', $sucursal_id);
        }

        if ($this->filtroSucursalModal)
            $query->where('sucursal_id', $this->filtroSucursalModal);

        if ($this->searchExistencia) {
            $query->whereHas(
                'existenciable',
                fn($q) =>
                $q->where('descripcion', 'like', '%' . $this->searchExistencia . '%')
            );
        }

        $this->existencias = $query->orderBy('id')->get();
    }



    public function updatingSearchExistencia()
    {
        $this->cargarExistencias();
    }

    public function filtrarSucursalModal($id)
    {
        $this->filtroSucursalModal = $id;
        $this->cargarExistencias();
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset(['asignacion_id', 'codigo', 'fecha', 'motivo', 'observaciones', 'items']);
        $this->accion = $accion;

        $usuario = auth()->user();
        $this->personal_id = $usuario->personal->id ?? null;

        if (!$this->personal_id) {
            $this->mensajeError = "No tienes un personal asignado.";
            $this->modalError = true;
            return;
        }

        $this->cargarExistencias();
        $this->sucursales = Sucursal::all();

        if ($accion === 'create') {
            $this->fecha = now()->format('Y-m-d\TH:i');
            $this->codigo = 'A-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $this->items = [];
        } else {
            $this->editar($id);
        }

        $this->modal = true;
    }

    public function agregarExistencia($existenciaId)
    {
        foreach ($this->items as $item) {
            if ($item['existencia_id'] == $existenciaId)
                return;
        }
        $this->items[] = ['existencia_id' => $existenciaId, 'cantidad' => 1];
    }

    public function quitarExistencia($existenciaId)
    {
        $this->items = array_values(array_filter($this->items, fn($item) => $item['existencia_id'] != $existenciaId));
    }

    public function editar($id)
    {
        $asignado = Asignado::with(['reposiciones.existencia.existenciable', 'personal'])->findOrFail($id);

        $this->asignacion_id = $asignado->id;
        $this->codigo = $asignado->codigo;
        $this->fecha = Carbon::parse($asignado->fecha)->format('Y-m-d\TH:i');
        $this->motivo = $asignado->motivo;
        $this->observaciones = $asignado->observaciones;

        $this->personal_id = $asignado->personal_id;

        $this->items = $asignado->reposiciones
            ->groupBy('existencia_id')
            ->map(fn($group) => [
                'existencia_id' => $group->first()->existencia_id,
                'cantidad' => $group->sum('pivot.cantidad')
            ])->values()->toArray();
    }


    public function guardarAsignacion()
    {
        $this->validate([
            'fecha' => 'required|date',
            'motivo' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.existencia_id' => 'required|exists:existencias,id',
            'items.*.cantidad' => 'required|integer|min:1',
        ]);

        $usuario = auth()->user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;
        $sucursalEmpleado = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');

        DB::beginTransaction();

        try {
            foreach ($this->items as $item) {
                $existencia = Existencia::findOrFail($item['existencia_id']);
                if ($rol === 4 && $existencia->sucursal_id != $sucursalEmpleado) {
                    throw new \Exception("No puedes asignar existencias de otra sucursal: {$existencia->existenciable->descripcion}");
                }
            }

            $totalCantidad = array_sum(array_column($this->items, 'cantidad'));

            if ($this->accion === 'edit' && $this->asignacion_id) {
                $asignado = Asignado::findOrFail($this->asignacion_id);
                $asignado->update([
                    'fecha' => $this->fecha,
                    'motivo' => $this->motivo,
                    'observaciones' => $this->observaciones,
                    'cantidad' => $totalCantidad,
                ]);

                foreach ($asignado->reposiciones as $repo) {
                    $repo->cantidad += $repo->pivot->cantidad_original;
                    $repo->save();
                }

                $asignado->reposiciones()->detach();
            } else {
                $asignado = Asignado::create([
                    'codigo' => $this->codigo,
                    'personal_id' => $this->personal_id,
                    'fecha' => $this->fecha,
                    'motivo' => $this->motivo,
                    'observaciones' => $this->observaciones,
                    'cantidad' => $totalCantidad,
                ]);
            }

            foreach ($this->items as $item) {
                $existencia = Existencia::findOrFail($item['existencia_id']);
                $cantidadSolicitada = $item['cantidad'];

                $reposiciones = Reposicion::where('existencia_id', $existencia->id)
                    ->where('estado_revision', true)
                    ->where('cantidad', '>', 0)
                    ->orderBy('fecha')
                    ->get();

                $totalDisponible = $reposiciones->sum('cantidad');
                if ($cantidadSolicitada > $totalDisponible) {
                    throw new \Exception("Stock insuficiente para {$existencia->existenciable->descripcion}");
                }

                $restante = $cantidadSolicitada;
                foreach ($reposiciones as $repo) {
                    if ($restante <= 0)
                        break;

                    $usar = min($repo->cantidad, $restante);
                    $asignado->reposiciones()->attach($repo->id, [
                        'cantidad' => $usar,
                        'cantidad_original' => $usar,
                        'existencia_id' => $existencia->id
                    ]);

                    $repo->cantidad -= $usar;
                    $repo->save();

                    $restante -= $usar;
                }

                $existencia->cantidad -= $cantidadSolicitada;
                $existencia->save();
            }

            DB::commit();
            session()->flash('message', 'Asignación registrada correctamente.');
            $this->cerrarModal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->mensajeError = $e->getMessage();
            $this->modalError = true;
        }
    }

    public function confirmarEliminarAsignacion($id)
    {
        $this->asignacionEliminarId = $id;
        $this->modalEliminar = true;
    }


    public function eliminarConfirmado()
    {
        $id = $this->asignacionEliminarId;
        if (!$id)
            return;

        $asignado = Asignado::with('reposiciones')->findOrFail($id);

        DB::transaction(function () use ($asignado) {
            foreach ($asignado->reposiciones as $repo) {
                $repo->cantidad += $repo->pivot->cantidad_original;
                $repo->save();
            }
            $asignado->reposiciones()->detach();
            $asignado->delete();
        });

        $this->modalEliminar = false;
        $this->asignacionEliminarId = null;

        session()->flash('message', 'Asignación eliminada correctamente.');
    }


    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset(['codigo', 'fecha', 'motivo', 'observaciones', 'items']);
    }

    public function modaldetalle($id)
    {
        $this->asignacionSeleccionada = Asignado::with(['reposiciones.existencia.existenciable', 'personal'])->find($id);
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->asignacionSeleccionada = null;
    }

    public function render()
    {
        $usuario = auth()->user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        $query = Asignado::with(['reposiciones.existencia.existenciable', 'personal']);

        if ($rol === 4 && $personal) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $query->whereHas('reposiciones.existencia', fn($q) => $q->where('sucursal_id', $sucursal_id));
        }

        $asignaciones = $query
            ->when($this->searchCodigo, fn($q) => $q->where('codigo', 'like', '%' . $this->searchCodigo . '%'))
            ->when($this->soloHoy, fn($q) => $q->whereDate('fecha', Carbon::today()))
            ->latest()
            ->get();

        $this->sucursales = Sucursal::all();

        return view('livewire.asignaciones', compact('asignaciones'));
    }
}

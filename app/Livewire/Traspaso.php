<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Traspaso as TraspasoModel;
use App\Models\Reposicion;
use App\Models\Personal;
use App\Models\Asignado;
use App\Models\Existencia;
use Illuminate\Support\Facades\DB;

class Traspaso extends Component
{
    public $confirmingDeleteId = null;
    public $modal = false;
    public $modalError = false;
    public $detalleModal = false;
    public $mensajeError = '';
    public $accion = 'create';
    public $traspaso_id;
    public $codigo;
    public $fecha_traspaso;
    public $origen_id;
    public $destino_id;
    public $cantidad;
    public $personal_id;
    public $observaciones;
    public $search = '';
    public $traspasos;
    public $reposicionesOrigen;
    public $reposicionesDestino;
    public $personals;
    public $traspasoSeleccionado = null;

    public function mount()
    {
        $usuario = auth()->user();
        $this->reposicionesOrigen = collect();
        $this->reposicionesDestino = collect();
        $this->traspasos = collect();
        $this->personals = $usuario->rol_id == 1 ? Personal::all() : collect([$usuario->personal]);
        $this->personal_id = $usuario->personal->id ?? null;
        $this->cargarReposiciones($usuario);
    }

    private function cargarReposiciones($usuario)
    {
        $sucursalId = $usuario->rol_id == 2
            ? optional($usuario->personal->trabajos()->where('estado', 1)->first())->sucursal_id
            : null;

        $query = Asignado::with('reposiciones.existencia')
            ->when($usuario->rol_id == 2, function ($q) use ($sucursalId) {
                $q->whereHas(
                    'reposiciones.existencia',
                    fn($q2) =>
                    $q2->where('sucursal_id', $sucursalId)
                );
            });

        if ($this->accion === 'create') {
            $query->whereHas('reposiciones', function ($q) {
                $q->where('asignado_reposicions.cantidad', '>', 0);
            });
        }

        if ($this->accion === 'edit' && $this->traspaso_id) {
            $traspaso = TraspasoModel::find($this->traspaso_id);
            $query->where(function ($q) use ($traspaso) {
                $q->whereHas('reposiciones', function ($q2) {
                    $q2->where('asignado_reposicions.cantidad', '>', 0);
                })
                    ->orWhere('id', $traspaso->asignacion_id);
            });
        }

        $asignados = $query->get()->map(function ($a) {
            $total = $a->reposiciones->sum(function ($r) {
                return $r->pivot->cantidad;
            });
            return (object) [
                'asignacion' => $a,
                'existencia' => optional($a->reposiciones->first())->existencia,
                'totalDisponible' => $total,
            ];
        });

        $asignados = $asignados->filter(function ($item) {
            $asignacion = $item->asignacion;
            $tipos = $asignacion->reposiciones
                ->pluck('existencia.existenciable_type')
                ->unique()
                ->values();
            return $tipos->count() === 1;
        });

        $this->reposicionesOrigen = $asignados->values();
        $this->reposicionesDestino = $this->filtrarDestinosPorTipo($this->origen_id);
    }


    private function filtrarDestinosPorTipo($origen_id)
    {
        if (!$origen_id)
            return collect();

        $origen = Asignado::with('reposiciones.existencia.existenciable')->find($origen_id);
        $existenciaOrigen = optional($origen->reposiciones->first())->existencia;
        $tipoOrigen = optional($existenciaOrigen)->existenciable_type;
        $sucursalOrigenId = optional($existenciaOrigen)->sucursal_id;

        if (!$tipoOrigen)
            return collect();

        return Existencia::with('existenciable', 'sucursal')
            ->where('sucursal_id', '!=', $sucursalOrigenId)
            ->where('existenciable_type', $tipoOrigen)
            ->get()
            ->map(fn($ex) => (object) [
                'existencia' => $ex,
                'totalDisponible' => $ex->cantidad
            ]);
    }

    public function updatedOrigenId($value)
    {
        $this->reposicionesDestino = $this->filtrarDestinosPorTipo($value);
    }

    public function abrirModal($accion, $id = null)
    {
        $this->accion = $accion;
        $this->modal = true;
        $this->reset(['origen_id', 'destino_id', 'cantidad', 'observaciones']);
        $usuario = auth()->user();

        if ($accion === 'create') {
            $this->codigo = 'T-' . now()->format('Ymd-His');
            $this->fecha_traspaso = now()->toDateTimeString();
            $this->traspaso_id = null;
            $this->cargarReposiciones($usuario);
            return;
        }

        if ($accion === 'edit') {
            $traspaso = TraspasoModel::with([
                'asignacion.reposiciones.existencia',
                'reposicionDestino.existencia'
            ])->findOrFail($id);

            $this->traspaso_id = $traspaso->id;
            $this->codigo = $traspaso->codigo;
            $this->fecha_traspaso = $traspaso->fecha_traspaso;
            $this->cantidad = $traspaso->cantidad;
            $this->observaciones = $traspaso->observaciones;
            $this->origen_id = $traspaso->asignacion_id;
            $this->destino_id = optional($traspaso->reposicionDestino)->existencia_id;

            $this->cargarReposiciones($usuario);
        }
    }

    public function guardar()
    {
        $this->validate([
            'origen_id' => 'required|exists:asignados,id',
            'destino_id' => 'required|exists:existencias,id',
        ]);

        $usuario = auth()->user();
        $personal = $usuario->personal;

        DB::transaction(function () use ($personal) {

            $asignacionOrigen = Asignado::with('reposiciones.existencia')->findOrFail($this->origen_id);
            $existenciaDestino = Existencia::findOrFail($this->destino_id);

            $cantidadTotal = 0;
            foreach ($asignacionOrigen->reposiciones as $repos) {
                $cantidadEnPivot = $repos->pivot->cantidad > 0
                    ? $repos->pivot->cantidad
                    : $repos->pivot->cantidad_original;

                $cantidadTotal += $cantidadEnPivot;

                $asignacionOrigen->reposiciones()
                    ->updateExistingPivot($repos->id, ['cantidad' => 0]);
            }

            if ($cantidadTotal <= 0) {
                throw new \Exception("No hay stock disponible para traspasar.");
            }

            $reposicionDestino = Reposicion::create([
                'existencia_id' => $existenciaDestino->id,
                'cantidad' => $cantidadTotal,
                'cantidad_inicial' => $cantidadTotal,
                'estado_revision' => true,
                'fecha' => now(),
                'codigo' => 'R-' . now()->format('YmdHis'),
                'personal_id' => $personal->id,
                'observaciones' => $this->observaciones ?? 'Reposición creada desde traspaso',
            ]);

            $existenciaDestino->cantidad += $cantidadTotal;
            $existenciaDestino->save();

            TraspasoModel::create([
                'codigo' => 'T-' . now()->format('YmdHis'),
                'asignacion_id' => $asignacionOrigen->id,
                'reposicion_destino_id' => $reposicionDestino->id,
                'personal_id' => $personal->id,
                'cantidad' => $cantidadTotal,
                'fecha_traspaso' => now(),
                'observaciones' => $this->observaciones,
            ]);
        });

        $this->cerrarModal();
        session()->flash('message', 'Traspaso realizado correctamente.');
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->modalError = false;
        $this->detalleModal = false;
        $this->reset(['traspaso_id', 'origen_id', 'destino_id', 'cantidad', 'observaciones']);
    }

    public function verDetalle($id)
    {
        $this->traspasoSeleccionado = TraspasoModel::with([
            'personal',
            'asignacion.reposiciones.existencia.sucursal',
            'reposicionDestino.existencia.sucursal'
        ])->findOrFail($id);

        $this->modal = false;
        $this->modalError = false;
        $this->detalleModal = true;
    }

    public function render()
    {
        $usuario = auth()->user();

        if (!$this->modal) {
            $this->cargarReposiciones($usuario);
        }

        $query = TraspasoModel::with([
            'personal',
            'asignacion.reposiciones.existencia',
            'reposicionDestino.existencia',
        ]);

        if ($usuario->rol_id == 2) {
            $query->where('personal_id', $usuario->personal->id);
        }

        if ($this->search) {
            $query->where('codigo', 'like', "%{$this->search}%");
        }

        $this->traspasos = $query->latest()->get();

        return view('livewire.traspaso');
    }

    public function confirmarEliminar($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function eliminarTraspaso()
    {
        if (!$this->confirmingDeleteId)
            return;

        $traspaso = TraspasoModel::with(['reposicionDestino', 'asignacion.reposiciones'])
            ->findOrFail($this->confirmingDeleteId);

        DB::transaction(function () use ($traspaso) {

            $asignacion = $traspaso->asignacion;

            if ($asignacion) {
                foreach ($asignacion->reposiciones as $reposicion) {
                    DB::table('asignado_reposicions')
                        ->where('asignado_id', $asignacion->id)
                        ->where('reposicion_id', $reposicion->id)
                        ->update(['cantidad' => $reposicion->pivot->cantidad_original]);
                }

                $asignacion->cantidad = DB::table('asignado_reposicions')
                    ->where('asignado_id', $asignacion->id)
                    ->sum('cantidad');

                $asignacion->save();
            }

            if ($traspaso->reposicionDestino) {
                $existenciaDestino = $traspaso->reposicionDestino->existencia;

                if ($existenciaDestino) {
                    $existenciaDestino->cantidad -= $traspaso->cantidad;
                    if ($existenciaDestino->cantidad < 0) {
                        $existenciaDestino->cantidad = 0;
                    }
                    $existenciaDestino->save();
                }

                $traspaso->reposicionDestino->delete();
            }

            $traspaso->delete();
        });

        $this->confirmingDeleteId = null;
        session()->flash('message', 'Traspaso eliminado correctamente.');
    }

    public function cancelarEliminar()
    {
        $this->confirmingDeleteId = null;
    }

    public function guardarObservaciones()
    {
        $this->validate([
            'observaciones' => 'nullable|string|max:500',
        ]);

        $traspaso = TraspasoModel::findOrFail($this->traspaso_id);
        $traspaso->observaciones = $this->observaciones;
        $traspaso->save();

        $this->cerrarModal();
        session()->flash('message', 'Observaciones actualizadas correctamente.');
    }
}

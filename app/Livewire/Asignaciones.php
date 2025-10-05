<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asignado;
use App\Models\Existencia;
use App\Models\Reposicion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class Asignaciones extends Component
{
    public $searchCodigo = '';
    public $modal = false;
    public $accion = 'create';
    public $asignacion_id;
    public $codigo;
    public $existencia_id;
    public $personal_id;
    public $cantidad;
    public $cantidad_original;
    public $fecha;
    public $motivo;
    public $observaciones;
    public $existencias = [];
    public $modalError = false;
    public $mensajeError = '';
    public $confirmingDeleteAsignacionId = null;

    public function abrirModal($accion = 'create', $id = null)
    {
        // Resetear los campos
        $this->reset([
            'asignacion_id',
            'existencia_id',
            'cantidad',
            'cantidad_original',
            'fecha',
            'observaciones',
            'motivo',
            'codigo',
        ]);

        $this->accion = $accion;

        $usuario = auth()->user();
        $rol = $usuario->rol_id;

        if (!in_array($rol, [1, 2])) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $personal = $usuario->personal;
        if (!$personal) {
            $this->mensajeError = "No estás asignado a ningún personal válido.";
            $this->modalError = true;
            return;
        }

        $this->personal_id = $personal->id;

        // Construir query de existencias
        $query = Existencia::with('existenciable', 'sucursal')
            ->whereHas('reposiciones', fn($q) => $q->where('cantidad', '>', 0));

        // Si el rol es 2 y tiene sucursal asignada, filtrar por sucursal
        if ($rol === 2) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');

            if ($sucursal_id) {
                $query->where('sucursal_id', $sucursal_id);
            } else {
                // Opcional: mostrar advertencia pero no bloquear
                $this->mensajeError = "No tienes una sucursal asignada. Mostrando todas las existencias disponibles.";
                $this->modalError = true;
            }
        }

        // Obtener existencias ordenadas
        $this->existencias = $query->orderBy('id')->get();

        // Inicializar datos para creación
        if ($accion === 'create') {
            $this->fecha = now()->format('Y-m-d\TH:i');
            $this->codigo = 'A-' . now()->format('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        }

        // Si es edición, cargar datos
        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }




    public function editar($id)
    {
        $asignado = Asignado::findOrFail($id);
        $this->asignacion_id = $asignado->id;
        $this->codigo = $asignado->codigo;
        $this->existencia_id = $asignado->existencia_id;
        $this->personal_id = $asignado->personal_id;
        $this->cantidad = $asignado->cantidad;
        $this->cantidad_original = $asignado->cantidad;
        $this->fecha = $asignado->fecha;
        $this->motivo = $asignado->motivo;
        $this->observaciones = $asignado->observaciones;
    }

    public function guardarAsignacion()
    {
        $usuario = auth()->user();
        if ($this->accion === 'edit' && $this->asignacion_id) {
            $asignado = Asignado::findOrFail($this->asignacion_id);
            $this->personal_id = $asignado->personal_id;
        } else {
            $this->personal_id = $usuario->personal->id ?? null;
        }
        $validator = Validator::make([
            'existencia_id' => $this->existencia_id,
            'personal_id' => $this->personal_id,
            'cantidad' => $this->cantidad,
            'fecha' => $this->fecha,
            'motivo' => $this->motivo,
            'observaciones' => $this->observaciones,
        ], [
            'existencia_id' => 'required|exists:existencias,id',
            'personal_id' => 'required|exists:personals,id',
            'cantidad' => 'required|integer|min:1',
            'fecha' => 'required|date',
            'motivo' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:500',
        ]);
        if ($validator->fails()) {
            $this->mensajeError = implode("\n", $validator->errors()->all());
            $this->modalError = true;
            return;
        }
        $existencia = Existencia::findOrFail($this->existencia_id);
        $cantidadSolicitada = $this->cantidad;
        $totalDisponible = Reposicion::where('existencia_id', $this->existencia_id)
            ->sum('cantidad');

        if ($cantidadSolicitada > $totalDisponible) {
            $this->mensajeError = "La cantidad solicitada supera el stock total disponible ({$totalDisponible}).";
            $this->modalError = true;
            return;
        }
        DB::transaction(function () use ($existencia, $cantidadSolicitada) {
            $restante = $cantidadSolicitada;

            if ($this->accion === 'edit' && $this->asignacion_id) {
                $asignado = Asignado::findOrFail($this->asignacion_id);
                $fechaFormateada = \Carbon\Carbon::parse($this->fecha)->format('Y-m-d H:i:s');
                foreach ($asignado->reposiciones as $lote) {
                    $lote->cantidad += $lote->pivot->cantidad;
                    $lote->save();
                }
                $existencia->cantidad += $asignado->cantidad;
                $asignado->reposiciones()->detach();

                $asignado->update([
                    'existencia_id' => $this->existencia_id,
                    'personal_id' => $this->personal_id,
                    'cantidad' => $cantidadSolicitada,
                    'cantidad_original' => $this->cantidad_original,
                    'motivo' => $this->motivo,
                    'observaciones' => $this->observaciones,
                    'fecha' => $fechaFormateada,
                ]);
            } else {
                $fechaFormateada = \Carbon\Carbon::parse($this->fecha)->format('Y-m-d H:i:s');

                $asignado = Asignado::create([
                    'codigo' => $this->codigo,
                    'existencia_id' => $this->existencia_id,
                    'personal_id' => $this->personal_id,
                    'cantidad' => $cantidadSolicitada,
                    'cantidad_original' => $cantidadSolicitada,
                    'fecha' => $fechaFormateada,
                    'motivo' => $this->motivo,
                    'observaciones' => $this->observaciones,
                ]);
            }
            $lotes = Reposicion::where('existencia_id', $this->existencia_id)
                ->where('cantidad', '>', 0)
                ->orderBy('fecha')
                ->get();

            foreach ($lotes as $lote) {
                if ($restante <= 0) break;

                $usar = min($restante, $lote->cantidad);
                $asignado->reposiciones()->attach($lote->id, ['cantidad' => $usar]);
                $lote->cantidad -= $usar;
                $lote->save();

                $restante -= $usar;
            }

            $existencia->cantidad -= $cantidadSolicitada;
            $existencia->save();
        });

        $this->cerrarModal();
        session()->flash('message', 'Asignación guardada correctamente!');
    }


    public function eliminarAsignacion($id)
    {
        $asignado = Asignado::with('reposiciones')->findOrFail($id);
        $existencia = Existencia::findOrFail($asignado->existencia_id);

        DB::transaction(function () use ($asignado, $existencia) {
            foreach ($asignado->reposiciones as $lote) {
                $cantidadAsignada = $lote->pivot->cantidad;
                $lote->cantidad += $cantidadAsignada;
                $lote->save();
            }
            $existencia->cantidad += $asignado->cantidad;
            $existencia->save();
            $asignado->reposiciones()->detach();
            $asignado->delete();
        });

        session()->flash('message', 'Asignación eliminada y lotes restaurados correctamente!');
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'asignacion_id',
            'codigo',
            'existencia_id',
            'personal_id',
            'cantidad',
            'cantidad_original',
            'fecha',
            'motivo',
            'observaciones',
        ]);
    }

    public function render()
    {
        $usuario = auth()->user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        $query = Asignado::with('existencia.existenciable', 'personal');
        if ($rol === 2 && $personal) {
            $sucursal_id = $personal->trabajos()
                ->latest('fechaInicio')
                ->value('sucursal_id');

            $query->whereHas('existencia', fn($q) => $q->where('sucursal_id', $sucursal_id));
        }

        $asignaciones = $query
            ->when($this->searchCodigo, fn($q) => $q->where('codigo', 'like', '%' . $this->searchCodigo . '%'))
            ->latest()
            ->get();

        return view('livewire.asignaciones', compact('asignaciones'));
    }

    public function confirmarEliminarAsignacion($id)
    {
        $this->confirmingDeleteAsignacionId = $id;
    }

    public function eliminarAsignacionConfirmado()
    {
        if (!$this->confirmingDeleteAsignacionId) return;

        $this->eliminarAsignacion($this->confirmingDeleteAsignacionId);

        $this->confirmingDeleteAsignacionId = null;
    }
}

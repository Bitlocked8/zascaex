<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Asignacion as ModeloAsignacion;
use App\Models\Coche;
use App\Models\Personal;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Asignacion extends Component
{
    use WithPagination;
    // use LivewireAlert;

    public $search = '';
    public $modal = false;
    public $detalleModal = false;
    public $accion = 'create';
    public $asignacionId = null;
    public $fechaInicio = '';
    public $fechaFinal = '';
    public $estado = 1;
    public $coche_id = '';
    public $personal_id = '';
    public $coches;
    public $personals;
    public $asignacionSeleccionada = null;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'fechaInicio' => 'required|date',
        'fechaFinal' => 'required|date|after_or_equal:fechaInicio',
        'estado' => 'required|boolean',
        'coche_id' => 'required|exists:coches,id',
        'personal_id' => 'required|exists:personals,id',
    ];

    public function mount()
    {
        $this->coches = Coche::where('estado', 1)->get(); // Solo coches activos
        $this->personals = Personal::all(); // Ajusta según tu lógica para personal
    }

    public function render()
    {
        $asignaciones = ModeloAsignacion::with('coche', 'personal')
            ->when($this->search, function ($query) {
                $query->whereHas('coche', function ($q) {
                    $q->where('placa', 'like', '%' . $this->search . '%')
                      ->orWhere('marca', 'like', '%' . $this->search . '%')
                      ->orWhere('modelo', 'like', '%' . $this->search . '%');
                })->orWhereHas('personal', function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%');
                });
            })->paginate(5);

        return view('livewire.asignacion', compact('asignaciones'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function abrirModal($accion)
    {
        $this->reset(['fechaInicio', 'fechaFinal', 'estado', 'coche_id', 'personal_id', 'asignacionId']);
        $this->accion = $accion;
        $this->fechaInicio = now()->format('Y-m-d');
        $this->fechaFinal = now()->format('Y-m-d');
        $this->estado = 1;
        $this->modal = true;
        $this->detalleModal = false;
    }

    public function editarAsignacion($id)
    {
        $asignacion = ModeloAsignacion::findOrFail($id);
        $this->asignacionId = $asignacion->id;
        $this->fechaInicio = $asignacion->fechaInicio;
        $this->fechaFinal = $asignacion->fechaFinal;
        $this->estado = $asignacion->estado;
        $this->coche_id = $asignacion->coche_id;
        $this->personal_id = $asignacion->personal_id;
        $this->accion = 'edit';
        $this->modal = true;
        $this->detalleModal = false;
    }

    public function verDetalle($id)
    {
        $this->asignacionSeleccionada = ModeloAsignacion::with('coche', 'personal')->findOrFail($id);
        $this->modal = false;
        $this->detalleModal = true;
    }

    public function guardarAsignacion()
    {
        $this->validate();

        try {
            if ($this->accion === 'edit' && $this->asignacionId) {
                $asignacion = ModeloAsignacion::findOrFail($this->asignacionId);
                $asignacion->update([
                    'fechaInicio' => $this->fechaInicio,
                    'fechaFinal' => $this->fechaFinal,
                    'estado' => $this->estado,
                    'coche_id' => $this->coche_id,
                    'personal_id' => $this->personal_id,
                ]);
                LivewireAlert::title('Asignación actualizada con éxito.')
                    ->success()
                    ->show();
            } else {
                ModeloAsignacion::create([
                    'fechaInicio' => $this->fechaInicio,
                    'fechaFinal' => $this->fechaFinal,
                    'estado' => $this->estado,
                    'coche_id' => $this->coche_id,
                    'personal_id' => $this->personal_id,
                ]);
                LivewireAlert::title('Asignación registrada con éxito.')
                    ->success()
                    ->show();
            }

            $this->cerrarModal();
        } catch (\Exception $e) {
            LivewireAlert::title('Ocurrió un error: ' . $e->getMessage())
                ->error()
                ->show();
        }
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->detalleModal = false;
        $this->reset(['fechaInicio', 'fechaFinal', 'estado', 'coche_id', 'personal_id', 'asignacionId', 'asignacionSeleccionada']);
        $this->resetErrorBag();
    }
}
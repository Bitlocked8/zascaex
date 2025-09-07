<?php

namespace App\Livewire;

use App\Models\Trabajo;
use App\Models\Sucursal;
use App\Models\Personal;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Trabajador extends Component
{
    use WithPagination, WithFileUploads;

    public $fechaInicio = '', $fechaFinal = '', $estado = 1;
    public $sucursal_id = null, $personal_id = null;
    public $trabajo_id = null;
    public $modal = false;
    public $modalDetalle = false;
    public $trabajoSeleccionado = [];

    public $accion = 'create';

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'fechaInicio' => 'required|date',
        'fechaFinal' => 'nullable|date|after_or_equal:fechaInicio',
        'estado' => 'required|boolean',
        'sucursal_id' => 'required|exists:sucursals,id',
        'personal_id' => 'required|exists:personals,id',
    ];

    public function render()
    {
        $trabajos = Trabajo::with(['sucursal', 'personal'])
            ->paginate(5); // Mostrar trabajos con paginación

        $sucursales = Sucursal::all(); // Obtener todas las sucursales
        $personales = Personal::all(); // Obtener todos los trabajadores

        return view('livewire.trabajador', compact('trabajos', 'sucursales', 'personales'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'trabajo_id',
            'fechaInicio',
            'fechaFinal',
            'estado',
            'sucursal_id',
            'personal_id',
        ]);
        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $trabajo = Trabajo::findOrFail($id);
        $this->trabajo_id = $trabajo->id;
        $this->fechaInicio = $trabajo->fechaInicio;
        $this->fechaFinal = $trabajo->fechaFinal;
        $this->estado = $trabajo->estado;
        $this->sucursal_id = $trabajo->sucursal_id;
        $this->personal_id = $trabajo->personal_id;
        $this->accion = 'edit';
    }

    public function guardar()
    {
        $this->validate();

        try {

            $fechaFinal = $this->fechaFinal ? $this->fechaFinal : null;  // Si no se proporciona una fecha, se guarda como null

            Trabajo::updateOrCreate(['id' => $this->trabajo_id], [
                'fechaInicio' => $this->fechaInicio,
                'fechaFinal' => $fechaFinal,
                'estado' => $this->estado,
                'sucursal_id' => $this->sucursal_id,
                'personal_id' => $this->personal_id,

            ]);

            LivewireAlert::title($this->trabajo_id ? 'Trabajo actualizado con éxito.' : 'Trabajo creado con éxito.')
                ->success()
                ->show();

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
        $this->reset(['fechaInicio', 'fechaFinal', 'estado', 'sucursal_id', 'personal_id', 'trabajo_id']);
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $this->trabajoSeleccionado = Trabajo::findOrFail($id);
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->trabajoSeleccionado = null;
    }
}

<?php

namespace App\Livewire;

use App\Models\Trabajo;
use App\Models\Sucursal;
use App\Models\Personal;
use Livewire\Component;

class Trabajador extends Component
{
    public $fechaInicio = '', $fechaFinal = '', $estado = 1;
    public $sucursal_id = null, $personal_id = null;
    public $trabajo_id = null;
    public $modal = false;
    public $modalDetalle = false;
    public $trabajoSeleccionado = null;
    public $accion = 'create';
    public $labor_id = null;

    public function render()
    {
        $trabajos = Trabajo::with(['sucursal', 'personal'])
            ->latest()
            ->get();

        $sucursales = Sucursal::all();
        $personales = Personal::all();

        return view('livewire.trabajador', compact('trabajos', 'sucursales', 'personales'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset(['trabajo_id', 'fechaInicio', 'fechaFinal', 'estado', 'sucursal_id', 'personal_id']);
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
        $this->labor_id = $trabajo->labor_id;
        $this->accion = 'edit';
    }

    public function guardar()
    {
        $this->validate([
            'fechaInicio' => 'required|date',
            'fechaFinal' => 'nullable|date|after_or_equal:fechaInicio',
            'estado' => 'required|boolean',
            'sucursal_id' => 'required|exists:sucursals,id',
            'personal_id' => 'required|exists:personals,id',
            'labor_id' => 'nulleable|exists:labors,id',
        ]);

        $fechaFinal = $this->fechaFinal ?: null;

        Trabajo::updateOrCreate(
            ['id' => $this->trabajo_id],
            [
                'fechaInicio' => $this->fechaInicio,
                'fechaFinal' => $fechaFinal,
                'estado' => $this->estado,
                'sucursal_id' => $this->sucursal_id,
                'personal_id' => $this->personal_id,
                'labor_id' => $this->labor_id,
            ]
        );

        session()->flash('message', $this->trabajo_id ? 'Trabajo actualizado con éxito.' : 'Trabajo creado con éxito.');

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset(['fechaInicio', 'fechaFinal', 'estado', 'sucursal_id', 'personal_id', 'trabajo_id', 'labor_id']);

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

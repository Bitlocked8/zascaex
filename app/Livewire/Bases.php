<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Base;
use App\Models\Existencia;

class Bases extends Component
{
    use WithFileUploads;

    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $imagen;
    public $imagenExistente;
    public $descripcion = '';
    public $base_id = null;
    public $capacidad = '';
    public $estado = 1;
    public $observaciones = '';
    public $accion = 'create';
    public $baseSeleccionada = null;

    protected $messages = [
        'capacidad.required' => 'La capacidad es obligatoria.',
        'capacidad.integer' => 'La capacidad debe ser un número entero.',
        'capacidad.min' => 'La capacidad no puede ser negativa.',
        'estado.required' => 'El estado es obligatorio.',
    ];

    public function render()
    {
        $bases = Base::query()
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where('capacidad', 'like', $searchTerm)
                      ->orWhere('descripcion', 'like', $searchTerm)
                      ->orWhere('observaciones', 'like', $searchTerm);
            })
            ->get();

        return view('livewire.bases', compact('bases'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'base_id', 'capacidad', 'estado', 'descripcion', 
            'observaciones', 'imagen', 'imagenExistente', 'baseSeleccionada'
        ]);

        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $base = Base::findOrFail($id);
        $this->base_id = $base->id;
        $this->capacidad = $base->capacidad;
        $this->estado = $base->estado;
        $this->descripcion = $base->descripcion;
        $this->observaciones = $base->observaciones;
        $this->imagen = null; // Dejar null para solo usar si suben un nuevo archivo
        $this->imagenExistente = $base->imagen; // Guardar imagen existente para mostrar en modal
        $this->accion = 'edit';
        $this->baseSeleccionada = $base;
    }

    public function guardar()
    {
        $this->validate([
            'capacidad' => 'required|integer|min:0',
            'estado' => 'required|boolean',
            'descripcion' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        // Validar y guardar nueva imagen si se sube
        if ($this->imagen && is_object($this->imagen)) {
            $this->validate([
                'imagen' => 'image|max:5120',
            ]);
            $imagenPath = $this->imagen->store('bases', 'public');
        } else {
            $imagenPath = $this->imagenExistente ?? null;
        }

        $base = Base::updateOrCreate(
            ['id' => $this->base_id],
            [
                'capacidad' => $this->capacidad,
                'estado' => $this->estado,
                'descripcion' => $this->descripcion,
                'observaciones' => $this->observaciones,
                'imagen' => $imagenPath,
            ]
        );

        // Crear existencia solo si es nueva base
        if (!$this->base_id) {
            Existencia::create([
                'existenciable_type' => Base::class,
                'existenciable_id' => $base->id,
                'cantidad' => 0,
            ]);
        }

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'base_id', 'capacidad', 'estado', 'descripcion', 
            'observaciones', 'imagen', 'imagenExistente', 'baseSeleccionada'
        ]);
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $this->baseSeleccionada = Base::findOrFail($id);
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->baseSeleccionada = null;
    }
}

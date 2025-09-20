<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Base;
use App\Models\Preforma;

class Bases extends Component
{
    use WithFileUploads;

    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $imagen; // Puede ser UploadedFile o string
    public $descripcion = '';
    public $base_id = null;
    public $capacidad = '';
    public $estado = 1;
    public $observaciones = '';
    public $preforma_id = null;
    public $accion = 'create';
    public $baseSeleccionada = null;
    public $todasLasPreformas = [];

    protected $rules = [
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5MB
        'capacidad' => 'required|integer|min:0',
        'estado' => 'required|boolean',
        'descripcion' => 'nullable|string|max:255',
        'observaciones' => 'nullable|string',
        'preforma_id' => 'nullable|exists:preformas,id',
    ];

    protected $messages = [
        'capacidad.required' => 'La capacidad es obligatoria.',
        'capacidad.integer' => 'La capacidad debe ser un número entero.',
        'capacidad.min' => 'La capacidad no puede ser negativa.',
        'estado.required' => 'El estado es obligatorio.',
        'preforma_id.exists' => 'La preforma seleccionada no es válida.',
    ];

    public function mount()
    {
        $this->todasLasPreformas = Preforma::where('estado', 1)
            ->orderBy('insumo')
            ->get();
    }

    public function render()
    {
        $bases = Base::with('preforma')
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where('capacidad', 'like', $searchTerm)
                    ->orWhereHas('preforma', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('insumo', 'like', $searchTerm);
                    });
            })
            ->get();

        return view('livewire.bases', compact('bases'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'base_id',
            'capacidad',
            'estado',
            'descripcion',
            'observaciones',
            'preforma_id',
            'imagen',
            'baseSeleccionada',
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
        $this->preforma_id = $base->preforma_id;
        $this->accion = 'edit';
        $this->baseSeleccionada = $base;
        $this->imagen = $base->imagen; // Mantener imagen si no se sube nueva
    }

    public function guardar()
    {
        $this->validate();

        if (is_object($this->imagen)) {
            // Nueva imagen
            $imagenPath = $this->imagen->store('bases', 'public');
        } else {
            // Mantener la existente
            $imagenPath = $this->base_id ? Base::find($this->base_id)->imagen : null;
        }

        Base::updateOrCreate(['id' => $this->base_id], [
            'capacidad' => $this->capacidad,
            'estado' => $this->estado,
            'observaciones' => $this->observaciones,
            'preforma_id' => $this->preforma_id ?: null,
            'imagen' => $imagenPath,
            'descripcion' => $this->descripcion,
        ]);

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'base_id',
            'capacidad',
            'estado',
            'descripcion',
            'observaciones',
            'preforma_id',
            'imagen',
            'baseSeleccionada',
        ]);
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $this->baseSeleccionada = Base::with('preforma')->findOrFail($id);
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->baseSeleccionada = null;
    }
}

<?php

namespace App\Livewire;

use Livewire\WithFileUploads;
use App\Models\Base;
use App\Models\Preforma;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Bases extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $imagen;
    public $descripcion = '';
    public $base_id = null;
    public $capacidad = '';
    public $estado = 1;
    public $observaciones = '';
    public $preforma_id = null;
    public $accion = 'create';
    public $baseSeleccionada = null;
    public $todasLasPreformas = [];

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'imagen' => 'nullable|image|max:1024',
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
        $this->todasLasPreformas = Preforma::where('estado', 1)->orderBy('insumo')->get();
    }

    public function render()
    {
        $bases = Base::with(['existencias', 'preforma'])
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where('capacidad', 'like', $searchTerm)
                    ->orWhereHas('preforma', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('insumo', 'like', $searchTerm);
                    });
            })
            ->paginate(4);

        return view('livewire.bases', compact('bases'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset(['capacidad', 'estado', 'imagen', 'descripcion', 'observaciones', 'preforma_id', 'base_id']);
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
        $this->observaciones = $base->observaciones;
        $this->preforma_id = $base->preforma_id;
        $this->accion = 'edit';
    }

    public function guardar()
    {
        $this->validate();

        try {
            if ($this->imagen) {
                $imagenPath = $this->imagen->store('bases', 'public');
            } else {
                $imagenPath = $this->base_id ? Base::find($this->base_id)->imagen : null;
            }

            // Asegúrate de guardar la descripción
            Base::updateOrCreate(['id' => $this->base_id], [
                'capacidad' => $this->capacidad,
                'estado' => $this->estado,
                'observaciones' => $this->observaciones,
                'preforma_id' => $this->preforma_id ?: null,
                'imagen' => $imagenPath,
                'descripcion' => $this->descripcion, // Añadido aquí
            ]);

            LivewireAlert::title($this->base_id ? 'Base actualizada con éxito.' : 'Base creada con éxito.')
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
        $this->reset(['capacidad', 'estado', 'observaciones', 'preforma_id', 'base_id']);
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

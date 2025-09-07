<?php

namespace App\Livewire;

use Livewire\WithFileUploads;
use App\Models\Preforma;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Preformas extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $preforma_id = null;
    public $insumo = '';
    public $descripcion = '';
    public $capacidad = '';
    public $color = '';
    public $estado = 1;
    public $observaciones = '';
    public $accion = 'create';
    public $preformaSeleccionada = [];
    public $imagen;
    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'insumo' => 'required|string',
        'descripcion' => 'nullable|string',
        'capacidad' => 'required|integer',
        'color' => 'required|string',
        'estado' => 'required|boolean',
        'observaciones' => 'nullable|string',
        'imagen' => 'nullable|image|max:1024',
    ];

    public function render()
    {
        $preformas = Preforma::with('existencias')
            ->when($this->search, function ($query) {
                $query->where('insumo', 'like', '%' . $this->search . '%')
                    ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            })
            ->paginate(4);

        return view('livewire.preformas', compact('preformas'));
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset(['insumo', 'descripcion', 'capacidad', 'color', 'estado', 'observaciones']);
        $this->accion = $accion;
        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }
        $this->modal = true;
    }

    public function editar($id)
    {
        $preforma = Preforma::findOrFail($id);
        $this->preforma_id = $preforma->id;
        $this->insumo = $preforma->insumo;
        $this->descripcion = $preforma->descripcion;
        $this->capacidad = $preforma->capacidad;
        $this->color = $preforma->color;
        $this->estado = $preforma->estado;
        $this->observaciones = $preforma->observaciones;
        $this->accion = 'edit';
    }

    public function guardar()
    {
        $this->validate();

        try {
            if ($this->imagen) {
                // Si se sube una nueva imagen, la almacenamos en el directorio 'public/tapas'
                $imagenPath = $this->imagen->store('tapas', 'public');
            } else {
                // Si no hay una nueva imagen, mantenemos la imagen actual (si existe)
                $imagenPath = $this->preforma_id ? Preforma::find($this->preforma_id)->imagen : null;
            }
            Preforma::updateOrCreate(['id' => $this->preforma_id], [
                'imagen' => $imagenPath,
                'insumo' => $this->insumo,
                'descripcion' => $this->descripcion,
                'capacidad' => $this->capacidad,
                'color' => $this->color,
                'estado' => $this->estado,
                'observaciones' => $this->observaciones,
            ]);

            LivewireAlert::title($this->preforma_id ? 'Preforma actualizada con éxito.' : 'Preforma creada con éxito.')
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
        $this->reset(['insumo', 'descripcion', 'capacidad', 'color', 'estado', 'observaciones', 'imagen', 'preforma_id']);
        $this->resetErrorBag();
    }

    // FUNCIONALIDAD PARA MODAL DE DETALLES
    public function modaldetalle($id)
    {
        $this->preformaSeleccionada = Preforma::findOrFail($id);
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->preformaSeleccionada = null;
    }
}

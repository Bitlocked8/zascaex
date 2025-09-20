<?php

namespace App\Livewire;

use App\Models\Preforma;
use Livewire\Component;
use Livewire\WithFileUploads;

class Preformas extends Component
{
    use WithFileUploads;

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
    public $preformaSeleccionada = null; // Modelo Preforma
    public $imagen; // Puede ser UploadedFile o string

    protected $rules = [
        'insumo' => 'required|string|max:255',
        'descripcion' => 'nullable|string|max:255',
        'capacidad' => 'required|integer',
        'color' => 'required|string|max:255',
        'estado' => 'required|boolean',
        'observaciones' => 'nullable|string|max:255',
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5 MB solo jpg/png
    ];

    public function render()
    {
        $preformas = Preforma::with('existencias')
            ->when($this->search, function ($query) {
                $query->where('insumo', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            })
            ->get(); // quitamos la paginación

        return view('livewire.preformas', compact('preformas'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset(['insumo', 'descripcion', 'capacidad', 'color', 'estado', 'observaciones', 'imagen']);
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
        $this->preformaSeleccionada = $preforma; // Para vista previa
        $this->imagen = $preforma->imagen; // Mantener imagen actual si no se sube nueva
    }

    public function guardar()
    {
        $this->validate();

        if (is_object($this->imagen)) {
            // Se subió nueva imagen
            $imagenPath = $this->imagen->store('preformas', 'public');
        } else {
            // Mantener la existente
            $imagenPath = $this->preforma_id ? Preforma::find($this->preforma_id)->imagen : null;
        }

        Preforma::updateOrCreate(['id' => $this->preforma_id], [
            'insumo' => $this->insumo,
            'descripcion' => $this->descripcion,
            'capacidad' => $this->capacidad,
            'color' => $this->color,
            'estado' => $this->estado,
            'observaciones' => $this->observaciones,
            'imagen' => $imagenPath,
        ]);

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset(['insumo', 'descripcion', 'capacidad', 'color', 'estado', 'observaciones', 'imagen', 'preforma_id', 'preformaSeleccionada']);
        $this->resetErrorBag();
    }

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

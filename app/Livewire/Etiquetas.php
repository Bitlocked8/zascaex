<?php

namespace App\Livewire;

use App\Models\Etiqueta;
use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithFileUploads;

class Etiquetas extends Component
{
    use WithFileUploads;

    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $etiqueta_id = null;
    public $imagen; // Puede ser UploadedFile o string
    public $capacidad = '';
    public $unidad = '';
    public $estado = 1;
    public $cliente_id = '';
    public $clientes;
    public $descripcion = '';
    public $accion = 'create';
    public $etiquetaSeleccionada = null; // Modelo Etiqueta

    protected $rules = [
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5 MB, solo jpg/png
        'capacidad' => 'required|string|max:255',
        'unidad' => 'nullable|in:L,ml,g,Kg,unidad',
        'descripcion' => 'nullable|string|max:255',
        'estado' => 'required|boolean',
        'cliente_id' => 'nullable|exists:clientes,id',
    ];

    public function mount()
    {
        $this->clientes = Cliente::all();
    }

    public function render()
    {
        $etiquetas = Etiqueta::with('existencias')
            ->when($this->search, function ($query) {
                $query->where('capacidad', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            })
            ->get(); // quitamos la paginación

        return view('livewire.etiquetas', compact('etiquetas'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset(['imagen', 'capacidad', 'unidad', 'estado', 'cliente_id', 'descripcion']);
        $this->accion = $accion;
        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }
        $this->modal = true;
    }

    public function editar($id)
    {
        $etiqueta = Etiqueta::findOrFail($id);
        $this->etiqueta_id = $etiqueta->id;
        $this->capacidad = $etiqueta->capacidad;
        $this->unidad = $etiqueta->unidad;
        $this->estado = $etiqueta->estado;
        $this->descripcion = $etiqueta->descripcion;
        $this->cliente_id = $etiqueta->cliente_id;
        $this->accion = 'edit';
        $this->etiquetaSeleccionada = $etiqueta; // para vista previa de imagen
        $this->imagen = $etiqueta->imagen; // mantener la imagen actual si no se sube nueva
    }

    public function guardar()
    {
        $this->validate();

        if (is_object($this->imagen)) {
            // Se subió nueva imagen
            $imagenPath = $this->imagen->store('etiquetas', 'public');
        } else {
            // Mantener la existente
            $imagenPath = $this->etiqueta_id ? Etiqueta::find($this->etiqueta_id)->imagen : null;
        }

        Etiqueta::updateOrCreate(['id' => $this->etiqueta_id], [
            'imagen' => $imagenPath,
            'capacidad' => $this->capacidad,
            'unidad' => $this->unidad,
            'estado' => $this->estado,
            'descripcion' => $this->descripcion,
            'cliente_id' => $this->cliente_id ?: null,
        ]);

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset(['imagen', 'capacidad', 'unidad', 'estado', 'cliente_id', 'etiqueta_id', 'descripcion', 'etiquetaSeleccionada']);
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $this->etiquetaSeleccionada = Etiqueta::findOrFail($id);
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->etiquetaSeleccionada = null;
    }
}

<?php

namespace App\Livewire;

use App\Models\Tapa;
use Livewire\Component;
use Livewire\WithFileUploads;

class Tapas extends Component
{
    use WithFileUploads;

    public $descripcion = '';
    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $tapa_id = null;
    public $color = '';
    public $tipo = '';
    public $estado = 1;
    public $imagen; // Puede ser UploadedFile o string
    public $accion = 'create';
    public $tapaSeleccionada = null; // Modelo Tapa

    protected $rules = [
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        'color' => 'required|string|max:255',
        'tipo' => 'required|string|max:255',
        'estado' => 'required|boolean',
        'descripcion' => 'nullable|string|max:255',
    ];

    public function render()
    {
        $tapas = Tapa::with('existencias')
            ->when($this->search, function ($query) {
                $query->where('color', 'like', '%' . $this->search . '%')
                    ->orWhere('tipo', 'like', '%' . $this->search . '%');
            })
            ->get();

        return view('livewire.tapas', compact('tapas'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'tapa_id',
            'color',
            'tipo',
            'descripcion',
            'estado',
            'imagen',
        ]);

        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $tapa = Tapa::findOrFail($id);
        $this->tapa_id = $tapa->id;
        $this->color = $tapa->color;
        $this->tipo = $tapa->tipo;
        $this->estado = $tapa->estado;
        $this->descripcion = $tapa->descripcion;
        $this->accion = 'edit';
        $this->tapaSeleccionada = $tapa; // ← importante para mostrar la imagen existente
        $this->imagen = $tapa->imagen;   // ← asignamos la ruta actual si no se sube nueva
    }

    public function guardar()
    {
        $this->validate();

        // Manejar la imagen
        if (is_object($this->imagen)) {
            $imagenPath = $this->imagen->store('tapas', 'public');
        } else {
            $imagenPath = $this->tapa_id ? Tapa::find($this->tapa_id)->imagen : null;
        }

        // Crear o actualizar la Tapa
        $tapa = Tapa::updateOrCreate(['id' => $this->tapa_id], [
            'color' => $this->color,
            'tipo' => $this->tipo,
            'descripcion' => $this->descripcion,
            'estado' => $this->estado,
            'imagen' => $imagenPath,
        ]);

        // Crear existencia automática si es una nueva Tapa
        if (!$this->tapa_id) {
            \App\Models\Existencia::create([
                'existenciable_type' => Tapa::class,
                'existenciable_id' => $tapa->id,
                'cantidad' => 0, // siempre 0 al principio
            ]);
        }

        $this->cerrarModal();
    }


    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'tapa_id',
            'color',
            'tipo',
            'descripcion',
            'estado',
            'imagen',
            'tapaSeleccionada',
        ]);
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $this->tapaSeleccionada = Tapa::findOrFail($id);
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->tapaSeleccionada = null;
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tapa;
use App\Models\Existencia;

class Tapas extends Component
{
    use WithFileUploads;

    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $tapa_id = null;

    public $imagen;
    public $imagenExistente;
    public $descripcion = '';
    public $color = '';
    public $tipo = '';
    public $estado = 1;

    public $accion = 'create';
    public $tapaSeleccionada = null;

    protected $messages = [
        'color.required' => 'El color es obligatorio.',
        'tipo.required' => 'El tipo es obligatorio.',
        'estado.required' => 'El estado es obligatorio.',
    ];

    public function render()
    {
        $tapas = Tapa::query()
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where('color', 'like', $searchTerm)
                      ->orWhere('tipo', 'like', $searchTerm)
                      ->orWhere('descripcion', 'like', $searchTerm);
            })
            ->get();

        return view('livewire.tapas', compact('tapas'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'tapa_id', 'color', 'tipo', 'descripcion',
            'estado', 'imagen', 'imagenExistente', 'tapaSeleccionada'
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
        $this->imagen = null; // solo si suben nueva
        $this->imagenExistente = $tapa->imagen;
        $this->tapaSeleccionada = $tapa;
        $this->accion = 'edit';
    }

    public function guardar()
    {
        $this->validate([
            'color' => 'required|string|max:100',
            'tipo' => 'required|string|max:100',
            'estado' => 'required|boolean',
            'descripcion' => 'nullable|string|max:255',
        ]);

        if ($this->imagen && is_object($this->imagen)) {
            $this->validate([
                'imagen' => 'image|max:5120', // 5MB
            ]);
            $imagenPath = $this->imagen->store('tapas', 'public');
        } else {
            $imagenPath = $this->imagenExistente ?? null;
        }

        $tapa = Tapa::updateOrCreate(
            ['id' => $this->tapa_id],
            [
                'color' => $this->color,
                'tipo' => $this->tipo,
                'estado' => $this->estado,
                'descripcion' => $this->descripcion,
                'imagen' => $imagenPath,
            ]
        );

        if (!$this->tapa_id) {
            Existencia::create([
                'existenciable_type' => Tapa::class,
                'existenciable_id' => $tapa->id,
                'cantidad' => 0,
            ]);
        }

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'tapa_id', 'color', 'tipo', 'descripcion',
            'estado', 'imagen', 'imagenExistente', 'tapaSeleccionada'
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

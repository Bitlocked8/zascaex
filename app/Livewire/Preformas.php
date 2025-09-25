<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Preforma;
use App\Models\Existencia;



class Preformas extends Component
{
    use WithFileUploads;

    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $preforma_id = null;
    public $descripcion = '';
    public $estado = 1;
    public $observaciones = '';
    public $imagen; 
    public $imagenExistente;
    public $accion = 'create';
    public $preformaSeleccionada = null;

    protected $messages = [
        'estado.required' => 'El estado es obligatorio.',
    ];

    public function render()
    {
        $preformas = Preforma::query()
            ->when($this->search, fn($q) => $q->where('descripcion', 'like', "%{$this->search}%"))
            ->get();

        return view('livewire.preformas', compact('preformas'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset(['descripcion', 'estado', 'observaciones', 'imagen', 'imagenExistente', 'preforma_id', 'preformaSeleccionada']);
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
        $this->descripcion = $preforma->descripcion;
        $this->estado = $preforma->estado;
        $this->observaciones = $preforma->observaciones;
        $this->imagen = null; 
        $this->imagenExistente = $preforma->imagen; 
        $this->accion = 'edit';
        $this->preformaSeleccionada = $preforma;
    }

    public function guardar()
    {
        $this->validate([
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'required|boolean',
            'observaciones' => 'nullable|string|max:255',
        ]);

        if ($this->imagen && is_object($this->imagen)) {
            $this->validate([
                'imagen' => 'image|max:5120',
            ]);
            $imagenPath = $this->imagen->store('preformas', 'public');
        } else {
            $imagenPath = $this->imagenExistente ?? null;
        }
        $preforma = Preforma::updateOrCreate(
            ['id' => $this->preforma_id],
            [
                'descripcion' => $this->descripcion,
                'estado' => $this->estado,
                'observaciones' => $this->observaciones,
                'imagen' => $imagenPath,
            ]
        );
        if (!$this->preforma_id) {
            Existencia::create([
                'existenciable_type' => Preforma::class,
                'existenciable_id' => $preforma->id,
                'cantidad' => 0,
            ]);
        }

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset(['descripcion', 'estado', 'observaciones', 'imagen', 'imagenExistente', 'preforma_id', 'preformaSeleccionada']);
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

<?php

namespace App\Livewire;

use App\Models\Tapa;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Tapas extends Component
{
    use WithPagination, WithFileUploads;
    public $descripcion = '';
    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $tapa_id = null;
    public $color = '';
    public $tipo = '';
    public $estado = 1;
    public $imagen;
    public $accion = 'create';
    public $tapaSeleccionada = [];

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'imagen' => 'nullable|image|max:1024',
        'color' => 'required|string|max:255',
        'tipo' => 'required|string|max:255',
        'estado' => 'required|boolean',
        'descripcion' => 'nullable|string|max:255',

    ];

    public function render()
    {
        $tapas = Tapa::with('existencias') // Cargar las existencias relacionadas
            ->when($this->search, function ($query) {
                $query->where('color', 'like', '%' . $this->search . '%')
                    ->orWhere('tipo', 'like', '%' . $this->search . '%');
            })
            ->paginate(4);

        return view('livewire.tapas', compact('tapas'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
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
    }

    public function guardar()
    {
        $this->validate();

        try {


            if ($this->imagen) {
                $imagenPath = $this->imagen->store('tapas', 'public');
            } else {
                // Si no hay una nueva imagen, mantener la imagen actual si existe
                $imagenPath = $this->tapa_id ? Tapa::find($this->tapa_id)->imagen : null;
            }

            Tapa::updateOrCreate(['id' => $this->tapa_id], [
                'color' => $this->color,
                'tipo' => $this->tipo,
                'descripcion' => $this->descripcion,
                'estado' => $this->estado,
                'imagen' => $imagenPath,
            ]);

            LivewireAlert::title($this->tapa_id ? 'Tapa actualizada con éxito.' : 'Tapa creada con éxito.')
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
        $this->reset(['color', 'tipo', 'descripcion', 'estado', 'imagen', 'tapa_id']);

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

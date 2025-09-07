<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Rol;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Roles extends Component
{
    use WithPagination;

    public $search = '';
    public $modal = false;
    public $detalleModal = false;
    public $accion = 'create';
    public $rolId = null;

    public $nombre = '';
    public $descripcion = '';

    public $rolSeleccionado = null;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string|max:500',
    ];

    public function render()
    {
        $roles = Rol::query()
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            })
            ->paginate(perPage: 5);

        return view('livewire.roles', compact('roles'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset(['nombre', 'descripcion', 'rolId']);
        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $rol = Rol::findOrFail($id);
        $this->rolId = $rol->id;
        $this->nombre = $rol->nombre;
        $this->descripcion = $rol->descripcion;
        $this->accion = 'edit';
        $this->modal = true;
        $this->detalleModal = false;
    }

    public function verDetalle($id)
    {
        $this->rolSeleccionado = Rol::findOrFail($id);
        $this->modal = false;
        $this->detalleModal = true;
    }

    public function guardarRol()
    {
        $this->validate();

        try {
            if ($this->accion === 'edit' && $this->rolId) {
                $rol = Rol::findOrFail($this->rolId);
                $rol->update([
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                ]);
                LivewireAlert::title('Rol actualizado con éxito.')->success()->show();
            } else {
                Rol::create([
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                ]);
                LivewireAlert::title('Rol creado con éxito.')->success()->show();
            }

            $this->cerrarModal();
        } catch (\Exception $e) {
            LivewireAlert::title('Ocurrió un error: ' . $e->getMessage())->error()->show();
        }
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->detalleModal = false;
        $this->reset(['nombre', 'descripcion', 'rolId', 'rolSeleccionado']);
        $this->resetErrorBag();
    }
}

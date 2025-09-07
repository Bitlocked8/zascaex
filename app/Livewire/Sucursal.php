<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sucursal as ModelSucursal;
use App\Models\Empresa;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Sucursal extends Component
{
    use WithPagination;

    public $search = '';
    public $modal = false;
    public $detalleModal = false;
    public $accion = 'create';
    public $sucursalId = null;

    public $nombre = '';
    public $direccion = '';
    public $telefono = '';
    public $zona = '';
    public $empresa_id = '';

    public $sucursalSeleccionada = null;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'direccion' => 'required|string|max:255',
        'telefono' => 'required|string|max:15',
        'zona' => 'nullable|string|max:255',
        'empresa_id' => 'required|exists:empresas,id',
    ];

    public function render()
    {
        $sucursales = ModelSucursal::with('empresa')
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('direccion', 'like', '%' . $this->search . '%');
            })
            ->paginate(5);

        $empresas = Empresa::all();

        return view('livewire.sucursal', compact('sucursales', 'empresas'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function abrirModal($accion)
    {
        $this->reset(['nombre', 'direccion', 'telefono', 'zona', 'empresa_id', 'sucursalId']);
        $this->accion = $accion;
        $this->modal = true;
        $this->detalleModal = false;
    }

    public function editarSucursal($id)
    {
        $sucursal = ModelSucursal::findOrFail($id);
        $this->sucursalId = $sucursal->id;
        $this->nombre = $sucursal->nombre;
        $this->direccion = $sucursal->direccion;
        $this->telefono = $sucursal->telefono;
        $this->zona = $sucursal->zona;
        $this->empresa_id = $sucursal->empresa_id;
        $this->accion = 'edit';
        $this->modal = true;
        $this->detalleModal = false;
    }

    public function verDetalle($id)
    {
        $this->sucursalSeleccionada = ModelSucursal::with('empresa')->findOrFail($id);
        $this->modal = false;
        $this->detalleModal = true;
    }

    public function guardarSucursal()
    {
        $this->validate();

        try {
            if ($this->accion === 'edit' && $this->sucursalId) {
                $sucursal = ModelSucursal::findOrFail($this->sucursalId);
                $sucursal->update([
                    'nombre' => $this->nombre,
                    'direccion' => $this->direccion,
                    'telefono' => $this->telefono,
                    'zona' => $this->zona,
                    'empresa_id' => $this->empresa_id,
                ]);
                LivewireAlert::title('Sucursal actualizada con éxito.')->success()->show();
            } else {
                ModelSucursal::create([
                    'nombre' => $this->nombre,
                    'direccion' => $this->direccion,
                    'telefono' => $this->telefono,
                    'zona' => $this->zona,
                    'empresa_id' => $this->empresa_id,
                ]);
                LivewireAlert::title('Sucursal registrada con éxito.')->success()->show();
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
        $this->reset(['nombre', 'direccion', 'telefono', 'zona', 'empresa_id', 'sucursalId', 'sucursalSeleccionada']);
        $this->resetErrorBag();
    }
}

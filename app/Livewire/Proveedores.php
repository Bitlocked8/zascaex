<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Proveedor as ModeloProveedor;

class Proveedores extends Component
{
    public $search = '';
    public $modal = false;
    public $detalleModal = false;
    public $accion = 'create';
    public $proveedorId = null;
    public $razonSocial = '';
    public $nombreContacto = '';
    public $direccion = '';
    public $telefono = '';
    public $correo = '';
    public $tipo = '';
    public $servicio = '';
    public $descripcion = '';
    public $precio = '';
    public $tiempoEntrega = '';
    public $estado = 1;
    public $proveedorSeleccionado = null;

    protected $rules = [
        'razonSocial' => 'required|string|max:255',
        'nombreContacto' => 'nullable|string|max:255',
        'direccion' => 'required|string|max:255',
        'telefono' => 'required|integer',
        'correo' => 'required|email|max:255',
        'tipo' => 'required|in:tapas,preformas,etiquetas',
        'servicio' => 'required|in:soplado,transporte',
        'descripcion' => 'required|string|max:255',
        'precio' => 'required|numeric|min:0',
        'tiempoEntrega' => 'required|string|max:255',
        'estado' => 'required|boolean',
    ];

    public function render()
    {
        $proveedores = ModeloProveedor::when($this->search, function ($query) {
            $query->where('razonSocial', 'like', '%' . $this->search . '%')
                  ->orWhere('nombreContacto', 'like', '%' . $this->search . '%')
                  ->orWhere('correo', 'like', '%' . $this->search . '%');
        })->get(); // Eliminamos paginate

        return view('livewire.proveedores', compact('proveedores'));
    }

    public function abrirModal($accion)
    {
        $this->reset(['razonSocial', 'nombreContacto', 'direccion', 'telefono', 'correo', 'tipo', 'servicio', 'descripcion', 'precio', 'tiempoEntrega', 'estado', 'proveedorId']);
        $this->accion = $accion;
        $this->estado = 1;
        $this->modal = true;
        $this->detalleModal = false;
    }

    public function editarProveedor($id)
    {
        $proveedor = ModeloProveedor::findOrFail($id);
        $this->proveedorId = $proveedor->id;
        $this->razonSocial = $proveedor->razonSocial;
        $this->nombreContacto = $proveedor->nombreContacto;
        $this->direccion = $proveedor->direccion;
        $this->telefono = $proveedor->telefono;
        $this->correo = $proveedor->correo;
        $this->tipo = $proveedor->tipo;
        $this->servicio = $proveedor->servicio;
        $this->descripcion = $proveedor->descripcion;
        $this->precio = $proveedor->precio;
        $this->tiempoEntrega = $proveedor->tiempoEntrega;
        $this->estado = $proveedor->estado;
        $this->accion = 'edit';
        $this->modal = true;
        $this->detalleModal = false;
    }

    public function verDetalle($id)
    {
        $this->proveedorSeleccionado = ModeloProveedor::findOrFail($id);
        $this->modal = false;
        $this->detalleModal = true;
    }

    public function guardarProveedor()
    {
        $this->validate();

        if ($this->accion === 'edit' && $this->proveedorId) {
            $proveedor = ModeloProveedor::findOrFail($this->proveedorId);
            $proveedor->update([
                'razonSocial' => $this->razonSocial,
                'nombreContacto' => $this->nombreContacto,
                'direccion' => $this->direccion,
                'telefono' => $this->telefono,
                'correo' => $this->correo,
                'tipo' => $this->tipo,
                'servicio' => $this->servicio,
                'descripcion' => $this->descripcion,
                'precio' => $this->precio,
                'tiempoEntrega' => $this->tiempoEntrega,
                'estado' => $this->estado,
            ]);
        } else {
            ModeloProveedor::create([
                'razonSocial' => $this->razonSocial,
                'nombreContacto' => $this->nombreContacto,
                'direccion' => $this->direccion,
                'telefono' => $this->telefono,
                'correo' => $this->correo,
                'tipo' => $this->tipo,
                'servicio' => $this->servicio,
                'descripcion' => $this->descripcion,
                'precio' => $this->precio,
                'tiempoEntrega' => $this->tiempoEntrega,
                'estado' => $this->estado,
            ]);
        }

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->detalleModal = false;
        $this->reset(['razonSocial', 'nombreContacto', 'direccion', 'telefono', 'correo', 'tipo', 'servicio', 'descripcion', 'precio', 'tiempoEntrega', 'estado', 'proveedorId', 'proveedorSeleccionado']);
        $this->resetErrorBag();
    }
}

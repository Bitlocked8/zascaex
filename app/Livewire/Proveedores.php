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
    public $precio = null;
    public $tiempoEntrega = '';
    public $estado = 1;
    public $proveedorSeleccionado = null;

    protected $rules = [
        'razonSocial' => 'required|string|max:255',
        'nombreContacto' => 'nullable|string|max:255',
        'direccion' => 'nullable|string|max:255',
        'telefono' => 'nullable|integer',
        'correo' => 'nullable|email|max:255',
        'tipo' => 'nullable|in:material,servicio',
        'servicio' => 'nullable|in:compra,servicio,produccion',
        'descripcion' => 'nullable|string|max:255',
        'precio' => 'nullable|numeric|min:0',
        'tiempoEntrega' => 'nullable|string|max:255',
        'estado' => 'nullable|boolean',
    ];

    public function render()
    {
        $proveedores = ModeloProveedor::when($this->search, function ($query) {
            $query->where('razonSocial', 'like', '%' . $this->search . '%')
                ->orWhere('nombreContacto', 'like', '%' . $this->search . '%')
                ->orWhere('correo', 'like', '%' . $this->search . '%');
        })->get();

        return view('livewire.proveedores', compact('proveedores'));
    }

    public function abrirModal($accion)
    {
        $this->reset([
            'razonSocial',
            'nombreContacto',
            'direccion',
            'telefono',
            'correo',
            'tipo',
            'servicio',
            'descripcion',
            'precio',
            'tiempoEntrega',
            'estado',
            'proveedorId'
        ]);
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

        $data = [
            'razonSocial' => $this->razonSocial,
            'nombreContacto' => $this->nombreContacto ?: null,
            'direccion' => $this->direccion ?: null,
            'telefono' => $this->telefono ?: null,
            'correo' => $this->correo ?: null,
            'tipo' => $this->tipo ?: null,
            'servicio' => $this->servicio ?: null,
            'descripcion' => $this->descripcion ?: null,
            'precio' => $this->precio !== '' ? $this->precio : null,
            'tiempoEntrega' => $this->tiempoEntrega ?: null,
            'estado' => $this->estado ?? 1,
        ];

        if ($this->accion === 'edit' && $this->proveedorId) {
            ModeloProveedor::findOrFail($this->proveedorId)->update($data);
        } else {
            ModeloProveedor::create($data);
        }

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->detalleModal = false;
        $this->reset([
            'razonSocial',
            'nombreContacto',
            'direccion',
            'telefono',
            'correo',
            'tipo',
            'servicio',
            'descripcion',
            'precio',
            'tiempoEntrega',
            'estado',
            'proveedorId',
            'proveedorSeleccionado'
        ]);
        $this->resetErrorBag();
    }
}

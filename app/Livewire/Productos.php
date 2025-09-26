<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Producto;
use App\Models\Existencia;

class Productos extends Component
{
    use WithFileUploads;

    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $producto_id = null;

    public $imagen;
    public $imagenExistente;
    public $nombre = '';
    public $tipoContenido = '';
    public $tipoProducto = 0; // 0 = sin retorno, 1 = con retorno
    public $capacidad = '';
    public $precioReferencia = '';
    public $descripcion = '';
    public $estado = 1;

    public $accion = 'create';
    public $productoSeleccionado = null;

    protected $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'tipoContenido.required' => 'El tipo de contenido es obligatorio.',
        'tipoProducto.required' => 'El tipo de producto es obligatorio.',
        'capacidad.required' => 'La capacidad es obligatoria.',
        'capacidad.integer' => 'La capacidad debe ser un número entero.',
        'capacidad.min' => 'La capacidad no puede ser negativa.',
        'precioReferencia.required' => 'El precio de referencia es obligatorio.',
        'precioReferencia.numeric' => 'El precio de referencia debe ser un número.',
        'estado.required' => 'El estado es obligatorio.',
    ];

    public function render()
    {
        $productos = Producto::query()
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where('nombre', 'like', $searchTerm)
                      ->orWhere('descripcion', 'like', $searchTerm)
                      ->orWhere('capacidad', 'like', $searchTerm);
            })
            ->get();

        return view('livewire.productos', compact('productos'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'producto_id', 'nombre', 'tipoContenido', 'tipoProducto',
            'capacidad', 'precioReferencia', 'descripcion',
            'estado', 'imagen', 'imagenExistente', 'productoSeleccionado'
        ]);

        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $producto = Producto::findOrFail($id);
        $this->producto_id = $producto->id;
        $this->nombre = $producto->nombre;
        $this->tipoContenido = $producto->tipoContenido;
        $this->tipoProducto = $producto->tipoProducto;
        $this->capacidad = $producto->capacidad;
        $this->precioReferencia = $producto->precioReferencia;
        $this->descripcion = $producto->descripcion;
        $this->estado = $producto->estado;

        $this->imagen = null; // solo si suben nueva
        $this->imagenExistente = $producto->imagen;

        $this->productoSeleccionado = $producto;
        $this->accion = 'edit';
    }

    public function guardar()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'tipoContenido' => 'required|integer',
            'tipoProducto' => 'required|boolean',
            'capacidad' => 'required|integer|min:0',
            'precioReferencia' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string|max:500',
            'estado' => 'required|boolean',
        ]);

        if ($this->imagen && is_object($this->imagen)) {
            $this->validate([
                'imagen' => 'image|max:5120', // 5MB
            ]);
            $imagenPath = $this->imagen->store('productos', 'public');
        } else {
            $imagenPath = $this->imagenExistente ?? null;
        }

        $producto = Producto::updateOrCreate(
            ['id' => $this->producto_id],
            [
                'nombre' => $this->nombre,
                'tipoContenido' => $this->tipoContenido,
                'tipoProducto' => $this->tipoProducto,
                'capacidad' => $this->capacidad,
                'precioReferencia' => $this->precioReferencia,
                'descripcion' => $this->descripcion,
                'estado' => $this->estado,
                'imagen' => $imagenPath,
            ]
        );

        if (!$this->producto_id) {
            Existencia::create([
                'existenciable_type' => Producto::class,
                'existenciable_id' => $producto->id,
                'cantidad' => 0,
            ]);
        }

        $this->cerrarModal();
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'producto_id', 'nombre', 'tipoContenido', 'tipoProducto',
            'capacidad', 'precioReferencia', 'descripcion',
            'estado', 'imagen', 'imagenExistente', 'productoSeleccionado'
        ]);
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $this->productoSeleccionado = Producto::findOrFail($id);
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->productoSeleccionado = null;
    }
}

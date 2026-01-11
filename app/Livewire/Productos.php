<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Producto;
use App\Models\Existencia;
use Illuminate\Support\Facades\Auth;

class Productos extends Component
{
    use WithFileUploads;

    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $producto_id = null;

    public $imagen;
    public $imagenExistente;
    public $descripcion = '';
    public $unidad = '';
    public $tipoContenido = '';
    public $tipoProducto = 0;
    public $precioAlternativo = null;
    public $capacidad = '';
    public $precioReferencia = '';
    public $paquete = null;
    public $tipo = '';
    public $observaciones = '';
    public $estado = 1;
    public $cantidadMinima = 0;

    public $accion = 'create';
    public $productoSeleccionado = null;

    protected $messages = [
        'descripcion.required' => 'La descripción es obligatoria.',
        'tipoContenido.required' => 'El tipo de contenido es obligatorio.',
        'tipoProducto.required' => 'El tipo de producto es obligatorio.',
        'capacidad.required' => 'La capacidad es obligatoria.',
        'capacidad.numeric' => 'La capacidad debe ser un número.',
        'capacidad.min' => 'La capacidad no puede ser negativa.',
        'precioReferencia.required' => 'El precio de referencia es obligatorio.',
        'precioReferencia.numeric' => 'El precio de referencia debe ser un número.',
        'estado.required' => 'El estado es obligatorio.',
    ];

    public function render()
    {
        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        $productosQuery = Producto::query()
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('descripcion', 'like', "%{$this->search}%")
                        ->orWhere('capacidad', 'like', "%{$this->search}%");
                });
            });

        if ($rol === 2 && $personal) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');

            $productosQuery->whereHas('existencias', function ($q) use ($sucursal_id) {
                $q->where('sucursal_id', $sucursal_id);
            });
        }


        $productos = $productosQuery->with('existencias')->get();

        return view('livewire.productos', compact('productos'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'producto_id',
            'descripcion',
            'unidad',
            'tipoContenido',
            'tipoProducto',
            'capacidad',
            'precioReferencia',
            'precioAlternativo',
            'paquete',
            'tipo',
            'observaciones',
            'estado',
            'imagen',
            'imagenExistente',
            'productoSeleccionado',
            'cantidadMinima'
        ]);


        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $producto = Producto::with('existencias')->findOrFail($id);

        $this->producto_id = $producto->id;
        $this->descripcion = $producto->descripcion;
        $this->unidad = $producto->unidad;
        $this->tipoContenido = $producto->tipoContenido;
        $this->tipoProducto = $producto->tipoProducto;
        $this->capacidad = $producto->capacidad;
        $this->precioReferencia = $producto->precioReferencia;
        $this->precioAlternativo = $producto->precioAlternativo;
        $this->paquete = $producto->paquete;
        $this->tipo = $producto->tipo;
        $this->observaciones = $producto->observaciones;
        $this->estado = $producto->estado;

        $this->imagen = null;
        $this->imagenExistente = $producto->imagen;

        $this->productoSeleccionado = $producto;
        $this->accion = 'edit';
        $this->cantidadMinima = $producto->existencias->first()?->cantidadMinima ?? 0;
    }

    public function guardar()
    {

        $this->validate([
            'descripcion' => 'required|string|max:500',
            'tipoContenido' => 'nullable|string|max:255',
            'tipoProducto' => 'required|integer|in:0,1,2,3',
            'capacidad' => 'nullable|numeric|min:0',
            'precioReferencia' => 'required|numeric|min:0',
            'precioAlternativo' => 'nullable|numeric|min:0',
            'unidad' => 'nullable|string|max:50',
            'paquete' => 'nullable|integer|min:0',

            'tipo' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string|max:1000',
            'estado' => 'required|boolean',
            'cantidadMinima' => 'nullable|integer|min:0',
        ]);

        if ($this->imagen && is_object($this->imagen)) {
            $this->validate(['imagen' => 'image|max:5120']);
            $imagenPath = $this->imagen->store('productos', 'public');
        } else {
            $imagenPath = $this->imagenExistente ?? null;
        }

        $producto = Producto::updateOrCreate(
            ['id' => $this->producto_id],
            [
                'descripcion' => $this->descripcion,
                'unidad' => $this->unidad,
                'tipoContenido' => $this->tipoContenido,
                'tipoProducto' => $this->tipoProducto,
                'capacidad' => $this->capacidad,
                'precioReferencia' => $this->precioReferencia,
                'precioAlternativo' => $this->precioAlternativo,
                'paquete' => $this->paquete,
                'tipo' => $this->tipo,
                'observaciones' => $this->observaciones,
                'estado' => $this->estado,
                'imagen' => $imagenPath,
            ]
        );
        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        if (!$this->producto_id) {
            $existenciaData = [
                'existenciable_type' => Producto::class,
                'existenciable_id' => $producto->id,
                'cantidad' => 0,
                'cantidadMinima' => $this->cantidadMinima,
            ];

            if ($rol === 2 && $personal) {
                $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
                $existenciaData['sucursal_id'] = $sucursal_id;
            }

            Existencia::create($existenciaData);
        } else {
            $existencia = $producto->existencias->first();
            if ($existencia) {
                $existencia->update(['cantidadMinima' => $this->cantidadMinima]);
            }
        }

        $this->cerrarModal();
    }


    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset([
            'producto_id',
            'descripcion',
            'unidad',
            'tipoContenido',
            'tipoProducto',
            'capacidad',
            'precioReferencia',
            'precioAlternativo',
            'paquete',
            'tipo',
            'observaciones',
            'estado',
            'imagen',
            'imagenExistente',
            'productoSeleccionado',
            'cantidadMinima'
        ]);
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $producto = Producto::with('existencias')->findOrFail($id);

        $usuario = Auth::user();
        $personal = $usuario->personal;

        if ($usuario->rol_id === 2 && $personal) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $producto->existencias = $producto->existencias->where('sucursal_id', $sucursal_id);
        }

        $this->productoSeleccionado = $producto;
        $this->cantidadMinima = $producto->existencias->first()?->cantidadMinima ?? 0;
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->productoSeleccionado = null;
        $this->cantidadMinima = 0;
    }
}

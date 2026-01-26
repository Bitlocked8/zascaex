<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Otro as OtroModel;
use App\Models\Existencia;
use Illuminate\Support\Facades\Auth;

class Otros extends Component
{
    use WithFileUploads;

    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $otro_id = null;

    public $imagen;
    public $imagenExistente;
    public $descripcion = '';
    public $unidad = '';
    public $tipoContenido = '';
    public $tipoProducto = 0;
    public $capacidad = '';
    public $precioReferencia = '';
    public $paquete = null;
    public $tipo = '';
    public $observaciones = '';
    public $estado = 1;
    public $cantidadMinima = 0;
    public $precioAlternativo = null;

    public $accion = 'create';
    public $otroSeleccionado = null;

    protected $messages = [
        'descripcion.required' => 'La descripción es obligatoria.',
        'tipoContenido.required' => 'El tipo de contenido es obligatorio.',
        'tipoProducto.required' => 'El tipo de producto es obligatorio.',
        'precioReferencia.required' => 'El precio de referencia es obligatorio.',
        'precioReferencia.numeric' => 'El precio de referencia debe ser un número.',
    ];

    public function render()
    {
        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        $otrosQuery = OtroModel::query()
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('descripcion', 'like', "%{$this->search}%")
                        ->orWhere('capacidad', 'like', "%{$this->search}%");
                });
            });

        if ($rol === 2 && $personal) {
            $sucursal_id = $personal->trabajos()
                ->latest('fechaInicio')
                ->value('sucursal_id');

            $otrosQuery->whereHas('existencias', function ($q) use ($sucursal_id) {
                $q->where('sucursal_id', $sucursal_id);
            });
        }

        $otros = $otrosQuery->with('existencias')->get();

        return view('livewire.otros', compact('otros'));
    }


    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'otro_id',
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
            'otroSeleccionado',
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
        $otro = OtroModel::with('existencias')->findOrFail($id);

        $this->otro_id = $otro->id;
        $this->descripcion = $otro->descripcion;
        $this->unidad = $otro->unidad;
        $this->tipoContenido = $otro->tipoContenido;
        $this->tipoProducto = $otro->tipoProducto;
        $this->capacidad = $otro->capacidad;
        $this->precioReferencia = $otro->precioReferencia;
        $this->precioAlternativo = $otro->precioAlternativo;

        $this->paquete = $otro->paquete;
        $this->tipo = $otro->tipo;
        $this->observaciones = $otro->observaciones;
        $this->estado = $otro->estado;

        $this->imagen = null;
        $this->imagenExistente = $otro->imagen;

        $this->otroSeleccionado = $otro;
        $this->accion = 'edit';
        $this->cantidadMinima = $otro->existencias->first()?->cantidadMinima ?? 0;
    }

    public function guardar()
    {
        $this->validate([
            'descripcion' => 'required|string|max:500',
            'tipoContenido' => 'nullable|string|max:255',
            'tipoProducto' => 'required|integer|in:0,1,2,3,4',
            'capacidad' => 'nullable|numeric|min:0',
            'precioReferencia' => 'required|numeric|min:0',
            'precioAlternativo' => 'nullable|numeric|min:0',
            'unidad' => 'nullable|string|max:50',
            'paquete' => 'nullable|integer|min:0',
            'tipo' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string|max:1000',
            'estado' => 'boolean',
            'cantidadMinima' => 'nullable|integer|min:0',
        ]);


        $this->capacidad = $this->capacidad === '' ? null : $this->capacidad;
        $this->precioAlternativo = $this->precioAlternativo === '' ? null : $this->precioAlternativo;
        $this->paquete = $this->paquete === '' ? null : $this->paquete;

        if ($this->imagen && is_object($this->imagen)) {
            $this->validate(['imagen' => 'image|max:5120']);
            $imagenPath = $this->imagen->store('otros', 'public');
        } else {
            $imagenPath = $this->imagenExistente ?? null;
        }

        $otro = OtroModel::updateOrCreate(
            ['id' => $this->otro_id],
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

        if (!$this->otro_id) {
            $existenciaData = [
                'existenciable_type' => OtroModel::class,
                'existenciable_id' => $otro->id,
                'cantidad' => 0,
                'cantidadMinima' => $this->cantidadMinima,
            ];

            if ($rol === 2 && $personal) {
                $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
                $existenciaData['sucursal_id'] = $sucursal_id;
            }

            Existencia::create($existenciaData);
        } else {
            $existencia = $otro->existencias->first();
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
            'otro_id',
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
            'otroSeleccionado',
            'cantidadMinima'
        ]);
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $otro = OtroModel::with('existencias')->findOrFail($id);

        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        if ($rol === 2 && $personal) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $otro->existencias = $otro->existencias->where('sucursal_id', $sucursal_id);
        }

        $this->otroSeleccionado = $otro;
        $this->cantidadMinima = $otro->existencias->first()?->cantidadMinima ?? 0;
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->otroSeleccionado = null;
        $this->cantidadMinima = 0;
    }
}

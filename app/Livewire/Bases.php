<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Base;
use App\Models\Existencia;
use Illuminate\Support\Facades\Auth;

class Bases extends Component
{
    use WithFileUploads;

    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $imagen;
    public $imagenExistente;
    public $descripcion = '';
    public $base_id = null;
    public $capacidad = '';
    public $estado = 1;
    public $observaciones = '';
    public $cantidadMinima = 0;
    public $accion = 'create';
    public $baseSeleccionada = null;

    protected $messages = [
        'capacidad.required' => 'La capacidad es obligatoria.',
        'capacidad.integer' => 'La capacidad debe ser un número entero.',
        'capacidad.min' => 'La capacidad no puede ser negativa.',
        'estado.required' => 'El estado es obligatorio.',
    ];

    public function render()
    {
        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        $basesQuery = Base::query()
            ->when($this->search, fn($q) => $q->where('capacidad', 'like', "%{$this->search}%")
                ->orWhere('descripcion', 'like', "%{$this->search}%")
                ->orWhere('observaciones', 'like', "%{$this->search}%"));

        if ($rol === 2 && $personal) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $basesQuery->whereHas('existencias', fn($q) => $q->where('sucursal_id', $sucursal_id));
        }

        $bases = $basesQuery->with('existencias')->get();

        return view('livewire.bases', compact('bases'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'base_id', 'capacidad', 'estado', 'descripcion', 
            'observaciones', 'imagen', 'imagenExistente', 'baseSeleccionada', 'cantidadMinima'
        ]);

        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $base = Base::with('existencias')->findOrFail($id);
        $this->base_id = $base->id;
        $this->capacidad = $base->capacidad;
        $this->estado = $base->estado;
        $this->descripcion = $base->descripcion;
        $this->observaciones = $base->observaciones;
        $this->imagen = null;
        $this->imagenExistente = $base->imagen;
        $this->accion = 'edit';
        $this->baseSeleccionada = $base;
        $this->cantidadMinima = $base->existencias->first()?->cantidadMinima ?? 0;
    }

    public function guardar()
    {
        $this->validate([
            'capacidad' => 'required|integer|min:0',
            'estado' => 'required|boolean',
            'descripcion' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'cantidadMinima' => 'nullable|integer|min:0',
        ]);

        if ($this->imagen && is_object($this->imagen)) {
            $this->validate(['imagen' => 'image|max:5120']);
            $imagenPath = $this->imagen->store('bases', 'public');
        } else {
            $imagenPath = $this->imagenExistente ?? null;
        }

        $base = Base::updateOrCreate(
            ['id' => $this->base_id],
            [
                'capacidad' => $this->capacidad,
                'estado' => $this->estado,
                'descripcion' => $this->descripcion,
                'observaciones' => $this->observaciones,
                'imagen' => $imagenPath,
            ]
        );

        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        if (!$this->base_id) {
            $existenciaData = [
                'existenciable_type' => Base::class,
                'existenciable_id' => $base->id,
                'cantidad' => 0,
                'cantidadMinima' => $this->cantidadMinima,
            ];

            if ($rol === 2 && $personal) {
                $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
                $existenciaData['sucursal_id'] = $sucursal_id;
            }

            Existencia::create($existenciaData);
        } else {
            $existencia = $base->existencias->first();
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
            'base_id', 'capacidad', 'estado', 'descripcion', 
            'observaciones', 'imagen', 'imagenExistente', 'baseSeleccionada', 'cantidadMinima'
        ]);
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $base = Base::with('existencias')->findOrFail($id);
        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        if ($rol === 2 && $personal) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $base->existencias = $base->existencias->where('sucursal_id', $sucursal_id);
        }

        $this->baseSeleccionada = $base;
        $this->cantidadMinima = $base->existencias->first()?->cantidadMinima ?? 0;
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->baseSeleccionada = null;
        $this->cantidadMinima = 0;
    }
}

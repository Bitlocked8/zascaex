<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Etiqueta;
use App\Models\Cliente;
use App\Models\Existencia;
use Illuminate\Support\Facades\Auth;

class Etiquetas extends Component
{
    use WithFileUploads;

    public $tipo = '';
    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $etiqueta_id = null;
    public $imagen;
    public $imagenExistente;
    public $capacidad = '';
    public $unidad = '';
    public $estado = 1;
    public $cliente_id = '';
    public $clientes;
    public $descripcion = '';
    public $accion = 'create';
    public $etiquetaSeleccionada = null;
    public $cantidadMinima = 0;

    protected $rules = [
        'capacidad' => 'required|string|max:255',
        'unidad' => 'nullable|string|max:10', // <-- corregido
        'descripcion' => 'nullable|string|max:255',
        'estado' => 'required|boolean',
        'cliente_id' => 'nullable|exists:clientes,id',
        'cantidadMinima' => 'nullable|integer|min:0',
        'tipo' => 'required|in:1,2',
    ];

    protected $messages = [
        'capacidad.required' => 'La capacidad es obligatoria.',
        'estado.required' => 'El estado es obligatorio.',
    ];

    public function mount()
    {
        $this->clientes = Cliente::all();
    }

    public function render()
    {
        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        $etiquetasQuery = Etiqueta::query()->with('existencias')
            ->when($this->search, fn($q) => $q->where('capacidad', 'like', "%{$this->search}%")
                ->orWhere('descripcion', 'like', "%{$this->search}%"));

        if ($rol === 2 && $personal) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $etiquetasQuery->whereHas('existencias', fn($q) => $q->where('sucursal_id', $sucursal_id));
        }

        $etiquetas = $etiquetasQuery->get();

        return view('livewire.etiquetas', compact('etiquetas'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'imagen',
            'imagenExistente',
            'capacidad',
            'unidad',
            'estado',
            'cliente_id',
            'descripcion',
            'etiqueta_id',
            'etiquetaSeleccionada',
            'cantidadMinima',
            'tipo'
        ]);

        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $etiqueta = Etiqueta::with('existencias')->findOrFail($id);

        $this->etiqueta_id = $etiqueta->id;
        $this->capacidad = $etiqueta->capacidad;
        $this->unidad = $etiqueta->unidad ?: '';
        $this->estado = $etiqueta->estado;
        $this->descripcion = $etiqueta->descripcion;
        $this->cliente_id = $etiqueta->cliente_id;
        $this->imagen = null;
        $this->imagenExistente = $etiqueta->imagen;
        $this->accion = 'edit';
        $this->etiquetaSeleccionada = $etiqueta;
        $this->tipo = $etiqueta->tipo;
        $this->cantidadMinima = $etiqueta->existencias->first()?->cantidadMinima ?? 0;
    }

    public function guardar()
    {
        $this->validate();

        // Guardar imagen
        if ($this->imagen && is_object($this->imagen)) {
            $this->validate(['imagen' => 'image|max:5120']);
            $imagenPath = $this->imagen->store('etiquetas', 'public');
        } else {
            $imagenPath = $this->imagenExistente ?? null;
        }

        $etiqueta = Etiqueta::updateOrCreate(
            ['id' => $this->etiqueta_id],
            [
                'imagen' => $imagenPath,
                'capacidad' => $this->capacidad,
                'unidad' => $this->unidad ?: null,
                'estado' => $this->estado,
                'descripcion' => $this->descripcion,
                'cliente_id' => $this->cliente_id ?: null,
                'tipo' => $this->tipo,
            ]
        );

        // Crear o actualizar existencia
        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        if (!$this->etiqueta_id) {
            $existenciaData = [
                'existenciable_type' => Etiqueta::class,
                'existenciable_id' => $etiqueta->id,
                'cantidad' => 0,
                'cantidadMinima' => $this->cantidadMinima,
            ];

            if ($rol === 2 && $personal) {
                $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
                $existenciaData['sucursal_id'] = $sucursal_id;
            }

            Existencia::create($existenciaData);
        } else {
            $existencia = $etiqueta->existencias->first();
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
            'imagen',
            'imagenExistente',
            'capacidad',
            'unidad',
            'estado',
            'cliente_id',
            'descripcion',
            'etiqueta_id',
            'etiquetaSeleccionada',
            'cantidadMinima',
            'tipo'
        ]);
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $etiqueta = Etiqueta::with('existencias')->findOrFail($id);

        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        if ($rol === 2 && $personal) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $etiqueta->existencias = $etiqueta->existencias->where('sucursal_id', $sucursal_id);
        }

        $this->etiquetaSeleccionada = $etiqueta;
        $this->cantidadMinima = $etiqueta->existencias->first()?->cantidadMinima ?? 0;
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->etiquetaSeleccionada = null;
        $this->cantidadMinima = 0;
    }
}

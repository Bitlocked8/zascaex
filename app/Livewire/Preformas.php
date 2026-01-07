<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Preforma;
use App\Models\Existencia;
use Illuminate\Support\Facades\Auth;

class Preformas extends Component
{
    use WithFileUploads;

    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $preforma_id = null;
    public $detalle = '';
    public $insumo = '';
    public $gramaje = '';
    public $cuello = '';
    public $descripcion = '';
    public $capacidad = '';
    public $color = '';
    public $estado = 1;
    public $observaciones = '';
    public $imagen;
    public $imagenExistente;

    public $accion = 'create';
    public $preformaSeleccionada = null;
    public $cantidadMinima = 0;

    protected $messages = [
        'estado.required' => 'El estado es obligatorio.',
        'capacidad.integer' => 'La capacidad debe ser un número entero.',
        'cantidadMinima.integer' => 'La cantidad mínima debe ser un número entero.',
    ];

    public function render()
    {
        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        $preformasQuery = Preforma::query()
            ->when(
                $this->search,
                fn($q) => $q->where('descripcion', 'like', "%{$this->search}%")
                    ->orWhere('detalle', 'like', "%{$this->search}%")
                    ->orWhere('insumo', 'like', "%{$this->search}%")
            );

        if ($rol === 4 && $personal) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $preformasQuery->whereHas('existencias', fn($q) => $q->where('sucursal_id', $sucursal_id));
        }

        $preformas = $preformasQuery->with('existencias')->get();

        return view('livewire.preformas', compact('preformas'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'preforma_id',
            'detalle',
            'insumo',
            'gramaje',
            'cuello',
            'descripcion',
            'capacidad',
            'color',
            'estado',
            'observaciones',
            'imagen',
            'imagenExistente',
            'preformaSeleccionada',
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
        $preforma = Preforma::with('existencias')->findOrFail($id);

        $this->preforma_id = $preforma->id;
        $this->detalle = $preforma->detalle;
        $this->insumo = $preforma->insumo;
        $this->gramaje = $preforma->gramaje;
        $this->cuello = $preforma->cuello;
        $this->descripcion = $preforma->descripcion;
        $this->capacidad = $preforma->capacidad;
        $this->color = $preforma->color;
        $this->estado = $preforma->estado;
        $this->observaciones = $preforma->observaciones;
        $this->imagen = null;
        $this->imagenExistente = $preforma->imagen;
        $this->accion = 'edit';
        $this->preformaSeleccionada = $preforma;

        $this->cantidadMinima = $preforma->existencias->first()?->cantidadMinima ?? 0;
    }

    public function guardar()
    {
        $this->validate([
            'detalle' => 'nullable|string|max:255',
            'insumo' => 'nullable|string|max:255',
            'gramaje' => 'nullable|string|max:255',
            'cuello' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'capacidad' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'estado' => 'required|boolean',
            'observaciones' => 'nullable|string|max:255',
            'cantidadMinima' => 'nullable|integer|min:0',
        ]);

        // Manejo de imagen
        if ($this->imagen && is_object($this->imagen)) {
            $this->validate(['imagen' => 'image|max:5120']);
            $imagenPath = $this->imagen->store('preformas', 'public');
        } else {
            $imagenPath = $this->imagenExistente ?? null;
        }

        $preforma = Preforma::updateOrCreate(
            ['id' => $this->preforma_id],
            [
                'detalle' => $this->detalle,
                'insumo' => $this->insumo,
                'gramaje' => $this->gramaje,
                'cuello' => $this->cuello,
                'descripcion' => $this->descripcion,
                'capacidad' => $this->capacidad,
                'color' => $this->color,
                'estado' => $this->estado,
                'observaciones' => $this->observaciones,
                'imagen' => $imagenPath,
            ]
        );

        // Crear o actualizar existencia
        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        if (!$this->preforma_id) {
            $existenciaData = [
                'existenciable_type' => Preforma::class,
                'existenciable_id' => $preforma->id,
                'cantidad' => 0,
                'cantidadMinima' => $this->cantidadMinima,
            ];

            if ($rol === 4 && $personal) {
                $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
                $existenciaData['sucursal_id'] = $sucursal_id;
            }

            Existencia::create($existenciaData);
        } else {
            $existencia = $preforma->existencias->first();
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
            'preforma_id',
            'detalle',
            'insumo',
            'gramaje',
            'cuello',
            'descripcion',
            'capacidad',
            'color',
            'estado',
            'observaciones',
            'imagen',
            'imagenExistente',
            'preformaSeleccionada',
            'cantidadMinima'
        ]);
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $preforma = Preforma::with('existencias')->findOrFail($id);

        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        if ($rol === 4 && $personal) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $preforma->existencias = $preforma->existencias->where('sucursal_id', $sucursal_id);
        }

        $this->preformaSeleccionada = $preforma;
        $this->cantidadMinima = $preforma->existencias->first()?->cantidadMinima ?? 0;
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->preformaSeleccionada = null;
        $this->cantidadMinima = 0;
    }
}

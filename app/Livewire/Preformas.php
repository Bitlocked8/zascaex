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
    public $descripcion = '';
    public $estado = 1;
    public $observaciones = '';
    public $imagen; 
    public $imagenExistente;
    public $accion = 'create';
    public $preformaSeleccionada = null;
    
    public $cantidadMinima = 0; // Nuevo campo

    protected $messages = [
        'estado.required' => 'El estado es obligatorio.',
    ];

    public function render()
    {
        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        $preformasQuery = Preforma::query()
            ->when($this->search, fn($q) => $q->where('descripcion', 'like', "%{$this->search}%"));
        
        if ($rol === 2 && $personal) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $preformasQuery->whereHas('existencias', fn($q) => $q->where('sucursal_id', $sucursal_id));
        }

        $preformas = $preformasQuery->with(['existencias'])->get();

        return view('livewire.preformas', compact('preformas'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'descripcion', 'estado', 'observaciones', 'imagen', 
            'imagenExistente', 'preforma_id', 'preformaSeleccionada',
            'cantidadMinima' // reset del nuevo campo
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
        $this->descripcion = $preforma->descripcion;
        $this->estado = $preforma->estado;
        $this->observaciones = $preforma->observaciones;
        $this->imagen = null; 
        $this->imagenExistente = $preforma->imagen; 
        $this->accion = 'edit';
        $this->preformaSeleccionada = $preforma;

        // Cargar cantidadMinima si existe alguna existencia
        $this->cantidadMinima = $preforma->existencias->first()?->cantidadMinima ?? 0;
    }

    public function guardar()
    {
        $this->validate([
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'required|boolean',
            'observaciones' => 'nullable|string|max:255',
            'cantidadMinima' => 'nullable|integer|min:0', // Validación del campo
        ]);

        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

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
                'descripcion' => $this->descripcion,
                'estado' => $this->estado,
                'observaciones' => $this->observaciones,
                'imagen' => $imagenPath,
            ]
        );

        // Crear o actualizar existencia
        if (!$this->preforma_id) {
            $existenciaData = [
                'existenciable_type' => Preforma::class,
                'existenciable_id' => $preforma->id,
                'cantidad' => 0,
                'cantidadMinima' => $this->cantidadMinima,
            ];

            if ($rol === 2 && $personal) {
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
            'descripcion', 'estado', 'observaciones', 'imagen', 
            'imagenExistente', 'preforma_id', 'preformaSeleccionada',
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

        if ($rol === 2 && $personal) {
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

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tapa;
use App\Models\Existencia;
use Illuminate\Support\Facades\Auth;

class Tapas extends Component
{
    use WithFileUploads;

    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $tapa_id = null;
    public $descripcion = '';
    public $color = '';
    public $tipo = '';
    public $estado = 1;
    public $observaciones = '';
    public $imagen;
    public $imagenExistente;
    public $accion = 'create';
    public $tapaSeleccionada = null;

    public $cantidadMinima = 0; // Nuevo campo

    protected $messages = [
        'color.required' => 'El color es obligatorio.',
        'tipo.required' => 'El tipo es obligatorio.',
        'estado.required' => 'El estado es obligatorio.',
    ];

    public function render()
    {
        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        $tapasQuery = Tapa::query()
            ->when(
                $this->search,
                fn($q) =>
                $q->where('descripcion', 'like', "%{$this->search}%")
                    ->orWhere('color', 'like', "%{$this->search}%")
                    ->orWhere('tipo', 'like', "%{$this->search}%")
            );

        if ($rol === 4 && $personal) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $tapasQuery->whereHas('existencias', fn($q) => $q->where('sucursal_id', $sucursal_id));
        }

        $tapas = $tapasQuery->with('existencias')->get();

        return view('livewire.tapas', compact('tapas'));
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset([
            'descripcion',
            'color',
            'tipo',
            'estado',
            'observaciones',
            'imagen',
            'imagenExistente',
            'tapa_id',
            'tapaSeleccionada',
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
        $tapa = Tapa::with('existencias')->findOrFail($id);
        $this->tapa_id = $tapa->id;
        $this->descripcion = $tapa->descripcion;
        $this->color = $tapa->color;
        $this->tipo = $tapa->tipo;
        $this->estado = $tapa->estado;
        $this->observaciones = $tapa->observaciones ?? '';
        $this->imagen = null;
        $this->imagenExistente = $tapa->imagen;
        $this->accion = 'edit';
        $this->tapaSeleccionada = $tapa;

        $this->cantidadMinima = $tapa->existencias->first()?->cantidadMinima ?? 0;
    }

    public function guardar()
    {
        $this->validate([
            'color' => 'required|string|max:100',
            'tipo' => 'required|string|max:100',
            'estado' => 'required|boolean',
            'descripcion' => 'nullable|string|max:255',
            'cantidadMinima' => 'nullable|integer|min:0',
        ]);

        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        // Manejo de imagen
        if ($this->imagen && is_object($this->imagen)) {
            $this->validate(['imagen' => 'image|max:5120']);
            $imagenPath = $this->imagen->store('tapas', 'public');
        } else {
            $imagenPath = $this->imagenExistente ?? null;
        }

        // Crear o editar Tapa
        if ($this->tapa_id) {
            $tapa = Tapa::find($this->tapa_id);
            $tapa->update([
                'descripcion' => $this->descripcion,
                'color' => $this->color,
                'tipo' => $this->tipo,
                'estado' => $this->estado,
                'imagen' => $imagenPath,
            ]);
        } else {
            $tapa = Tapa::create([
                'descripcion' => $this->descripcion,
                'color' => $this->color,
                'tipo' => $this->tipo,
                'estado' => $this->estado,
                'imagen' => $imagenPath,
            ]);
        }

        // Crear o actualizar existencia
        if (!$this->tapa_id) {
            $existenciaData = [
                'existenciable_type' => Tapa::class,
                'existenciable_id' => $tapa->id,
                'cantidad' => 0,
                'cantidadMinima' => $this->cantidadMinima,
            ];

            if ($rol === 4 && $personal) {
                $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
                $existenciaData['sucursal_id'] = $sucursal_id;
            }

            Existencia::create($existenciaData);
        } else {
            $existencia = $tapa->existencias->first();
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
            'descripcion',
            'color',
            'tipo',
            'estado',
            'observaciones',
            'imagen',
            'imagenExistente',
            'tapa_id',
            'tapaSeleccionada',
            'cantidadMinima'
        ]);
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $tapa = Tapa::with('existencias')->findOrFail($id);

        $usuario = Auth::user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;

        if ($rol === 4 && $personal) {
            $sucursal_id = $personal->trabajos()->latest('fechaInicio')->value('sucursal_id');
            $tapa->existencias = $tapa->existencias->where('sucursal_id', $sucursal_id);
        }

        $this->tapaSeleccionada = $tapa;
        $this->cantidadMinima = $tapa->existencias->first()?->cantidadMinima ?? 0;
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->tapaSeleccionada = null;
        $this->cantidadMinima = 0;
    }
}

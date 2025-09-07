<?php

namespace App\Livewire;

use App\Models\Embotellado as ModelEmbotellado;
use App\Models\Existencia;
use App\Models\Personal;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Embotellado extends Component
{
    use WithPagination;

    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $accion = 'create';

    public $embotelladoId;
    public $fecha_embotellado;
    public $personal_id;
    public $existencia_base_id;
    public $existencia_tapa_id;
    public $existencia_producto_id;
    public $cantidad_base_usada;
    public $cantidad_tapa_usada;
    public $cantidad_generada;
    public $observaciones;

    public $embotelladoSeleccionado = [];
    public $personales = [];
    public $existencias_base = [];
    public $existencias_tapa = [];

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'fecha_embotellado' => 'required|date',
        'personal_id' => 'required|exists:personals,id',
        'existencia_base_id' => 'required|exists:existencias,id',
        'existencia_tapa_id' => 'required|exists:existencias,id',
        'cantidad_base_usada' => 'required|integer|min:1',
        'cantidad_tapa_usada' => 'required|integer|min:1',
        'cantidad_generada' => 'nullable|integer|min:0',
        'observaciones' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->personales = Personal::all();
        $this->existencias_base = Existencia::where('existenciable_type', 'App\\Models\\Base')->get();
        $this->existencias_tapa = Existencia::where('existenciable_type', 'App\\Models\\Tapa')->get();
    }

    public function render()
    {
        $embotellados = ModelEmbotellado::with(['personal'])
            ->when($this->search, function ($query) {
                $query->whereDate('fecha_embotellado', $this->search)
                    ->orWhereHas('personal', function ($q) {
                        $q->where('nombres', 'like', "%{$this->search}%");
                    });
            })
            ->latest()
            ->paginate(5);

        return view('livewire.embotellado', compact('embotellados'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function abrirModal()
    {
        $this->resetForm();
        $this->accion = 'create';
        $this->modal = true;
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function guardar()
    {
        $this->validate();

        try {
            $base = Existencia::findOrFail($this->existencia_base_id);
            $tapa = Existencia::findOrFail($this->existencia_tapa_id);

            if ($this->cantidad_base_usada > $base->cantidad) {
                $this->addError('cantidad_base_usada', 'Stock insuficiente de base');
                return;
            }

            if ($this->cantidad_tapa_usada > $tapa->cantidad) {
                $this->addError('cantidad_tapa_usada', 'Stock insuficiente de tapa');
                return;
            }

            $producto = Existencia::firstOrCreate(
                [
                    'existenciable_type' => 'App\\Models\\Producto',
                    'existenciable_id' => $base->existenciable_id,
                    'sucursal_id' => $base->sucursal_id,
                ],
                [
                    'cantidad' => 0,
                    'descripcion' => 'Generado desde embotellado'
                ]
            );

            $this->existencia_producto_id = $producto->id;

            ModelEmbotellado::create([
                'fecha_embotellado' => $this->fecha_embotellado,
                'personal_id' => $this->personal_id,
                'existencia_base_id' => $this->existencia_base_id,
                'existencia_tapa_id' => $this->existencia_tapa_id,
                'existencia_producto_id' => $this->existencia_producto_id,
                'cantidad_base_usada' => $this->cantidad_base_usada,
                'cantidad_tapa_usada' => $this->cantidad_tapa_usada,
                'cantidad_generada' => $this->cantidad_generada,
                'observaciones' => $this->observaciones,
            ]);

            $base->cantidad -= $this->cantidad_base_usada;
            $tapa->cantidad -= $this->cantidad_tapa_usada;
            $producto->cantidad += $this->cantidad_generada;
            $base->save();
            $tapa->save();
            $producto->save();

            LivewireAlert::title('Embotellado registrado con éxito')->success()->show();
            $this->cerrarModal();

        } catch (\Exception $e) {
            LivewireAlert::title('Error: ' . $e->getMessage())->error()->show();
        }
    }

    public function editar($id)
    {
        $registro = ModelEmbotellado::findOrFail($id);

        $this->embotelladoId = $registro->id;
        $this->fecha_embotellado = $registro->fecha_embotellado;
        $this->personal_id = $registro->personal_id;
        $this->existencia_base_id = $registro->existencia_base_id;
        $this->existencia_tapa_id = $registro->existencia_tapa_id;
        $this->cantidad_base_usada = $registro->cantidad_base_usada;
        $this->cantidad_tapa_usada = $registro->cantidad_tapa_usada;
        $this->cantidad_generada = $registro->cantidad_generada;
        $this->observaciones = $registro->observaciones;

        $this->accion = 'edit';
        $this->modal = true;
    }

    public function modaldetalle($id)
    {
        $this->embotelladoSeleccionado = ModelEmbotellado::with(['personal'])->findOrFail($id);
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->embotelladoSeleccionado = [];
    }

    private function resetForm()
    {
        $this->reset([
            'embotelladoId', 'fecha_embotellado', 'personal_id',
            'existencia_base_id', 'existencia_tapa_id', 'cantidad_base_usada',
            'cantidad_tapa_usada', 'cantidad_generada', 'observaciones'
        ]);
    }
}

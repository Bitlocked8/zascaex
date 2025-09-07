<?php

namespace App\Livewire;

use App\Models\Etiquetado as ModelEtiquetado;
use App\Models\Existencia;
use App\Models\Personal;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Etiquetado extends Component
{
    use WithPagination;

    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $accion = 'create';

    public $etiquetadoId;
    public $fecha_etiquetado;
    public $personal_id;
    public $existencia_producto_id;
    public $existencia_etiqueta_id;
    public $existencia_stock_id;
    public $cantidad_producto_usado;
    public $cantidad_etiqueta_usada;
    public $cantidad_generada;
    public $observaciones;

    public $etiquetadoSeleccionado = [];
    public $personales = [];
    public $existencias_producto = [];
    public $existencias_etiqueta = [];

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'fecha_etiquetado' => 'required|date',
        'personal_id' => 'required|exists:personals,id',
        'existencia_producto_id' => 'required|exists:existencias,id',
        'existencia_etiqueta_id' => 'required|exists:existencias,id',
        'cantidad_producto_usado' => 'required|integer|min:1',
        'cantidad_etiqueta_usada' => 'required|integer|min:1',
        'cantidad_generada' => 'nullable|integer|min:0',
        'observaciones' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->personales = Personal::all();
        $this->existencias_producto = Existencia::where('existenciable_type', 'App\\Models\\Producto')->get();
        $this->existencias_etiqueta = Existencia::where('existenciable_type', 'App\\Models\\Etiqueta')->get();
    }

    public function render()
    {
        $etiquetados = ModelEtiquetado::with(['personal'])
            ->when($this->search, function ($query) {
                $query->whereDate('fecha_etiquetado', $this->search)
                    ->orWhereHas('personal', function ($q) {
                        $q->where('nombres', 'like', "%{$this->search}%");
                    });
            })
            ->latest()
            ->paginate(5);

        return view('livewire.etiquetado', compact('etiquetados'));
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
            $producto = Existencia::findOrFail($this->existencia_producto_id);
            $etiqueta = Existencia::findOrFail($this->existencia_etiqueta_id);

            if ($this->cantidad_producto_usado > $producto->cantidad) {
                $this->addError('cantidad_producto_usado', 'Stock insuficiente de producto');
                return;
            }

            if ($this->cantidad_etiqueta_usada > $etiqueta->cantidad) {
                $this->addError('cantidad_etiqueta_usada', 'Stock insuficiente de etiquetas');
                return;
            }

            $stock = Existencia::firstOrCreate(
                [
                    'existenciable_type' => 'App\\Models\\StockFinal',
                    'existenciable_id' => $producto->existenciable_id,
                    'sucursal_id' => $producto->sucursal_id,
                ],
                [
                    'cantidad' => 0,
                    'descripcion' => 'Generado desde etiquetado'
                ]
            );

            $this->existencia_stock_id = $stock->id;

            ModelEtiquetado::create([
                'fecha_etiquetado' => $this->fecha_etiquetado,
                'personal_id' => $this->personal_id,
                'existencia_producto_id' => $this->existencia_producto_id,
                'existencia_etiqueta_id' => $this->existencia_etiqueta_id,
                'existencia_stock_id' => $this->existencia_stock_id,
                'cantidad_producto_usado' => $this->cantidad_producto_usado,
                'cantidad_etiqueta_usada' => $this->cantidad_etiqueta_usada,
                'cantidad_generada' => $this->cantidad_generada,
                'observaciones' => $this->observaciones,
            ]);

            $producto->cantidad -= $this->cantidad_producto_usado;
            $etiqueta->cantidad -= $this->cantidad_etiqueta_usada;
            $stock->cantidad += $this->cantidad_generada;
            $producto->save();
            $etiqueta->save();
            $stock->save();

            LivewireAlert::title('Etiquetado registrado con éxito')->success()->show();
            $this->cerrarModal();

        } catch (\Exception $e) {
            LivewireAlert::title('Error: ' . $e->getMessage())->error()->show();
        }
    }

    public function editar($id)
    {
        $registro = ModelEtiquetado::findOrFail($id);

        $this->etiquetadoId = $registro->id;
        $this->fecha_etiquetado = $registro->fecha_etiquetado;
        $this->personal_id = $registro->personal_id;
        $this->existencia_producto_id = $registro->existencia_producto_id;
        $this->existencia_etiqueta_id = $registro->existencia_etiqueta_id;
        $this->cantidad_producto_usado = $registro->cantidad_producto_usado;
        $this->cantidad_etiqueta_usada = $registro->cantidad_etiqueta_usada;
        $this->cantidad_generada = $registro->cantidad_generada;
        $this->observaciones = $registro->observaciones;

        $this->accion = 'edit';
        $this->modal = true;
    }

    public function modaldetalle($id)
    {
        $this->etiquetadoSeleccionado = ModelEtiquetado::with(['personal'])->findOrFail($id);
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->etiquetadoSeleccionado = [];
    }

    private function resetForm()
    {
        $this->reset([
            'etiquetadoId', 'fecha_etiquetado', 'personal_id',
            'existencia_producto_id', 'existencia_etiqueta_id', 'cantidad_producto_usado',
            'cantidad_etiqueta_usada', 'cantidad_generada', 'observaciones'
        ]);
    }
}

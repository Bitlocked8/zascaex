<?php

namespace App\Livewire;

use App\Models\Stock;
use App\Models\Etiqueta;
use App\Models\Existencia;
use App\Models\Producto;
use App\Models\Sucursal;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Stocks extends Component
{
    use WithPagination;
    // Modal de Existencia
    public $modalExistencia = false;
    public $existencia_id = null;
    public $cantidad = '';
    public $existencia_sucursal_id = '';
    public $existenciable_id = '';
    public $existenciable_type = '';
    public $cantidadMinima = '';

    public $search = '';
    public $modal = false;
    public $modalDetalle = false;
    public $stock_id = null;
    public $fechaElaboracion = '';
    public $fechaVencimiento = '';
    public $observaciones = '';
    public $etiqueta_id = '';
    public $producto_id = '';
    public $sucursal_id = '';
    public $accion = 'create';
    public $stockSeleccionado = [];

    public $todasExistencias;

    public $etiquetas;
    public $productos;
    public $sucursales;
    public $existencias;
    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'fechaElaboracion' => 'required|date',
        'fechaVencimiento' => 'required|date|after_or_equal:fechaElaboracion',
        'observaciones' => 'nullable|string|max:255',
        'etiqueta_id' => 'nullable|exists:etiquetas,id',
        'producto_id' => 'nullable|exists:productos,id',
        // 'sucursal_id' => 'nullable|exists:sucursals,id',
    ];

    public function mount()
    {
        $this->etiquetas = Etiqueta::all();
        $this->productos = Producto::all();
        $this->sucursales = Sucursal::all();
        $this->existencias = Existencia::all();
    }

    public function render()
    {
        $stocks = Stock::with(['etiqueta', 'producto', 'sucursal','existencias'])
            ->when($this->search, function ($query) {
                $query->where('observaciones', 'like', '%' . $this->search . '%');
            })
            ->paginate(5);

        return view('livewire.stocks', compact('stocks'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function abrirModal($accion = 'create', $id = null)
    {
        $this->reset(['fechaElaboracion', 'fechaVencimiento', 'observaciones', 'etiqueta_id', 'producto_id']);
        $this->accion = $accion;

        if ($accion === 'edit' && $id) {
            $this->editar($id);
        }

        $this->modal = true;
    }

    public function editar($id)
    {
        $stock = Stock::findOrFail($id);

        $this->stock_id = $stock->id;
        $this->fechaElaboracion = $stock->fechaElaboracion;
        $this->fechaVencimiento = $stock->fechaVencimiento;
        $this->observaciones = $stock->observaciones;
        $this->etiqueta_id = $stock->etiqueta_id;
        $this->producto_id = $stock->producto_id;
        // $this->sucursal_id = $stock->sucursal_id;
        $this->accion = 'edit';
    }

    public function guardar()
    {
        $this->validate();

        try {
            Stock::updateOrCreate(['id' => $this->stock_id], [
                'fechaElaboracion' => $this->fechaElaboracion,
                'fechaVencimiento' => $this->fechaVencimiento,
                'observaciones' => $this->observaciones,
                'etiqueta_id' => $this->etiqueta_id ?: null,
                'producto_id' => $this->producto_id ?: null,
                // 'sucursal_id' => $this->sucursal_id ?: null,
            ]);

            LivewireAlert::title($this->stock_id ? 'Stock actualizado con éxito.' : 'Stock creado con éxito.')
                ->success()
                ->show();

            $this->cerrarModal();
        } catch (\Exception $e) {
            LivewireAlert::title('Ocurrió un error: ' . $e->getMessage())
                ->error()
                ->show();
        }
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->reset(['fechaElaboracion', 'fechaVencimiento', 'observaciones', 'etiqueta_id', 'producto_id', 'stock_id']);
        $this->resetErrorBag();
    }

    public function modaldetalle($id)
    {
        $this->stockSeleccionado = Stock::with(['etiqueta', 'producto', 'sucursal'])->findOrFail($id);
        $this->modalDetalle = true;
    }

    public function cerrarModalDetalle()
    {
        $this->modalDetalle = false;
        $this->stockSeleccionado = null;
    }
    public function abrirModalExistencia($id = null)
    {
        $this->reset(['existencia_id', 'cantidad', 'cantidadMinima', 'existencia_sucursal_id', 'existenciable_id', 'existenciable_type']); // Resetear cantidad mínima

        if ($id) {
            $existencia = \App\Models\Existencia::findOrFail($id);
            $this->existencia_id = $existencia->id;
            $this->cantidad = $existencia->cantidad;
            $this->cantidadMinima = $existencia->cantidadMinima; // Asignar cantidad mínima
            $this->existencia_sucursal_id = $existencia->sucursal_id;
            $this->existenciable_id = $existencia->existenciable_id;
            $this->existenciable_type = $existencia->existenciable_type;
        }

        $this->modalExistencia = true;
    }

    public function guardarExistencia()
    {
        // Validación actualizada
        $this->validate([
            'cantidad' => 'required|integer|min:1',
            'cantidadMinima' => 'required|integer|min:0',
            'existencia_sucursal_id' => 'required|exists:sucursals,id',
            'existenciable_id' => 'required|exists:existencias,id'
        ]);

        try {
            // Obtener la existencia relacionada para determinar el tipo
            $existenciaRelacionada = Existencia::findOrFail($this->existenciable_id);

            // Guardar o actualizar
            Existencia::updateOrCreate(
                ['id' => $this->existencia_id],
                [
                    'cantidad' => $this->cantidad,
                    'cantidadMinima' => $this->cantidadMinima,
                    'sucursal_id' => $this->existencia_sucursal_id,
                    'existenciable_id' => $this->existenciable_id,
                    'existenciable_type' => $existenciaRelacionada->existenciable_type
                ]
            );

            LivewireAlert::success('Existencia guardada correctamente');
            $this->cerrarModalExistencia();
            $this->cargarExistencias();
        } catch (\Exception $e) {
            LivewireAlert::error('Error: ' . $e->getMessage());
        }
    }
    public function cerrarModalExistencia()
    {
        $this->modalExistencia = false;
        $this->reset(['existencia_id', 'cantidad', 'cantidadMinima', 'existencia_sucursal_id', 'existenciable_id', 'existenciable_type']);
        $this->resetErrorBag();
    }
    public $modalVerExistencias = false;

    public function abrirModalVerExistencias()
    {
        $this->todasExistencias = Existencia::with(['sucursal', 'existenciable'])->get();
        $this->modalVerExistencias = true;
    }

    public function cerrarModalVerExistencias()
    {
        $this->modalVerExistencias = false;
    }
    public function cargarExistencias()
    {
        $this->todasExistencias = Existencia::with(['sucursal', 'existenciable'])->get();
    }
}

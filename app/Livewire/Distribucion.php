<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Distribucion as ModeloDistribucion;
use App\Models\ItemDistribucion;
use App\Models\Asignacion;
use App\Models\Venta;
use App\Models\Stock;
use App\Models\Existencia;
use App\Models\ItemVenta;

use App\Models\Sucursal;
use App\Models\Producto;
use Illuminate\Support\Collection;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\DB;

class Distribucion extends Component
{
    use WithPagination;
    // use LivewireAlert;

    // Propiedades básicas
    public $search = '';
    public $modal = false;
    public $detalleModal = false;
    public $previewVentaModal = false; // Nueva propiedad para modal de previsualización
    public $itemManualModal = false; // Nueva propiedad para modal de item manual
    public $accion = 'create';
    public $distribucionId = null;
    public $fecha;
    public $estado = 1;
    public $observaciones = '';
    public $asignacion_id;
    public $asignaciones;

    // Propiedades para manejar ventas
    public $venta_id;
    public $ventasContado;
    public $ventaSeleccionada; // Para previsualizar
    public $stocksVenta;
    public $itemsVentaDisponibles = [];
    public $stocksSucursal;

    // Propiedades para manejar stocks sueltos
    public $stocksDisponibles;
    public $selectedSucursal;
    public $sucursales;
    public $modoSeleccion = 'venta'; // 'venta' o 'stock'

    // Propiedades para items de distribución
    public $itemsDistribucion = [];

    // Propiedades para item manual
    public $productoSeleccionado;
    public $stocksProducto;
    public $stockManualId;
    public $cantidadManualNuevo = 0;
    public $cantidadManualUsados = 0;

    // Propiedades para detalles
    public $distribucionSeleccionada = null;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'fecha' => 'required|date',
        'estado' => 'required|in:1,2',
        'observaciones' => 'nullable|string|max:500',
        'asignacion_id' => 'required|exists:asignacions,id',
        'itemsDistribucion' => 'array',
        'itemsDistribucion.*.stock_id' => 'required|exists:stocks,id',
        'itemsDistribucion.*.cantidadNuevo' => 'required|integer|min:0',
        'itemsDistribucion.*.cantidadUsados' => 'required|integer|min:0',
    ];

    protected $listeners = [
        'confirmarEliminacionItem',
        'confirmarGuardarDistribucion',
        'agregarStockSuelto',
        'agregarStockManual'
    ];

    public function mount()
    {
        $this->asignaciones = Asignacion::all();
        $this->ventasContado = Venta::where('estadoPedido', 1)->with('cliente')->get();
        $this->sucursales = Sucursal::all();
        $this->fecha = now()->format('Y-m-d');
        $this->selectedSucursal = $this->sucursales->first()->id ?? null;
        $this->cargarStocksDisponibles();
    }

    public function render()
    {
        $distribucions = ModeloDistribucion::when($this->search, function ($query) {
            $query->where('fecha', 'like', '%' . $this->search . '%')
                ->orWhere('observaciones', 'like', '%' . $this->search . '%');
        })->with(['asignacion.personal', 'itemdistribucions.stock.producto'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Cargar productos para selector de items manuales
        $productos = Producto::orderBy('nombre')->get();

        return view('livewire.distribucion', compact('distribucions', 'productos'));
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedSucursal()
    {
        $this->cargarStocksDisponibles();
    }

    public function cambiarModoSeleccion($modo)
    {
        $this->modoSeleccion = $modo;

        if ($modo === 'stock') {
            $this->cargarStocksDisponibles();
            $this->reset(['venta_id', 'stocksVenta', 'itemsVentaDisponibles']);
        } else {
            $this->reset(['stocksDisponibles']);
        }
    }

    public function cargarStocksDisponibles()
    {
        if ($this->selectedSucursal) {
            $this->stocksDisponibles = Stock::whereHas('existencias', function ($query) {
                $query->where('sucursal_id', $this->selectedSucursal)
                    ->where('cantidad', '>', 0);
            })->with(['producto', 'existencias', 'etiqueta'])->get();
        } else {
            $this->stocksDisponibles = collect();
        }
    }

    public function previewVenta()
    {
        if (!$this->venta_id) {
            $this->alert('error', 'Debe seleccionar una venta primero.');
            return;
        }

        $this->ventaSeleccionada = Venta::with([
            'cliente',
            'itemVentas.existencia.existenciable',
            'personal'
        ])->find($this->venta_id);

        if (!$this->ventaSeleccionada) {
            $this->alert('error', 'No se encontró la venta seleccionada.');
            return;
        }

        $this->previewVentaModal = true;
    }

    public function cargarStocksVenta()
    {
        $this->itemsVentaDisponibles = [];

        if (!$this->venta_id || !$this->selectedSucursal) {
            $this->stocksVenta = null;
            return;
        }

        $venta = Venta::with(['itemVentas.existencia.existenciable', 'cliente'])->find($this->venta_id);

        if (!$venta) {
            $this->alert('error', 'No se encontró la venta seleccionada.');
            return;
        }

        // Cargar los items de venta y verificar su disponibilidad en la sucursal
        foreach ($venta->itemVentas as $itemVenta) {
            if ($itemVenta->existencia && $itemVenta->existencia->existenciable_type === 'App\\Models\\Stock') {
                $stock_id = $itemVenta->existencia->existenciable_id;
                $stock = Stock::with([
                    'producto',
                    'existencias' => function ($query) {
                        $query->where('sucursal_id', $this->selectedSucursal);
                    }
                ])->find($stock_id);

                if ($stock) {
                    $existencia = $stock->existencias->first(); // ⬅️ tomar solo una, si hay
                    $cantidadDisponible = $existencia ? $existencia->cantidad : 0;
                    $faltante = $itemVenta->cantidad - $cantidadDisponible;

                    $this->itemsVentaDisponibles[] = [
                        'item_venta_id' => $itemVenta->id,
                        'stock_id' => $stock_id,
                        'producto' => $stock->producto->nombre,
                        'cantidad_pedida' => $itemVenta->cantidad,
                        'cantidad_disponible' => $cantidadDisponible,
                        'precio' => $itemVenta->precio,
                        'faltante' => max(0, $faltante),
                        'disponible' => $cantidadDisponible >= $itemVenta->cantidad,
                    ];
                }
            }
        }

        $this->stocksVenta = $venta;
    }

    public function agregarItemVenta($itemVentaIndex)
    {
        $itemVenta = $this->itemsVentaDisponibles[$itemVentaIndex];

        if (!$itemVenta['disponible']) {
            $this->alert('error', 'No hay suficiente stock disponible para este ítem.');
            return;
        }

        // Verificar si ya existe este stock en los items de distribución
        $existeIndex = $this->buscarItemDistribucionPorStockId($itemVenta['stock_id']);

        if ($existeIndex !== false) {
            // Actualizar cantidades
            $this->itemsDistribucion[$existeIndex]['cantidadNuevo'] += $itemVenta['cantidad_pedida'];
        } else {
            // Agregar nuevo item
            $this->itemsDistribucion[] = [
                'stock_id' => $itemVenta['stock_id'],
                'producto' => $itemVenta['producto'],
                'cantidadNuevo' => $itemVenta['cantidad_pedida'],
                'cantidadUsados' => 0,
                'origen' => 'venta',
                'item_venta_id' => $itemVenta['item_venta_id']
            ];
        }

        // Marcar como agregado para no permitir agregar dos veces
        $this->itemsVentaDisponibles[$itemVentaIndex]['agregado'] = true;

        $this->alert('success', 'Ítem agregado a la distribución.');
    }

    public function agregarStockSuelto($stockId, $cantidad = 1)
    {
        $stock = $this->stocksDisponibles->firstWhere('id', $stockId);

        if (!$stock) {
            $this->alert('error', 'No se encontró el stock seleccionado.');
            return;
        }

        $cantidadDisponible = $stock->existencia ? $stock->existencia->cantidad : 0;

        if ($cantidadDisponible < $cantidad) {
            $this->alert('error', 'No hay suficiente stock disponible.');
            return;
        }

        // Verificar si ya existe este stock en los items de distribución
        $existeIndex = $this->buscarItemDistribucionPorStockId($stockId);

        if ($existeIndex !== false) {
            // Actualizar cantidades
            $this->itemsDistribucion[$existeIndex]['cantidadNuevo'] += $cantidad;
        } else {
            // Agregar nuevo item
            $this->itemsDistribucion[] = [
                'stock_id' => $stockId,
                'producto' => $stock->producto->nombre,
                'cantidadNuevo' => $cantidad,
                'cantidadUsados' => 0,
                'origen' => 'stock'
            ];
        }

        $this->alert('success', 'Stock agregado a la distribución.');
    }

    // Nuevo método para abrir modal de item manual
    public function abrirModalItemManual()
    {
        $this->reset(['productoSeleccionado', 'stocksProducto', 'stockManualId', 'cantidadManualNuevo', 'cantidadManualUsados']);
        $this->itemManualModal = true;
    }

    // Cargar stocks disponibles por producto
    public function updatedProductoSeleccionado()
    {
        if ($this->productoSeleccionado && $this->selectedSucursal) {
            $this->stocksProducto = Stock::whereHas('existencia', function ($query) {
                $query->where('sucursal_id', $this->selectedSucursal)
                    ->where('cantidad', '>', 0);
            })
                ->where('producto_id', $this->productoSeleccionado)
                ->with(['producto', 'existencia', 'etiqueta'])
                ->get();
        } else {
            $this->stocksProducto = collect();
        }
    }

    // Agregar item manual a la distribución
    public function agregarStockManual()
    {
        if (!$this->stockManualId) {
            $this->alert('error', 'Debe seleccionar un stock.');
            return;
        }

        if ($this->cantidadManualNuevo <= 0 && $this->cantidadManualUsados <= 0) {
            $this->alert('error', 'Debe ingresar al menos una cantidad.');
            return;
        }

        $stock = Stock::with('producto', 'existencia')->find($this->stockManualId);

        if (!$stock) {
            $this->alert('error', 'No se encontró el stock seleccionado.');
            return;
        }

        $cantidadDisponible = $stock->existencia ? $stock->existencia->cantidad : 0;
        $cantidadTotal = $this->cantidadManualNuevo + $this->cantidadManualUsados;

        if ($cantidadDisponible < $cantidadTotal) {
            $this->alert('error', 'No hay suficiente stock disponible. Disponible: ' . $cantidadDisponible);
            return;
        }

        // Verificar si ya existe este stock en los items de distribución
        $existeIndex = $this->buscarItemDistribucionPorStockId($this->stockManualId);

        if ($existeIndex !== false) {
            // Actualizar cantidades
            $this->itemsDistribucion[$existeIndex]['cantidadNuevo'] += $this->cantidadManualNuevo;
            $this->itemsDistribucion[$existeIndex]['cantidadUsados'] += $this->cantidadManualUsados;
        } else {
            // Agregar nuevo item
            $this->itemsDistribucion[] = [
                'stock_id' => $this->stockManualId,
                'producto' => $stock->producto->nombre,
                'cantidadNuevo' => $this->cantidadManualNuevo,
                'cantidadUsados' => $this->cantidadManualUsados,
                'origen' => 'manual'
            ];
        }

        $this->itemManualModal = false;
        $this->alert('success', 'Item agregado manualmente a la distribución.');
    }

    public function eliminarItemDistribucion($index)
    {
        // Si el ítem viene de una venta, restaurar su estado para poder volver a agregarlo
        if (
            isset($this->itemsDistribucion[$index]['origen']) &&
            $this->itemsDistribucion[$index]['origen'] === 'venta' &&
            isset($this->itemsDistribucion[$index]['item_venta_id'])
        ) {

            $itemVentaId = $this->itemsDistribucion[$index]['item_venta_id'];

            foreach ($this->itemsVentaDisponibles as $idx => $item) {
                if ($item['item_venta_id'] === $itemVentaId) {
                    $this->itemsVentaDisponibles[$idx]['agregado'] = false;
                    break;
                }
            }
        }

        // Eliminar el ítem
        unset($this->itemsDistribucion[$index]);
        $this->itemsDistribucion = array_values($this->itemsDistribucion);

        $this->alert('info', 'Ítem eliminado de la distribución.');
    }

    public function confirmarEliminacionItem($index)
    {
        $this->eliminarItemDistribucion($index);
    }

    private function buscarItemDistribucionPorStockId($stockId)
    {
        foreach ($this->itemsDistribucion as $index => $item) {
            if ($item['stock_id'] == $stockId) {
                return $index;
            }
        }

        return false;
    }

    public function abrirModal($accion, $id = null)
    {
        $this->reset([
            'fecha',
            'estado',
            'observaciones',
            'asignacion_id',
            'venta_id',
            'stocksVenta',
            'distribucionId',
            'itemsDistribucion',
            'itemsVentaDisponibles'
        ]);

        $this->accion = $accion;
        $this->fecha = now()->format('Y-m-d');
        $this->estado = 1;

        if ($accion === 'edit' && $id) {
            $this->editarDistribucion($id);
        }

        $this->modal = true;
        $this->detalleModal = false;
        $this->previewVentaModal = false;
        $this->itemManualModal = false;
    }

    public function editarDistribucion($id)
    {
        $distribucion = ModeloDistribucion::with(['itemdistribucions', 'itemdistribucions.stock.producto'])->findOrFail($id);

        $this->distribucionId = $distribucion->id;
        $this->fecha = $distribucion->fecha;
        $this->estado = $distribucion->estado;
        $this->observaciones = $distribucion->observaciones;
        $this->asignacion_id = $distribucion->asignacion_id;

        // Cargar los items de distribución
        $this->itemsDistribucion = [];
        foreach ($distribucion->itemdistribucions as $item) {
            $this->itemsDistribucion[] = [
                'stock_id' => $item->stock_id,
                'producto' => $item->stock->producto->nombre ?? 'Producto no encontrado',
                'cantidadNuevo' => $item->cantidadNuevo,
                'cantidadUsados' => $item->cantidadUsados,
                'origen' => 'manual', // Marcar como manual para edición
            ];
        }
    }

    public function verDetalle($id)
    {
        $distribucion = ModeloDistribucion::with([
            'asignacion.personal',
            'itemdistribucions.stock.producto'
        ])->findOrFail($id);

        $this->distribucionSeleccionada = $distribucion->toArray();
        $this->modal = false;
        $this->detalleModal = true;
        $this->previewVentaModal = false;
        $this->itemManualModal = false;
    }

    public function guardarDistribucion()
    {
        // Validar que haya al menos un item en la distribución
        if (empty($this->itemsDistribucion)) {
            LivewireAlert::error('Error', 'Debe agregar al menos un ítem a la distribución.')->show();
            return;
        }

        // Validar datos básicos
        $this->validate([
            'fecha' => 'required|date',
            'estado' => 'required|in:1,2',
            'observaciones' => 'nullable|string|max:500',
            'asignacion_id' => 'required|exists:asignacions,id',
        ]);

        // Iniciar transacción
        \DB::beginTransaction();

        try {
            if ($this->accion === 'edit' && $this->distribucionId) {
                $distribucion = ModeloDistribucion::findOrFail($this->distribucionId);
                $distribucion->update([
                    'fecha' => $this->fecha,
                    'estado' => $this->estado,
                    'observaciones' => $this->observaciones,
                    'asignacion_id' => $this->asignacion_id,
                ]);

                // Eliminar items anteriores
                ItemDistribucion::where('distribucion_id', $distribucion->id)->delete();
            } else {
                $distribucion = ModeloDistribucion::create([
                    'fecha' => $this->fecha,
                    'estado' => $this->estado,
                    'observaciones' => $this->observaciones,
                    'asignacion_id' => $this->asignacion_id,
                ]);
            }

            // Crear los nuevos items de distribución
            foreach ($this->itemsDistribucion as $item) {
                ItemDistribucion::create([
                    'cantidadNuevo' => $item['cantidadNuevo'],
                    'cantidadUsados' => $item['cantidadUsados'],
                    'stock_id' => $item['stock_id'],
                    'distribucion_id' => $distribucion->id,
                ]);

                // Actualizar existencias si la distribución está en estado "En distribución"
                if ($this->estado == 1) {
                    $stock = Stock::findOrFail($item['stock_id']);
                    $existencia = Existencia::where('existenciable_type', 'App\\Models\\Stock')
                        ->where('existenciable_id', $stock->id)
                        ->where('sucursal_id', $this->selectedSucursal)
                        ->first();

                    if ($existencia) {
                        $cantidadTotal = $item['cantidadNuevo'] + $item['cantidadUsados'];
                        $existencia->cantidad -= $cantidadTotal;
                        $existencia->save();
                    }
                }
            }

            \DB::commit();

            LivewireAlert::success(
                'Éxito',
                $this->accion === 'edit'
                    ? 'Distribución actualizada con éxito.'
                    : 'Distribución registrada con éxito.'
            )->show();

            $this->cerrarModal();
        } catch (\Exception $e) {
            \DB::rollBack();
            LivewireAlert::error('Error', 'Ocurrió un error: ' . $e->getMessage())->show();
        }
    }


    public function confirmarGuardarDistribucion()
    {
        $this->guardarDistribucion();
    }

    public function retornarStock($id)
    {
        $distribucion = ModeloDistribucion::with(['itemdistribucions.stock'])->findOrFail($id);

        // Solo permitir retornar si está en distribución
        if ($distribucion->estado != 1) {
            $this->alert('error', 'Solo se puede retornar stock de distribuciones en estado "En distribución".');
            return;
        }

        try {
            \DB::beginTransaction();

            // Actualizar estado de la distribución
            $distribucion->estado = 0; // Cancelado/retornado
            $distribucion->save();

            // Devolver stock a existencias
            foreach ($distribucion->itemdistribucions as $item) {
                $stock = $item->stock;
                $existencia = Existencia::where('existenciable_type', 'App\\Models\\Stock')
                    ->where('existenciable_id', $stock->id)
                    ->where('sucursal_id', $stock->sucursal_id)
                    ->first();

                if ($existencia) {
                    $cantidadTotal = $item->cantidadNuevo + $item->cantidadUsados;
                    $existencia->cantidad += $cantidadTotal;
                    $existencia->save();
                }
            }

            \DB::commit();

            $this->alert('success', 'Stock retornado con éxito para la Distribución #' . $distribucion->id);
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->alert('error', 'Ocurrió un error al retornar stock: ' . $e->getMessage());
        }
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->detalleModal = false;
        $this->previewVentaModal = false;
        $this->itemManualModal = false;
        $this->reset([
            'fecha',
            'estado',
            'observaciones',
            'asignacion_id',
            'venta_id',
            'stocksVenta',
            'distribucionId',
            'itemsDistribucion',
            'itemsVentaDisponibles',
            'distribucionSeleccionada',
            'productoSeleccionado',
            'stocksProducto',
            'stockManualId',
            'cantidadManualNuevo',
            'cantidadManualUsados'
        ]);
        $this->resetErrorBag();
    }
}

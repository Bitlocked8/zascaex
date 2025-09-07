<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Venta as ModeloVenta;
use App\Models\Cliente;
use App\Models\Personal;
use Carbon\Carbon;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Auth;
use App\Models\Existencia;
use App\Models\Producto;

class Venta extends Component
{
    public $search = '';
    public $searchCliente = '';

    public $modal = false;
    public $detalleModal = false;
    public $accion = 'create';
    public $ventaId = null;
    public $usuario;

    public $fechaPedido;
    public $fechaEntrega;
    public $fechaMaxima;
    public $estadoPedido = 1;
    public $estadoPago = 1;
    public $cliente_id;
    public $personal_id;
    public $personalEntrega_id;
    public $ventaSeleccionada;

    public $clientes;
    public $personales;
    public $sucursal_id;
    public $sucursales;
    public $sucursalAnterior;

    public $productos = [];
    public $productosSeleccionados = [];
    public $productosAgregados = [];


    public $mensajeError = null;

    public $filtroEstadoPedido = null;
    public $filtroEstadoPago = null;

    protected $rules = [
        'fechaPedido' => 'nullable|date',
        'fechaEntrega' => 'nullable|date|after_or_equal:fechaPedido',
        'fechaMaxima' => 'nullable|date|after_or_equal:fechaEntrega',
        'estadoPedido' => 'required|in:0,1,2',
        'estadoPago' => 'required|in:0,1',
        'cliente_id' => 'required|exists:clientes,id',
        'personal_id' => 'required|exists:personals,id',
        'personalEntrega_id' => 'nullable|exists:personals,id',
        'sucursal_id' => 'required|exists:sucursals,id',
    ];

    public function mount()
    {
        $this->usuario = Auth::user();
        $this->clientes = Cliente::all(['id', 'nombre']);
        $this->personales = Personal::all(['id', 'nombres']);
        $this->sucursales = Sucursal::all(['id', 'nombre']);
        $this->sucursalAnterior = $this->sucursal_id;
    }

    public function updatedSucursalId($value)
    {
        if (!empty($this->productosAgregados)) {
            $this->mensajeError = "No puedes cambiar de sucursal mientras haya productos agregados a la venta.";
            $this->sucursal_id = $this->sucursalAnterior;
            return;
        }

        $this->sucursalAnterior = $value;
        $this->cargarProductos();
    }

    public function cargarProductos()
    {
        if (!$this->sucursal_id) {
            $this->productos = [];
            $this->productosSeleccionados = [];
            return;
        }

        $this->productos = Existencia::with('existenciable')
            ->where('sucursal_id', $this->sucursal_id)
            ->get()
            ->filter(fn($e) => $e->existenciable_type === Producto::class)
            ->map(fn($e) => [
                'id' => $e->existenciable->id,
                'nombre' => $e->existenciable->nombre ?? 'Producto no definido',
                'cantidad' => $e->cantidad,
                'precioReferencia' => $e->existenciable->precioReferencia ?? 0, // 👈 agregar aquí
            ])->toArray();


        $this->productosSeleccionados = [];
        foreach ($this->productos as $index => $prod) {
            $this->productosSeleccionados[$index] = [
                'id' => $prod['id'],
                'nombre' => $prod['nombre'],
                'cantidad' => 1,
                'precio' => $prod['precioReferencia'], // Precio por defecto del producto
                'seleccionado' => false
            ];
        }
    }

    public function stockDisponible($productoId)
    {
        $existencia = Existencia::where('sucursal_id', $this->sucursal_id)
            ->where('existenciable_id', $productoId)
            ->where('existenciable_type', Producto::class)
            ->first();

        if (!$existencia) return 0;

        $stock = $existencia->cantidad;

        foreach ($this->productosAgregados as $p) {
            if ($p['id'] === $productoId) {
                $stock -= $p['cantidad'];
            }
        }

        return max($stock, 0);
    }

    public function agregarProducto($index)
    {
        $producto = $this->productosSeleccionados[$index];
        $stockDisponible = $this->stockDisponible($producto['id']);

        if ($producto['cantidad'] > $stockDisponible) {
            $this->mensajeError = "No puedes añadir más de la cantidad disponible ({$stockDisponible})";
            $this->productosSeleccionados[$index]['cantidad'] = $stockDisponible;
            return;
        }

        // Ver si ya existe en productosAgregados
        $key = collect($this->productosAgregados)->search(fn($p) => $p['id'] == $producto['id']);

        if ($key !== false) {
            // Sumar la cantidad al producto existente
            $this->productosAgregados[$key]['cantidad'] += $producto['cantidad'];
        } else {
            $this->productosAgregados[] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'cantidad' => $producto['cantidad'],
                'precio' => $producto['precio'], // ✅ Guardamos precio unitario
            ];
        }

        $this->productosSeleccionados[$index]['cantidad'] = 1;
        $this->productosSeleccionados[$index]['seleccionado'] = false;
        $this->mensajeError = null;

        $this->recalcularProductos();
    }


    public function quitarProducto($index)
    {
        unset($this->productosAgregados[$index]);
        $this->productosAgregados = array_values($this->productosAgregados);

        $this->recalcularProductos();
    }


    public function render()
    {
        $ventas = ModeloVenta::with(['cliente', 'personal', 'personalEntrega', 'sucursal'])
            ->when($this->search, fn($q) =>
            $q->where('id', 'like', "%{$this->search}%"))
            ->when($this->searchCliente, fn($q) =>
            $q->whereHas('cliente', fn($c) =>
            $c->where('nombre', 'like', "%{$this->searchCliente}%")))
            ->when($this->filtroEstadoPedido !== null, fn($q) =>
            $q->where('estadoPedido', $this->filtroEstadoPedido))
            ->when($this->filtroEstadoPago !== null, fn($q) =>
            $q->where('estadoPago', $this->filtroEstadoPago))
            ->orderBy('id', 'desc')
            ->get();

        return view('livewire.venta', compact('ventas'));
    }

    public function filtrarEstadoPedido($estado)
    {
        $this->filtroEstadoPedido = $estado;
    }
    public function filtrarEstadoPago($estado)
    {
        $this->filtroEstadoPago = $estado;
    }

    public function abrirModal($accion)
    {

        $this->reset([
            'fechaPedido',
            'fechaEntrega',
            'fechaMaxima',
            'estadoPedido',
            'estadoPago',
            'cliente_id',
            'personal_id',
            'personalEntrega_id',
            'sucursal_id',
            'ventaId',
            'productosAgregados',
            'productosSeleccionados',
            'mensajeError',
        ]);

        if ($accion === 'create') {
            $this->fechaPedido = Carbon::now()->format('Y-m-d');
            $this->sucursal_id = $this->usuario->sucursal_id ?? null;
        }

        $this->accion = $accion;
        $this->modal = true;
        $this->detalleModal = false;


        $this->cargarProductos();
    }


    public function cerrarModal()
    {
        $this->modal = false;
        $this->detalleModal = false;
        $this->reset(['ventaId', 'ventaSeleccionada']);
        $this->resetErrorBag();
    }

    public function guardarVenta()
    {
        $this->validate();

        if ($this->accion === 'create') {
            // Crear venta nueva
            $venta = ModeloVenta::create([
                'fechaPedido'       => $this->fechaPedido ?? Carbon::now()->format('Y-m-d'),
                'fechaEntrega'      => $this->fechaEntrega,
                'fechaMaxima'       => $this->fechaMaxima,
                'estadoPedido'      => $this->estadoPedido,
                'estadoPago'        => $this->estadoPago,
                'cliente_id'        => $this->cliente_id,
                'personal_id'       => $this->personal_id,
                'personalEntrega_id' => $this->personalEntrega_id,
                'sucursal_id'       => $this->sucursal_id ?? $this->usuario->sucursal_id ?? 1,
            ]);
        } else {
            // Editar venta existente
            $venta = ModeloVenta::with('itemventas')->findOrFail($this->ventaId);

            // 1️⃣ Devolver stock de items antiguos
            foreach ($venta->itemventas as $item) {
                $existencia = $item->existencia;
                if ($existencia) {
                    $existencia->increment('cantidad', $item->cantidad);
                }
            }

            // 2️⃣ Actualizar datos de la venta
            $venta->update([
                'fechaPedido'       => $this->fechaPedido,
                'fechaEntrega'      => $this->fechaEntrega,
                'fechaMaxima'       => $this->fechaMaxima,
                'estadoPedido'      => $this->estadoPedido,
                'estadoPago'        => $this->estadoPago,
                'cliente_id'        => $this->cliente_id,
                'personal_id'       => $this->personal_id,
                'personalEntrega_id' => $this->personalEntrega_id,
                'sucursal_id'       => $this->sucursal_id ?? $venta->sucursal_id,
            ]);

            // 3️⃣ Borrar items antiguos
            $venta->itemventas()->delete();
        }

        // 🔹 Guardar items nuevos y descontar stock
        foreach ($this->productosAgregados as $p) {
            $existencia = Existencia::where('sucursal_id', $this->sucursal_id)
                ->where('existenciable_id', $p['id'])
                ->where('existenciable_type', Producto::class)
                ->first();

            if ($existencia) {
                $venta->itemventas()->create([
                    'cantidad'      => $p['cantidad'],
                    'precio'        => $p['precio'],
                    'existencia_id' => $existencia->id,
                    'estado'        => 1,
                ]);

                $existencia->decrement('cantidad', $p['cantidad']);
            }
        }

        $this->resetFormulario();
        $this->cerrarModal();
    }

    public function recalcularProductos()
    {
        $this->productos = Existencia::with('existenciable')
            ->where('sucursal_id', $this->sucursal_id)
            ->get()
            ->filter(fn($e) => $e->existenciable_type === Producto::class)
            ->map(fn($e) => [
                'id' => $e->existenciable->id,
                'nombre' => $e->existenciable->nombre ?? 'Producto no definido',
                'cantidad' => max(
                    0,
                    $e->cantidad - collect($this->productosAgregados)
                        ->where('id', $e->existenciable->id)
                        ->sum('cantidad')
                ),
                'precioReferencia' => $e->existenciable->precioReferencia ?? 0, // 👈 agregar precio
            ])->toArray();


        // 🔹 Sincronizar productosSeleccionados con el nuevo stock
        $this->productosSeleccionados = [];
        foreach ($this->productos as $index => $prod) {
            $this->productosSeleccionados[$index] = [
                'id' => $prod['id'],
                'nombre' => $prod['nombre'],
                'cantidad' => 1,
                'precio' => $prod['precioReferencia'], // precio por defecto
                'seleccionado' => false
            ];
        }
    }


    public function editarVenta($id)
    {
        $venta = ModeloVenta::with('itemventas')->findOrFail($id);

        $this->ventaId = $venta->id;
        $this->fechaPedido = $venta->fechaPedido;
        $this->fechaEntrega = $venta->fechaEntrega;
        $this->fechaMaxima = $venta->fechaMaxima;
        $this->estadoPedido = $venta->estadoPedido;
        $this->estadoPago = $venta->estadoPago;
        $this->cliente_id = $venta->cliente_id;
        $this->personal_id = $venta->personal_id;
        $this->personalEntrega_id = $venta->personalEntrega_id;
        $this->sucursal_id = $venta->sucursal_id;

        $this->accion = 'edit';
        $this->modal = true;

        $this->cargarProductos();

        // Cargar productos agregados desde la venta existente
        $this->productosAgregados = [];
        foreach ($venta->itemventas as $item) {
            $this->productosAgregados[] = [
                'id' => $item->existencia->existenciable_id,
                'nombre' => $item->existencia->existenciable->nombre,
                'cantidad' => $item->cantidad,
                'precio' => $item->precio,
            ];
        }
    }
    private function resetFormulario()
    {
        $this->ventaId = null;
        $this->accion = 'create';
        $this->cliente_id = null;
        $this->personal_id = null;
        $this->personalEntrega_id = null;
        $this->sucursal_id = null;

        $this->fechaPedido = Carbon::now()->format('Y-m-d');
        $this->fechaEntrega = null;
        $this->fechaMaxima = null;
        $this->estadoPedido = 1;
        $this->estadoPago = 0;

        $this->productos = [];
        $this->productosSeleccionados = [];
        $this->productosAgregados = [];
        $this->mensajeError = null;
    }

    public function verDetalle($id)
    {
        $this->ventaSeleccionada = ModeloVenta::with(['cliente', 'personal', 'personalEntrega', 'sucursal', 'itemventas'])->findOrFail($id);
        $this->modal = false;
        $this->detalleModal = true;
    }
}

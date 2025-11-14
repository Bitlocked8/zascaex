<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\SolicitudPedido;
use App\Models\SolicitudPedidoDetalle;
use Illuminate\Support\Facades\Auth;

class Hubclientes extends Component
{
    public $productos = [];
    public $carrito = [];
    public $cantidades = [];
    public $modalPedidosCliente = false;
    public $pedidosCliente = [];
    public $mostrarCarrito = false;

    public function mount()
    {
        $this->productos = Producto::where('estado', 1)->get();
    }

    public function agregarAlCarrito($productoId)
    {
        $producto = Producto::find($productoId);
        if (!$producto)
            return;

        $cantidad = $this->cantidades[$productoId] ?? 1;

        if (isset($this->carrito[$productoId])) {
            $this->carrito[$productoId]['cantidad'] += $cantidad;
        } else {
            $this->carrito[$productoId] = [
                'id' => $producto->id,
                'descripcion' => $producto->descripcion,
                'precio' => $producto->precioReferencia,
                'cantidad' => $cantidad,
                'imagen' => $producto->imagen,
            ];
        }

        $this->cantidades[$productoId] = 1;
    }

    public function eliminarDelCarrito($productoId)
    {
        unset($this->carrito[$productoId]);
    }

    public function hacerPedido()
    {
        $clienteId = Auth::user()->cliente->id ?? null;
        if (!$clienteId || empty($this->carrito))
            return;

        $solicitud = SolicitudPedido::create([
            'cliente_id' => $clienteId,
            'codigo' => 'SP-' . now()->format('YmdHis'),
            'estado' => 'pendiente',
            'observaciones' => null,
        ]);

        foreach ($this->carrito as $item) {
            SolicitudPedidoDetalle::create([
                'solicitud_pedido_id' => $solicitud->id,
                'producto_id' => $item['id'],
                'cantidad' => $item['cantidad'],
                'precio' => $item['precio'],
            ]);
        }

        $this->carrito = [];
        $this->mostrarCarrito = false;
        $this->verMisPedidos();
    }

    public function verMisPedidos()
    {
        $cliente = Auth::user()->cliente ?? null;
        if (!$cliente)
            return;

        $this->pedidosCliente = SolicitudPedido::where('cliente_id', $cliente->id)
            ->with('detalles.producto')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->modalPedidosCliente = true;
    }

    public function cerrarModalPedidos()
    {
        $this->modalPedidosCliente = false;
    }

    public function render()
    {
        return view('livewire.hubclientes', [
            'productos' => $this->productos,
            'carrito' => $this->carrito,
            'pedidosCliente' => $this->pedidosCliente,
        ]);
    }

    public function eliminarSolicitud($id)
    {
        $pedido = SolicitudPedido::where('id', $id)->first();

        if (!$pedido)
            return;

        $pedido->detalles()->delete();
        $pedido->delete();

        $this->pedidosCliente = $this->pedidosCliente->filter(fn($p) => $p->id !== $id);

        $this->modalPedidosCliente = false;
        $this->dispatch('cerrar-modal');
    }

}

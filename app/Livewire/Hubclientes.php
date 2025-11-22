<?php

namespace App\Livewire;

use App\Models\PagoPedido;
use App\Models\Producto;
use App\Models\Otro;
use App\Models\Tapa;
use App\Models\Etiqueta;
use App\Models\SolicitudPedido;
use App\Models\SolicitudPedidoDetalle;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
class Hubclientes extends Component
{
        use WithFileUploads; 
    public $productos = [];
    public $carrito = [];
    public $modalProducto = false;
    public $productoSeleccionado = null;
    public $mostrarCarrito = false;

    public $tapas = [];
    public $etiquetas = [];
    public $tapaSeleccionada = null;
    public $etiquetaSeleccionada = null;
    public $cantidadSeleccionada = 1;

    public $modalPedidosCliente = false;
    public $pedidosCliente = [];
    public $archivoPago;
    public $pagoSeleccionado;
    public function mount()
    {
        $productos = Producto::where('estado', 1)
            ->with('existencias.sucursal')
            ->orderBy('descripcion')
            ->get();

        foreach ($productos as $p) {
            $this->productos[] = [
                'uid' => 'producto_' . $p->id,
                'modelo' => $p,
                'tipo_modelo' => 'producto',
            ];
        }
        $otros = Otro::where('estado', 1)
            ->with('existencias.sucursal')
            ->orderBy('descripcion')
            ->get();

        foreach ($otros as $o) {
            $this->productos[] = [
                'uid' => 'otro_' . $o->id,
                'modelo' => $o,
                'tipo_modelo' => 'otro',
            ];
        }
    }

    public function abrirModalProducto($uid)
    {
        $this->productoSeleccionado = collect($this->productos)->firstWhere('uid', $uid);
        if (!$this->productoSeleccionado)
            return;

        $modelo = $this->productoSeleccionado['modelo'];
        $sucursalId = $modelo->existencias->first()->sucursal->id ?? null;

        $this->tapas = Tapa::where('estado', 1)
            ->whereHas('existencias', fn($q) => $q->where('sucursal_id', $sucursalId))
            ->orderBy('descripcion')
            ->get();

        $this->etiquetas = Etiqueta::where('estado', 1)
            ->whereHas('existencias', fn($q) => $q->where('sucursal_id', $sucursalId))
            ->orderBy('descripcion')
            ->get();

        $this->tapaSeleccionada = null;
        $this->etiquetaSeleccionada = null;
        $this->cantidadSeleccionada = 1;
        $this->modalProducto = true;
    }

    public function agregarAlCarritoDesdeModal()
    {
        if (!$this->productoSeleccionado)
            return;

        $modelo = $this->productoSeleccionado['modelo'];
        $uid = $this->productoSeleccionado['uid'] . '_' . ($this->tapaSeleccionada ?? '0') . '_' . ($this->etiquetaSeleccionada ?? '0');

        $this->carrito[$uid] = [
            'uid' => $uid,
            'tipo_modelo' => $this->productoSeleccionado['tipo_modelo'],
            'id' => $modelo->id,
            'modelo' => $modelo,
            'cantidad' => $this->cantidadSeleccionada,
            'tapa_id' => $this->tapaSeleccionada,
            'etiqueta_id' => $this->etiquetaSeleccionada,
        ];

        $this->modalProducto = false;
    }

    public function hacerPedido()
    {
        if (empty($this->carrito))
            return;

        $clienteId = Auth::user()->cliente->id ?? null;
        if (!$clienteId)
            return;

        $solicitud = SolicitudPedido::create([
            'cliente_id' => $clienteId,
            'codigo' => 'SP-' . now()->format('YmdHis'),
            'estado' => 0,
            'metodo_pago' => 0,
            'observaciones' => null
        ]);

        foreach ($this->carrito as $item) {
            SolicitudPedidoDetalle::create([
                'solicitud_pedido_id' => $solicitud->id,
                'producto_id' => $item['tipo_modelo'] === 'producto' ? $item['id'] : null,
                'otro_id' => $item['tipo_modelo'] === 'otro' ? $item['id'] : null,
                'tapa_id' => $item['tapa_id'],
                'etiqueta_id' => $item['etiqueta_id'],
                'cantidad' => $item['cantidad'],
            ]);
        }

        $this->carrito = [];
        $this->verMisPedidos();
    }

    public function verMisPedidos()
    {
        $cliente = Auth::user()->cliente ?? null;
        if (!$cliente)
            return;

        $this->pedidosCliente = SolicitudPedido::where('cliente_id', $cliente->id)
            ->with([
                'detalles.producto',
                'detalles.otro',
                'detalles.tapa',
                'detalles.etiqueta',
                'pedido.pagoPedidos.sucursalPago'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        $this->modalPedidosCliente = true;
    }


    public function eliminarSolicitud($id)
    {
        $solicitud = SolicitudPedido::find($id);
        if (!$solicitud)
            return;

        $solicitud->detalles()->delete();
        $solicitud->delete();

        $this->pedidosCliente = array_filter($this->pedidosCliente, fn($p) => $p['id'] !== $id);
        $this->modalPedidosCliente = false;
    }

    public function eliminarDelCarrito($uid)
    {
        unset($this->carrito[$uid]);
    }

    public function render()
    {
        return view('livewire.hubclientes', [
            'productos' => $this->productos,
            'carrito' => $this->carrito,
            'pedidosCliente' => $this->pedidosCliente
        ]);
    }

   public function actualizarMetodoPago($pedidoId, $valor)
{
    $pedido = SolicitudPedido::find($pedidoId);
    if (!$pedido) return;

    $pedido->metodo_pago = $valor;
    $pedido->save();

    foreach ($this->pedidosCliente as &$p) {
        if ($p['id'] == $pedidoId) {
            $p['metodo_pago'] = $valor;
        }
    }
}

public function subirComprobante($pagoId)
{
    if (!$this->archivoPago) return;

    $pago = PagoPedido::find($pagoId);
    if (!$pago) return;

    $path = $this->archivoPago->store('pagos', 'public');
    $pago->imagen_comprobante = $path;
    $pago->estado = true;
    $pago->fecha_pago = now();
    $pago->save();

    $this->archivoPago = null;

    $this->verMisPedidos();
}


}

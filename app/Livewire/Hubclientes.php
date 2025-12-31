<?php

namespace App\Livewire;

use App\Models\PagoPedido;
use App\Models\Producto;
use App\Models\Otro;
use App\Models\Tapa;
use App\Models\Etiqueta;
use App\Models\SolicitudPedido;
use App\Models\SolicitudPedidoDetalle;
use App\Models\Sucursal;
use App\Models\Empresa;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
class Hubclientes extends Component
{
    use WithFileUploads;
    public $modalCotizacion = false;
    public $cotizacion = [
        'subtotal' => 0,
        'total' => 0,
    ];

    public $expandidoPedidos = [];
    public $sucursalFiltro = null;
    public $carrito = [];
    public $mostrarCarrito = false;
    public $modalProducto = false;
    public $productoSeleccionado = null;
    public $tapas = [];
    public $etiquetas = [];
    public $tapaSeleccionada = null;
    public $etiquetaSeleccionada = null;
    public $cantidadSeleccionada = 1;
    public $modalPedidosCliente = false;
    public $pedidosCliente = [];
    public $archivoPago;
    public $modalVerQR = false;
    public $qrSeleccionado = null;
    public $pagoSeleccionado = null;

    public function abrirModalProducto($uid)
    {
        $producto = $this->productos()->firstWhere('uid', $uid);
        if (!$producto)
            return;

        $this->productoSeleccionado = $producto;
        $sucursalId = $producto['modelo']->existencias->first()->sucursal->id ?? null;

        $this->tapas = Tapa::where('estado', 1)
            ->whereHas('existencias', fn($q) => $q->where('sucursal_id', $sucursalId))
            ->orderBy('descripcion')
            ->get();

        $clienteId = Auth::user()->cliente->id ?? null;

        $this->etiquetas = Etiqueta::where('estado', 1)
            ->where('cliente_id', $clienteId)
            ->whereHas('existencias', function ($q) use ($sucursalId) {
                $q->where('sucursal_id', $sucursalId);
            })
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

    public function eliminarDelCarrito($uid)
    {
        unset($this->carrito[$uid]);
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
                'detalles.producto.existencias.sucursal',
                'detalles.otro.existencias.sucursal',
                'detalles.tapa',
                'detalles.etiqueta',
                'pedido.pagoPedidos.sucursalPago'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($sp) {
                $array = $sp->toArray();
                $array['estado_real'] = $sp->pedido->estado_pedido ?? $sp->estado;
                return $array;
            })
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

    public function actualizarMetodoPago($pedidoId, $valor)
    {
        $pedido = SolicitudPedido::find($pedidoId);
        if (!$pedido)
            return;

        $pedido->metodo_pago = $valor;
        $pedido->save();

        foreach ($this->pedidosCliente as &$p) {
            if ($p['id'] == $pedidoId)
                $p['metodo_pago'] = $valor;
        }
    }

    public function actualizarEstadoPedido($solicitudId, $nuevoEstado)
    {
        $solicitud = SolicitudPedido::find($solicitudId);
        if (!$solicitud)
            return;

        $solicitud->estado = $nuevoEstado;
        $solicitud->save();

        if ($solicitud->pedido) {
            $solicitud->pedido->estado_pedido = $nuevoEstado;
            $solicitud->pedido->save();
        }

        foreach ($this->pedidosCliente as &$p) {
            if ($p['id'] == $solicitudId) {
                $p['estado'] = $nuevoEstado;
                $p['estado_real'] = $nuevoEstado;
            }
        }
    }

    public function subirComprobante($pagoId)
    {
        if (!$this->archivoPago)
            return;

        $pago = PagoPedido::find($pagoId);
        if (!$pago)
            return;

        if ($pago->imagen_comprobante && \Storage::disk('public')->exists($pago->imagen_comprobante)) {
            \Storage::disk('public')->delete($pago->imagen_comprobante);
        }

        $path = $this->archivoPago->store('pagos', 'public');

        $pago->imagen_comprobante = $path;
        $pago->estado = true;
        $pago->fecha_pago = now();
        $pago->save();

        $this->archivoPago = null;
        $this->verMisPedidos();
    }

    public function eliminarComprobante($pagoId)
    {
        $pago = PagoPedido::find($pagoId);
        if (!$pago)
            return;

        if ($pago->imagen_comprobante && \Storage::disk('public')->exists($pago->imagen_comprobante)) {
            \Storage::disk('public')->delete($pago->imagen_comprobante);
        }

        $pago->imagen_comprobante = null;
        $pago->estado = false;
        $pago->fecha_pago = null;
        $pago->save();

        $this->verMisPedidos();
    }

    public function verQr($pagoId)
    {
        $pago = PagoPedido::with('sucursalPago')->find($pagoId);
        if (!$pago || !$pago->sucursalPago || !$pago->sucursalPago->imagen_qr)
            return;

        $this->qrSeleccionado = $pago->sucursalPago->imagen_qr;
        $this->modalVerQR = true;
    }

    public function productos()
    {
        $productos = Producto::where('estado', 1)
            ->with('existencias.sucursal')
            ->orderBy('descripcion')
            ->get()
            ->map(fn($p) => [
                'uid' => 'producto_' . $p->id,
                'modelo' => $p,
                'tipo_modelo' => 'producto',
            ]);

        $otros = Otro::where('estado', 1)
            ->with('existencias.sucursal')
            ->orderBy('descripcion')
            ->get()
            ->map(fn($o) => [
                'uid' => 'otro_' . $o->id,
                'modelo' => $o,
                'tipo_modelo' => 'otro',
            ]);

        return $productos->concat($otros);
    }

    public function render()
    {
        $sucursales = Sucursal::orderBy('nombre')->get();
        $productos = $this->productos();

        if ($this->sucursalFiltro) {
            $productos = $productos->filter(fn($item) => $item['modelo']->existencias->contains(fn($e) => $e->sucursal_id == $this->sucursalFiltro));
        }

        return view('livewire.hubclientes', [
            'productos' => $productos,
            'sucursales' => $sucursales,
            'carrito' => $this->carrito,
            'pedidosCliente' => $this->pedidosCliente,
            'mostrarCarrito' => $this->mostrarCarrito,
        ]);
    }

    public function cotizar()
    {
        if (empty($this->carrito))
            return;

        $subtotal = 0;

        foreach ($this->carrito as $item) {
            $modelo = $item['modelo'];

            $precioUnitario = $modelo->precioReferencia ?? 0;
            $unidadesPorPaquete = $modelo->paquete ?? 1;
            $cantidadPaquetes = $item['cantidad'];

            $subtotal += $precioUnitario * $unidadesPorPaquete * $cantidadPaquetes;
        }

        $this->cotizacion = [
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ];

        $this->modalCotizacion = true;
    }
    public function descargarCotizacionPdf()
    {
        if (empty($this->carrito)) {
            return;
        }
        $empresa = Empresa::with('sucursales')->find(1);
        $sucursal = $empresa->sucursales->first();
        $pdf = Pdf::loadView('pdf.cotizacion', [
            'carrito' => $this->carrito,
            'cotizacion' => $this->cotizacion,
            'empresa' => $empresa,
            'sucursal' => $sucursal,
        ])->setPaper('letter', 'portrait');
        return response()->streamDownload(
            fn() => print ($pdf->output()),
            'cotizacion.pdf'
        );
    }
}

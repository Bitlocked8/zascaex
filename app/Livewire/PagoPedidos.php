<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pedido;
use App\Models\PagoDetalle;
use App\Models\PagoPedido;
use Carbon\Carbon;

class PagoPedidos extends Component
{
    use WithFileUploads;

    public $pedidoSeleccionado = null;
    public $modalAbierto = false;
    public $detallesPago = [];

    public $modalPagoPedido = false;
    public $pagos = [];

    public function abrirModal(Pedido $pedido)
    {
        $this->pedidoSeleccionado = $pedido->load('detalles.existencia.existenciable', 'pagos');
        $this->detallesPago = [];

        foreach ($this->pedidoSeleccionado->detalles as $detalle) {
            $pagoExistente = PagoDetalle::where('pedido_detalle_id', $detalle->id)->first();
            $precioBase = (float) ($detalle->existencia->existenciable->precioReferencia ?? 0);
            $precioAplicado = $pagoExistente?->precio_aplicado ?? $precioBase;
            $cantidad = $detalle->cantidad ?? 0;

            $this->detallesPago[$detalle->id] = [
                'precio_base' => $precioBase,
                'precio_aplicado' => (float) $precioAplicado,
                'subtotal' => $cantidad * (float) $precioAplicado,
            ];
        }

        $this->modalAbierto = true;
    }

    public function actualizarSubtotal($detalleId)
    {
        $detalle = $this->pedidoSeleccionado->detalles->find($detalleId);
        $cantidad = $detalle?->cantidad ?? 0;
        $precioBase = (float) $this->detallesPago[$detalleId]['precio_base'];
        $precioAplicado = $this->detallesPago[$detalleId]['precio_aplicado'] ?? $precioBase;
        $this->detallesPago[$detalleId]['subtotal'] = $cantidad * (float) $precioAplicado;
    }

    public function guardarDetalles()
    {
        foreach ($this->detallesPago as $detalleId => $data) {
            $detalle = $this->pedidoSeleccionado->detalles->find($detalleId);

            $cantidad = isset($detalle->cantidad) ? (float) $detalle->cantidad : 0;
            $precioBase = isset($data['precio_base']) ? (float) $data['precio_base'] : 0;
            $precioAplicado = isset($data['precio_aplicado']) && $data['precio_aplicado'] !== null && $data['precio_aplicado'] !== ''
                ? (float) $data['precio_aplicado']
                : $precioBase;

            $subtotal = $cantidad * $precioAplicado;

            PagoDetalle::updateOrCreate(
                ['pedido_detalle_id' => $detalleId],
                [
                    'precio_base' => $precioBase,
                    'precio_aplicado' => $precioAplicado,
                    'subtotal' => $subtotal,
                ]
            );
        }

        $this->cerrarModal();
    }



    public function cerrarModal()
    {
        $this->modalAbierto = false;
        $this->detallesPago = [];
    }

    public function abrirModalPagoPedido($pedidoId)
    {
        $this->pedidoSeleccionado = Pedido::with('detalles.existencia.existenciable', 'pagos')->find($pedidoId);

        $this->pagos = $this->pedidoSeleccionado->pagos->map(fn($p) => [
            'id' => $p->id,
            'sucursal_pago_id' => $p->sucursal_pago_id,
            'metodo' => $p->metodo,
            'referencia' => $p->referencia,
            'codigo_factura' => $p->codigo_factura,
            'fecha' => $p->fecha ? Carbon::parse($p->fecha)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i'),
            'observaciones' => $p->observaciones,
            'monto' => $p->monto,
            'archivoFactura' => $p->archivo_factura,
            'archivoComprobante' => $p->archivo_comprobante,
        ])->toArray();

        $this->modalPagoPedido = true;
    }

    public function agregarPago()
    {
        $this->pagos[] = [
            'id' => null,
            'sucursal_pago_id' => null,
            'metodo' => 0,
            'referencia' => null,
            'codigo_factura' => null,
            'fecha' => now()->format('Y-m-d\TH:i'),
            'observaciones' => null,
            'monto' => null,
            'archivoFactura' => null,
            'archivoComprobante' => null,
        ];
    }

   public function eliminarPago($index)
{
    $pago = $this->pagos[$index] ?? null;

    if ($pago && isset($pago['id']) && $pago['id']) {
        PagoPedido::find($pago['id'])?->delete();
    }

    unset($this->pagos[$index]);
    $this->pagos = array_values($this->pagos);
}


    public function guardarPagos()
    {
        if (!$this->pedidoSeleccionado) return;

        foreach ($this->pagos as $pago) {

            $archivoFacturaPath = $pago['archivoFactura']
                ? $pago['archivoFactura']->store('pagos/facturas', 'public')
                : $pago['archivoFactura'];

            $archivoComprobantePath = $pago['archivoComprobante']
                ? $pago['archivoComprobante']->store('pagos/comprobantes', 'public')
                : $pago['archivoComprobante'];

            PagoPedido::updateOrCreate(
                ['id' => $pago['id']],
                [
                    'pedido_id' => $this->pedidoSeleccionado->id,
                    'sucursal_pago_id' => $pago['sucursal_pago_id'],
                    'monto' => $pago['monto'] ?? 0,
                    'metodo' => $pago['metodo'] ?? 0,
                    'estado' => true,
                    'referencia' => $pago['referencia'],
                    'codigo_factura' => $pago['codigo_factura'],
                    'fecha' => $pago['fecha'],
                    'archivo_factura' => $archivoFacturaPath,
                    'archivo_comprobante' => $archivoComprobantePath,
                    'observaciones' => $pago['observaciones'],
                ]
            );
        }

        $this->cerrarModalPagoPedido();
    }


    public function cerrarModalPagoPedido()
    {
        $this->modalPagoPedido = false;
        $this->pagos = [];
    }

    public function render()
    {
        return view('livewire.pago-pedidos', [
            'pedidos' => Pedido::with([
                'cliente',
                'solicitudPedido',
                'detalles.existencia.existenciable',
                'pagos'
            ])->get()
        ]);
    }
}

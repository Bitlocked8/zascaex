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

    public $searchCliente = '';
    public $pedidoSeleccionado = null;
    public $modalAbierto = false;
    public $detallesPago = [];
    public $modalPagoPedido = false;
    public $pagos = [];

    private function puedeVerPedido(Pedido $pedido): bool
    {
        $usuario = auth()->user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;
        $sucursalId = $personal->trabajos()->latest()->first()?->sucursal_id;

        if ($rol === 1) return true;

        if ($rol === 3) {
            return $pedido->personal_id === $personal->id
                && $pedido->detalles()
                    ->whereHas('existencia', fn($q) =>
                        $q->where('sucursal_id', $sucursalId)
                    )->exists();
        }

        if ($rol === 2) {
            return $pedido->detalles()
                ->whereHas('existencia', fn($q) =>
                    $q->where('sucursal_id', $sucursalId)
                )->exists();
        }

        return false;
    }

    public function abrirModal(Pedido $pedido)
    {
        if (!$this->puedeVerPedido($pedido)) return;

        $this->pedidoSeleccionado = $pedido->load(
            'detalles.existencia.existenciable',
            'pagos'
        );

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
        if (!$this->pedidoSeleccionado || !$this->puedeVerPedido($this->pedidoSeleccionado)) return;

        foreach ($this->detallesPago as $detalleId => $data) {
            $detalle = $this->pedidoSeleccionado->detalles->find($detalleId);
            $cantidad = (float) ($detalle->cantidad ?? 0);
            $precioBase = (float) ($data['precio_base'] ?? 0);
            $precioAplicado = isset($data['precio_aplicado']) && $data['precio_aplicado'] !== ''
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
        $pedido = Pedido::with('detalles.existencia', 'pagos')->findOrFail($pedidoId);

        if (!$this->puedeVerPedido($pedido)) return;

        $this->pedidoSeleccionado = $pedido->load(
            'detalles.existencia.existenciable',
            'pagos'
        );

        $this->pagos = $this->pedidoSeleccionado->pagos->map(fn($p) => [
            'id' => $p->id,
            'sucursal_pago_id' => $p->sucursal_pago_id,
            'metodo' => $p->metodo,
            'referencia' => $p->referencia,
            'codigo_factura' => $p->codigo_factura,
            'fecha' => $p->fecha
                ? Carbon::parse($p->fecha)->format('Y-m-d\TH:i')
                : now()->format('Y-m-d\TH:i'),
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
        if (!$this->pedidoSeleccionado || !$this->puedeVerPedido($this->pedidoSeleccionado)) return;

        $pago = $this->pagos[$index] ?? null;

        if ($pago && !empty($pago['id'])) {
            PagoPedido::find($pago['id'])?->delete();
        }

        unset($this->pagos[$index]);
        $this->pagos = array_values($this->pagos);
    }

    public function guardarPagos()
    {
        if (!$this->pedidoSeleccionado || !$this->puedeVerPedido($this->pedidoSeleccionado)) return;

        foreach ($this->pagos as $pago) {
            $archivoFacturaPath = $pago['archivoFactura'];
            if (is_object($archivoFacturaPath) && method_exists($archivoFacturaPath, 'store')) {
                $archivoFacturaPath = $archivoFacturaPath->store('pagos/facturas', 'public');
            }

            $archivoComprobantePath = $pago['archivoComprobante'];
            if (is_object($archivoComprobantePath) && method_exists($archivoComprobantePath, 'store')) {
                $archivoComprobantePath = $archivoComprobantePath->store('pagos/comprobantes', 'public');
            }

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
        $usuario = auth()->user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;
        $sucursalId = $personal->trabajos()->latest()->first()?->sucursal_id;

        $query = Pedido::with([
            'cliente',
            'solicitudPedido',
            'detalles.existencia.existenciable',
            'pagos'
        ]);

        if ($rol === 3) {
            $query
                ->where('personal_id', $personal->id)
                ->whereHas(
                    'detalles.existencia',
                    fn($q) => $q->where('sucursal_id', $sucursalId)
                );
        }

        if ($rol === 2) {
            $query->whereHas(
                'detalles.existencia',
                fn($q) => $q->where('sucursal_id', $sucursalId)
            );
        }

        if ($this->searchCliente) {
            $query->whereHas('cliente', function ($q) {
                $q->where('nombre', 'like', '%' . $this->searchCliente . '%')
                    ->orWhere('codigo', 'like', '%' . $this->searchCliente . '%')
                    ->orWhere('empresa', 'like', '%' . $this->searchCliente . '%')
                    ->orWhere('razonSocial', 'like', '%' . $this->searchCliente . '%')
                    ->orWhere('nitCi', 'like', '%' . $this->searchCliente . '%');
            });
        }

        return view('livewire.pago-pedidos', [
            'pedidos' => $query->latest()->get()
        ]);
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pedido;
use App\Models\PagoPedido;
use Carbon\Carbon;
use App\Models\SucursalPago;

class Pedidospersonal extends Component
{
    use WithFileUploads;

    public $search = '';
    public $modalPagoPedido = false;
    public $pedidoSeleccionado = null;
    public $pagos = [];
    public $soloHoy = true;
    public function render()
    {
        $usuario = auth()->user();
        $rol = $usuario->rol_id;
        $personal = $usuario->personal;
        $search = $this->search;
        $sucursalId = $personal?->trabajos()->latest()->first()?->sucursal_id;

        $query = Pedido::with([
            'cliente',
            'detalles.existencia.existenciable',
            'detalles.existencia.sucursal',
            'solicitudPedido.detalles.producto',
            'solicitudPedido.detalles.otro',
            'solicitudPedido.detalles.tapa',
            'solicitudPedido.detalles.etiqueta',
            'pagos'
        ]);

        if ($this->soloHoy) {
            $query->whereDate('created_at', Carbon::today());
        }

        if (in_array($rol, [2, 3]) && $sucursalId) {
            $query->whereHas('detalles.existencia', function ($q) use ($sucursalId) {
                $q->where('sucursal_id', $sucursalId);
            });
        }

        if ($rol === 3 && $personal) {
            $query->whereHas('cliente', function ($q) use ($personal) {
                $q->where('personal_id', $personal->id);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('codigo', 'like', "%{$search}%")
                    ->orWhereHas('cliente', function ($c) use ($search) {
                        $c->where('nombre', 'like', "%{$search}%");
                    })
                    ->orWhereHas('solicitudPedido', function ($s) use ($search) {
                        $s->where('codigo', 'like', "%{$search}%");
                    });
            });
        }

        $pedidos = $query->latest()->get();
        $sucursalesPago = SucursalPago::where('estado', true)
            ->select('id', 'nombre', 'imagen_qr')
            ->orderBy('nombre')
            ->get();

        return view('livewire.pedidospersonal', [
            'pedidos' => $pedidos,
            'sucursalesPago' => $sucursalesPago
        ]);
    }



    public function cambiarEstadoPedido($pedidoId, $nuevoEstado)
    {
        $pedido = Pedido::find($pedidoId);
        if ($pedido) {
            $pedido->estado_pedido = $nuevoEstado;
            $pedido->save();
        }
    }

    public function abrirModalPagoPedido($pedidoId)
    {
        $pedido = Pedido::with('pagos')->findOrFail($pedidoId);
        $this->pedidoSeleccionado = $pedido;

        $this->pagos = $pedido->pagos->map(fn($p) => [
            'id' => $p->id,
            'sucursal_pago_id' => $p->sucursal_pago_id,
            'metodo' => $p->metodo,
            'estado' => $p->estado ? 1 : 0,
            'referencia' => $p->referencia,
            'codigo_factura' => $p->codigo_factura,
            'fecha' => $p->fecha
                ? Carbon::parse($p->fecha)->format('Y-m-d\TH:i')
                : now()->format('Y-m-d\TH:i'),
            'observaciones' => $p->observaciones,
            'monto' => $p->monto,
            'archivoFactura' => $p->archivo_factura,
            'archivoComprobante' => $p->archivo_comprobante
        ])->toArray();

        $this->modalPagoPedido = true;
    }

    public function agregarPago()
    {
        $this->pagos[] = [
            'id' => null,
            'sucursal_pago_id' => null,
            'metodo' => 0,
            'estado' => 0,
            'referencia' => null,
            'codigo_factura' => null,
            'fecha' => now()->format('Y-m-d\TH:i'),
            'observaciones' => null,
            'monto' => null,
            'archivoFactura' => null,
            'archivoComprobante' => null
        ];
    }

    public function eliminarPago($index)
    {
        $pago = $this->pagos[$index] ?? null;
        if ($pago && !empty($pago['id'])) {
            PagoPedido::find($pago['id'])?->delete();
        }
        unset($this->pagos[$index]);
        $this->pagos = array_values($this->pagos);
    }

    public function guardarPagos()
    {
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
                    'estado' => (bool) ($pago['estado'] ?? 0),
                    'referencia' => $pago['referencia'],
                    'codigo_factura' => $pago['codigo_factura'],
                    'fecha' => $pago['fecha'],
                    'archivo_factura' => $archivoFacturaPath,
                    'archivo_comprobante' => $archivoComprobantePath,
                    'observaciones' => $pago['observaciones']
                ]
            );
        }

        $this->cerrarModalPagoPedido();
    }

    public function cerrarModalPagoPedido()
    {
        $this->modalPagoPedido = false;
        $this->pedidoSeleccionado = null;
        $this->pagos = [];
    }
}

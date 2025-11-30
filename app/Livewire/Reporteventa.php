<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Personal;
use App\Models\Sucursal;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
class Reporteventa extends Component
{
    public $cliente_id = null;
    public $personal_id = null;
    public $codigo = '';
    public $estado_pedido = null;
    public $filtroEstadoPago = '';
    public $filtroMetodoPago = '';
    public $producto = '';
    public $sucursal_id = '';

    public $fecha_inicio_dia;
    public $fecha_inicio_mes;
    public $fecha_inicio_ano;
    public $fecha_inicio_hora;
    public $fecha_inicio_min;

    public $fecha_fin_dia;
    public $fecha_fin_mes;
    public $fecha_fin_ano;
    public $fecha_fin_hora;
    public $fecha_fin_min;

    public $totalCantidad = 0;
    public $totalMonto = 0;

    public function render()
    {
        $pedidos = $this->filtrarPedidos();

        $this->totalCantidad = $pedidos->sum(fn($pedido) => $pedido->detalles->sum('cantidad'));
        $this->totalMonto = $pedidos->sum(function ($pedido) {
            return $pedido->detalles->sum(function ($detalle) {
                $precio = $detalle->existencia?->existenciable?->precioReferencia ?? 0;
                $cantidad = $detalle->cantidad ?? 0;
                return $precio * $cantidad;
            });
        });

        return view('livewire.reporteventa', [
            'pedidos' => $pedidos,
            'clientes' => Cliente::orderBy('nombre')->get(),
            'personales' => Personal::orderBy('nombres')->get(),
            'sucursales' => Sucursal::orderBy('nombre')->get(),
        ]);
    }

    private function filtrarPedidos()
    {
        $query = Pedido::with([
            'solicitudPedido.cliente',
            'personal',
            'detalles.existencia.existenciable',
            'detalles.existencia.sucursal',
            'pagoPedidos',
        ])->orderBy('fecha_pedido', 'desc');

        if ($this->codigo !== '') {
            $query->where('codigo', 'like', "%{$this->codigo}%");
        }

        if ($this->cliente_id) {
            $query->whereHas('solicitudPedido.cliente', fn($q) => $q->where('id', $this->cliente_id));
        }

        if ($this->personal_id) {
            $query->where('personal_id', $this->personal_id);
        }

        if ($this->estado_pedido !== null) {
            $query->where('estado_pedido', (int) $this->estado_pedido);
        }

        if ($this->producto !== '') {
            $query->whereHas('detalles.existencia.existenciable', fn($q) => $q->where('descripcion', 'like', "%{$this->producto}%"));
        }

        $fecha_inicio = $this->crearFecha(
            $this->fecha_inicio_ano,
            $this->fecha_inicio_mes,
            $this->fecha_inicio_dia,
            $this->fecha_inicio_hora,
            $this->fecha_inicio_min
        );

        $fecha_fin = $this->crearFecha(
            $this->fecha_fin_ano,
            $this->fecha_fin_mes,
            $this->fecha_fin_dia,
            $this->fecha_fin_hora,
            $this->fecha_fin_min
        );

        if ($fecha_inicio)
            $query->where('fecha_pedido', '>=', $fecha_inicio);
        if ($fecha_fin)
            $query->where('fecha_pedido', '<=', $fecha_fin);

        $pedidos = $query->get();

        $pedidos = $pedidos->map(function ($pedido) {
            $pedido->detalles = $pedido->detalles->filter(function ($detalle) {
                return $this->sucursal_id === '' || ($detalle->existencia?->sucursal?->id == $this->sucursal_id);
            });
            return $pedido;
        })->filter(fn($pedido) => $pedido->detalles->isNotEmpty());

        return $pedidos;
    }

    private function crearFecha($ano, $mes, $dia, $hora, $min)
    {
        if ($ano && $mes && $dia && $hora !== null && $min !== null) {
            try {
                return Carbon::create($ano, $mes, $dia, $hora, $min);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    public function descargarPDF()
    {
        $pedidos = $this->filtrarPedidos();

        $cliente_nombre = $this->cliente_id ? Cliente::find($this->cliente_id)?->nombre : 'Todos los clientes';
        $personal_nombre = $this->personal_id ? Personal::find($this->personal_id)?->nombres : 'Todos los vendedores';
        $sucursal_nombre = $this->sucursal_id ? Sucursal::find($this->sucursal_id)?->nombre : 'Todas las sucursales';
        $producto = $this->producto ?: 'Todos los productos';
        $estado_pago_texto = $this->filtroEstadoPago === '' ? 'Todos' : ($this->filtroEstadoPago == 1 ? 'Pagado' : 'Sin pagar');
        $metodo_pago_texto = match ($this->filtroMetodoPago) {
            '' => 'Todos',
            0 => 'QR',
            1 => 'Efectivo',
            2 => 'Crédito',
        };

        $totalPagado = 0;
        $totalSinPagar = 0;
        $totalDeudaCredito = 0;
        $resumenMetodos = ['QR' => 0, 'Efectivo' => 0, 'Crédito' => 0];

        foreach ($pedidos as $pedido) {
            $creditoBase = $pedido->pagoPedidos->where('metodo', 2)->sum('monto');
            $pagosRealizados = $pedido->pagoPedidos->where('metodo', '!=', 2)->where('estado', 1)->sum('monto');
            $totalDeudaCredito += max($creditoBase - $pagosRealizados, 0);

            foreach ($pedido->pagoPedidos as $pago) {
                if (
                    ($this->filtroEstadoPago === '' || $pago->estado == (int) $this->filtroEstadoPago) &&
                    ($this->filtroMetodoPago === '' || $pago->metodo == (int) $this->filtroMetodoPago)
                ) {
                    if ($pago->estado == 1 && $pago->metodo != 2) {
                        $totalPagado += $pago->monto;
                    } elseif ($pago->estado == 0) {
                        $totalSinPagar += $pago->monto;
                    }

                    if ($pago->metodo == 0)
                        $resumenMetodos['QR'] += $pago->monto;
                    if ($pago->metodo == 1)
                        $resumenMetodos['Efectivo'] += $pago->monto;
                    if ($pago->metodo == 2)
                        $resumenMetodos['Crédito'] += $pago->monto;
                }
            }
        }

        $pdf = Pdf::loadView('pdf.reporteventa', [
            'pedidos' => $pedidos,
            'cliente_nombre' => $cliente_nombre,
            'personal_nombre' => $personal_nombre,
            'sucursal_nombre' => $sucursal_nombre,
            'producto' => $producto,
            'estado_pago_texto' => $estado_pago_texto,
            'metodo_pago_texto' => $metodo_pago_texto,
            'totalPagado' => $totalPagado,
            'totalSinPagar' => $totalSinPagar,
            'resumenMetodos' => $resumenMetodos,
            'totalDeudaCredito' => $totalDeudaCredito,
        ]);

        $filename = 'Reporte_Ventas_' . now()->format('Ymd_His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }



}

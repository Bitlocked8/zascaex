<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\Personal;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class Reporteventa extends Component
{
    public $fechaInicio;
    public $fechaFin;
    public $cliente_id = '';
    public $personal_id = '';
    public $codigo = '';
    public $estado_pedido = '';
    public $estado_pago = '';

    public $totalCantidad = 0;
    public $totalMonto = 0;

    public function mount()
    {
        $this->fechaInicio = Carbon::now()->startOfMonth()->format('Y-m-d\TH:i');
        $this->fechaFin = Carbon::now()->endOfMonth()->format('Y-m-d\TH:i');
    }

    public function render()
    {
        $query = Pedido::with([
            'cliente',
            'personal',
            'detalles.existencia.existenciable',
            'pagoPedidos'
        ])->orderBy('fecha_pedido', 'desc');

        // 🔹 Filtros dinámicos
        if (!empty($this->codigo)) {
            $query->where('codigo', 'like', "%{$this->codigo}%");
        }

        if (!empty($this->cliente_id)) {
            $query->where('cliente_id', $this->cliente_id);
        }

        if (!empty($this->personal_id)) {
            $query->where('personal_id', $this->personal_id);
        }

        if ($this->estado_pedido !== '') {
            $query->where('estado_pedido', (int) $this->estado_pedido);
        }

        if ($this->estado_pago !== '') {
            $query->whereHas('pagoPedidos', function ($q) {
                $q->where('estado', (int) $this->estado_pago);
            });
        }

        // 🔹 Filtro por rango de fecha y hora
        if (!empty($this->fechaInicio) && !empty($this->fechaFin)) {
            $inicio = Carbon::parse($this->fechaInicio);
            $fin = Carbon::parse($this->fechaFin);

            $query->whereBetween('fecha_pedido', [$inicio, $fin]);
        }

        // 🔹 Obtener pedidos
        $pedidos = $query->get();

        // 🔹 Calcular totales
        $this->totalCantidad = $pedidos->sum(function ($pedido) {
            return $pedido->detalles->sum('cantidad');
        });

        $this->totalMonto = $pedidos->sum(function ($pedido) {
            return $pedido->detalles->sum(function ($detalle) {
                $precio = $detalle->existencia->existenciable->precioReferencia ?? 0;
                return $detalle->cantidad * $precio;
            });
        });

        return view('livewire.reporteventa', [
            'pedidos' => $pedidos,
            'clientes' => Cliente::orderBy('nombre')->get(),
            'personales' => Personal::orderBy('nombres')->get(),
            'totalCantidad' => $this->totalCantidad,
            'totalMonto' => $this->totalMonto,
        ]);
    }
    public function generarPDF()
    {
        $query = Pedido::with([
            'cliente',
            'personal',
            'detalles.existencia.existenciable',
            'pagoPedidos'
        ])->orderBy('fecha_pedido', 'desc');

        if (!empty($this->codigo)) {
            $query->where('codigo', 'like', "%{$this->codigo}%");
        }
        if (!empty($this->cliente_id)) {
            $query->where('cliente_id', $this->cliente_id);
        }
        if (!empty($this->personal_id)) {
            $query->where('personal_id', $this->personal_id);
        }
        if ($this->estado_pedido !== '') {
            $query->where('estado_pedido', (int) $this->estado_pedido);
        }
        if ($this->estado_pago !== '') {
            $query->whereHas('pagoPedidos', function ($q) {
                $q->where('estado', (int) $this->estado_pago);
            });
        }
        if (!empty($this->fechaInicio) && !empty($this->fechaFin)) {
            $inicio = Carbon::parse($this->fechaInicio);
            $fin = Carbon::parse($this->fechaFin);
            $query->whereBetween('fecha_pedido', [$inicio, $fin]);
        }

        $pedidos = $query->get();

        // 🔹 Calcular totales
        $totalGeneralCantidad = 0;
        $totalGeneralMonto = 0;
        $totalGeneralPagado = 0;
        $totalGeneralPendiente = 0;
        $totalesPorPago = [
            '1' => ['nombre' => 'QR', 'monto' => 0, 'pedidos' => 0],
            '2' => ['nombre' => 'Efectivo', 'monto' => 0, 'pedidos' => 0],
            '3' => ['nombre' => 'Crédito', 'monto' => 0, 'pedidos' => 0],
            '0' => ['nombre' => 'Sin pago', 'monto' => 0, 'pedidos' => 0],
        ];

        foreach ($pedidos as $pedido) {
            $montoPedido = $pedido->detalles->sum(fn($d) => $d->cantidad * ($d->existencia->existenciable->precioReferencia ?? 0));
            $pago = $pedido->pagoPedidos->first();
            $montoPagado = $pago->monto ?? 0;
            $montoPendiente = max($montoPedido - $montoPagado, 0);

            $totalGeneralPagado += $montoPagado;
            $totalGeneralPendiente += $montoPendiente;

            $tipoPago = $pago->estado ?? 0;
            $totalesPorPago[$tipoPago]['monto'] += $montoPagado;
            $totalesPorPago[$tipoPago]['pedidos']++;

            foreach ($pedido->detalles as $detalle) {
                $precio = $detalle->existencia->existenciable->precioReferencia ?? 0;
                $subtotal = $detalle->cantidad * $precio;
                $totalGeneralCantidad += $detalle->cantidad;
                $totalGeneralMonto += $subtotal;
            }
        }

        $pdf = Pdf::loadView('pdf.reporteventa', [
            'pedidos' => $pedidos,
            'fechaInicio' => $this->fechaInicio,
            'fechaFin' => $this->fechaFin,
            'totalGeneralCantidad' => $totalGeneralCantidad,
            'totalGeneralMonto' => $totalGeneralMonto,
            'totalGeneralPagado' => $totalGeneralPagado,
            'totalGeneralPendiente' => $totalGeneralPendiente,
            'totalesPorPago' => $totalesPorPago,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            function () use ($pdf) {
                echo $pdf->output();
            },
            'reporte_pedidos_' . now()->format('Ymd_His') . '.pdf'
        );
    }



}

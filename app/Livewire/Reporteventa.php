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
    public $cliente_id = null;
    public $personal_id = null;
    public $codigo = '';
    public $estado_pedido = null;
    public $estado_pago = null;

    public $totalCantidad = 0;
    public $totalMonto = 0;
    public $inicioDia;
    public $inicioMes;
    public $inicioAnio;
    public $inicioHora;
    public $inicioMin;

    public $finDia;
    public $finMes;
    public $finAnio;
    public $finHora;
    public $finMin;

    public function mount()
    {
        $this->fechaInicio = '';
        $this->fechaFin = '';
    }


    public function render()
    {
        $query = Pedido::with([
            'cliente',
            'personal',
            'detalles.existencia.existenciable',
            'pagoPedidos'
        ])->orderBy('fecha_pedido', 'desc');

        if ($this->codigo !== '') {
            $query->where('codigo', 'like', "%{$this->codigo}%");
        }

        if (!is_null($this->cliente_id)) {
            $query->where('cliente_id', $this->cliente_id);
        }

        if (!is_null($this->personal_id)) {
            $query->where('personal_id', $this->personal_id);
        }

        if (!is_null($this->estado_pedido)) {
            $query->where('estado_pedido', (int) $this->estado_pedido);
        }

        if (!is_null($this->estado_pago)) {
            $query->whereHas('pagoPedidos', function ($q) {
                $q->where('estado', (int) $this->estado_pago);
            });
        }

        if (
            $this->inicioDia && $this->inicioMes && $this->inicioAnio &&
            $this->inicioHora !== null && $this->inicioMin !== null &&
            $this->finDia && $this->finMes && $this->finAnio &&
            $this->finHora !== null && $this->finMin !== null
        ) {
            try {
                // Crear fechas completas a partir de los inputs individuales
                $inicio = Carbon::create(
                    $this->inicioAnio,
                    $this->inicioMes,
                    $this->inicioDia,
                    $this->inicioHora,
                    $this->inicioMin
                )->startOfMinute();

                $fin = Carbon::create(
                    $this->finAnio,
                    $this->finMes,
                    $this->finDia,
                    $this->finHora,
                    $this->finMin
                )->endOfMinute();

                $query->whereBetween('fecha_pedido', [$inicio, $fin]);
            } catch (\Exception $e) {
                logger()->error('Error al crear fechas en Reporteventa', [
                    'inicio' => [$this->inicioDia, $this->inicioMes, $this->inicioAnio, $this->inicioHora, $this->inicioMin],
                    'fin' => [$this->finDia, $this->finMes, $this->finAnio, $this->finHora, $this->finMin],
                    'error' => $e->getMessage(),
                ]);
            }
        }



        $pedidos = $query->get();

        $this->totalCantidad = $pedidos->sum(fn($pedido) => $pedido->detalles->sum('cantidad'));
        $this->totalMonto = $pedidos->sum(fn($pedido) => $pedido->detalles->sum(fn($detalle) => ($detalle->existencia->existenciable->precioReferencia ?? 0) * $detalle->cantidad));

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

        if ($this->codigo !== '')
            $query->where('codigo', 'like', "%{$this->codigo}%");
        if (!is_null($this->cliente_id))
            $query->where('cliente_id', $this->cliente_id);
        if (!is_null($this->personal_id))
            $query->where('personal_id', $this->personal_id);
        if (!is_null($this->estado_pedido))
            $query->where('estado_pedido', (int) $this->estado_pedido);
        if (!is_null($this->estado_pago))
            $query->whereHas('pagoPedidos', fn($q) => $q->where('estado', (int) $this->estado_pago));

        if (
            $this->inicioDia && $this->inicioMes && $this->inicioAnio &&
            $this->inicioHora !== null && $this->inicioMin !== null &&
            $this->finDia && $this->finMes && $this->finAnio &&
            $this->finHora !== null && $this->finMin !== null
        ) {
            try {
                // Crear fechas completas a partir de los inputs individuales
                $inicio = Carbon::create(
                    $this->inicioAnio,
                    $this->inicioMes,
                    $this->inicioDia,
                    $this->inicioHora,
                    $this->inicioMin
                )->startOfMinute();

                $fin = Carbon::create(
                    $this->finAnio,
                    $this->finMes,
                    $this->finDia,
                    $this->finHora,
                    $this->finMin
                )->endOfMinute();

                $query->whereBetween('fecha_pedido', [$inicio, $fin]);
            } catch (\Exception $e) {
                logger()->error('Error al crear fechas en Reporteventa', [
                    'inicio' => [$this->inicioDia, $this->inicioMes, $this->inicioAnio, $this->inicioHora, $this->inicioMin],
                    'fin' => [$this->finDia, $this->finMes, $this->finAnio, $this->finHora, $this->finMin],
                    'error' => $e->getMessage(),
                ]);
            }
        }




        $pedidos = $query->get();

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
            $montoPedido = $pedido->detalles->sum(fn($d) => ($d->existencia->existenciable->precioReferencia ?? 0) * $d->cantidad);
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

        return response()->streamDownload(fn() => print ($pdf->output()), 'reporte_pedidos_' . now()->format('Ymd_His') . '.pdf');
    }
}

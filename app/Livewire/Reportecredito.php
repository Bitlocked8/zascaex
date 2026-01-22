<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedido;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class Reportecredito extends Component
{
    public $hoy = false;
    public $fechaInicio;
    public $fechaFin;

    public $filtroMetodo = null;
    public $filtroEstadoPago = null;

    public $searchCliente = '';

    public function toggleHoy()
    {
        $this->hoy = !$this->hoy;

        if ($this->hoy) {
            $hoy = Carbon::now()->format('d/m/Y');
            $this->fechaInicio = $hoy;
            $this->fechaFin = $hoy;
        } else {
            $this->fechaInicio = null;
            $this->fechaFin = null;
        }
    }

    public function render()
    {
        $pedidos = Pedido::with(['cliente', 'personal', 'pagos']);

        if ($this->fechaInicio) {
            $inicio = Carbon::createFromFormat('d/m/Y', $this->fechaInicio)->startOfDay();
            $pedidos->where('fecha_pedido', '>=', $inicio);
        }

        if ($this->fechaFin) {
            $fin = Carbon::createFromFormat('d/m/Y', $this->fechaFin)->endOfDay();
            $pedidos->where('fecha_pedido', '<=', $fin);
        }

        if ($this->searchCliente) {
            $pedidos->whereHas('cliente', function ($q) {
                $q->where('nombre', 'like', '%' . $this->searchCliente . '%')
                    ->orWhere('codigo', 'like', '%' . $this->searchCliente . '%');
            });
        }

        if ($this->filtroMetodo !== null) {
            $pedidos->whereHas('pagos', function ($q) {
                $q->where('metodo', $this->filtroMetodo);
            });
        }

        if ($this->filtroEstadoPago !== null) {
            $estado = $this->filtroEstadoPago == 0 ? 0 : 1;
            $pedidos->whereHas('pagos', function ($q) use ($estado) {
                $q->where('estado', $estado);
            });
        }

        $pedidos = $pedidos->latest()->get();

        return view('livewire.reportecredito', [
            'pedidos' => $pedidos
        ]);
    }

    public function exportarPDF()
    {
        $pedidos = Pedido::with(['cliente', 'pagos']);

        // Filtros de fecha
        if ($this->fechaInicio) {
            $inicio = Carbon::createFromFormat('d/m/Y', $this->fechaInicio)->startOfDay();
            $pedidos->where('fecha_pedido', '>=', $inicio);
        }

        if ($this->fechaFin) {
            $fin = Carbon::createFromFormat('d/m/Y', $this->fechaFin)->endOfDay();
            $pedidos->where('fecha_pedido', '<=', $fin);
        }

        // Filtro por cliente
        if ($this->searchCliente) {
            $pedidos->whereHas('cliente', function ($q) {
                $q->where('nombre', 'like', '%' . $this->searchCliente . '%')
                    ->orWhere('codigo', 'like', '%' . $this->searchCliente . '%');
            });
        }

        // Filtro por método
        if ($this->filtroMetodo !== null) {
            $pedidos->whereHas('pagos', function ($q) {
                $q->where('metodo', $this->filtroMetodo);
            });
        }

        // Filtro por estado
        if ($this->filtroEstadoPago !== null) {
            $estado = $this->filtroEstadoPago == 0 ? 0 : 1;
            $pedidos->whereHas('pagos', function ($q) use ($estado) {
                $q->where('estado', $estado);
            });
        }

        $pedidos = $pedidos->latest()->get();

        // ====== CÁLCULOS IGUALES A LA VISTA ======
        $totalPedidos = $pedidos->count();

        $qrPagados = $pedidos->sum(fn($p) => $p->pagos->where('metodo', 0)->where('estado', 0)->count());
        $qrSinPagar = $pedidos->sum(fn($p) => $p->pagos->where('metodo', 0)->where('estado', 1)->count());

        $efectivoPagados = $pedidos->sum(fn($p) => $p->pagos->where('metodo', 1)->where('estado', 0)->count());
        $efectivoSinPagar = $pedidos->sum(fn($p) => $p->pagos->where('metodo', 1)->where('estado', 1)->count());

        $creditoPagados = $pedidos->sum(fn($p) => $p->pagos->where('metodo', 2)->where('estado', 0)->count());
        $creditoSinPagar = $pedidos->sum(fn($p) => $p->pagos->where('metodo', 2)->where('estado', 1)->count());

        $pdf = Pdf::loadView('pdf.reportecredito', [
            'pedidos' => $pedidos,
            'fechaInicio' => $this->fechaInicio,
            'fechaFin' => $this->fechaFin,
            'totalPedidos' => $totalPedidos,
            'qrPagados' => $qrPagados,
            'qrSinPagar' => $qrSinPagar,
            'efectivoPagados' => $efectivoPagados,
            'efectivoSinPagar' => $efectivoSinPagar,
            'creditoPagados' => $creditoPagados,
            'creditoSinPagar' => $creditoSinPagar,
        ]);

        $pdf->setPaper('letter', 'landscape');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'reporte_creditos.pdf'
        );
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedido;
use App\Models\Gasto;
use App\Models\Personal;
use Carbon\Carbon;

class Reporteventa extends Component
{
    public $filtroPersonal = null;
    public $hoy = false;

    public $fechaInicio = null;
    public $fechaFin = null;

    public function toggleHoy()
    {
        $this->hoy = !$this->hoy;

        if ($this->hoy) {
            $hoy = Carbon::today()->format('d/m/Y');
            $this->fechaInicio = $hoy;
            $this->fechaFin = $hoy;
        } else {
            $this->fechaInicio = null;
            $this->fechaFin = null;
        }
    }

    private function parseFecha($fecha)
    {
        if (!$fecha) return null;

        $partes = explode('/', $fecha);
        if (count($partes) !== 3) return null;

        [$d, $m, $y] = $partes;

        if (!ctype_digit($d) || !ctype_digit($m) || !ctype_digit($y)) return null;
        if (!checkdate((int)$m, (int)$d, (int)$y)) return null;

        return sprintf('%04d-%02d-%02d', $y, $m, $d);
    }

    public function render()
    {
        $queryPedidos = Pedido::with([
            'cliente.personal',
            'pagos',
            'detalles' => fn($q) => $q->with([
                'existencia.existenciable',
                'pagoDetalles',
            ])
        ]);

        $queryGastos = Gasto::with('personal');

        if ($this->filtroPersonal) {
            $queryPedidos->whereHas('cliente.personal', fn($q) => $q->where('id', $this->filtroPersonal));
            $queryGastos->where('personal_id', $this->filtroPersonal);
        }

        $inicio = $this->parseFecha($this->fechaInicio);
        $fin = $this->parseFecha($this->fechaFin);

        if ($inicio) {
            $queryPedidos->where('fecha_pedido', '>=', $inicio . ' 00:00:00');
            $queryGastos->where('fecha', '>=', $inicio . ' 00:00:00');
        }

        if ($fin) {
            $queryPedidos->where('fecha_pedido', '<=', $fin . ' 23:59:59');
            $queryGastos->where('fecha', '<=', $fin . ' 23:59:59');
        }

        $pedidos = $queryPedidos->orderBy('fecha_pedido', 'desc')->get();
        $gastos = $queryGastos->orderBy('fecha', 'desc')->get();

        $totalGastos = $gastos->sum('monto');

        $totalSubtotales = 0;

        $ventasPorMetodo = [
            'qr' => 0,
            'efectivo' => 0,
            'credito' => 0,
        ];

        foreach ($pedidos as $pedido) {
            foreach ($pedido->detalles as $detalle) {
                $pagoDetalle = $detalle->pagoDetalles->first();
                $pagoPedido = $pedido->pagos->first();

                if (!$pagoDetalle || !$pagoPedido) {
                    continue;
                }

                switch ($pagoPedido->metodo) {
                    case 0:
                        $ventasPorMetodo['qr'] += $pagoDetalle->subtotal;
                        break;
                    case 1:
                        $ventasPorMetodo['efectivo'] += $pagoDetalle->subtotal;
                        break;
                    case 2:
                        $ventasPorMetodo['credito'] += $pagoDetalle->subtotal;
                        break;
                }
            }
        }

        $totalGastos = $gastos->sum('monto');

        $totalVentas =
            $ventasPorMetodo['qr'] +
            $ventasPorMetodo['efectivo'] +
            $ventasPorMetodo['credito'] -
            $totalGastos;


        $personales = Personal::whereHas('user', fn($q) => $q->where('rol_id', 3))
            ->orderBy('nombres')
            ->get();

        return view('livewire.reporteventa', compact(
            'pedidos',
            'gastos',
            'personales',
            'ventasPorMetodo',
            'totalGastos',
            'totalVentas'
        ));
    }
}

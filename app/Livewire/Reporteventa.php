<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedido;
use App\Models\Gasto;
use App\Models\Personal;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class Reporteventa extends Component
{
    public $filtroPersonal = null;
    public $hoy = false;
    public $searchCliente = '';
    public $searchExistencia = '';
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
            'detalles' => function ($q) {
                $q->with([
                    'existencia.existenciable',
                    'pagoDetalles',
                ]);

                if ($this->searchExistencia) {
                    $q->whereHas('existencia.existenciable', function ($qq) {
                        $qq->where(
                            'descripcion',
                            'like',
                            '%' . $this->searchExistencia . '%'
                        );
                    });
                }
            }

        ])->where('estado_pedido', 2);

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

        if ($this->searchCliente) {
            $queryPedidos->whereHas('cliente', function ($q) {
                $q->where('nombre', 'like', '%' . $this->searchCliente . '%');
            });
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
    public function exportarPDF()
    {
        $queryPedidos = Pedido::with([
            'cliente.personal',
            'pagos',
            'detalles' => function ($q) {
                $q->with([
                    'existencia.existenciable',
                    'pagoDetalles',
                ]);

                if ($this->searchExistencia) {
                    $q->whereHas(
                        'existencia.existenciable',
                        fn($qq) =>
                        $qq->where('descripcion', 'like', '%' . $this->searchExistencia . '%')
                    );
                }
            }
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

        if ($this->searchCliente) {
            $queryPedidos->whereHas(
                'cliente',
                fn($q) =>
                $q->where('nombre', 'like', '%' . $this->searchCliente . '%')
            );
        }


        $pedidos = $queryPedidos->get();
        $gastos = $queryGastos->get();

        $ventasPorMetodo = ['qr' => 0, 'efectivo' => 0, 'credito' => 0];

        foreach ($pedidos as $pedido) {
            foreach ($pedido->detalles as $detalle) {
                $pagoDetalle = $detalle->pagoDetalles->first();
                $pago = $pedido->pagos->first();
                if (!$pagoDetalle || !$pago) continue;

                match ($pago->metodo) {
                    0 => $ventasPorMetodo['qr'] += $pagoDetalle->subtotal,
                    1 => $ventasPorMetodo['efectivo'] += $pagoDetalle->subtotal,
                    2 => $ventasPorMetodo['credito'] += $pagoDetalle->subtotal,
                };
            }
        }

        $totalGastos = $gastos->sum('monto');

        $totalVentas =
            $ventasPorMetodo['qr'] +
            $ventasPorMetodo['efectivo'] +
            $ventasPorMetodo['credito'] -
            $totalGastos;

        $resumenExistencias = $this->resumenExistencias($pedidos);

        $pdf = Pdf::loadView('pdf.reporteventa', [
            'pedidos' => $pedidos,
            'gastos' => $gastos,
            'ventasPorMetodo' => $ventasPorMetodo,
            'totalGastos' => $totalGastos,
            'totalVentas' => $totalVentas,
            'resumenExistencias' => $resumenExistencias,
            'fechaInicio' => $this->fechaInicio,
            'fechaFin' => $this->fechaFin,
        ])->setPaper('letter', 'landscape');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'reporte-ventas.pdf'
        );
    }


    private function resumenExistencias($pedidos)
    {
        $resumenExistencias = [];

        foreach ($pedidos as $pedido) {
            foreach ($pedido->detalles as $detalle) {
                $existencia = $detalle->existencia;
                $pagoDetalle = $detalle->pagoDetalles->first();

                if (!$existencia || !$pagoDetalle) continue;

                $key = $existencia->id;

                if (!isset($resumenExistencias[$key])) {
                    $resumenExistencias[$key] = [
                        'codigo' => $existencia->codigo ?? $existencia->id,
                        'descripcion' => $existencia->existenciable?->descripcion ?? 'Sin descripción',
                        'cantidad' => 0,
                        'subtotal' => 0,
                    ];
                }

                $resumenExistencias[$key]['cantidad'] += $detalle->cantidad;
                $resumenExistencias[$key]['subtotal'] += $pagoDetalle->subtotal;
            }
        }

        return $resumenExistencias;
    }
}

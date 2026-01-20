<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Créditos</title>

    <style>
        @page { margin: 5mm 5mm; } /* menos margen */

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 7px; /* más pequeño */
            line-height: 1.0;
        }

        .container {
            width: 100%;
        }

        h3 {
            text-align: center;
            font-size: 10px; /* más pequeño */
            font-weight: bold;
            text-transform: uppercase;
            color: #4f46e5;
            background: #e0e7ff;
            padding: 4px 8px;
            border-radius: 999px;
            margin-bottom: 6px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
            margin-bottom: 8px;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 5px;
        }

        .card .title {
            color: #4b5563;
            font-weight: 600;
            font-size: 7px;
        }

        .card .value {
            color: #312e81;
            font-weight: 800;
            font-size: 10px;
        }

        .card .value-small {
            font-size: 7px;
            font-weight: 700;
        }

        .table-wrapper {
            width: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 7px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 3px 4px; /* menos padding */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 7px;
        }

        th {
            background: #eef2ff;
            color: #4f46e5;
            font-weight: 700;
        }

        .right { text-align: right; }
        .center { text-align: center; }

        .badge {
            padding: 1px 4px;
            border-radius: 6px;
            font-size: 6px;
            font-weight: 700;
        }

        .badge-yellow { background: #fef3c7; color: #b45309; }
        .badge-blue { background: #dbebff; color: #1d4ed8; }
        .badge-green { background: #dcfce7; color: #15803d; }

        .text-red { color: #b91c1c; font-weight: 700; }
        .text-green { color: #15803d; font-weight: 700; }
        .text-gray { color: #6b7280; }

        .divider {
            border-bottom: 1px solid #e5e7eb;
            margin: 3px 0;
        }

        .small {
            font-size: 6px;
        }

        .multiline {
            white-space: normal !important;
            word-break: break-word;
            line-height: 1.0;
        }
    </style>
</head>
<body>

<div class="container">

    <h3>Reporte Créditos</h3>

    <p class="small">
        <strong>Desde:</strong> {{ $fechaInicio ?? '---' }}
        &nbsp;&nbsp;
        <strong>Hasta:</strong> {{ $fechaFin ?? '---' }}
    </p>

    <div class="grid">

        <div class="card">
            <div class="title">Pedidos a Crédito</div>
            <div class="value">{{ $pedidos->count() }}</div>
        </div>

        <div class="card">
            <div class="title">QR</div>
            <div class="value-small">
                Sin pagar: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo', 0)->where('estado', 0)->count()) }}
                <span class="text-gray">/</span>
                Pagado: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo', 0)->where('estado', 1)->count()) }}
            </div>
        </div>

        <div class="card">
            <div class="title">Efectivo</div>
            <div class="value-small">
                Sin pagar: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo', 1)->where('estado', 0)->count()) }}
                <span class="text-gray">/</span>
                Pagado: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo', 1)->where('estado', 1)->count()) }}
            </div>
        </div>

        <div class="card">
            <div class="title">Crédito/Contrato</div>
            <div class="value-small">
                Sin pagar: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo', 2)->where('estado', 0)->count()) }}
                <span class="text-gray">/</span>
                Pagado: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo', 2)->where('estado', 1)->count()) }}
            </div>
        </div>

    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width:12%">Fecha</th>
                    <th style="width:12%">Pedido</th>
                    <th style="width:20%">Cliente</th>
                    <th style="width:10%">Estado</th>
                    <th style="width:15%">Pagos</th>
                    <th style="width:15%" class="right">Total Crédito</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pedidos as $pedido)
                    <tr>
                        <td>
                            {{ $pedido->fecha_pedido
                                ? \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i')
                                : '-' }}
                        </td>
                        <td class="font-semibold">{{ $pedido->codigo }}</td>
                        <td>
                            <div class="font-semibold">
                                {{ $pedido->cliente->nombre ?? 'Sin cliente' }}
                            </div>
                            <div class="small text-gray">
                                {{ $pedido->cliente->codigo ?? '' }}
                            </div>
                        </td>

                        <td>
                            @if($pedido->estado_pedido == 0)
                                <span class="badge badge-yellow">Pendiente</span>
                            @elseif($pedido->estado_pedido == 1)
                                <span class="badge badge-blue">En espera de pago</span>
                            @elseif($pedido->estado_pedido == 2)
                                <span class="badge badge-green">Entregado</span>
                            @else
                                <span class="text-gray">—</span>
                            @endif
                        </td>

                        <td class="multiline">
                            @if($pedido->pagos->isEmpty())
                                <span class="text-gray italic small">Sin pagos</span>
                            @else
                                @foreach($pedido->pagos as $pago)
                                    @php
                                        $metodo = match($pago->metodo) {
                                            0 => 'QR',
                                            1 => 'Efectivo',
                                            2 => 'Crédito',
                                            default => '—'
                                        };

                                        $estadoPago = $pago->estado ? 'Pagado' : 'Sin Pagar';
                                        $claseEstado = $pago->estado ? 'text-green' : 'text-red';
                                    @endphp

                                    <div class="divider"></div>

                                    <div class="small">
                                        <strong>{{ $metodo }}</strong> ·
                                        <span class="{{ $claseEstado }}">{{ $estadoPago }}</span>
                                    </div>

                                    <div class="small text-gray">
                                        Bs {{ number_format($pago->monto, 2) }}
                                    </div>

                                    <div class="small text-gray">
                                        {{ $pago->fecha ? \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y H:i') : '' }}
                                    </div>
                                @endforeach
                            @endif
                        </td>

                        @php
                            $baseCredito = $pedido->pagos->where('metodo', 2)->sum('monto');

                            if ($baseCredito > 0) {
                                $pagosNoCreditoPagados = $pedido->pagos
                                    ->whereIn('metodo', [0, 1])
                                    ->where('estado', 1)
                                    ->sum('monto');

                                $totalNeto = $baseCredito - $pagosNoCreditoPagados;
                            } else {
                                $totalNeto = 0;
                            }
                        @endphp

                        <td class="right text-red">
                            Bs {{ number_format($totalNeto, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="center text-gray italic">
                            No hay pedidos a crédito
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

</body>
</html>

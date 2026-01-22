<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Créditos</title>

    <style>
        @page { margin: 7mm 6mm; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
            line-height: 1.1;
        }

        h2 {
            text-align: center;
            font-size: 10px;
            margin: 0 0 4px 0;
        }

        .resumen {
            width: 100%;
            margin-bottom: 6px;
        }

        .resumen td {
            border: 0.5px solid #aaa;
            padding: 2px;
            font-size: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            border: 0.5px solid #999;
            padding: 1px 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        th {
            background: #eee;
            font-weight: bold;
        }

        .right { text-align: right; }
        .center { text-align: center; }

        .titulo {
            font-weight: bold;
            margin: 4px 0 2px;
        }

        .text-red { color: #e3342f; font-weight: bold; }
        .text-green { color: #16a34a; font-weight: bold; }
        .text-gray { color: #6b7280; }

        .multiline {
            white-space: normal !important;
            word-break: break-word;
            line-height: 1.1;
        }
    </style>
</head>
<body>

<h2>Reporte de Créditos</h2>

<p>
    <strong>Desde:</strong> {{ $fechaInicio ?? '---' }}
    &nbsp;&nbsp;
    <strong>Hasta:</strong> {{ $fechaFin ?? '---' }}
</p>

<table class="resumen">
    <tr>
        <td>
            Pedidos a Crédito<br>
            <strong>{{ $pedidos->count() }}</strong>
        </td>

        <td>
            QR<br>
            <span class="text-red">
                Pendientes: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo',0)->where('estado',0)->count()) }}
            </span><br>
            <span class="text-green">
                Pagados: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo',0)->where('estado',1)->count()) }}
            </span>
        </td>

        <td>
            Efectivo<br>
            <span class="text-red">
                Pendientes: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo',1)->where('estado',0)->count()) }}
            </span><br>
            <span class="text-green">
                Pagados: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo',1)->where('estado',1)->count()) }}
            </span>
        </td>

        <td>
            Crédito / Contrato<br>
            <span class="text-red">
                Pendientes: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo',2)->where('estado',0)->count()) }}
            </span><br>
            <span class="text-green">
                Pagados: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo',2)->where('estado',1)->count()) }}
            </span>
        </td>
    </tr>
</table>
<div class="titulo">Pedidos a Crédito</div>

<table>
    <thead>
        <tr>
            <th style="width:12%">Fecha</th>
            <th style="width:12%">Pedido</th>
            <th style="width:22%">Cliente</th>
            <th style="width:12%">Estado</th>
            <th style="width:22%">Pagos</th>
            <th style="width:10%" class="right">Saldo Crédito</th>
        </tr>
    </thead>

    <tbody>
        @forelse($pedidos as $pedido)

            @php
                $baseCredito = $pedido->pagos->where('metodo',2)->sum('monto');

                if ($baseCredito > 0) {
                    $pagosPagados = $pedido->pagos
                        ->whereIn('metodo',[0,1])
                        ->where('estado',1)
                        ->sum('monto');

                    $saldo = $baseCredito - $pagosPagados;
                } else {
                    $saldo = 0;
                }
            @endphp

            <tr>
                <td>
                    {{ $pedido->fecha_pedido
                        ? \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i')
                        : '-' }}
                </td>

                <td>{{ $pedido->codigo }}</td>

                <td class="multiline">
                    <strong>{{ $pedido->cliente->nombre ?? 'Sin cliente' }}</strong><br>
                    <span class="text-gray">{{ $pedido->cliente->codigo ?? '' }}</span>
                </td>

                <td>
                    @switch($pedido->estado_pedido)
                        @case(0) Pendiente @break
                        @case(1) En espera de pago @break
                        @case(2) Entregado @break
                        @default -
                    @endswitch
                </td>

                <td class="multiline">
                    @forelse($pedido->pagos as $pago)
                        @php
                            $metodo = match($pago->metodo) {
                                0 => 'QR',
                                1 => 'Efectivo',
                                2 => 'Crédito',
                                default => 'Otro'
                            };
                        @endphp

                        <strong>{{ $metodo }}</strong> -
                        <span class="{{ $pago->estado ? 'text-green' : 'text-red' }}">
                            {{ $pago->estado ? 'Pagado' : 'Pendiente' }}
                        </span><br>

                        Bs {{ number_format($pago->monto,2) }}<br>
                        <span class="text-gray">
                            {{ $pago->fecha ? \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y H:i') : '' }}
                        </span>
                        <br><br>
                    @empty
                        <span class="text-gray">Sin pagos</span>
                    @endforelse
                </td>

                <td class="right text-red">
                    Bs {{ number_format($saldo,2) }}
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="6" class="center text-gray">
                    No hay pedidos a crédito
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>

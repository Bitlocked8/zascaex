<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>

    <style>
        @page {
            margin: 7mm 6mm;
        }

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

        th,
        td {
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

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .titulo {
            font-weight: bold;
            margin: 4px 0 2px;
        }

        .text-red {
            color: #e3342f;
            font-weight: bold;
        }

        .text-green {
            color: #16a34a;
            font-weight: bold;
        }

        .text-gray {
            color: #6b7280;
        }

        /* NUEVO: para que se adapte el contenido de facturas y recibos */
        .multiline {
            white-space: normal !important;
            word-break: break-word;
            line-height: 1.1;
        }
    </style>
</head>

<body>

    <h2>Reporte de Ventas</h2>

    <p>
        <strong>Desde:</strong> {{ $fechaInicio ?? '---' }}
        &nbsp;&nbsp;
        <strong>Hasta:</strong> {{ $fechaFin ?? '---' }}
    </p>
    <table class="resumen">
        <tr>
            <td>Ventas QR<br><strong>Bs {{ number_format($ventasPorMetodo['qr'],2) }}</strong></td>
            <td>Ventas Efectivo<br><strong>Bs {{ number_format($ventasPorMetodo['efectivo'],2) }}</strong></td>
            <td>Ventas Crédito<br><strong>Bs {{ number_format($ventasPorMetodo['credito'],2) }}</strong></td>
            <td>Gastos<br><strong>Bs {{ number_format($totalGastos,2) }}</strong></td>
            <td>Total Neto<br><strong>Bs {{ number_format($totalVentas,2) }}</strong></td>
        </tr>
    </table>

    <div class="titulo">Gastos</div>

    <table>
        <thead>
            <tr>
                <th style="width:15%">Fecha</th>
                <th style="width:20%">Personal</th>
                <th style="width:45%">Descripción</th>
                <th style="width:20%" class="right">Monto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gastos as $gasto)
            <tr>
                <td>{{ \Carbon\Carbon::parse($gasto->fecha)->format('d/m/Y H:i') }}</td>
                <td>{{ $gasto->personal?->nombres }}</td>
                <td>{{ $gasto->descripcion }}</td>
                <td class="right">Bs {{ number_format($gasto->monto,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @php
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
    @endphp

    <div class="titulo">Resumen por Existencia</div>

    <table>
        <thead>
            <tr>
                <th style="width:45%">Existencia</th>
                <th style="width:15%" class="right">Cantidad total</th>
                <th style="width:15%" class="right">Subtotal total</th>
                <th style="width:10%" class="right">Precio unitario</th>
            </tr>
        </thead>
        <tbody>
            @forelse($resumenExistencias as $existencia)
            @php
            $precioUnitario = $existencia['cantidad'] > 0
            ? $existencia['subtotal'] / $existencia['cantidad']
            : 0;
            @endphp
            <tr>
                <td>{{ $existencia['descripcion'] }}</td>
                <td class="right">{{ $existencia['cantidad'] }}</td>
                <td class="right">Bs {{ number_format($existencia['subtotal'], 2) }}</td>
                <td class="right">Bs {{ number_format($precioUnitario, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="center">No hay existencias para agrupar.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="titulo">Pedidos</div>

    <table>
        <thead>
            <tr>
                <th style="width:10%">Fecha</th>
                <th style="width:12%">Cliente</th>
                <th style="width:12%">Vendedor</th>
                <th style="width:20%">Producto</th>
                <th style="width:5%" class="right">Cant.</th>
                <th style="width:8%" class="right">Precio N.</th>
                <th style="width:8%" class="right">Precio A.</th>
                <th style="width:8%" class="right">Subtotal</th>
                <th style="width:8%">Método</th>
                <th style="width:9%">N° factura(s)</th>
                <th style="width:10%">N° recibo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $pedido)

            @forelse($pedido->detalles as $detalle)
            @php
            $pagoDetalle = $detalle->pagoDetalles->first();
            @endphp

            <tr>
                <td>
                    {{ $pedido->fecha_pedido ? \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') : 'Sin fecha' }}
                </td>

                <td>{{ $pedido->cliente?->nombre ?? 'Sin cliente' }}</td>
                <td>{{ $pedido->cliente?->personal?->nombres ?? 'Sin vendedor' }}</td>
                <td>{{ $detalle->existencia?->existenciable?->descripcion ?? 'Sin producto' }}</td>
                <td class="right font-semibold">{{ $detalle->cantidad }}</td>
                <td class="right text-gray">
                    {{ $pagoDetalle ? 'Bs '.number_format($pagoDetalle->precio_base, 2) : '-' }}
                </td>
                <td class="right font-semibold">
                    {{ $pagoDetalle ? 'Bs '.number_format($pagoDetalle->precio_aplicado, 2) : '-' }}
                </td>
                <td class="right font-bold">
                    {{ $pagoDetalle ? 'Bs '.number_format($pagoDetalle->subtotal, 2) : '-' }}
                </td>

                <td>
                    @php $pago = $pedido->pagos->first(); @endphp
                    @if($pago)
                    @if($pago->estado == 0)
                    <span class="text-red">
                        @else
                        <span class="text-green">
                            @endif

                            @switch($pago->metodo)
                            @case(0) QR @break
                            @case(1) Efectivo @break
                            @case(2) Crédito/Contrato @break
                            @default Otro
                            @endswitch

                        </span>
                        @else
                        -
                        @endif
                </td>


                <td class="multiline">
                    @if($pedido->pagos->count())
                    @foreach($pedido->pagos as $pago)
                    {{ $pago->codigo_factura ?? '-' }}
                    @if(!$loop->last)<br>@endif
                    @endforeach
                    @else
                    -
                    @endif
                </td>

                <td class="multiline">
                    @if($pedido->pagos->count())
                    @foreach($pedido->pagos as $pago)
                    {{ $pago->referencia ?? '-' }}
                    @if(!$loop->last)<br>@endif
                    @endforeach
                    @else
                    -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="center">Pedido sin detalles</td>
            </tr>
            @endforelse

            @empty
            <tr>
                <td colspan="11" class="center">No hay pedidos registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
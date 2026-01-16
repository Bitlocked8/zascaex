<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>

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
    </style>
</head>
<body>

<h2>Reporte de Ventas</h2>

<p>
    <strong>Desde:</strong> {{ $fechaInicio ?? '---' }}
    &nbsp;&nbsp;
    <strong>Hasta:</strong> {{ $fechaFin ?? '---' }}
</p>

<!-- ================= RESUMEN (TARJETAS) ================= -->
<table class="resumen">
    <tr>
        <td>Ventas QR<br><strong>Bs {{ number_format($ventasPorMetodo['qr'],2) }}</strong></td>
        <td>Ventas Efectivo<br><strong>Bs {{ number_format($ventasPorMetodo['efectivo'],2) }}</strong></td>
        <td>Ventas Crédito<br><strong>Bs {{ number_format($ventasPorMetodo['credito'],2) }}</strong></td>
        <td>Gastos<br><strong>Bs {{ number_format($totalGastos,2) }}</strong></td>
        <td>Total Neto<br><strong>Bs {{ number_format($totalVentas,2) }}</strong></td>
    </tr>
</table>

<!-- ================= GASTOS ================= -->
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

<!-- ================= PEDIDOS ================= -->
<div class="titulo">Pedidos</div>

<table>
    <thead>
        <tr>
            <th style="width:8%">Fecha</th>
            <th style="width:12%">Cliente</th>
            <th style="width:12%">Vendedor</th>
            <th style="width:25%">Producto</th>
            <th style="width:5%" class="right">Cant</th>
            <th style="width:8%" class="right">P.N.</th>
            <th style="width:8%" class="right">P.A.</th>
            <th style="width:8%" class="right">Subt</th>
            <th style="width:6%">Mét.</th>
            <th style="width:8%">Factura</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pedidos as $pedido)
            @foreach($pedido->detalles as $detalle)
                @php
                    $pd = $detalle->pagoDetalles->first();
                    $pago = $pedido->pagos->first();
                @endphp
                @if($pd && $pago)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') }}</td>
                    <td>{{ $pedido->cliente?->nombre }}</td>
                    <td>{{ $pedido->cliente?->personal?->nombres }}</td>
                    <td>{{ $detalle->existencia?->existenciable?->descripcion }}</td>
                    <td class="right">{{ $detalle->cantidad }}</td>
                    <td class="right">{{ number_format($pd->precio_base,2) }}</td>
                    <td class="right">{{ number_format($pd->precio_aplicado,2) }}</td>
                    <td class="right">{{ number_format($pd->subtotal,2) }}</td>
                    <td class="center">
                        {{ ['QR','EF','CR'][$pago->metodo] ?? '-' }}
                    </td>
                    <td>{{ $pago->codigo_factura }}</td>
                </tr>
                @endif
            @endforeach
        @endforeach
    </tbody>
</table>

</body>
</html>

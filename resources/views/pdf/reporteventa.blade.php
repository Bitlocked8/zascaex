<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Pedidos</title>
<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        color: #333;
        font-size: 12px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        margin-bottom: 20px;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 6px;
        vertical-align: top;
    }
    th {
        background: #0d6efd;
        color: #fff;
        text-align: center;
    }
    td {
        text-align: center;
    }
    .text-right { text-align: right; }
    .text-left { text-align: left; }
    .bg-yellow { background-color: #facc15; color: #000; padding: 2px 4px; border-radius: 3px; border: 1px solid #d4af0a; }
    .bg-green { background-color: #16a34a; color: #fff; padding: 2px 4px; border-radius: 3px; border: 1px solid #0f6636; }
    .bg-red { background-color: #dc2626; color: #fff; padding: 2px 4px; border-radius: 3px; border: 1px solid #a21d1d; }
    .bg-blue { background-color: #2563eb; color: #fff; padding: 2px 4px; border-radius: 3px; border: 1px solid #1e4bb8; }
    .bg-purple { background-color: #7e22ce; color: #fff; padding: 2px 4px; border-radius: 3px; border: 1px solid #5a1699; }
    .bg-gray { background-color: #6b7280; color: #fff; padding: 2px 4px; border-radius: 3px; border: 1px solid #4b5563; }
</style>
</head>
<body>

<!-- Totales generales -->
<table>
    <tr>
        <th>Total unidades</th>
        <th>Total Bs.</th>
        <th>Total pagado</th>
        <th>Saldo pendiente</th>
    </tr>
    <tr>
        <td class="text-right">{{ number_format($totalGeneralCantidad, 2, ',', '.') }}</td>
        <td class="text-right">{{ number_format($totalGeneralMonto, 2, ',', '.') }}</td>
        <td class="text-right">{{ number_format($totalGeneralPagado, 2, ',', '.') }} Bs</td>
        <td class="text-right">{{ number_format($totalGeneralPendiente, 2, ',', '.') }} Bs</td>
    </tr>
</table>

<!-- Totales por tipo de pago -->
<table>
    <tr>
        @foreach ($totalesPorPago as $tipo)
            <th>{{ $tipo['nombre'] }}</th>
        @endforeach
    </tr>
    <tr>
        @foreach ($totalesPorPago as $tipo)
            <td class="text-right">{{ number_format($tipo['monto'], 2, ',', '.') }} Bs<br>({{ $tipo['pedidos'] }} pedidos)</td>
        @endforeach
    </tr>
</table>

<!-- Tabla de pedidos -->
<table>
    <thead>
        <tr>
            <th>Código</th>
            <th>Cliente</th>
            <th>Personal</th>
            <th>Fecha</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unit.</th>
            <th>Subtotal</th>
            <th>Estado Pedido</th>
            <th>Tipo Pago</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($pedidos as $pedido)
            @php $pago = $pedido->pagoPedidos->first(); @endphp
            @foreach ($pedido->detalles as $detalle)
                @php
                    $producto = $detalle->existencia->existenciable ?? null;
                    $precio = $producto->precioReferencia ?? 0;
                    $subtotal = $detalle->cantidad * $precio;
                @endphp
                <tr>
                    <td>{{ $pedido->codigo }}</td>
                    <td class="text-left">{{ $pedido->cliente->nombre ?? 'N/A' }}</td>
                    <td class="text-left">{{ $pedido->personal->nombres ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') }}</td>
                    <td class="text-left">{{ $producto->descripcion ?? 'N/A' }}</td>
                    <td class="text-right">{{ number_format($detalle->cantidad, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($precio, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($subtotal, 2, ',', '.') }}</td>
                    <td>
                        @if($pedido->estado_pedido == 0)
                            <span class="bg-yellow">Pendiente</span>
                        @elseif($pedido->estado_pedido == 1)
                            <span class="bg-green">Completado</span>
                        @else
                            <span class="bg-red">Cancelado</span>
                        @endif
                    </td>
                    <td>
                        @if(!$pago)
                            <span class="bg-gray">Sin pago</span>
                        @elseif($pago->estado == 1)
                            <span class="bg-blue">QR</span>
                        @elseif($pago->estado == 2)
                            <span class="bg-green">Efectivo</span>
                        @elseif($pago->estado == 3)
                            <span class="bg-purple">Crédito</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="10">No se encontraron pedidos.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>

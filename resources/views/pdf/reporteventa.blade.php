<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        .header {
            margin-bottom: 20px;
        }

        .header div {
            margin: 2px 0;
        }

        .summary-box {
            border: 1px solid #000;
            padding: 5px;
            margin: 5px 0;
            text-align: center;
        }
    </style>
</head>

<body>

    <h2 class="text-center">Reporte de Ventas</h2>

    <!-- Filtros seleccionados -->
    <div class="header">
        <div><strong>Fecha Inicio:</strong> {{ $fecha_inicio ?? 'No definida' }} </div>
        <div><strong>Fecha Fin:</strong> {{ $fecha_fin ?? 'No definida' }} </div>
        <div><strong>Cliente:</strong> {{ $cliente_nombre ?? 'Todos los clientes' }} </div>
        <div><strong>Vendedor:</strong> {{ $personal_nombre ?? 'Todos los vendedores' }} </div>
        <div><strong>Sucursal:</strong> {{ $sucursal_nombre ?? 'Todas las sucursales' }} </div>
        <div><strong>Producto:</strong> {{ $producto ?: 'Todos los productos' }} </div>
        <div><strong>Estado de Pago:</strong> {{ $estado_pago_texto ?? 'Todos' }} </div>
        <div><strong>Método de Pago:</strong> {{ $metodo_pago_texto ?? 'Todos' }} </div>
    </div>

    <!-- Productos por Pedido -->
    <h3 class="text-center">Productos por Pedido</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Sucursal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos as $pedido)
                @foreach($pedido->detalles as $detalle)
                    <tr>
                        <td>{{ $pedido->codigo }}</td>
                        <td>{{ $pedido->solicitudPedido?->cliente?->nombre ?? 'N/A' }}</td>
                        <td>{{ $pedido->personal?->nombres ?? 'N/A' }}</td>
                        <td>{{ $pedido->fecha_pedido ? date('d/m/Y H:i', strtotime($pedido->fecha_pedido)) : 'N/D' }}</td>
                        <td>
                            {{ $detalle->existencia?->existenciable?->descripcion ?? '' }}

                            @if(!empty($detalle->existencia?->existenciable?->tipoContenido))
                                <br>
                                <small style="color:#555;">
                                    {{ $detalle->existencia->existenciable->tipoContenido }}
                                </small>
                            @endif
                        </td>

                        <td>{{ $detalle->cantidad }}</td>
                        <td>{{ $detalle->existencia?->sucursal?->nombre ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <!-- Pagos por Pedido -->
    <h3 class="text-center">Pagos por Pedido</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Código Pedido</th>
                <th>Monto</th>
                <th>Estado Pago</th>
                <th>Método Pago</th>
                <th>Deuda Crédito</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos as $pedido)
                @php
                    $creditoBase = $pedido->pagoPedidos->where('metodo', 2)->sum('monto');
                    $pagosNoCredito = $pedido->pagoPedidos->where('metodo', '!=', 2)->where('estado', 1)->sum('monto');
                    $deudaTotalCredito = max($creditoBase - $pagosNoCredito, 0);
                @endphp
                @foreach($pedido->pagoPedidos as $pago)
                    <tr>
                        <td>{{ $pedido->codigo }}</td>
                        <td>Bs {{ number_format($pago->monto, 2) }}</td>
                        <td>{{ $pago->estado ? 'Pagado' : 'Sin pagar' }}</td>
                        <td>
                            @if($pago->metodo == 0) QR
                            @elseif($pago->metodo == 1) Efectivo
                            @elseif($pago->metodo == 2) Crédito
                            @endif
                        </td>
                        <td>{{ $pago->metodo == 2 ? 'Bs ' . number_format($deudaTotalCredito, 2) : '-' }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <!-- Resumen de Pagos -->
    <h3 class="text-center">Resumen de Pagos</h3>
    <div class="summary-box"><strong>Total Pagado:</strong> Bs {{ number_format($totalPagado, 2) }}</div>
    <div class="summary-box"><strong>Total Sin Pagar:</strong> Bs {{ number_format($totalSinPagar, 2) }}</div>
    <div class="summary-box"><strong>QR:</strong> Bs {{ number_format($resumenMetodos['QR'], 2) }}</div>
    <div class="summary-box"><strong>Efectivo:</strong> Bs {{ number_format($resumenMetodos['Efectivo'], 2) }}</div>
    <div class="summary-box"><strong>Crédito:</strong> Bs {{ number_format($resumenMetodos['Crédito'], 2) }}</div>
    <div class="summary-box"><strong>Deuda (Crédito pendiente):</strong> Bs {{ number_format($totalDeudaCredito, 2) }}
    </div>

</body>

</html>
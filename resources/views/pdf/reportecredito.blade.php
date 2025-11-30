<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reporte de Distribución</title>
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
    </style>
</head>

<body>

    <h2 class="text-center">Reporte de Distribución</h2>

    <!-- Filtros aplicados -->
    <div class="header">
        <div><strong>Fecha Inicio:</strong> {{ $fecha_inicio ? $fecha_inicio->format('d/m/Y H:i') : 'No definida' }}</div>
        <div><strong>Fecha Fin:</strong> {{ $fecha_fin ? $fecha_fin->format('d/m/Y H:i') : 'No definida' }}</div>
        <div><strong>Código:</strong> {{ $codigo ?: 'Todos' }}</div>
        <div><strong>Vehículo:</strong>
            {{ $coche_id ? ($distribuciones->firstWhere('coche_id', $coche_id)?->coche->placa ?? 'N/A') : 'Todos' }}
        </div>
        <div><strong>Personal:</strong>
            {{ $personal_id ? ($distribuciones->firstWhere('personal_id', $personal_id)?->personal->nombres ?? 'N/A') : 'Todos' }}
        </div>
        <div><strong>Sucursal:</strong>
            {{ $sucursal_id ? ($distribuciones->first()?->personal?->trabajos->first()?->sucursal->nombre ?? 'N/A') : 'Todas' }}
        </div>
        <div><strong>Estado:</strong>
            @if($estado === '')
                Todos
            @elseif($estado == 0)
                Pendiente
            @elseif($estado == 1)
                Entregado
            @endif
        </div>
    </div>

    <!-- Distribuciones -->
    <h3 class="text-center">Distribuciones</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Fecha Asignación</th>
                <th>Fecha Entrega</th>
                <th>Personal</th>
                <th>Sucursal</th>
                <th>Vehículo</th>
                <th>Estado</th>
                <th>Pedidos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($distribuciones as $dist)
                <tr>
                    <td>{{ $dist->codigo }}</td>
                    <td>{{ $dist->fecha_asignacion ? date('d/m/Y H:i', strtotime($dist->fecha_asignacion)) : 'N/D' }}</td>
                    <td>{{ $dist->fecha_entrega ? date('d/m/Y H:i', strtotime($dist->fecha_entrega)) : 'N/D' }}</td>
                    <td>{{ $dist->personal->nombres ?? 'N/A' }}</td>
                    <td>{{ $dist->personal->trabajos->first()?->sucursal->nombre ?? 'N/A' }}</td>
                    <td>{{ $dist->coche->placa ?? 'N/A' }}</td>
                    <td>
                        @if($dist->estado == 0) Pendiente
                        @elseif($dist->estado == 1) Entregado
                        @endif
                    </td>
                    <td>
                        @foreach($dist->pedidos as $pedido)
                            {{ $pedido->codigo }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pedidos por Distribución -->
    <h3 class="text-center">Pedidos asignados</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Código Pedido</th>
                <th>Estado Pedido</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Sucursal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($distribuciones->flatMap->pedidos as $pedido)
                @foreach($pedido->detalles as $detalle)
                    <tr>
                        <td>{{ $pedido->codigo }}</td>
                        <td>
                            @if($pedido->estado_pedido == 0) Preparando
                            @elseif($pedido->estado_pedido == 1) En revisión
                            @elseif($pedido->estado_pedido == 2) Completado
                            @endif
                        </td>
                        <td>
                            {{ $detalle->existencia?->existenciable?->descripcion ?? 'N/A' }}
                            @if(!empty($detalle->existencia?->existenciable?->tipoContenido))
                                <br><small>{{ $detalle->existencia->existenciable->tipoContenido }}</small>
                            @endif
                        </td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>{{ $detalle->existencia?->sucursal?->nombre ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

</body>
</html>

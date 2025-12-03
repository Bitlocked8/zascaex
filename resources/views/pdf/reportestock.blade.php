<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reporte de Stock</title>
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

        .bg-green {
            background-color: #d1fae5;
        }

        .bg-orange {
            background-color: #ffedd5;
        }

        .bg-cyan {
            background-color: #cffafe;
        }
    </style>
</head>

<body>

    <h2 class="text-center">Reporte de Stock</h2>

    <!-- Filtros aplicados -->
    <div class="header">
        <div><strong>Fecha Inicio:</strong> {{ $fecha_inicio ? $fecha_inicio->format('d/m/Y H:i') : 'No definida' }}</div>
        <div><strong>Fecha Fin:</strong> {{ $fecha_fin ? $fecha_fin->format('d/m/Y H:i') : 'No definida' }}</div>
        <div><strong>Existencia:</strong> {{ $existencia_nombre ?? 'Todos' }}</div>
        <div><strong>Sucursal:</strong> {{ $sucursal_nombre ?? 'Todas' }}</div>
        <div><strong>Personal:</strong> {{ $personal_nombre ?? 'Todo el personal' }}</div>
    </div>

    <!-- Tabla de stock -->
    <h3 class="text-center">Reporte Stock</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Personal</th>
                <th>Existencia</th>
                <th>Sucursal</th>
                @if(!$ocultar_cantidad)
                    <th>Cantidad</th>
                @endif
                @if(!$ocultar_monto)
                    <th>Precio Unitario</th>
                    <th>Monto Total</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($reposiciones as $rep)
                @php
                    $entrada = $rep->cantidad_inicial ?? $rep->cantidad ?? 0;
                    $sucursal = $rep->existencia?->sucursal->nombre ?? 'N/A';
                    $comprobante = $rep->comprobantePagos->first();
                    $monto = $comprobante->monto ?? 0;
                    $precio_unitario = $entrada ? $monto / $entrada : 0;
                    $total_salida = $rep->asignadoReposicions->sum('cantidad_original');
                    $cantidad_restante = max($entrada - $total_salida, 0);
                    $monto_restante = $cantidad_restante * $precio_unitario;
                @endphp

                {{-- Entrada --}}
                <tr class="bg-green">
                    <td>{{ $rep->codigo }}</td>
                    <td>{{ $rep->personal->nombres ?? 'N/A' }}</td>
                    <td>{{ $rep->existencia?->existenciable->descripcion ?? 'N/A' }}</td>
                    <td>{{ $sucursal }}</td>
                    @if(!$ocultar_cantidad)
                        <td>{{ $entrada }}</td>
                    @endif
                    @if(!$ocultar_monto)
                        <td>{{ number_format($precio_unitario, 2) }}</td>
                        <td>{{ number_format($monto, 2) }}</td>
                    @endif
                </tr>

                {{-- Salidas --}}
                @foreach($rep->asignadoReposicions as $ar)
                    @php
                        $cantidad_asignada = $ar->cantidad_original ?? 0;
                        $monto_total_ar = $cantidad_asignada * $precio_unitario;
                    @endphp
                    <tr class="bg-orange">
                        <td>{{ $ar->asignado->codigo ?? '-' }}</td>
                        <td>{{ $ar->asignado->personal->nombres ?? '-' }}</td>
                        <td>{{ $ar->existencia?->existenciable->descripcion ?? 'N/A' }}</td>
                        <td>{{ $sucursal }}</td>
                        @if(!$ocultar_cantidad)
                            <td>{{ $cantidad_asignada }}</td>
                        @endif
                        @if(!$ocultar_monto)
                            <td>{{ number_format($precio_unitario, 2) }}</td>
                            <td>{{ number_format($monto_total_ar, 2) }}</td>
                        @endif
                    </tr>
                @endforeach

                {{-- Total --}}
                <tr class="bg-cyan font-bold">
                    <td>Total</td>
                    <td>-</td>
                    <td>-</td>
                    <td>{{ $sucursal }}</td>
                    @if(!$ocultar_cantidad)
                        <td>{{ $cantidad_restante }}</td>
                    @endif
                    @if(!$ocultar_monto)
                        <td>{{ number_format($precio_unitario, 2) }}</td>
                        <td>{{ number_format($monto_restante, 2) }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Resumen General -->
    <div class="mt-6 p-4 bg-cyan rounded">
        @php
            $totalEntradas = 0;
            $totalSalidas = 0;
            $totalRestante = 0;

            foreach ($reposiciones as $rep) {
                $entrada = $rep->cantidad_inicial ?? $rep->cantidad;
                $totalEntradas += $entrada;

                $salidas = $rep->asignadoReposicions->sum('cantidad_original');
                $totalSalidas += $salidas;

                $totalRestante += max($entrada - $salidas, 0);
            }
        @endphp

        <h3 class="text-center font-semibold mb-2">Resumen de Stock</h3>
        <table class="table">
            <tr>
                <td>Total Entradas</td>
                <td>{{ number_format($totalEntradas, 2) }}</td>
            </tr>
            <tr>
                <td>Total Salidas</td>
                <td>{{ number_format($totalSalidas, 2) }}</td>
            </tr>
            <tr>
                <td>Total Restante</td>
                <td>{{ number_format($totalRestante, 2) }}</td>
            </tr>
        </table>
    </div>

</body>
</html>

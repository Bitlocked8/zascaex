<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Stock</title>

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

        .text-green { color: #16a34a; font-weight: bold; }
        .text-orange { color: #ea580c; font-weight: bold; }
        .text-cyan { color: #0891b2; font-weight: bold; }
        .text-gray { color: #6b7280; }

        .multiline {
            white-space: normal !important;
            word-break: break-word;
            line-height: 1.1;
        }
    </style>
</head>
<body>

<h2>Reporte de Stock</h2>

<p>
    <strong>Desde:</strong> {{ $fecha_inicio ? $fecha_inicio->format('d/m/Y H:i') : '---' }}
    &nbsp;&nbsp;
    <strong>Hasta:</strong> {{ $fecha_fin ? $fecha_fin->format('d/m/Y H:i') : '---' }}
</p>

<!-- ================= FILTROS ================= -->
<table class="resumen">
    <tr>
        <td>Existencia<br><strong>{{ $existencia_nombre ?? 'Todas' }}</strong></td>
        <td>Sucursal<br><strong>{{ $sucursal_nombre ?? 'Todas' }}</strong></td>
        <td>Personal<br><strong>{{ $personal_nombre ?? 'Todo' }}</strong></td>
    </tr>
</table>

<!-- ================= STOCK ================= -->
<div class="titulo">Detalle de Stock</div>

<table>
    <thead>
        <tr>
            <th style="width:12%">Código</th>
            <th style="width:18%">Personal</th>
            <th style="width:25%">Existencia</th>
            <th style="width:15%">Sucursal</th>

            @if(!$ocultar_cantidad)
                <th style="width:10%" class="right">Cantidad</th>
            @endif

            @if(!$ocultar_monto)
                <th style="width:10%" class="right">Precio Unit.</th>
                <th style="width:10%" class="right">Monto</th>
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

            <!-- ENTRADA -->
            <tr>
                <td>{{ $rep->codigo }}</td>
                <td>{{ $rep->personal->nombres ?? 'N/A' }}</td>
                <td class="multiline">
                    <span class="text-green">Entrada</span><br>
                    {{ $rep->existencia?->existenciable->descripcion ?? 'N/A' }}
                </td>
                <td>{{ $sucursal }}</td>

                @if(!$ocultar_cantidad)
                    <td class="right">{{ $entrada }}</td>
                @endif

                @if(!$ocultar_monto)
                    <td class="right">{{ number_format($precio_unitario,2) }}</td>
                    <td class="right">{{ number_format($monto,2) }}</td>
                @endif
            </tr>

            <!-- SALIDAS -->
            @foreach($rep->asignadoReposicions as $ar)
                @php
                    $cantidad_asignada = $ar->cantidad_original ?? 0;
                    $monto_total_ar = $cantidad_asignada * $precio_unitario;
                @endphp
                <tr>
                    <td>{{ $ar->asignado->codigo ?? '-' }}</td>
                    <td>{{ $ar->asignado->personal->nombres ?? '-' }}</td>
                    <td class="multiline">
                        <span class="text-orange">Salida</span><br>
                        {{ $ar->existencia?->existenciable->descripcion ?? 'N/A' }}
                    </td>
                    <td>{{ $sucursal }}</td>

                    @if(!$ocultar_cantidad)
                        <td class="right">{{ $cantidad_asignada }}</td>
                    @endif

                    @if(!$ocultar_monto)
                        <td class="right">{{ number_format($precio_unitario,2) }}</td>
                        <td class="right">{{ number_format($monto_total_ar,2) }}</td>
                    @endif
                </tr>
            @endforeach

            <!-- TOTAL -->
            <tr>
                <td colspan="3" class="text-cyan">Saldo</td>
                <td>{{ $sucursal }}</td>

                @if(!$ocultar_cantidad)
                    <td class="right text-cyan">{{ $cantidad_restante }}</td>
                @endif

                @if(!$ocultar_monto)
                    <td class="right">{{ number_format($precio_unitario,2) }}</td>
                    <td class="right text-cyan">{{ number_format($monto_restante,2) }}</td>
                @endif
            </tr>

        @endforeach
    </tbody>
</table>

@php
    $totalEntradas = 0;
    $totalSalidas = 0;
    $totalRestante = 0;

    foreach ($reposiciones as $rep) {
        $entrada = $rep->cantidad_inicial ?? $rep->cantidad ?? 0;
        $totalEntradas += $entrada;

        $salidas = $rep->asignadoReposicions->sum('cantidad_original');
        $totalSalidas += $salidas;

        $totalRestante += max($entrada - $salidas, 0);
    }
@endphp

<div class="titulo">Resumen General de Stock</div>

<table class="resumen">
    <tr>
        <td>Total Entradas<br><strong>{{ number_format($totalEntradas,2) }}</strong></td>
        <td>Total Salidas<br><strong>{{ number_format($totalSalidas,2) }}</strong></td>
        <td>Total Restante<br><strong>{{ number_format($totalRestante,2) }}</strong></td>
    </tr>
</table>

</body>
</html>

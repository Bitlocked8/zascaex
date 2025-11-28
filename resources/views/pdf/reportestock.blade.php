<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Stock y Asignaciones</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #999; padding: 4px; text-align: left; }
        th { background-color: #b2f5ea; font-weight: bold; }
        .bg-gray { background-color: #f0f0f0; font-weight: bold; }
        .bg-teal { background-color: #81e6d9; font-weight: bold; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Reporte de Stock y Asignaciones</h2>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Fecha</th>
                @if(!$ocultar_cantidad) <th>Cantidad</th> @endif
                <th>Proveedor</th>
                <th>Material / Descripción</th>
                @if(!$ocultar_monto) <th>Monto</th> @endif
                <th>Precio Unidad</th>
                <th>Personal</th>
                <th>Sucursal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reposiciones as $reposicion)
                @php
                    $total_monto = $reposicion->comprobantes->sum('monto') ?? 0;
                    $cantidad_total = $reposicion->cantidad_inicial > 0 ? $reposicion->cantidad_inicial : 1;
                    $precio_por_unidad = $total_monto / $cantidad_total;
                    $stock_restante = $reposicion->cantidad_inicial;
                    $monto_restante = $total_monto;
                    $existencia_repo = $reposicion->existencia ?? null;
                    $tipo_material = $existencia_repo?->existenciable_type ? class_basename($existencia_repo->existenciable_type) : '-';
                    $material_desc = $tipo_material . ' - ' . ($existencia_repo?->existenciable->descripcion ?? $existencia_repo?->descripcion ?? '-');
                @endphp

                <tr class="bg-gray">
                    <td>{{ $reposicion->codigo }}</td>
                    <td>{{ $reposicion->fecha }}</td>
                    @if(!$ocultar_cantidad) <td>{{ $reposicion->cantidad_inicial }}</td> @endif
                    <td>{{ $reposicion->proveedor->razonSocial ?? '-' }}</td>
                    <td>{{ $material_desc }}</td>
                    @if(!$ocultar_monto) <td>{{ number_format($total_monto, 2) }}</td> @endif
                    <td>{{ number_format($precio_por_unidad, 2) }}</td>
                    <td>{{ $reposicion->personal->nombres ?? '-' }}</td>
                    <td>{{ $existencia_repo?->sucursal->nombre ?? '-' }}</td>
                </tr>

                @foreach($reposicion->asignados as $asignado)
                    @if($personal_id && $asignado->personal_id != $personal_id) @continue @endif
                    @php
                        $detalle = $asignado->asignadoReposicions->where('reposicion_id', $reposicion->id)->first();
                        if (!$detalle) continue;
                        $cantidad_asignada = $detalle->cantidad_original ?? 0;
                        $monto_asignacion = $cantidad_asignada * $precio_por_unidad;
                        $stock_restante -= $cantidad_asignada;
                        $monto_restante -= $monto_asignacion;
                        $tipo_material_asig = $detalle->existencia?->existenciable_type ? class_basename($detalle->existencia->existenciable_type) : '-';
                        $material_asignado = $tipo_material_asig . ' - ' . ($detalle->existencia?->existenciable->descripcion ?? $detalle->existencia?->descripcion ?? '-');
                    @endphp

                    <tr>
                        <td>→ {{ $asignado->codigo }}</td>
                        <td>{{ $asignado->fecha }}</td>
                        @if(!$ocultar_cantidad) <td>{{ $cantidad_asignada }}</td> @endif
                        <td>{{ $asignado->proveedor?->razonSocial ?? '-' }}</td>
                        <td>{{ $material_asignado }}</td>
                        @if(!$ocultar_monto) <td>{{ number_format($monto_asignacion, 2) }}</td> @endif
                        <td>{{ number_format($precio_por_unidad, 2) }}</td>
                        <td>{{ $asignado->personal->nombres ?? '-' }}</td>
                        <td>{{ $detalle->existencia?->sucursal->nombre ?? '-' }}</td>
                    </tr>
                @endforeach

                <tr class="bg-teal">
                    <td>STOCK RESTANTE</td>
                    <td>-</td>
                    @if(!$ocultar_cantidad) <td>{{ $stock_restante }}</td> @endif
                    <td>-</td>
                    <td>-</td>
                    @if(!$ocultar_monto) <td>{{ number_format($monto_restante, 2) }}</td> @endif
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

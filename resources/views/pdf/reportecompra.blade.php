<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Asignaciones por Area</title>
   <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        color: #000;
        margin: 0;
        padding: 0;
    }

    .container {
        padding: 15px;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header h1 {
        font-size: 18px;
        margin: 0;
        color: #0d9488;
    }

    .header-info {
        font-size: 10px;
        color: #666;
        margin-top: 5px;
    }

    .filters-info {
        margin-bottom: 15px;
        font-size: 10px;
        border: 1px solid #d1d5db;
        border-radius: 5px;
        padding: 8px;
        background-color: #f9fafb;
    }

    .filter-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .section-title {
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        margin: 20px 0 10px 0;
        padding: 5px;
        border-radius: 4px;
        background-color: #f3f4f6;
        color: #374151;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
        font-size: 11px;
        page-break-inside: auto;
    }

    table thead {
        display: table-header-group;
    }

    table th {
        border: 1px solid #000;
        padding: 5px;
        font-weight: bold;
        text-align: center;
        background-color: #e5e7eb;
    }

    table td {
        border: 1px solid #000;
        padding: 4px;
        text-align: center;
    }

    .total-row {
        background-color: #d9f99d;
        font-weight: bold;
    }

    .no-border td {
        border: none !important;
    }

    .break-word {
        word-break: break-word;
        max-width: 150px;
    }

    .footer {
        text-align: center;
        margin-top: 20px;
        font-size: 9px;
        color: #4b5563;
    }

    .page-break {
        page-break-before: always;
    }
</style>

</head>
<body>
    <div class="container">
        <!-- Encabezado -->
        <div class="header">
            <h1>Reporte Asignaciones por Area</h1>
            <div class="header-info">
                Generado: {{ now()->format('d/m/Y H:i:s') }}
                @if($fecha_inicio || $fecha_fin)
                    | Periodo: 
                    @if($fecha_inicio) {{ $fecha_inicio->format('d/m/Y H:i') }} @endif
                    @if($fecha_inicio && $fecha_fin) al @endif
                    @if($fecha_fin) {{ $fecha_fin->format('d/m/Y H:i') }} @endif
                @endif
            </div>
        </div>
        
        <!-- Información de filtros -->
        <div class="filters-info">
            <div class="filter-row">
                <div><strong>Tipo:</strong> {{ $tipo ? ucfirst($tipo) : 'Todos' }}</div>
                <div><strong>Personal:</strong> 
                    @if($personal_id)
                        {{ \App\Models\Personal::find($personal_id)->nombres ?? 'N/A' }}
                    @else
                        Todos
                    @endif
                </div>
                <div><strong>Sucursal:</strong> 
                    @if($sucursal_id)
                        {{ \App\Models\Sucursal::find($sucursal_id)->nombre ?? 'N/A' }}
                    @else
                        Todas
                    @endif
                </div>
                <div><strong>Código:</strong> {{ $codigo ?: 'Todos' }}</div>
            </div>
            <div class="filter-row">
                <div><strong>Mostrar Cantidades:</strong> {{ $mostrarCantidades ? 'Sí' : 'No' }}</div>
                <div><strong>Mostrar Montos:</strong> {{ $mostrarMontos ? 'Sí' : 'No' }}</div>
                <div><strong>Mostrar Mermas:</strong> {{ $mostrarMermas ? 'Sí' : 'No' }}</div>
            </div>
        </div>
        
        <!-- REPOSICIONES -->
        @if($tipo === '' || $tipo === 'reposiciones')
            <div class="section-title">Reposiciones Asignadas</div>
            <table>
                <thead>
                    <tr>
                        <th>Asignación</th>
                        <th>Class Base</th>
                        <th>Producto</th>
                        @if($mostrarCantidades)
                            <th>Cant. restante</th>
                            <th>Cant. Original</th>
                        @endif
                        <th>Sucursal</th>
                        @if($mostrarMontos)
                            <th>Monto Asignación</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($asignaciones->flatMap->asignadoReposicions as $detalle)
                        @php
                            $existencia = $detalle->existencia;
                            $item = $existencia?->existenciable;
                            $reposicion = $detalle->reposicion;
                            $montoTotal = $reposicion?->comprobantes->sum('monto') ?? 0;
                            $cantidadInicial = $reposicion?->cantidad_inicial ?? 1;
                            $precioUnitario = $cantidadInicial > 0 ? $montoTotal / $cantidadInicial : 0;
                            $montoAsignacion = $detalle->cantidad_original * $precioUnitario;
                        @endphp
                        <tr>
                            <td>{{ $detalle->asignado->codigo }}</td>
                            <td>{{ $item ? class_basename($item) : 'N/A' }}</td>
                            <td class="break-word">{{ $item?->descripcion ?? 'N/A' }}</td>
                            @if($mostrarCantidades)
                                <td>{{ $detalle->cantidad }}</td>
                                <td>{{ $detalle->cantidad_original }}</td>
                            @endif
                            <td>{{ $existencia?->sucursal?->nombre ?? 'N/A' }}</td>
                            @if($mostrarMontos)
                                <td>{{ number_format($montoAsignacion, 2, ',', '.') }} Bs</td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 3 + ($mostrarCantidades ? 2 : 0) + 1 + ($mostrarMontos ? 1 : 0) }}" style="text-align: center;">No hay reposiciones asignadas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif
        
        <!-- SOPLADOS -->
        @if($tipo === '' || $tipo === 'soplados')
            @if($asignaciones->flatMap->soplados->count() > 0)
                <div class="section-title">Soplados</div>
                <table>
                    <thead>
                        <tr>
                            <th>Asignación</th>
                            <th>Class Base</th>
                            <th>Producto</th>
                            @if($mostrarCantidades)
                                <th>Cantidad</th>
                            @endif
                            @if($mostrarMermas)
                                <th>Merma</th>
                            @endif
                            @if($mostrarMontos)
                                <th>Monto Cant.</th>
                                <th>Monto Merma</th>
                            @endif
                            <th>Sucursal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asignaciones->flatMap->soplados as $soplado)
                            @php
                                $asignacion = $soplado->asignado;
                                $cantidadProduccion = $soplado->cantidad;
                                $mermaSoplado = $soplado->merma ?? 0;
                                $sucursalFinal = $soplado->existencia?->sucursal?->nombre ?? 'N/A';
                                $asignadoReposicions = $asignacion->asignadoReposicions ?? collect();
                                $cantidadRestante = $cantidadProduccion;
                                $totalMontoCantidad = 0;
                                $totalMontoMerma = 0;
                                $reposicionesOrdenadas = $asignadoReposicions->sortByDesc(fn($r) => $r->cantidad_original ?? 0);
                                $mermaAplicada = false;
                            @endphp

                            @foreach($reposicionesOrdenadas as $asignadoReposicion)
                                @php
                                    $item = $asignadoReposicion->existencia?->existenciable ?? $asignadoReposicion->reposicion;
                                    $sucursal = $asignadoReposicion->existencia?->sucursal?->nombre ?? 'N/A';
                                    $cantidadOriginalItem = $asignadoReposicion->cantidad_original ?? 0;

                                    if (!$mermaAplicada && $mermaSoplado > 0) {
                                        $cantidadUsadaItem = min($cantidadRestante, $cantidadOriginalItem - $mermaSoplado);
                                        $mermaItem = $mermaSoplado;
                                        $mermaAplicada = true;
                                    } else {
                                        $cantidadUsadaItem = min($cantidadRestante, $cantidadOriginalItem);
                                        $mermaItem = 0;
                                    }

                                    $reposicion = $asignadoReposicion->reposicion;
                                    $montoTotal = $reposicion?->comprobantes->sum('monto') ?? 0;
                                    $cantidadInicial = $reposicion?->cantidad_inicial ?? 1;
                                    $precioUnitarioItem = $cantidadInicial > 0 ? $montoTotal / $cantidadInicial : 0;

                                    $montoCantidadItem = $cantidadUsadaItem * $precioUnitarioItem;
                                    $montoMermaItem = $mermaItem * $precioUnitarioItem;

                                    $totalMontoCantidad += $montoCantidadItem;
                                    $totalMontoMerma += $montoMermaItem;
                                    $cantidadRestante -= $cantidadUsadaItem;
                                @endphp

                                <tr>
                                    <td>{{ $asignacion->codigo }}</td>
                                    <td>{{ class_basename($item) }}</td>
                                    <td class="break-word">{{ $item->descripcion ?? 'N/A' }}</td>
                                    @if($mostrarCantidades)
                                        <td>{{ $cantidadUsadaItem }}</td>
                                    @endif
                                    @if($mostrarMermas)
                                        <td>{{ $mermaItem }}</td>
                                    @endif
                                    @if($mostrarMontos)
                                        <td>{{ number_format($montoCantidadItem, 2, ',', '.') }} Bs</td>
                                        <td>{{ number_format($montoMermaItem, 2, ',', '.') }} Bs</td>
                                    @endif
                                    <td>{{ $sucursal }}</td>
                                </tr>
                            @endforeach

                            <!-- Fila resumen -->
                            <tr class="total-row">
                                <td>{{ $asignacion->codigo }}</td>
                                <td>Producto</td>
                                <td class="break-word">{{ $soplado->existencia?->existenciable?->descripcion ?? 'Producto Final' }}</td>
                                @if($mostrarCantidades)
                                    <td>{{ $cantidadProduccion }}</td>
                                @endif
                                @if($mostrarMermas)
                                    <td>{{ $mermaSoplado }}</td>
                                @endif
                                @if($mostrarMontos)
                                    <td>{{ number_format($totalMontoCantidad, 2, ',', '.') }} Bs</td>
                                    <td>{{ number_format($totalMontoMerma, 2, ',', '.') }} Bs</td>
                                @endif
                                <td>{{ $sucursalFinal }}</td>
                            </tr>
                            <tr class="no-border"><td colspan="9">&nbsp;</td></tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif
        
        <!-- LLENADOS -->
        @if($tipo === '' || $tipo === 'llenados')
            @if($asignaciones->flatMap->llenados->count() > 0)
                <div class="section-title">Llenados</div>
                <table>
                    <thead>
                        <tr>
                            <th>Asignación</th>
                            <th>Class Base</th>
                            <th>Producto</th>
                            @if($mostrarCantidades)
                                <th>Cantidad</th>
                            @endif
                            @if($mostrarMermas)
                                <th>Merma</th>
                            @endif
                            @if($mostrarMontos)
                                <th>Monto Cant.</th>
                                <th>Monto Merma</th>
                            @endif
                            <th>Sucursal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asignaciones->flatMap->llenados as $llenado)
                            @php
                                $asignacion = $llenado->asignado;
                                $sucursalFinal = $llenado->existencia?->sucursal?->nombre ?? 'N/A';
                                $cantidadUsada = $llenado->cantidad;
                                $mermaFinal = $llenado->merma ?? 0;
                                $totalMontoCantidad = 0;
                                $totalMontoMerma = 0;
                            @endphp

                            @foreach($asignacion->asignadoReposicions->groupBy(fn($r) => $r->existencia?->existenciable?->id ?? $r->reposicion->id) as $reposiciones)
                                @php
                                    $reposicionMayorMonto = $reposiciones->sortByDesc(fn($r) => $r->reposicion?->comprobantes->sum('monto') ?? 0)->first();
                                    $cantidadRestante = $llenado->cantidad;
                                @endphp

                                @foreach($reposiciones as $asignadoReposicion)
                                    @php
                                        $item = $asignadoReposicion->existencia?->existenciable ?? $asignadoReposicion->reposicion;
                                        $sucursal = $asignadoReposicion->existencia?->sucursal?->nombre ?? 'N/A';
                                        $cantidadOriginalItem = $asignadoReposicion->cantidad_original ?? 0;
                                        $cantidadUsadaItem = min($cantidadRestante, $cantidadOriginalItem);
                                        $mermaItem = ($asignadoReposicion->id === $reposicionMayorMonto->id) ? max(0, $cantidadOriginalItem - $cantidadUsadaItem) : 0;

                                        $reposicion = $asignadoReposicion->reposicion;
                                        $montoTotal = $reposicion?->comprobantes->sum('monto') ?? 0;
                                        $cantidadInicial = $reposicion?->cantidad_inicial ?? 1;
                                        $precioUnitarioItem = $cantidadInicial > 0 ? $montoTotal / $cantidadInicial : 0;

                                        $montoCantidadItem = $cantidadUsadaItem * $precioUnitarioItem;
                                        $montoMermaItem = $mermaItem * $precioUnitarioItem;

                                        $totalMontoCantidad += $montoCantidadItem;
                                        $totalMontoMerma += $montoMermaItem;
                                        $cantidadRestante -= $cantidadUsadaItem;
                                    @endphp

                                    <tr>
                                        <td>{{ $asignacion->codigo }}</td>
                                        <td>{{ class_basename($item) }}</td>
                                        <td class="break-word">{{ $item->descripcion ?? 'N/A' }}</td>
                                        @if($mostrarCantidades)
                                            <td>{{ $cantidadUsadaItem }}</td>
                                        @endif
                                        @if($mostrarMermas)
                                            <td>{{ $mermaItem }}</td>
                                        @endif
                                        @if($mostrarMontos)
                                            <td>{{ number_format($montoCantidadItem, 2, ',', '.') }} Bs</td>
                                            <td>{{ number_format($montoMermaItem, 2, ',', '.') }} Bs</td>
                                        @endif
                                        <td>{{ $sucursal }}</td>
                                    </tr>
                                @endforeach
                            @endforeach

                            <!-- Fila resumen -->
                            <tr class="total-row">
                                <td>{{ $asignacion->codigo }}</td>
                                <td>Producto</td>
                                <td class="break-word">{{ $llenado->existencia?->existenciable?->descripcion ?? 'Producto Final' }}</td>
                                @if($mostrarCantidades)
                                    <td>{{ $cantidadUsada }}</td>
                                @endif
                                @if($mostrarMermas)
                                    <td>{{ $mermaFinal }}</td>
                                @endif
                                @if($mostrarMontos)
                                    <td>{{ number_format($totalMontoCantidad, 2, ',', '.') }} Bs</td>
                                    <td>{{ number_format($totalMontoMerma, 2, ',', '.') }} Bs</td>
                                @endif
                                <td>{{ $sucursalFinal }}</td>
                            </tr>
                            <tr class="no-border"><td colspan="9">&nbsp;</td></tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif
        
        <!-- TRASPASOS -->
        @if($tipo === '' || $tipo === 'traspasos')
            @if($asignaciones->flatMap->traspasos->count() > 0)
                <div class="section-title">Traspasos</div>
                <table>
                    <thead>
                        <tr>
                            <th>Asignación</th>
                            <th>Class Base</th>
                            <th>Producto</th>
                            @if($mostrarCantidades)
                                <th>Cant. Trasp.</th>
                            @endif
                            <th>Suc. Origen</th>
                            <th>Suc. Destino</th>
                            @if($mostrarMontos)
                                <th>Monto Traspaso</th>
                            @endif
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asignaciones->flatMap->traspasos as $traspaso)
                            @php
                                $asignacion = $traspaso->asignacion;
                                $cantidadTraspasoTotal = $traspaso->cantidad;
                                $cantidadTraspasoRestante = $cantidadTraspasoTotal;
                                $montoTotalTraspaso = 0;

                                $sucursalDestino = 'N/A';
                                if ($traspaso->reposicionDestino && $traspaso->reposicionDestino->existencia) {
                                    $sucursalDestino = $traspaso->reposicionDestino->existencia->sucursal->nombre ?? 'N/A';
                                }
                                if ($sucursalDestino === 'N/A' && $traspaso->reposicionDestino) {
                                    $sucursalDestino = $traspaso->reposicionDestino->sucursalFinal->nombre ?? 'Santa Cruz';
                                }
                            @endphp

                            @foreach($asignacion->asignadoReposicions as $detalle)
                                @php
                                    $cantidadOriginal = $detalle->cantidad_original;
                                    if ($cantidadOriginal <= 0) continue;

                                    $reposicion = $detalle->reposicion;
                                    $montoTotal = $reposicion?->comprobantes->sum('monto') ?? 0;
                                    $cantidadInicial = $reposicion?->cantidad_inicial ?? 1;
                                    $precioUnitario = $cantidadInicial > 0 ? $montoTotal / $cantidadInicial : 0;

                                    $cantidadUsada = min($cantidadTraspasoRestante, $cantidadOriginal);
                                    $montoTraspasoLote = $cantidadUsada * $precioUnitario;

                                    $existencia = $detalle->existencia;
                                    $sucursalOrigen = $existencia?->sucursal?->nombre ?? 'N/A';
                                    $item = $existencia?->existenciable ?? $reposicion;

                                    $cantidadTraspasoRestante -= $cantidadUsada;
                                    $montoTotalTraspaso += $montoTraspasoLote;
                                @endphp

                                <tr>
                                    <td>{{ $asignacion->codigo }}</td>
                                    <td>{{ class_basename($item) }}</td>
                                    <td class="break-word">{{ $item->descripcion ?? 'N/A' }}</td>
                                    @if($mostrarCantidades)
                                        <td>{{ $cantidadUsada }}</td>
                                    @endif
                                    <td>{{ $sucursalOrigen }}</td>
                                    <td>{{ $sucursalDestino }}</td>
                                    @if($mostrarMontos)
                                        <td>{{ number_format($montoTraspasoLote, 2, ',', '.') }} Bs</td>
                                    @endif
                                    <td class="break-word">{{ $traspaso->observaciones ?? '-' }}</td>
                                </tr>

                                @if($cantidadTraspasoRestante <= 0)
                                    @break
                                @endif
                            @endforeach

                            <!-- Fila resumen -->
                            <tr class="total-row">
                                <td>{{ $asignacion->codigo }}</td>
                                <td>Traspaso</td>
                                <td class="break-word">{{ $item->descripcion ?? 'Producto Traspasado' }}</td>
                                @if($mostrarCantidades)
                                    <td>{{ $cantidadTraspasoTotal }}</td>
                                @endif
                                <td>Varias</td>
                                <td>{{ $sucursalDestino }}</td>
                                @if($mostrarMontos)
                                    <td>{{ number_format($montoTotalTraspaso, 2, ',', '.') }} Bs</td>
                                @endif
                                <td class="break-word">{{ $traspaso->observaciones ?? 'Traspaso completado' }}</td>
                            </tr>
                            <tr class="no-border"><td colspan="9">&nbsp;</td></tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif
        
        <!-- RESUMEN FINAL -->
        <div class="page-break"></div>
        <div class="summary-section">
            <h3>Resumen General de Reposiciones 
                @if($tipo)
                    <span style="font-size: 12px; color: #666;">(Filtrado por: {{ ucfirst($tipo) }})</span>
                @endif
            </h3>
            
            <div class="summary-grid">
                @if($mostrarCantidades)
                <div class="summary-card">
                    <div class="label">Cantidad Total</div>
                    <div class="value">{{ $cantidadTotal }}</div>
                    <div class="subtext">unidades</div>
                </div>
                @endif
                
                @if($mostrarMontos)
                <div class="summary-card">
                    <div class="label">Monto Total</div>
                    <div class="value">Bs {{ number_format($montoTotal, 2, ',', '.') }}</div>
                    <div class="subtext">valor total</div>
                </div>
                @endif
                
                <div class="summary-card">
                    <div class="label">Reposiciones</div>
                    <div class="value">{{ $cantidadReposicionesUnicas }}</div>
                    <div class="subtext">reposiciones únicas</div>
                </div>
            </div>
            
            <!-- Distribución por producto -->
            @if($cantidadProductosDiferentes <= 10 && $mostrarCantidades)
            <div style="margin-top: 15px;">
                <h4 style="font-size: 12px; text-align: center; margin-bottom: 8px;">Distribución por Producto</h4>
                <table class="distribution-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Porcentaje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productosCount as $producto => $cantidad)
                        @php
                            $porcentaje = $cantidadTotal > 0 ? ($cantidad / $cantidadTotal) * 100 : 0;
                        @endphp
                        <tr>
                            <td class="break-word">{{ $producto }}</td>
                            <td>{{ $cantidad }}</td>
                            <td>{{ number_format($porcentaje, 1) }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        
        <div class="footer">
            Reporte generado el {{ now()->format('d/m/Y H:i:s') }} | 
            Total de registros: {{ $asignaciones->count() }}
        </div>
    </div>
</body>
</html>
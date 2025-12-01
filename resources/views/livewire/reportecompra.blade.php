<div class="p-4 bg-gray-50 min-h-screen mt-20">
    <h2 class="text-2xl font-bold mb-6 text-center text-teal-700">
        Reporte Asignaciones por Area
    </h2>
    <div class="overflow-x-auto mb-10">
        <h3 class="text-xl font-semibold mb-2 text-center">Reposiciones Asignadas</h3>

        <table class="min-w-full bg-white border rounded text-center">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">Asignación</th>
                    <th class="px-4 py-2 border">Class Base</th>
                    <th class="px-4 py-2 border">Producto</th>
                    <th class="px-4 py-2 border">Cantidad restante</th>
                    <th class="px-4 py-2 border">Cantidad Original</th>
                    <th class="px-4 py-2 border">Sucursal</th>
                    <th class="px-4 py-2 border">Monto Asignación</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($asignaciones->flatMap->asignadoReposicions as $detalle)
                    @php
                        $existencia = $detalle->existencia;
                        $item = $existencia?->existenciable;
                        $reposicion = $detalle->reposicion;
                        $montoTotal = $reposicion?->comprobantes->sum('monto') ?? 0;
                        $cantidadInicial = $reposicion?->cantidad_inicial ?? 1;
                        $precioUnitario = $cantidadInicial > 0 ? $montoTotal / $cantidadInicial : 0;
                        $montoAsignacion = $detalle->cantidad_original * $precioUnitario;
                    @endphp
                    <tr class="text-sm">
                        <td class="px-2 py-1 border">{{ $detalle->asignado->codigo }}</td>
                        <td class="px-2 py-1 border">{{ $item ? class_basename($item) : 'N/A' }}</td>
                        <td class="px-2 py-1 border">{{ $item?->descripcion ?? 'N/A' }}</td>
                        <td class="px-2 py-1 border">{{ $detalle->cantidad }}</td>
                        <td class="px-2 py-1 border">{{ $detalle->cantidad_original }}</td>
                        <td class="px-2 py-1 border">{{ $existencia?->sucursal?->nombre ?? 'N/A' }}</td>
                        <td class="px-2 py-1 border">{{ number_format($montoAsignacion, 2, ',', '.') }} Bs</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 py-2">
                            No hay reposiciones asignadas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="overflow-x-auto mb-6">
        <h3 class="text-xl font-semibold mb-2 text-center">Soplados</h3>

        <table class="min-w-full bg-white border rounded text-center">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">Asignación</th>
                    <th class="px-4 py-2 border">Class Base</th>
                    <th class="px-4 py-2 border">Producto</th>
                    <th class="px-4 py-2 border">Cantidad</th>
                    <th class="px-4 py-2 border">Merma</th>
                    <th class="px-4 py-2 border">Monto Cantidad</th>
                    <th class="px-4 py-2 border">Monto Merma</th>
                    <th class="px-4 py-2 border">Sucursal</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($asignaciones->flatMap->soplados as $soplado)
                    @php
                        $asignacion = $soplado->asignado;
                        $cantidadProduccion = $soplado->cantidad;
                        $mermaSoplado = $soplado->merma ?? 0;
                        $sucursalFinal = $soplado->existencia?->sucursal?->nombre ?? 'N/A';

                        $asignadoReposicions = $asignacion->asignadoReposicions ?? collect();
                        $cantidadRestante = $cantidadProduccion;
                        $totalMontoCantidad = 0;
                        $totalMontoMerma = 0;

                        // Ordenamos los lotes de mayor a menor cantidad original
                        $reposicionesOrdenadas = $asignadoReposicions->sortByDesc(fn($r) => $r->cantidad_original ?? 0);

                        $mermaAplicada = false; // Solo aplicaremos la merma una vez al lote mayor
                    @endphp

                    @foreach ($reposicionesOrdenadas as $asignadoReposicion)
                        @php
                            $item = $asignadoReposicion->existencia?->existenciable ?? $asignadoReposicion->reposicion;
                            $sucursal = $asignadoReposicion->existencia?->sucursal?->nombre ?? 'N/A';

                            $cantidadOriginalItem = $asignadoReposicion->cantidad_original ?? 0;

                            // Aplicar merma solo al primer lote mayor
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

                        <tr class="text-sm">
                            <td class="px-2 py-1 border">{{ $asignacion->codigo }}</td>
                            <td class="px-2 py-1 border">{{ class_basename($item) }}</td>
                            <td class="px-2 py-1 border">{{ $item->descripcion ?? 'N/A' }}</td>
                            <td class="px-2 py-1 border">{{ $cantidadUsadaItem }}</td>
                            <td class="px-2 py-1 border">{{ $mermaItem }}</td>
                            <td class="px-2 py-1 border">{{ number_format($montoCantidadItem, 2, ',', '.') }} Bs</td>
                            <td class="px-2 py-1 border">{{ number_format($montoMermaItem, 2, ',', '.') }} Bs</td>
                            <td class="px-2 py-1 border">{{ $sucursal }}</td>
                        </tr>
                    @endforeach

                    {{-- Fila final resumen del producto --}}
                    <tr class="text-sm bg-lime-100 font-bold">
                        <td class="px-2 py-1 border">{{ $asignacion->codigo }}</td>
                        <td class="px-2 py-1 border">Producto</td>
                        <td class="px-2 py-1 border">
                            {{ $soplado->existencia?->existenciable?->descripcion ?? 'Producto Final' }}
                        </td>
                        <td class="px-2 py-1 border">{{ $cantidadProduccion }}</td>
                        <td class="px-2 py-1 border">{{ $mermaSoplado }}</td>
                        <td class="px-2 py-1 border">{{ number_format($totalMontoCantidad, 2, ',', '.') }} Bs</td>
                        <td class="px-2 py-1 border">{{ number_format($totalMontoMerma, 2, ',', '.') }} Bs</td>
                        <td class="px-2 py-1 border">{{ $sucursalFinal }}</td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-500 py-2">No hay soplados registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="overflow-x-auto mb-6">
        <h3 class="text-xl font-semibold mb-2 text-center">Llenados</h3>

        <table class="min-w-full bg-white border rounded text-center">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">Asignación</th>
                    <th class="px-4 py-2 border">Class Base</th>
                    <th class="px-4 py-2 border">Producto</th>
                    <th class="px-4 py-2 border">Cantidad</th>
                    <th class="px-4 py-2 border">Merma</th>
                    <th class="px-4 py-2 border">Monto Cantidad</th>
                    <th class="px-4 py-2 border">Monto Merma</th>
                    <th class="px-4 py-2 border">Sucursal</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($asignaciones->flatMap->llenados as $llenado)
                    @php
                        $asignacion = $llenado->asignado;
                        $sucursalFinal = $llenado->existencia?->sucursal?->nombre ?? 'N/A';
                        $cantidadUsada = $llenado->cantidad;
                        $mermaFinal = $llenado->merma ?? 0;

                        $totalMontoCantidad = 0;
                        $totalMontoMerma = 0;
                    @endphp

                    @foreach ($asignacion->asignadoReposicions->groupBy(fn($r) => $r->existencia?->existenciable?->id ?? $r->reposicion->id) as $reposiciones)
                        @php
                            // Encontrar la reposición con mayor monto en el grupo
                            $reposicionMayorMonto = $reposiciones->sortByDesc(fn($r) => $r->reposicion?->comprobantes->sum('monto') ?? 0)->first();
                            $cantidadRestante = $llenado->cantidad;
                        @endphp

                        @foreach ($reposiciones as $asignadoReposicion)
                            @php
                                $item = $asignadoReposicion->existencia?->existenciable ?? $asignadoReposicion->reposicion;
                                $sucursal = $asignadoReposicion->existencia?->sucursal?->nombre ?? 'N/A';

                                $cantidadOriginalItem = $asignadoReposicion->cantidad_original ?? 0;
                                $cantidadUsadaItem = min($cantidadRestante, $cantidadOriginalItem);

                                // Merma solo para la reposición con mayor monto
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

                            <tr class="text-sm">
                                <td class="px-2 py-1 border">{{ $asignacion->codigo }}</td>
                                <td class="px-2 py-1 border">{{ class_basename($item) }}</td>
                                <td class="px-2 py-1 border">{{ $item->descripcion ?? 'N/A' }}</td>
                                <td class="px-2 py-1 border">{{ $cantidadUsadaItem }}</td>
                                <td class="px-2 py-1 border">{{ $mermaItem }}</td>
                                <td class="px-2 py-1 border">{{ number_format($montoCantidadItem, 2, ',', '.') }} Bs</td>
                                <td class="px-2 py-1 border">{{ number_format($montoMermaItem, 2, ',', '.') }} Bs</td>
                                <td class="px-2 py-1 border">{{ $sucursal }}</td>
                            </tr>
                        @endforeach
                    @endforeach


                    <tr class="text-sm bg-lime-100 font-bold">
                        <td class="px-2 py-1 border">{{ $asignacion->codigo }}</td>
                        <td class="px-2 py-1 border">Producto</td>
                        <td class="px-2 py-1 border">
                            {{ $llenado->existencia?->existenciable?->descripcion ?? 'Producto Final' }}
                        </td>
                        <td class="px-2 py-1 border">{{ $cantidadUsada }}</td>
                        <td class="px-2 py-1 border">{{ $mermaFinal }}</td>
                        <td class="px-2 py-1 border">{{ number_format($totalMontoCantidad, 2, ',', '.') }} Bs</td>
                        <td class="px-2 py-1 border">{{ number_format($totalMontoMerma, 2, ',', '.') }} Bs</td>
                        <td class="px-2 py-1 border">{{ $sucursalFinal }}</td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-500 py-2">No hay llenados registrados</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

<div class="overflow-x-auto mb-6">
    <h3 class="text-xl font-semibold mb-2 text-center">Traspasos</h3>

    <table class="min-w-full bg-white border rounded text-center">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2 border">Asignación</th>
                <th class="px-4 py-2 border">Class Base</th>
                <th class="px-4 py-2 border">Producto</th>
                <th class="px-4 py-2 border">Cantidad Traspasada</th>
                <th class="px-4 py-2 border">Sucursal Origen</th>
                <th class="px-4 py-2 border">Sucursal Destino</th>
                <th class="px-4 py-2 border">Monto Traspaso</th>
                <th class="px-4 py-2 border">Observaciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($asignaciones->flatMap->traspasos as $traspaso)
                @php
                    $asignacion = $traspaso->asignacion;
                    $cantidadTraspasoTotal = $traspaso->cantidad;
                    $cantidadTraspasoRestante = $cantidadTraspasoTotal;
                    $montoTotalTraspaso = 0;
                    
                    // OBTENER SUCURSAL DESTINO CORRECTAMENTE
                    $sucursalDestino = 'N/A';
                    if ($traspaso->reposicionDestino && $traspaso->reposicionDestino->existencia) {
                        $sucursalDestino = $traspaso->reposicionDestino->existencia->sucursal->nombre ?? 'N/A';
                    }
                    if ($sucursalDestino === 'N/A' && $traspaso->reposicionDestino) {
                        $sucursalDestino = $traspaso->reposicionDestino->sucursalFinal->nombre ?? 'Santa Cruz';
                    }
                @endphp

                @foreach ($asignacion->asignadoReposicions as $detalle)
                    @php
                        // Usar cantidad ORIGINAL del pivote para el cálculo
                        $cantidadOriginal = $detalle->cantidad_original;
                        
                        if ($cantidadOriginal <= 0) {
                            continue;
                        }

                        $reposicion = $detalle->reposicion;
                        $montoTotal = $reposicion?->comprobantes->sum('monto') ?? 0;
                        $cantidadInicial = $reposicion?->cantidad_inicial ?? 1;
                        
                        // Calcular precio unitario basado en CANTIDAD INICIAL de la reposición
                        $precioUnitario = $cantidadInicial > 0 ? $montoTotal / $cantidadInicial : 0;

                        // Cantidad a usar de este lote
                        $cantidadUsada = min($cantidadTraspasoRestante, $cantidadOriginal);
                        
                        // Calcular monto proporcional
                        $montoTraspasoLote = $cantidadUsada * $precioUnitario;

                        // Obtener datos reales de sucursales desde la existencia
                        $existencia = $detalle->existencia;
                        $sucursalOrigen = $existencia?->sucursal?->nombre ?? 'N/A';

                        // Obtener el producto
                        $item = $existencia?->existenciable ?? $reposicion;

                        $cantidadTraspasoRestante -= $cantidadUsada;
                        $montoTotalTraspaso += $montoTraspasoLote;
                    @endphp

                    <tr class="text-sm">
                        <td class="px-2 py-1 border">{{ $asignacion->codigo }}</td>
                        <td class="px-2 py-1 border">{{ class_basename($item) }}</td>
                        <td class="px-2 py-1 border">{{ $item->descripcion ?? 'N/A' }}</td>
                        <td class="px-2 py-1 border">{{ $cantidadUsada }}</td>
                        <td class="px-2 py-1 border">{{ $sucursalOrigen }}</td>
                        <td class="px-2 py-1 border">{{ $sucursalDestino }}</td>
                        <td class="px-2 py-1 border">{{ number_format($montoTraspasoLote, 2, ',', '.') }} Bs</td>
                        <td class="px-2 py-1 border">{{ $traspaso->observaciones ?? '-' }}</td>
                    </tr>

                    @if($cantidadTraspasoRestante <= 0)
                        @break
                    @endif
                @endforeach

                {{-- Fila de resumen del traspaso - ESTILO MEJORADO --}}
                <tr class="text-sm bg-lime-100 font-bold">
                    <td class="px-2 py-1 border">{{ $asignacion->codigo }}</td>
                    <td class="px-2 py-1 border">Traspaso</td>
                    <td class="px-2 py-1 border">
                        {{ $item->descripcion ?? 'Producto Traspasado' }}
                    </td>
                    <td class="px-2 py-1 border">{{ $cantidadTraspasoTotal }}</td>
                    <td class="px-2 py-1 border">Varias</td>
                    <td class="px-2 py-1 border">{{ $sucursalDestino }}</td>
                    <td class="px-2 py-1 border">{{ number_format($montoTotalTraspaso, 2, ',', '.') }} Bs</td>
                    <td class="px-2 py-1 border">{{ $traspaso->observaciones ?? 'Traspaso completado' }}</td>
                </tr>

                {{-- Fila vacía para separación --}}
                <tr>
                    <td colspan="8" class="py-2"></td>
                </tr>

            @empty
                <tr>
                    <td colspan="8" class="text-center text-gray-500 py-2">No hay traspasos registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</div>
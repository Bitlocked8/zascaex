<div class="p-4 bg-transparent min-h-screen mt-20">
    <h2 class="text-2xl font-bold mb-6 text-center text-teal-700">
        Reporte Asignaciones por Area
    </h2>
    <div class="flex flex-col gap-4 mb-6">
        <div class="flex flex-wrap justify-center gap-2">
            <label class="font-semibold w-full text-center mb-1">Fecha Inicio:</label>

            <input type="number" min="1" max="31" wire:model.live="fecha_inicio_dia" placeholder="Día"
                class="w-16 p-2 rounded text-center border">

            <input type="number" min="1" max="12" wire:model.live="fecha_inicio_mes" placeholder="Mes"
                class="w-16 p-2 rounded text-center border">

            <input type="number" min="2000" max="2100" wire:model.live="fecha_inicio_ano" placeholder="Año"
                class="w-20 p-2 rounded text-center border">

            <input type="number" min="0" max="23" wire:model.live="fecha_inicio_hora" placeholder="Hora"
                class="w-16 p-2 rounded text-center border">

            <input type="number" min="0" max="59" wire:model.live="fecha_inicio_min" placeholder="Minuto"
                class="w-16 p-2 rounded text-center border">
        </div>
        <div class="flex flex-wrap justify-center gap-2">
            <label class="font-semibold w-full text-center mb-1">Fecha Fin:</label>

            <input type="number" min="1" max="31" wire:model.live="fecha_fin_dia" placeholder="Día"
                class="w-16 p-2 rounded text-center border">

            <input type="number" min="1" max="12" wire:model.live="fecha_fin_mes" placeholder="Mes"
                class="w-16 p-2 rounded text-center border">

            <input type="number" min="2000" max="2100" wire:model.live="fecha_fin_ano" placeholder="Año"
                class="w-20 p-2 rounded text-center border">

            <input type="number" min="0" max="23" wire:model.live="fecha_fin_hora" placeholder="Hora"
                class="w-16 p-2 rounded text-center border">

            <input type="number" min="0" max="59" wire:model.live="fecha_fin_min" placeholder="Minuto"
                class="w-16 p-2 rounded text-center border">
        </div>
        <div class="flex flex-wrap justify-center gap-4 mt-2">
            <input type="text" wire:model.live="codigo" placeholder="Buscar código"
                class="border p-2 rounded w-40 text-center">
        </div>
      <div class="flex flex-wrap justify-center gap-4 mt-2">

    {{-- Filtro Personal --}}
    <div class="w-40">
        <label class="font-semibold text-sm mb-2 block">Personal</label>
        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white max-h-[170px] overflow-y-auto grid grid-cols-1 gap-2">

            <button type="button" wire:click="$set('personal_id', '')"
                class="w-full px-3 py-2 rounded-md border text-left transition
                {{ $personal_id == ''
                    ? 'bg-cyan-600 text-white border-cyan-700 shadow'
                    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                <p class="font-semibold text-sm">Todo el personal</p>
            </button>

            @forelse($personales as $p)
                <button type="button" wire:click="$set('personal_id', {{ $p->id }})"
                    class="w-full px-3 py-2 rounded-md border text-left transition
                    {{ $personal_id == $p->id
                        ? 'bg-cyan-600 text-white border-cyan-700 shadow'
                        : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                    <p class="font-semibold text-sm">{{ $p->nombres }}</p>
                </button>
            @empty
                <p class="text-center text-gray-500 py-3 text-sm">No hay personal</p>
            @endforelse

        </div>
    </div>


    {{-- Filtro Sucursal --}}
    <div class="w-40">
        <label class="font-semibold text-sm mb-2 block">Sucursal</label>
        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white max-h-[170px] overflow-y-auto grid grid-cols-1 gap-2">

            <button type="button" wire:click="$set('sucursal_id', '')"
                class="w-full px-3 py-2 rounded-md border text-left transition
                {{ $sucursal_id == ''
                    ? 'bg-cyan-600 text-white border-cyan-700 shadow'
                    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                <p class="font-semibold text-sm">Todas las sucursales</p>
            </button>

            @forelse($sucursales as $s)
                <button type="button" wire:click="$set('sucursal_id', {{ $s->id }})"
                    class="w-full px-3 py-2 rounded-md border text-left transition
                    {{ $sucursal_id == $s->id
                        ? 'bg-cyan-600 text-white border-cyan-700 shadow'
                        : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                    <p class="font-semibold text-sm">{{ $s->nombre }}</p>
                </button>
            @empty
                <p class="text-center text-gray-500 py-3 text-sm">No hay sucursales</p>
            @endforelse

        </div>
    </div>


    {{-- Filtro Tipo --}}
    <div class="w-40">
        <label class="font-semibold text-sm mb-2 block">Tipo</label>
        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white max-h-[170px] overflow-y-auto grid grid-cols-1 gap-2">

            {{-- Todos --}}
            <button type="button" wire:click="$set('tipo', '')"
                class="w-full px-3 py-2 rounded-md border text-left transition
                {{ $tipo == ''
                    ? 'bg-cyan-600 text-white border-cyan-700 shadow'
                    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                <p class="font-semibold text-sm">Todos los tipos</p>
            </button>

            {{-- Tipos disponibles --}}
            @php
                $tipos = [
                    'reposiciones' => 'Reposiciones',
                    'soplados' => 'Soplados',
                    'llenados' => 'Llenados',
                    'traspasos' => 'Traspasos',
                ];
            @endphp

            @foreach($tipos as $valor => $texto)
                <button type="button" wire:click="$set('tipo', '{{ $valor }}')"
                    class="w-full px-3 py-2 rounded-md border text-left transition
                    {{ $tipo === $valor
                        ? 'bg-cyan-600 text-white border-cyan-700 shadow'
                        : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                    <p class="font-semibold text-sm">{{ $texto }}</p>
                </button>
            @endforeach

        </div>
    </div>

</div>

        <div class="flex flex-wrap justify-center gap-4 mt-2">
            <button wire:click="$toggle('mostrarCantidades')"
                class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300 transition">
                {{ $mostrarCantidades ? 'Ocultar Cantidades' : 'Mostrar Cantidades' }}
            </button>

            <button wire:click="$toggle('mostrarMontos')"
                class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300 transition">
                {{ $mostrarMontos ? 'Ocultar Montos' : 'Mostrar Montos' }}
            </button>

            <button wire:click="$toggle('mostrarMermas')"
                class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300 transition">
                {{ $mostrarMermas ? 'Ocultar Mermas' : 'Mostrar Mermas' }}
            </button>
        </div>
        <div class="flex justify-center mb-6">
            <button wire:click="descargarPDF"
                class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 transition">
                Descargar PDF
            </button>
        </div>

    </div>
    @if($tipo === '' || $tipo === 'reposiciones')
        <div class="overflow-x-auto mb-10">
            <h3 class="text-xl font-semibold mb-2 text-center">Reposiciones Asignadas</h3>

            <table class="min-w-full bg-white border rounded text-center">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 border">Asignación</th>
                        <th class="px-4 py-2 border">Class Base</th>
                        <th class="px-4 py-2 border">Producto</th>

                        @if($mostrarCantidades)
                            <th class="px-4 py-2 border">Cantidad restante</th>
                            <th class="px-4 py-2 border">Cantidad Original</th>
                        @endif

                        <th class="px-4 py-2 border">Sucursal</th>

                        @if($mostrarMontos)
                            <th class="px-4 py-2 border">Monto Asignación</th>
                        @endif
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

                            @if($mostrarCantidades)
                                <td class="px-2 py-1 border">{{ $detalle->cantidad }}</td>
                                <td class="px-2 py-1 border">{{ $detalle->cantidad_original }}</td>
                            @endif

                            <td class="px-2 py-1 border">{{ $existencia?->sucursal?->nombre ?? 'N/A' }}</td>

                            @if($mostrarMontos)
                                <td class="px-2 py-1 border">{{ number_format($montoAsignacion, 2, ',', '.') }} Bs</td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 3 + ($mostrarCantidades ? 2 : 0) + 1 + ($mostrarMontos ? 1 : 0) }}"
                                class="text-center text-gray-500 py-2">
                                No hay reposiciones asignadas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if($tipo === '' || $tipo === 'soplados')
        <div class="overflow-x-auto mb-6">
            <h3 class="text-xl font-semibold mb-2 text-center">Soplados</h3>

            <table class="min-w-full bg-white border rounded text-center">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 border">Asignación</th>
                        <th class="px-4 py-2 border">Class Base</th>
                        <th class="px-4 py-2 border">Producto</th>

                        @if($mostrarCantidades)
                            <th class="px-4 py-2 border">Cantidad</th>
                        @endif

                        @if($mostrarMermas)
                            <th class="px-4 py-2 border">Merma</th>
                        @endif

                        @if($mostrarMontos)
                            <th class="px-4 py-2 border">Monto Cantidad</th>
                            <th class="px-4 py-2 border">Monto Merma</th>
                        @endif

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

                            $reposicionesOrdenadas = $asignadoReposicions->sortByDesc(fn($r) => $r->cantidad_original ?? 0);
                            $mermaAplicada = false;
                        @endphp

                        @foreach ($reposicionesOrdenadas as $asignadoReposicion)
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

                            <tr class="text-sm">
                                <td class="px-2 py-1 border">{{ $asignacion->codigo }}</td>
                                <td class="px-2 py-1 border">{{ class_basename($item) }}</td>
                                <td class="px-2 py-1 border">{{ $item->descripcion ?? 'N/A' }}</td>

                                @if($mostrarCantidades)
                                    <td class="px-2 py-1 border">{{ $cantidadUsadaItem }}</td>
                                @endif

                                @if($mostrarMermas)
                                    <td class="px-2 py-1 border">{{ $mermaItem }}</td>
                                @endif

                                @if($mostrarMontos)
                                    <td class="px-2 py-1 border">{{ number_format($montoCantidadItem, 2, ',', '.') }} Bs</td>
                                    <td class="px-2 py-1 border">{{ number_format($montoMermaItem, 2, ',', '.') }} Bs</td>
                                @endif

                                <td class="px-2 py-1 border">{{ $sucursal }}</td>
                            </tr>
                        @endforeach

                        {{-- Fila resumen del producto --}}
                        <tr class="text-sm bg-lime-100 font-bold">
                            <td class="px-2 py-1 border">{{ $asignacion->codigo }}</td>
                            <td class="px-2 py-1 border">Producto</td>
                            <td class="px-2 py-1 border">
                                {{ $soplado->existencia?->existenciable?->descripcion ?? 'Producto Final' }}
                            </td>

                            @if($mostrarCantidades)
                                <td class="px-2 py-1 border">{{ $cantidadProduccion }}</td>
                            @endif

                            @if($mostrarMermas)
                                <td class="px-2 py-1 border">{{ $mermaSoplado }}</td>
                            @endif

                            @if($mostrarMontos)
                                <td class="px-2 py-1 border">{{ number_format($totalMontoCantidad, 2, ',', '.') }} Bs</td>
                                <td class="px-2 py-1 border">{{ number_format($totalMontoMerma, 2, ',', '.') }} Bs</td>
                            @endif

                            <td class="px-2 py-1 border">{{ $sucursalFinal }}</td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="{{ 3 + ($mostrarCantidades ? 1 : 0) + ($mostrarMermas ? 1 : 0) + ($mostrarMontos ? 2 : 0) + 1 }}"
                                class="text-center text-gray-500 py-2">
                                No hay soplados registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif


    @if($tipo === '' || $tipo === 'llenados')
        <div class="overflow-x-auto mb-6">
            <h3 class="text-xl font-semibold mb-2 text-center">Llenados</h3>

            <table class="min-w-full bg-white border rounded text-center">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 border">Asignación</th>
                        <th class="px-4 py-2 border">Class Base</th>
                        <th class="px-4 py-2 border">Producto</th>

                        @if($mostrarCantidades)
                            <th class="px-4 py-2 border">Cantidad</th>
                        @endif

                        @if($mostrarMermas)
                            <th class="px-4 py-2 border">Merma</th>
                        @endif

                        @if($mostrarMontos)
                            <th class="px-4 py-2 border">Monto Cantidad</th>
                            <th class="px-4 py-2 border">Monto Merma</th>
                        @endif

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
                                $reposicionMayorMonto = $reposiciones->sortByDesc(fn($r) => $r->reposicion?->comprobantes->sum('monto') ?? 0)->first();
                                $cantidadRestante = $llenado->cantidad;
                            @endphp

                            @foreach ($reposiciones as $asignadoReposicion)
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

                                <tr class="text-sm">
                                    <td class="px-2 py-1 border">{{ $asignacion->codigo }}</td>
                                    <td class="px-2 py-1 border">{{ class_basename($item) }}</td>
                                    <td class="px-2 py-1 border">{{ $item->descripcion ?? 'N/A' }}</td>

                                    @if($mostrarCantidades)
                                        <td class="px-2 py-1 border">{{ $cantidadUsadaItem }}</td>
                                    @endif

                                    @if($mostrarMermas)
                                        <td class="px-2 py-1 border">{{ $mermaItem }}</td>
                                    @endif

                                    @if($mostrarMontos)
                                        <td class="px-2 py-1 border">{{ number_format($montoCantidadItem, 2, ',', '.') }} Bs</td>
                                        <td class="px-2 py-1 border">{{ number_format($montoMermaItem, 2, ',', '.') }} Bs</td>
                                    @endif

                                    <td class="px-2 py-1 border">{{ $sucursal }}</td>
                                </tr>
                            @endforeach
                        @endforeach

                        {{-- Fila resumen --}}
                        <tr class="text-sm bg-lime-100 font-bold">
                            <td class="px-2 py-1 border">{{ $asignacion->codigo }}</td>
                            <td class="px-2 py-1 border">Producto</td>
                            <td class="px-2 py-1 border">
                                {{ $llenado->existencia?->existenciable?->descripcion ?? 'Producto Final' }}
                            </td>

                            @if($mostrarCantidades)
                                <td class="px-2 py-1 border">{{ $cantidadUsada }}</td>
                            @endif

                            @if($mostrarMermas)
                                <td class="px-2 py-1 border">{{ $mermaFinal }}</td>
                            @endif

                            @if($mostrarMontos)
                                <td class="px-2 py-1 border">{{ number_format($totalMontoCantidad, 2, ',', '.') }} Bs</td>
                                <td class="px-2 py-1 border">{{ number_format($totalMontoMerma, 2, ',', '.') }} Bs</td>
                            @endif

                            <td class="px-2 py-1 border">{{ $sucursalFinal }}</td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="{{ 3 + ($mostrarCantidades ? 1 : 0) + ($mostrarMermas ? 1 : 0) + ($mostrarMontos ? 2 : 0) + 1 }}"
                                class="text-center text-gray-500 py-2">
                                No hay llenados registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif


    @if($tipo === '' || $tipo === 'traspasos')
        <div class="overflow-x-auto mb-6">
            <h3 class="text-xl font-semibold mb-2 text-center">Traspasos</h3>

            <table class="min-w-full bg-white border rounded text-center">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 border">Asignación</th>
                        <th class="px-4 py-2 border">Class Base</th>
                        <th class="px-4 py-2 border">Producto</th>

                        @if($mostrarCantidades)
                            <th class="px-4 py-2 border">Cantidad Traspasada</th>
                        @endif

                        <th class="px-4 py-2 border">Sucursal Origen</th>
                        <th class="px-4 py-2 border">Sucursal Destino</th>

                        @if($mostrarMontos)
                            <th class="px-4 py-2 border">Monto Traspaso</th>
                        @endif

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
                                $cantidadOriginal = $detalle->cantidad_original;
                                if ($cantidadOriginal <= 0)
                                    continue;

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

                            <tr class="text-sm">
                                <td class="px-2 py-1 border">{{ $asignacion->codigo }}</td>
                                <td class="px-2 py-1 border">{{ class_basename($item) }}</td>
                                <td class="px-2 py-1 border">{{ $item->descripcion ?? 'N/A' }}</td>

                                @if($mostrarCantidades)
                                    <td class="px-2 py-1 border">{{ $cantidadUsada }}</td>
                                @endif

                                <td class="px-2 py-1 border">{{ $sucursalOrigen }}</td>
                                <td class="px-2 py-1 border">{{ $sucursalDestino }}</td>

                                @if($mostrarMontos)
                                    <td class="px-2 py-1 border">{{ number_format($montoTraspasoLote, 2, ',', '.') }} Bs</td>
                                @endif

                                <td class="px-2 py-1 border">{{ $traspaso->observaciones ?? '-' }}</td>
                            </tr>

                            @if($cantidadTraspasoRestante <= 0)
                                @break
                            @endif
                        @endforeach

                        {{-- Fila resumen --}}
                        <tr class="text-sm bg-lime-100 font-bold">
                            <td class="px-2 py-1 border">{{ $asignacion->codigo }}</td>
                            <td class="px-2 py-1 border">Traspaso</td>
                            <td class="px-2 py-1 border">{{ $item->descripcion ?? 'Producto Traspasado' }}</td>

                            @if($mostrarCantidades)
                                <td class="px-2 py-1 border">{{ $cantidadTraspasoTotal }}</td>
                            @endif

                            <td class="px-2 py-1 border">Varias</td>
                            <td class="px-2 py-1 border">{{ $sucursalDestino }}</td>

                            @if($mostrarMontos)
                                <td class="px-2 py-1 border">{{ number_format($montoTotalTraspaso, 2, ',', '.') }} Bs</td>
                            @endif

                            <td class="px-2 py-1 border">{{ $traspaso->observaciones ?? 'Traspaso completado' }}</td>
                        </tr>

                        <tr>
                            <td colspan="8" class="py-2"></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 3 + ($mostrarCantidades ? 1 : 0) + 2 + ($mostrarMontos ? 1 : 0) + 1 }}"
                                class="text-center text-gray-500 py-2">No hay traspasos registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif


    <div class="mt-6 p-4 bg-white rounded-lg shadow">
        <h3 class="text-xl font-semibold mb-4 text-center">
            Resumen General de Reposiciones
            @if($tipo)
                <span class="text-sm text-gray-600">(Filtrado por: {{ ucfirst($tipo) }})</span>
            @endif
        </h3>

        @php
            $cantidadTotal = 0;
            $montoTotal = 0;
            $reposicionesUnicas = [];
            $productosCount = [];
            foreach ($asignaciones->flatMap->asignadoReposicions as $detalle) {
                $reposicion = $detalle->reposicion;
                $montoReposicion = $reposicion?->comprobantes->sum('monto') ?? 0;
                $cantidadInicial = $reposicion?->cantidad_inicial ?? 1;
                $precioUnitario = $cantidadInicial > 0 ? $montoReposicion / $cantidadInicial : 0;

                $cantidadDetalle = $detalle->cantidad_original;
                $montoDetalle = $cantidadDetalle * $precioUnitario;

                $cantidadTotal += $cantidadDetalle;
                $montoTotal += $montoDetalle;
                $reposicionId = $reposicion?->id ?? 0;
                if ($reposicionId) {
                    $reposicionesUnicas[$reposicionId] = true;
                }
                $item = $detalle->existencia?->existenciable ?? $reposicion;
                if ($item && isset($item->descripcion)) {
                    $productoKey = $item->descripcion;
                    if (!isset($productosCount[$productoKey])) {
                        $productosCount[$productoKey] = 0;
                    }
                    $productosCount[$productoKey] += $cantidadDetalle;
                }
            }

            $cantidadReposicionesUnicas = count($reposicionesUnicas);
            $cantidadProductosDiferentes = count($productosCount);
        @endphp

        <div class="mb-6 p-4 bg-gray-100 rounded text-center shadow">
            <div class="text-2xl font-bold text-teal-700 mb-2">Resumen Total</div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if($mostrarCantidades)
                    <div class="p-3 bg-white rounded">
                        <div class="text-sm text-gray-600">Cantidad Total</div>
                        <div class="text-2xl font-bold">{{ $cantidadTotal }}</div>
                        <div class="text-xs text-gray-500">unidades</div>
                    </div>
                @endif

                @if($mostrarMontos)
                    <div class="p-3 bg-white rounded">
                        <div class="text-sm text-gray-600">Monto Total</div>
                        <div class="text-2xl font-bold text-teal-700">Bs {{ number_format($montoTotal, 2, ',', '.') }}</div>
                        <div class="text-xs text-gray-500">valor total</div>
                    </div>
                @endif

                <div class="p-3 bg-white rounded">
                    <div class="text-sm text-gray-600">Reposiciones</div>
                    <div class="text-2xl font-bold">{{ $cantidadReposicionesUnicas }}</div>
                    <div class="text-xs text-gray-500">reposiciones únicas</div>
                </div>
            </div>
        </div>

        @if($cantidadProductosDiferentes <= 10)
            <div class="mt-4">
                <h4 class="font-semibold mb-2 text-center">Distribución por Producto</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-3 py-2 border text-left">Producto</th>
                                @if($mostrarCantidades)
                                    <th class="px-3 py-2 border text-center">Cantidad</th>
                                @endif
                                @if($mostrarCantidades)
                                    <th class="px-3 py-2 border text-center">Porcentaje</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productosCount as $producto => $cantidad)
                                @php
                                    $porcentaje = $cantidadTotal > 0 ? ($cantidad / $cantidadTotal) * 100 : 0;
                                @endphp
                                <tr>
                                    <td class="px-3 py-2 border">{{ $producto }}</td>
                                    @if($mostrarCantidades)
                                        <td class="px-3 py-2 border text-center">{{ $cantidad }}</td>
                                        <td class="px-3 py-2 border text-center">{{ number_format($porcentaje, 1) }}%</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>


</div>
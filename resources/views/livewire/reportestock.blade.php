<div class="p-4 bg-gray-50 min-h-screen mt-20">

    <h2 class="text-2xl font-bold mb-6 text-center text-teal-700">
        Reporte de Stock
    </h2>


    <div class="flex flex-col gap-4 mb-6">
        <div class="flex flex-wrap justify-center gap-2">
            <label class="font-semibold w-full text-center mb-1">Fecha Inicio:</label>
            <input type="number" wire:model.live="fecha_inicio_dia" placeholder="Día" min="1" max="31"
                class="w-16 p-2 rounded text-center border">
            <input type="number" wire:model.live="fecha_inicio_mes" placeholder="Mes" min="1" max="12"
                class="w-16 p-2 rounded text-center border">
            <input type="number" wire:model.live="fecha_inicio_ano" placeholder="Año" min="2000" max="2100"
                class="w-20 p-2 rounded text-center border">
            <input type="number" wire:model.live="fecha_inicio_hora" placeholder="Hora" min="0" max="23"
                class="w-16 p-2 rounded text-center border">
            <input type="number" wire:model.live="fecha_inicio_min" placeholder="Minuto" min="0" max="59"
                class="w-16 p-2 rounded text-center border">
        </div>

        <div class="flex flex-wrap justify-center gap-2">
            <label class="font-semibold w-full text-center mb-1">Fecha Fin:</label>
            <input type="number" wire:model.live="fecha_fin_dia" placeholder="Día" min="1" max="31"
                class="w-16 p-2 rounded text-center border">
            <input type="number" wire:model.live="fecha_fin_mes" placeholder="Mes" min="1" max="12"
                class="w-16 p-2 rounded text-center border">
            <input type="number" wire:model.live="fecha_fin_ano" placeholder="Año" min="2000" max="2100"
                class="w-20 p-2 rounded text-center border">
            <input type="number" wire:model.live="fecha_fin_hora" placeholder="Hora" min="0" max="23"
                class="w-16 p-2 rounded text-center border">
            <input type="number" wire:model.live="fecha_fin_min" placeholder="Minuto" min="0" max="59"
                class="w-16 p-2 rounded text-center border">
        </div>
        <div class="flex flex-wrap justify-center gap-4 mt-2">
            <input type="text" wire:model.live="search" class="border p-2 rounded w-full text-center"
                placeholder="Buscar código de reposiciones">
        </div>

        <div class="flex flex-wrap justify-center gap-4 mt-2">

            <div class="w-40">
                <label class="font-semibold text-sm mb-2 block">Existencia</label>
                <div
                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white max-h-[170px] overflow-y-auto grid grid-cols-1 gap-2">

                    <button type="button" wire:click="$set('existencia_id', '')" class="w-full px-3 py-2 rounded-md border text-left transition
                {{ $existencia_id == ''
    ? 'bg-cyan-600 text-white border-cyan-700 shadow'
    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                        <p class="font-semibold text-sm">Todos</p>
                    </button>

                    @forelse($existencias as $e)
                                    <button type="button" wire:click="$set('existencia_id', {{ $e->id }})" class="w-full px-3 py-2 rounded-md border text-left transition
                                                    {{ $existencia_id == $e->id
                        ? 'bg-cyan-600 text-white border-cyan-700 shadow'
                        : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                        <p class="font-semibold text-sm">
                                            {{ $e->descripcion ?? $e->existenciable->descripcion ?? 'N/A' }}
                                        </p>
                                    </button>
                    @empty
                        <p class="text-center text-gray-500 py-3 text-sm">No hay existencias disponibles</p>
                    @endforelse
                </div>
            </div>
            <div class="w-40">
                <label class="font-semibold text-sm mb-2 block">Sucursal</label>
                <div
                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white max-h-[170px] overflow-y-auto grid grid-cols-1 gap-2">

                    <button type="button" wire:click="$set('sucursal_id', '')" class="w-full px-3 py-2 rounded-md border text-left transition
                {{ $sucursal_id == ''
    ? 'bg-cyan-600 text-white border-cyan-700 shadow'
    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                        <p class="font-semibold text-sm">Todos</p>
                    </button>

                    @forelse($sucursales as $s)
                                    <button type="button" wire:click="$set('sucursal_id', {{ $s->id }})" class="w-full px-3 py-2 rounded-md border text-left transition
                                                    {{ $sucursal_id == $s->id
                        ? 'bg-cyan-600 text-white border-cyan-700 shadow'
                        : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                        <p class="font-semibold text-sm">{{ $s->nombre }}</p>
                                        <p class="text-xs text-gray-600 {{ $sucursal_id == $s->id ? 'text-cyan-100' : '' }}">
                                            {{ $s->empresa?->nombre ?? 'Sin empresa' }}
                                        </p>
                                        <p class="text-xs text-gray-600 {{ $sucursal_id == $s->id ? 'text-cyan-100' : '' }}">
                                            {{ $s->telefono ?? 'Sin teléfono' }}
                                        </p>
                                    </button>
                    @empty
                        <p class="text-center text-gray-500 py-3 text-sm">No hay sucursales disponibles</p>
                    @endforelse
                </div>
            </div>
            <div class="w-40">
                <label class="font-semibold text-sm mb-2 block">Personal</label>
                <div
                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white max-h-[170px] overflow-y-auto grid grid-cols-1 gap-2">

                    <button type="button" wire:click="$set('personal_id', '')" class="w-full px-3 py-2 rounded-md border text-left transition
                {{ $personal_id == ''
    ? 'bg-cyan-600 text-white border-cyan-700 shadow'
    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                        <p class="font-semibold text-sm">Todos</p>
                    </button>

                    @forelse($personales as $p)
                                    <button type="button" wire:click="$set('personal_id', {{ $p->id }})" class="w-full px-3 py-2 rounded-md border text-left transition
                                                    {{ $personal_id == $p->id
                        ? 'bg-cyan-600 text-white border-cyan-700 shadow'
                        : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                        <p class="font-semibold text-sm">{{ $p->nombres }}</p>
                                    </button>
                    @empty
                        <p class="text-center text-gray-500 py-3 text-sm">No hay personal disponible</p>
                    @endforelse
                </div>
            </div>

        </div>


        <div class="flex flex-wrap justify-center gap-4 mt-2">
            <button wire:click="toggleCantidad" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300 transition">
                {{ $ocultar_cantidad ? 'Mostrar Cantidad' : 'Ocultar Cantidad' }}
            </button>

            <button wire:click="toggleMonto" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300 transition">
                {{ $ocultar_monto ? 'Mostrar Monto' : 'Ocultar Monto' }}
            </button>
        </div>



        <div class="flex justify-center mb-6">
            <button wire:click="descargarPDF"
                class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 transition">
                Descargar PDF
            </button>
        </div>
    </div>


    <div class="overflow-x-auto mb-10">
        <h3 class="text-xl font-semibold mb-2 text-center">Reporte stock</h3>
        <table class="min-w-full bg-white border rounded text-center">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">Código</th>
                    <th class="px-4 py-2 border">Personal</th>
                    <th class="px-4 py-2 border">Existencia</th>
                    <th class="px-4 py-2 border">Sucursal</th>
                    @if(!$ocultar_cantidad)
                        <th class="px-4 py-2 border">Cantidad</th>
                    @endif
                    @if(!$ocultar_monto)
                        <th class="px-4 py-2 border">Precio Unitario</th>
                        <th class="px-4 py-2 border">Monto Total</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($reposiciones as $rep)
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

                    {{-- FILA ENTRADA --}}
                    <tr class="text-sm font-semibold bg-green-50">
                        <td class="px-2 py-1 border text-emerald-600">{{ $rep->codigo }}</td>
                        <td class="px-2 py-1 border text-emerald-600">{{ $rep->personal->nombres ?? 'N/A' }}</td>
                        <td class="px-2 py-1 border">{{ $rep->existencia?->existenciable->descripcion ?? 'N/A' }}</td>
                        <td class="px-2 py-1 border">{{ $sucursal }}</td>
                        @if(!$ocultar_cantidad)
                            <td class="px-2 py-1 border text-emerald-600">{{ $entrada }}</td>
                        @endif
                        @if(!$ocultar_monto)
                            <td class="px-2 py-1 border text-emerald-600">{{ number_format($precio_unitario, 2) }}</td>
                            <td class="px-2 py-1 border text-emerald-600">{{ number_format($monto, 2) }}</td>
                        @endif
                    </tr>

                    {{-- FILAS SALIDAS --}}
                    @foreach($rep->asignadoReposicions as $ar)
                        @php
                            $cantidad_asignada = $ar->cantidad_original ?? 0;
                            $monto_total_ar = $cantidad_asignada * $precio_unitario;
                        @endphp
                        <tr class="text-sm bg-orange-50">
                            <td class="px-2 py-1 border text-orange-600">{{ $ar->asignado->codigo ?? '-' }}</td>
                            <td class="px-2 py-1 border text-orange-600">{{ $ar->asignado->personal->nombres ?? '-' }}</td>
                            <td class="px-2 py-1 border">{{ $ar->existencia?->existenciable->descripcion ?? 'N/A' }}</td>
                            <td class="px-2 py-1 border">{{ $sucursal }}</td>
                            @if(!$ocultar_cantidad)
                                <td class="px-2 py-1 border text-orange-600">{{ $cantidad_asignada }}</td>
                            @endif
                            @if(!$ocultar_monto)
                                <td class="px-2 py-1 border text-orange-600">{{ number_format($precio_unitario, 2) }}</td>
                                <td class="px-2 py-1 border text-orange-600">{{ number_format($monto_total_ar, 2) }}</td>
                            @endif
                        </tr>
                    @endforeach

                    {{-- TOTAL --}}
                    <tr class="text-sm font-bold bg-cyan-100">
                        <td class="px-2 py-1 border">Total</td>
                        <td class="px-2 py-1 border">-</td>
                        <td class="px-2 py-1 border">-</td>
                        <td class="px-2 py-1 border">{{ $sucursal }}</td>
                        @if(!$ocultar_cantidad)
                            <td class="px-2 py-1 border text-emerald-600">{{ $cantidad_restante }}</td>
                        @endif
                        @if(!$ocultar_monto)
                            <td class="px-2 py-1 border text-emerald-600">{{ number_format($precio_unitario, 2) }}</td>
                            <td class="px-2 py-1 border text-emerald-600">{{ number_format($monto_restante, 2) }}</td>
                        @endif
                    </tr>

                @empty
                    <tr>
                        <td colspan="{{ $ocultar_cantidad && $ocultar_monto ? 4 : ($ocultar_cantidad || $ocultar_monto ? 5 : 7) }}"
                            class="text-center text-gray-500 py-2">
                            No hay reposiciones registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 p-4 bg-white rounded-lg shadow">

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

        <div class="mt-6 p-4 bg-gray-100 rounded shadow">
            <h3 class="text-xl font-semibold mb-4 text-center">Resumen de Stock</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                <div class="p-4 bg-green-100 rounded">
                    <span class="font-semibold">Total Entradas:</span> {{ number_format($totalEntradas, 2) }}
                </div>
                <div class="p-4 bg-red-100 rounded">
                    <span class="font-semibold">Total Salidas:</span> {{ number_format($totalSalidas, 2) }}
                </div>
                <div class="p-4 bg-gray-200 rounded">
                    <span class="font-semibold">Total Restante:</span> {{ number_format($totalRestante, 2) }}
                </div>
            </div>
        </div>


    </div>
</div>
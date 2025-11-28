<div class="p-4 bg-gray-50 min-h-[auto] mt-10">
    <div class="max-w-7xl mx-auto bg-white shadow-lg rounded-2xl p-4 sm:p-6">
        <h2 class="text-2xl sm:text-3xl font-bold text-center text-teal-700 mb-6 uppercase">
            Reporte de Stock y Asignaciones
        </h2>

        <div class="flex flex-col items-center gap-4 mb-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full sm:w-4/5 mx-auto">

                <div class="flex flex-col gap-4">
                    <input type="text" wire:model.live="search" placeholder="Buscar código..."
                        class="border p-2 rounded-lg w-full text-center">

                    <div>
                        <label class="font-semibold text-sm mb-2 block text-center">Personal</label>
                        <div
                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 max-h-[200px] overflow-y-auto">
                            <button type="button" wire:click="$set('personal_id', '')"
                                class="w-full p-3 rounded-lg border-2 transition flex flex-col items-center text-center
                                {{ $personal_id == '' ? 'border-cyan-600 text-cyan-600 bg-cyan-50' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 hover:bg-cyan-50' }}">
                                <span class="font-medium text-sm">Mostrar todo</span>
                            </button>
                            @forelse($personales as $per)
                                <button type="button" wire:click="$set('personal_id', {{ $per->id }})"
                                    class="w-full p-3 rounded-lg border-2 transition flex flex-col items-center text-center
                                            {{ $personal_id == $per->id ? 'border-cyan-600 text-cyan-600 bg-cyan-50' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 hover:bg-cyan-50' }}">
                                    <span class="font-medium text-sm">{{ $per->nombres }}</span>
                                </button>
                            @empty
                                <p class="text-gray-500 text-sm text-center py-2 col-span-full">
                                    No hay personal disponible
                                </p>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-2 block text-center">Existencias</label>
                        <div
                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 max-h-[200px] overflow-y-auto">
                            <button type="button" wire:click="$set('search_desc', '')"
                                class="w-full p-3 rounded-lg border-2 transition flex flex-col items-center text-center
                                {{ $search_desc == '' ? 'border-cyan-600 text-cyan-600 bg-cyan-50' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 hover:bg-cyan-50' }}">
                                <span class="font-medium text-sm">Mostrar todo</span>
                            </button>

                            @foreach($existencias as $existencia)
                                <button type="button" wire:click="$set('search_desc', '{{ $existencia }}')"
                                    class="w-full p-3 rounded-lg border-2 transition flex flex-col items-center text-center
                                        {{ $search_desc == $existencia ? 'border-cyan-600 text-cyan-600 bg-cyan-50' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 hover:bg-cyan-50' }}">
                                    <span class="font-medium text-sm">{{ $existencia }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <div>
                        <label class="font-semibold w-full text-center mb-1">Fecha Inicio:</label>
                        <div class="flex flex-wrap justify-center gap-2">
                            <input type="number" min="1" max="31" wire:model.live="fecha_inicio_dia" placeholder="Día"
                                class="w-16 p-2 rounded text-center border">
                            <input type="number" min="1" max="12" wire:model.live="fecha_inicio_mes" placeholder="Mes"
                                class="w-16 p-2 rounded text-center border">
                            <input type="number" min="2000" max="2100" wire:model.live="fecha_inicio_ano"
                                placeholder="Año" class="w-20 p-2 rounded text-center border">
                            <input type="number" min="0" max="23" wire:model.live="fecha_inicio_hora" placeholder="Hora"
                                class="w-16 p-2 rounded text-center border">
                            <input type="number" min="0" max="59" wire:model.live="fecha_inicio_min"
                                placeholder="Minuto" class="w-16 p-2 rounded text-center border">
                        </div>
                    </div>

                    <div>
                        <label class="font-semibold w-full text-center mb-1">Fecha Fin:</label>
                        <div class="flex flex-wrap justify-center gap-2">
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
                    </div>

                    <div class="flex justify-center gap-4 mb-4">
                        <button type="button" wire:click="$toggle('ocultar_cantidad')"
                            class="px-4 py-2 rounded-lg border transition
        {{ $ocultar_cantidad ? 'bg-cyan-600 text-white border-cyan-600' : 'bg-white text-gray-800 border-gray-300 hover:bg-cyan-50 hover:text-cyan-600' }}">
                            {{ $ocultar_cantidad ? 'Mostrar Cantidad' : 'Ocultar Cantidad' }}
                        </button>

                        <button type="button" wire:click="$toggle('ocultar_monto')"
                            class="px-4 py-2 rounded-lg border transition
        {{ $ocultar_monto ? 'bg-cyan-600 text-white border-cyan-600' : 'bg-white text-gray-800 border-gray-300 hover:bg-cyan-50 hover:text-cyan-600' }}">
                            {{ $ocultar_monto ? 'Mostrar Monto' : 'Ocultar Monto' }}
                        </button>

                        <div class="flex justify-center gap-4 mb-6">
                            <button type="button" wire:click="descargarPDF"
                                class="px-4 py-2 rounded-lg border bg-cyan-600 text-white hover:bg-cyan-700 transition">
                                Descargar PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden md:block overflow-x-auto rounded-xl border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 table-auto">
                <thead class="bg-teal-100">
                    <tr>
                        <th class="px-2 sm:px-4 py-2 text-left text-sm sm:text-base font-medium text-gray-700">Código
                        </th>
                        <th class="px-2 sm:px-4 py-2 text-left text-sm sm:text-base font-medium text-gray-700">Fecha
                        </th>
                        @if(!$ocultar_cantidad)
                            <th class="px-2 sm:px-4 py-2 text-left text-sm sm:text-base font-medium text-gray-700">Cantidad
                            </th>
                        @endif
                        <th class="px-2 sm:px-4 py-2 text-left text-sm sm:text-base font-medium text-gray-700">Proveedor
                        </th>
                        <th class="px-2 sm:px-4 py-2 text-left text-sm sm:text-base font-medium text-gray-700">Material
                            / Descripción</th>
                        @if(!$ocultar_monto)
                            <th class="px-2 sm:px-4 py-2 text-left text-sm sm:text-base font-medium text-gray-700">Monto
                            </th>
                        @endif
                        <th class="px-2 sm:px-4 py-2 text-left text-sm sm:text-base font-medium text-gray-700">Precio
                            Unidad</th>
                        <th class="px-2 sm:px-4 py-2 text-left text-sm sm:text-base font-medium text-gray-700">Personal
                        </th>
                        <th class="px-2 sm:px-4 py-2 text-left text-sm sm:text-base font-medium text-gray-700">Sucursal
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm sm:text-base">
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

                        <tr class="bg-gray-100 font-bold">
                            <td class="px-2 sm:px-4 py-2">{{ $reposicion->codigo }}</td>
                            <td class="px-2 sm:px-4 py-2">{{ $reposicion->fecha }}</td>
                            @if(!$ocultar_cantidad)
                                <td class="px-2 sm:px-4 py-2">{{ $reposicion->cantidad_inicial }}</td>
                            @endif
                            <td class="px-2 sm:px-4 py-2">{{ $reposicion->proveedor->razonSocial ?? '-' }}</td>
                            <td class="px-2 sm:px-4 py-2">{{ $material_desc }}</td>
                            @if(!$ocultar_monto)
                                <td class="px-2 sm:px-4 py-2">{{ number_format($total_monto, 2) }}</td>
                            @endif
                            <td class="px-2 sm:px-4 py-2">{{ number_format($precio_por_unidad, 2) }}</td>
                            <td class="px-2 sm:px-4 py-2">{{ $reposicion->personal->nombres ?? '-' }}</td>
                            <td class="px-2 sm:px-4 py-2">{{ $existencia_repo?->sucursal->nombre ?? '-' }}</td>
                        </tr>

                        @foreach($reposicion->asignados as $asignado)
                            @if($personal_id && $asignado->personal_id != $personal_id)
                                @continue
                            @endif
                            @php
                                $detalle = $asignado->asignadoReposicions->where('reposicion_id', $reposicion->id)->first();
                                if (!$detalle)
                                    continue;
                                $cantidad_asignada = $detalle->cantidad_original ?? 0;
                                $monto_asignacion = $cantidad_asignada * $precio_por_unidad;
                                $stock_restante -= $cantidad_asignada;
                                $monto_restante -= $monto_asignacion;
                                $tipo_material_asig = $detalle->existencia?->existenciable_type ? class_basename($detalle->existencia->existenciable_type) : '-';
                                $material_asignado = $tipo_material_asig . ' - ' . ($detalle->existencia?->existenciable->descripcion ?? $detalle->existencia?->descripcion ?? '-');
                            @endphp
                            <tr class="bg-white">
                                <td class="px-4 py-2 text-sm sm:text-base">→ {{ $asignado->codigo }}</td>
                                <td class="px-2 sm:px-4 py-2">{{ $asignado->fecha }}</td>
                                @if(!$ocultar_cantidad)
                                    <td class="px-2 sm:px-4 py-2">{{ $cantidad_asignada }}</td>
                                @endif
                                <td class="px-2 sm:px-4 py-2">{{ $asignado->proveedor?->razonSocial ?? '-' }}</td>
                                <td class="px-2 sm:px-4 py-2">{{ $material_asignado }}</td>
                                @if(!$ocultar_monto)
                                    <td class="px-2 sm:px-4 py-2">{{ number_format($monto_asignacion, 2) }}</td>
                                @endif
                                <td class="px-2 sm:px-4 py-2">{{ number_format($precio_por_unidad, 2) }}</td>
                                <td class="px-2 sm:px-4 py-2">{{ $asignado->personal->nombres ?? '-' }}</td>
                                <td class="px-2 sm:px-4 py-2">{{ $detalle->existencia?->sucursal->nombre ?? '-' }}</td>
                            </tr>
                        @endforeach

                        <tr class="bg-teal-200 font-bold">
                            <td class="px-2 sm:px-4 py-2">STOCK RESTANTE</td>
                            <td class="px-2 sm:px-4 py-2">-</td>
                            @if(!$ocultar_cantidad)
                                <td class="px-2 sm:px-4 py-2">{{ $stock_restante }}</td>
                            @endif
                            <td class="px-2 sm:px-4 py-2">-</td>
                            <td class="px-2 sm:px-4 py-2">-</td>
                            @if(!$ocultar_monto)
                                <td class="px-2 sm:px-4 py-2">{{ number_format($monto_restante, 2) }}</td>
                            @endif
                            <td class="px-2 sm:px-4 py-2">-</td>
                            <td class="px-2 sm:px-4 py-2">-</td>
                            <td class="px-2 sm:px-4 py-2">-</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="md:hidden flex flex-col gap-6">
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

                <div class="bg-white shadow rounded-2xl p-4 border border-gray-200">
                    <div class="flex flex-col gap-2">
                        <div><span class="font-bold">Código:</span> {{ $reposicion->codigo }}</div>
                        <div><span class="font-bold">Fecha:</span> {{ $reposicion->fecha }}</div>
                        @if(!$ocultar_cantidad)
                            <div><span class="font-bold">Cantidad:</span> {{ $reposicion->cantidad_inicial }}</div>
                        @endif
                        <div><span class="font-bold">Proveedor:</span> {{ $reposicion->proveedor->razonSocial ?? '-' }}
                        </div>
                        <div><span class="font-bold">Material / Descripción:</span> {{ $material_desc }}</div>
                        @if(!$ocultar_monto)
                            <div><span class="font-bold">Monto:</span> {{ number_format($total_monto, 2) }}</div>
                        @endif
                        <div><span class="font-bold">Precio Unidad:</span> {{ number_format($precio_por_unidad, 2) }}</div>
                        <div><span class="font-bold">Personal:</span> {{ $reposicion->personal->nombres ?? '-' }}</div>
                        <div><span class="font-bold">Sucursal:</span> {{ $existencia_repo?->sucursal->nombre ?? '-' }}</div>
                    </div>

                    @foreach($reposicion->asignados as $asignado)
                        @if($personal_id && $asignado->personal_id != $personal_id)
                            @continue
                        @endif
                        @php
                            $detalle = $asignado->asignadoReposicions->where('reposicion_id', $reposicion->id)->first();
                            if (!$detalle)
                                continue;
                            $cantidad_asignada = $detalle->cantidad_original ?? 0;
                            $monto_asignacion = $cantidad_asignada * $precio_por_unidad;
                            $stock_restante -= $cantidad_asignada;
                            $monto_restante -= $monto_asignacion;
                            $tipo_material_asig = $detalle->existencia?->existenciable_type ? class_basename($detalle->existencia->existenciable_type) : '-';
                            $material_asignado = $tipo_material_asig . ' - ' . ($detalle->existencia?->existenciable->descripcion ?? $detalle->existencia?->descripcion ?? '-');
                        @endphp

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-3 mt-2 ml-4">
                            <div class="flex flex-col gap-1">
                                <div><span class="font-bold">Código:</span> → {{ $asignado->codigo }}</div>
                                <div><span class="font-bold">Fecha:</span> {{ $asignado->fecha }}</div>
                                @if(!$ocultar_cantidad)
                                    <div><span class="font-bold">Cantidad:</span> {{ $cantidad_asignada }}</div>
                                @endif
                                <div><span class="font-bold">Proveedor:</span> {{ $asignado->proveedor?->razonSocial ?? '-' }}
                                </div>
                                <div><span class="font-bold">Material:</span> {{ $material_asignado }}</div>
                                @if(!$ocultar_monto)
                                    <div><span class="font-bold">Monto:</span> {{ number_format($monto_asignacion, 2) }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <div class="bg-teal-100 rounded-xl p-3 mt-2 flex flex-col gap-1">
                        @if(!$ocultar_cantidad)
                            <div><span class="font-bold">STOCK RESTANTE:</span> {{ $stock_restante }}</div>
                        @endif
                        @if(!$ocultar_monto)
                            <div><span class="font-bold">MONTO RESTANTE:</span> {{ number_format($monto_restante, 2) }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
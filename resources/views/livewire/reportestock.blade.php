<div class="p-4 mt-20 flex flex-col items-center bg-white">
    <div class="w-full max-w-screen-xl mb-4 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        {{-- Filtros de fecha --}}
        <div class="flex gap-2 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                <input type="date" wire:model="fechaInicio" class="border px-2 py-1 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                <input type="date" wire:model="fechaFinal" class="border px-2 py-1 rounded-md">
            </div>
            <div>
                <button wire:click="$refresh" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Filtrar</button>
            </div>
            <div>
                <button wire:click="limpiarFiltros" class="bg-gray-300 px-4 py-2 rounded-md hover:bg-gray-400">Limpiar</button>
            </div>
        </div>

        {{-- Búsqueda por código --}}
        <div>
            <input type="text" wire:model="search" placeholder="Buscar por código..." class="border px-2 py-1 rounded-md">
        </div>
    </div>

    {{-- Tabla de Movimientos --}}
    <div class="w-full max-w-screen-xl overflow-x-auto">
        <h2 class="text-2xl font-semibold mb-4">Reporte de Movimientos (Reposiciones y Asignados)</h2>

        <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Código</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Fecha</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Tipo</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Artículo</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Entrada / Salida</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Personal</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Proveedor / Motivo</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Monto</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">

                {{-- Reposiciones (Entradas) --}}
                @foreach($reposiciones as $reposicion)
                <tr>
                    <td class="px-4 py-2">{{ $reposicion->codigo }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($reposicion->fecha)->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 text-blue-600 font-semibold uppercase">entrada</td>
                    <td class="px-4 py-2">{{ $reposicion->existencia->existenciable->descripcion ?? 'N/A' }}</td>
                    <td class="px-4 py-2 text-green-700 font-semibold">{{ $reposicion->cantidad }}</td>
                    <td class="px-4 py-2">{{ $reposicion->personal->nombres ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $reposicion->proveedor->razonSocial ?? 'Sin proveedor' }}</td>
                    <td class="px-4 py-2">
                        {{ $reposicion->comprobantes->sum('monto') ?? '-' }}
                    </td>
                </tr>
                @endforeach

                {{-- Asignados (Salidas) --}}
                @foreach($asignados as $asignado)
                <tr>
                    <td class="px-4 py-2">{{ $asignado->codigo }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($asignado->fecha)->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 text-red-600 font-semibold uppercase">salida</td>
                    <td class="px-4 py-2">{{ $asignado->existencia->existenciable->descripcion ?? 'N/A' }}</td>
                    <td class="px-4 py-2 text-red-700 font-semibold">-{{ $asignado->cantidad }}</td>
                    <td class="px-4 py-2">{{ $asignado->personal->nombres ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $asignado->motivo ?? '-' }}</td>
                    <td class="px-4 py-2 text-gray-400 text-sm">No aplica</td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>

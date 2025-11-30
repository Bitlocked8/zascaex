<div class="p-4 bg-gray-50 min-h-screen mt-20">
    <h2 class="text-2xl font-bold mb-6 text-center text-teal-700">
        Reporte de Distribución
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

            <select wire:model.live="coche_id" class="border p-2 rounded w-40 text-center">
                <option value="">Todos los vehículos</option>
                @foreach($coches as $coche)
                    <option value="{{ $coche->id }}">{{ $coche->placa }}</option>
                @endforeach
            </select>

            <select wire:model.live="personal_id" class="border p-2 rounded w-40 text-center">
                <option value="">Todo el personal</option>
                @foreach($personales as $personal)
                    <option value="{{ $personal->id }}">{{ $personal->nombres }}</option>
                @endforeach
            </select>

            <select wire:model.live="sucursal_id" class="border p-2 rounded w-40 text-center">
                <option value="">Todas las sucursales</option>
                @foreach($sucursales as $sucursal)
                    <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                @endforeach
            </select>

            <select wire:model.live="estado" class="border p-2 rounded w-40 text-center">
                <option value="">Todos los estados</option>
                <option value="0">Pendiente</option>
                <option value="1">Entregado</option>
            </select>
        </div>
        <div class="flex justify-center mb-6">
            <button wire:click="descargarPDF"
                class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 transition">
                Descargar PDF
            </button>
        </div>
    </div>


    <div class="overflow-x-auto mb-10">
        <h3 class="text-xl font-semibold mb-2 text-center">Distribuciones</h3>

        <table class="min-w-full bg-white border rounded text-center">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">Código</th>
                    <th class="px-4 py-2 border">Fecha Asignación</th>
                    <th class="px-4 py-2 border">Fecha Entrega</th>
                    <th class="px-4 py-2 border">Personal</th>
                    <th class="px-4 py-2 border">Sucursal</th>
                    <th class="px-4 py-2 border">Vehículo</th>
                    <th class="px-4 py-2 border">Estado</th>
                    <th class="px-4 py-2 border">Pedidos</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($distribuciones as $dist)
                    <tr class="text-sm">
                        <td class="px-2 py-1 border">{{ $dist->codigo }}</td>
                        <td class="px-2 py-1 border">
                            {{ $dist->fecha_asignacion ? date('d/m/Y H:i', strtotime($dist->fecha_asignacion)) : 'N/D' }}
                        </td>
                        <td class="px-2 py-1 border">
                            {{ $dist->fecha_entrega ? date('d/m/Y H:i', strtotime($dist->fecha_entrega)) : 'N/D' }}
                        </td>

                        <td class="px-2 py-1 border">{{ $dist->personal->nombres ?? 'N/A' }}</td>

                        <td class="px-2 py-1 border">
                            {{ $dist->personal->trabajos->first()?->sucursal->nombre ?? 'N/A' }}
                        </td>

                        <td class="px-2 py-1 border">{{ $dist->coche->placa ?? 'N/A' }}</td>

                        <td class="px-2 py-1 border">
                            @if($dist->estado == 0)
                                <span class="px-2 py-1 rounded bg-gray-500 text-white text-xs">Pendiente</span>
                            @elseif($dist->estado == 1)
                                <span class="px-2 py-1 rounded bg-green-600 text-white text-xs">Entregado</span>
                            @endif
                        </td>

                        <td class="px-2 py-1 border">
                            @foreach($dist->pedidos as $pedido)
                                <div class="text-xs bg-gray-100 rounded p-1 my-1">
                                    {{ $pedido->codigo }}
                                </div>
                            @endforeach
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-500 py-2">
                            No hay distribuciones registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="overflow-x-auto mb-6">
        <h3 class="text-xl font-semibold mb-2 text-center">Pedidos asignados</h3>

        <table class="min-w-full bg-white border rounded text-center">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">Código Pedido</th>
                    <th class="px-4 py-2 border">Estado Pedido</th>
                    <th class="px-4 py-2 border">Producto</th>
                    <th class="px-4 py-2 border">Cantidad</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $todosPedidos = $distribuciones->flatMap->pedidos;
                @endphp

                @forelse($todosPedidos as $pedido)
                    @foreach($pedido->detalles as $detalle)
                        <tr class="text-sm">
                            <td class="px-2 py-1 border">{{ $pedido->codigo }}</td>

                            <td class="px-2 py-1 border">
                                @if($pedido->estado_pedido == 0)
                                    <span class="px-2 py-1 rounded bg-cyan-600 text-white text-xs">Preparando</span>
                                @elseif($pedido->estado_pedido == 1)
                                    <span class="px-2 py-1 rounded bg-yellow-500 text-white text-xs">En revisión</span>
                                @elseif($pedido->estado_pedido == 2)
                                    <span class="px-2 py-1 rounded bg-emerald-600 text-white text-xs">Completado</span>
                                @endif
                            </td>

                            <td class="px-2 py-1 border">
                                {{ $detalle->existencia?->existenciable?->descripcion ?? 'N/A' }}
                                @if(!empty($detalle->existencia?->existenciable?->tipoContenido))
                                    <div class="text-sm text-gray-500">
                                        Tipo: {{ $detalle->existencia->existenciable->tipoContenido }}
                                    </div>
                                @endif
                                <div class="text-sm text-gray-600 mt-1">
                                    Sucursal: {{ $detalle->existencia?->sucursal?->nombre ?? 'N/A' }}
                                </div>
                            </td>


                            <td class="px-2 py-1 border">{{ $detalle->cantidad }}</td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-500 py-2">
                            No hay pedidos asignados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
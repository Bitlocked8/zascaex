<div class="p-2 mt-20 flex justify-center bg-transparent">
    <div class="w-full max-w-screen-xl">

        <h3 class="text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto mb-4">
            Etiquetado
        </h3>

        <div class="flex items-center gap-2 mb-4 flex-wrap">

            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por código o pedido..."
                class="input-minimal w-full sm:w-auto flex-1" />

            <button wire:click="abrirModal('create')" class="btn-cyan flex items-center gap-1">
                Añadir
            </button>

            <button wire:click="$set('filtroEntrega', 'todos')"
                class="btn-cyan {{ $filtroEntrega === 'todos' ? 'bg-teal-600 text-white' : '' }}">
                Todos (recientes)
            </button>

            <button wire:click="$set('filtroEntrega', 'entregados')"
                class="btn-cyan {{ $filtroEntrega === 'entregados' ? 'bg-teal-600 text-white' : '' }}">
                Entregados
            </button>

            <button wire:click="$set('filtroEntrega', 'no_entregados')"
                class="btn-cyan {{ $filtroEntrega === 'no_entregados' ? 'bg-teal-600 text-white' : '' }}">
                No entregados
            </button>
        </div>


        <div class="overflow-auto max-h-[500px] border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-teal-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Código</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Pedido</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Observaciones</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Estado</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Etiqueta</th>
                        <th class="px-4 py-2 text-center text-teal-700 font-semibold">Acciones</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($adornados as $adornado)
                    <tr class="hover:bg-teal-50">
                        <td class="px-4 py-2">{{ $adornado->codigo ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $adornado->pedido->codigo ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $adornado->observaciones ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            <span class="{{ $adornado->pedido->estado_pedido == 0 ? 'text-yellow-600' : '' }} {{ $adornado->pedido->estado_pedido == 1 ? 'text-blue-600' : '' }} {{ $adornado->pedido->estado_pedido == 2 ? 'text-green-600' : '' }}">
                                {{ $adornado->pedido->estado_pedido == 0 ? 'Preparación' : ($adornado->pedido->estado_pedido == 1 ? 'En espera de pago' : 'Entregado') }}
                            </span>
                        </td>


                        <td class="px-4 py-2">
                            @if($adornado->reposiciones->isNotEmpty())
                            <div class="font-semibold">
                                {{ $adornado->reposiciones->pluck('existencia.existenciable.descripcion')->join(', ') }}
                            </div>

                            <div class="mt-1 text-xs text-gray-500">
                                Cliente etiqueta:
                                {{ $adornado->reposiciones->first()->existencia->existenciable->cliente->nombre ?? 'Sin cliente' }}
                            </div>
                            @else
                            N/A
                            @endif
                        </td>

                        <td class="px-4 py-2 flex justify-center gap-1">
                            <button wire:click="abrirModal('edit', {{ $adornado->id }})" class="btn-cyan">
                                Editar
                            </button>
                            <button wire:click="$set('confirmingDeleteAdornadoId', {{ $adornado->id }})" class="btn-cyan">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-600">
                            No hay adornados registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>



    @if($modal)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">

                @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">¡Atención!</strong>
                    <span class="block sm:inline">Debes corregir los siguientes errores:</span>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="grid grid-cols-1 gap-2 mt-2">
                    <p class="font-semibold text-sm">
                        <span class="text-u">{{ $codigo }}</span>
                    </p>
                    <div>
                        <label class="text-u font-semibold mb-2 block">Pedido (Requerido)</label>
                        @if($accion === 'edit')

                        @php

                        $pedidoActual = $pedidos->firstWhere('id', $pedido_id);
                        @endphp

                        <div class="flex flex-col gap-4 p-4 rounded-lg border-2 bg-white text-gray-800">

                            <div
                                class="flex flex-col gap-2 p-4 rounded-lg border-2 bg-white text-gray-800 items-center">

                                <span
                                    class="font-medium text-lg text-center">{{ $pedidoActual->codigo ?? ('Pedido #' . $pedido_id) }}</span>

                                <span class="text-xs font-semibold text-white bg-teal-600 px-3 py-1 rounded-full">
                                    Cliente: {{ $pedidoActual->solicitudPedido?->cliente?->nombre ?? 'Sin cliente' }}
                                </span>

                                <span class="text-xs font-semibold text-center">
                                    Personal: {{ $pedidoActual->personal?->nombres ?? 'Sin personal' }}
                                </span>

                                <span class="text-xs font-semibold text-u">
                                    Fecha: {{ optional($pedidoActual->created_at)->format('d/m/Y') ?? 'N/A' }}
                                </span>

                                <span
                                    class="text-xs font-semibold text-center  {{ $pedidoActual->estado_pedido == 0 ? 'text-blue-700' : ($pedidoActual->estado_pedido == 1 ? 'text-yellow-500' : 'text-green-600') }}">
                                    {{ $pedidoActual->estado_pedido == 0 ? 'Preparando' : ($pedidoActual->estado_pedido == 1 ? 'En Revisión' : 'Completado') }}
                                </span>

                            </div>

                            @if($pedidoActual->solicitudPedido?->detalles->count())
                            <div class="mt-3 w-full bg-gray-50 rounded-lg p-2 space-y-2">
                                <p class="text-xs font-semibold text-gray-700">Detalles del Pedido:</p>

                                @foreach($pedidoActual->solicitudPedido->detalles as $detalle)
                                @php
                                $item = $detalle->producto ?? $detalle->otro;
                                $itemDescripcion = $item?->descripcion ?? '-';

                                $tapaDescripcion = $detalle->tapa?->descripcion ?? '-';
                                $tapaImagen = $detalle->tapa?->imagen ?? null;
                                $tapaSucursal = $detalle->tapa?->existencias->first()?->sucursal?->nombre ?? '-';

                                $etiquetaDescripcion = $detalle->etiqueta?->descripcion ?? '-';
                                $etiquetaImagen = $detalle->etiqueta?->imagen ?? null;
                                $etiquetaSucursal = $detalle->etiqueta?->existencias->first()?->sucursal?->nombre ?? '-';

                                $sucursalNombre = $item?->existencias->first()?->sucursal?->nombre ?? '-';
                                $contenido = $item?->descripcion ?? '-';
                                $paquete = $item?->paquete ?? 1;

                                $totalUnidades = $detalle->cantidad * $paquete;
                                @endphp

                                <div class="bg-white border border-gray-200 rounded-lg p-3 text-gray-700 text-xs">
                                    <div>
                                        <p class="font-semibold mb-2">{{ $itemDescripcion }} x
                                            <span class="text-cyan-700">{{ $detalle->cantidad }} paquetes</span>
                                        </p>
                                        <p>Sucursal del Producto: {{ $sucursalNombre }}</p>
                                        <p>Contenido: {{ $contenido }} | Paquete: {{ $paquete }} unidades</p>
                                        <p class="font-semibold text-cyan-700 mt-1">Total unidades: {{ $totalUnidades }}
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                                        <div class="flex flex-col items-center">
                                            <p class="font-semibold text-center">{{ $tapaDescripcion }}</p>
                                            @if($tapaImagen)
                                            <img src="{{ asset('storage/' . $tapaImagen) }}"
                                                alt="{{ $tapaDescripcion }}"
                                                class="w-32 h-32 sm:w-32 sm:h-32 rounded-lg border border-gray-300 mb-1 object-contain">
                                            @endif
                                            <span class="text-gray-500 text-xs text-center">Sucursal:
                                                {{ $tapaSucursal }}</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <p class="font-semibold text-center">{{ $etiquetaDescripcion }}</p>
                                            @if($etiquetaImagen)
                                            <img src="{{ asset('storage/' . $etiquetaImagen) }}"
                                                alt="{{ $etiquetaDescripcion }}"
                                                class="w-32 h-32 sm:w-32 sm:h-32 rounded-lg border border-gray-300 mb-1 object-contain">
                                            @endif
                                            <span class="text-gray-500 text-xs text-center">Sucursal:
                                                {{ $etiquetaSucursal }}</span>
                                        </div>
                                    </div>

                                </div>
                                @endforeach

                            </div>
                            @endif
                            @else
                            <div class="mb-4">
                                <input type="text" wire:model.live="searchPedido" class="input-minimal w-full"
                                    placeholder="Buscar código o cliente..." />
                            </div>
                            <div
                                class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 sm:grid-cols-2 gap-2 overflow-y-auto max-h-[300px]">
                                @forelse($pedidos as $pedido)
                                @php
                                $estadoColor = match ($pedido->estado_pedido) {
                                0 => 'bg-blue-700 text-white',
                                1 => 'bg-yellow-500 text-white',
                                2 => 'bg-green-600 text-white',
                                default => 'bg-gray-400 text-white',
                                };
                                @endphp

                                <button type="button" wire:click="$set('pedido_id', {{ $pedido->id }})"
                                    class="w-full p-4 rounded-lg border-2 transition flex flex-col items-start text-left
                                                                                                                                                                                                                                                                                                        {{ $pedido_id == $pedido->id ? 'border-cyan-600 text-cyan-600 bg-cyan-50' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }} bg-white">

                                    <span class="font-semibold">{{ $pedido->codigo }}</span>
                                    <span class="text-sm text-gray-700">Cliente:
                                        {{ $pedido->solicitudPedido?->cliente?->nombre ?? 'N/A' }}</span>
                                    <span class="text-sm text-gray-700">Personal:
                                        {{ $pedido->personal?->nombres ?? 'N/A' }}</span>
                                    <span class="text-u">Fecha:
                                        {{ optional($pedido->created_at)->format('d/m/Y') ?? 'N/A' }}</span>
                                    <span class="mt-1 px-2 py-1 rounded-full text-xs font-semibold {{ $estadoColor }}">
                                        {{ $pedido->estado_pedido == 0 ? 'Preparando' : ($pedido->estado_pedido == 1 ? 'En Revisión' : 'Completado') }}
                                    </span>
                                    <span class="text-xs text-gray-500 mt-1">Observaciones:
                                        {{ $pedido->solicitudPedido?->observaciones ?? 'Ninguna' }}
                                    </span>

                                    @if($pedido->solicitudPedido?->detalles->count())
                                    <div class="mt-2 space-y-1">
                                        @foreach($pedido->solicitudPedido->detalles as $detalle)
                                        @php
                                        $item = $detalle->producto ?? $detalle->otro;
                                        $itemDescripcion = $item?->descripcion ?? '-';
                                        $itemSucursal = $item?->existencias->first()?->sucursal?->nombre ?? 'N/A';

                                        $paquete = $item?->paquete ?? 1;
                                        $totalUnidades = $detalle->cantidad * $paquete;

                                        $tapaDescripcion = $detalle->tapa?->descripcion ?? '-';
                                        $tapaSucursal = $detalle->tapa?->existencias->first()?->sucursal?->nombre ?? '-';

                                        $etiquetaDescripcion = $detalle->etiqueta?->descripcion ?? '-';
                                        $etiquetaSucursal = $detalle->etiqueta?->existencias->first()?->sucursal?->nombre ?? '-';
                                        @endphp

                                        <p>
                                            Producto/Otro Sucursal: {{ $itemSucursal }}<br>
                                            <span class="font-semibold">{{ $itemDescripcion }}</span> x
                                            {{ $detalle->cantidad }} paquetes
                                            (Paquete: {{ $paquete }} unidades | Total: {{ $totalUnidades }}
                                            unidades)
                                        </p>
                                        <p>

                                            Tapa: {{ $tapaDescripcion }} | Sucursal: {{ $tapaSucursal }}
                                            <br>
                                            Etiqueta: {{ $etiquetaDescripcion }} | Sucursal: {{ $etiquetaSucursal }}
                                        </p>
                                        @endforeach
                                    </div>
                                    @endif

                                </button>

                                @empty
                                <p class="text-gray-500 text-sm text-center py-2 col-span-full">No hay pedidos
                                    disponibles</p>
                                @endforelse
                            </div>


                            @endif
                        </div>


                        <div>
                            <label class="font-semibold text-sm">Observaciones (Opcional)</label>
                            <input type="text" wire:model="observaciones" class="input-minimal w-full"
                                placeholder="Escribe alguna observación...">
                        </div>

                        <div class="mt-2">
                            <label class="text-u">Reposiciones de Etiquetas disponibles (requerido)</label>

                            <div
                                class="grid grid-cols-1 gap-2 mt-1 max-h-[250px] overflow-y-auto p-2 border border-gray-300 rounded-xl bg-gray-50 shadow-inner">

                                @forelse($reposiciones as $repo)
                                @php
                                $seleccionado = isset($reposicionesSeleccionadas[$repo->id]);
                                @endphp

                                <div
                                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-white border border-cyan-300 rounded-xl p-3 shadow-sm hover:shadow-md transition">

                                    <div class="flex-1">
                                        <h3 class="text-sm font-semibold text-cyan-800 mb-1">
                                            {{ $repo->existencia->existenciable->descripcion ?? 'Reposición #' . $repo->id }}
                                        </h3>
                                        <p class="text-xs text-gray-500">
                                            Cant. disponible: <span class="font-semibold">{{ $repo->cantidad }}</span>
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            Sucursal: <span
                                                class="font-semibold">{{ $repo->existencia->sucursal->nombre ?? 'Sin sucursal' }}</span>
                                        </p>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-2">
                                        @if($seleccionado)
                                        <h3 class="w-full text-xs font-semibold text-gray-700">Ingresar cantidad y merma
                                        </h3>
                                        <input type="number" min="0"
                                            wire:model.lazy="reposicionesSeleccionadas.{{ $repo->id }}.cantidad_usada"
                                            class="w-20 border border-cyan-600 rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-cyan-500"
                                            placeholder="Usada" />

                                        <input type="number" min="0"
                                            wire:model.lazy="reposicionesSeleccionadas.{{ $repo->id }}.merma"
                                            class="w-20 border border-red-600 rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-red-400"
                                            placeholder="Merma" />

                                        <button type="button" wire:click="toggleReposicion({{ $repo->id }})"
                                            class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs hover:bg-red-600 shadow-sm transition">
                                            Quitar
                                        </button>
                                        @else
                                        <button type="button" wire:click="toggleReposicion({{ $repo->id }})"
                                            class="bg-cyan-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-cyan-700 shadow-sm transition">
                                            Seleccionar
                                        </button>
                                        @endif
                                    </div>
                                </div>

                                @empty
                                <p class="text-gray-500 text-sm text-center py-2">
                                    No hay reposiciones revisadas de etiquetas disponibles.
                                </p>
                                @endforelse

                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" wire:click="cerrarModal" class="btn-cyan" title="Cerrar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                                <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
                            </svg>
                            CERRAR
                        </button>
                        <button type="button" wire:click="guardar" class="btn-cyan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" />
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M14 4l0 4l-6 0l0 -4" />
                            </svg>
                            Guardar
                        </button>
                    </div>

                </div>
            </div>
        </div>
        @endif



    </div>
<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3 class="inline-block bg-teal-700 text-white px-5 py-2 rounded-full text-xl font-bold uppercase shadow-md">
            Adornados
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="search" placeholder="Buscar por código o pedido..."
                class="input-minimal w-full" />
            <button wire:click="abrirModal" class="btn-cyan flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor">
                    <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
                Añadir
            </button>
        </div>

        @forelse($adornados as $adornado)
            <div class="card-teal flex flex-col gap-4">
                <div class="flex flex-col gap-1">
                    <p class="text-u">{{ $adornado->codigo ?? 'N/A' }}</p>
                    <p><strong>Pedido:</strong> {{ $adornado->pedido->codigo ?? 'N/A' }}</p>
                    <p><strong>Observaciones:</strong> {{ $adornado->observaciones ?? 'N/A' }}</p>

                    <div class="mt-2">
                        <p class="font-semibold text-sm mb-1">Reposiciones usadas:</p>
                        @forelse($adornado->reposiciones as $repo)
                            <div
                                class="bg-cyan-50 border border-cyan-300 rounded-lg px-3 py-1 mb-1 flex justify-between text-sm">
                                <span>{{ $repo->existencia->existenciable->descripcion ?? 'Reposición #' . $repo->id }}</span>
                                <span class="text-cyan-700 font-semibold">{{ $repo->pivot->cantidad_usada ?? 0 }}</span>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No se usaron reposiciones</p>
                        @endforelse
                    </div>
                </div>

                <div class="flex flex-wrap justify-center md:justify-center gap-2 border-t border-gray-200 pt-3 pb-2">
                    <button wire:click="abrirModal('edit', {{ $adornado->id }})" class="btn-cyan" title="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                            <path d="M16 5l3 3" />
                        </svg>
                        Editar
                    </button>

                    <button wire:click="modaldetalle({{ $adornado->id }})" class="btn-cyan flex items-center gap-1"
                        title="Ver detalles">
                        👁️ Detalles
                    </button>

                    <button wire:click="eliminar({{ $adornado->id }})" class="btn-cyan flex items-center gap-1"
                        title="Eliminar">
                        🗑️ Eliminar
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-4 text-gray-600">
                No hay adornados registrados.
            </div>
        @endforelse

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
                            <label class="text-u">Pedido (Requerido)</label>

                            @if($accion === 'edit')
                                @php
                                    $pedidoActual = $pedidos->firstWhere('id', $pedido_id);
                                @endphp

                                <p
                                    class="flex flex-col items-center gap-2 p-4 rounded-lg border-2 bg-white text-gray-800 text-center">
                                    <span class="font-medium">
                                        {{ $pedidoActual->codigo ?? ('Pedido #' . $pedido_id) }}
                                    </span>

                                    <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        Cliente: {{ $pedidoActual->cliente->nombre ?? 'Sin cliente' }}
                                    </span>

                                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        Estado:
                                        {{ $pedidoActual->estado_pedido == 0 ? 'Pendiente' : ($pedidoActual->estado_pedido == 1 ? 'Entregado' : 'Cancelado') }}
                                    </span>
                                </p>
                            @else
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar pedido</label>
                                    <input type="text" wire:model.live="searchPedido" class="input-minimal w-full"
                                        placeholder="Escribe el código o cliente..." />
                                </div>

                                <div
                                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 sm:grid-cols-2 gap-2 overflow-y-auto max-h-[300px]">
                                    @forelse($pedidos as $pedido)
                                        @php
                                            $estadoColor = match ($pedido->estado_pedido) {
                                                0 => 'bg-yellow-500 text-white',
                                                1 => 'bg-emerald-600 text-white',
                                                2 => 'bg-red-600 text-white',
                                                default => 'bg-gray-400 text-white',
                                            };
                                        @endphp

                                        <button type="button" wire:click="$set('pedido_id', {{ $pedido->id }})"
                                            class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center 
                                                                {{ $pedido_id == $pedido->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }} bg-white">
                                            <span class="text-u font-medium">
                                                {{ $pedido->codigo }}
                                            </span>

                                            <span class="text-sm font-semibold">
                                                Cliente: {{ $pedido->cliente->nombre ?? 'N/A' }}
                                            </span>

                                            <span class="text-sm font-semibold">
                                                Personal: {{ $pedido->personal->nombres ?? 'N/A' }}
                                            </span>

                                            <span class="mt-2 px-2 py-1 rounded-full text-xs font-semibold {{ $estadoColor }}">
                                                {{ $pedido->estado_pedido == 0 ? 'Pendiente' : ($pedido->estado_pedido == 1 ? 'Entregado' : 'Cancelado') }}
                                            </span>
                                        </button>
                                    @empty
                                        <p class="text-gray-500 text-sm text-center py-2 col-span-full">
                                            No hay pedidos disponibles
                                        </p>
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
                            <label class="font-semibold text-sm">Reposiciones de Etiquetas disponibles</label>

                            <div
                                class="grid grid-cols-1 gap-2 mt-1 max-h-[250px] overflow-y-auto p-2 border border-gray-300 rounded-xl bg-gray-50 shadow-inner">

                                @forelse($reposiciones as $repo)
                                    @php
                                        $seleccionado = isset($reposicionesSeleccionadas[$repo->id]);
                                    @endphp

                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-white border border-cyan-300 rounded-xl p-3 shadow-sm hover:shadow-md transition">

                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-cyan-800">
                                                {{ $repo->existencia->existenciable->descripcion ?? 'Reposición #' . $repo->id }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                Cant. disponible: <span class="font-semibold">{{ $repo->cantidad }}</span>
                                            </p>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2">
                                            @if($seleccionado)
                                                <div class="flex items-center gap-2">
                                                    <input type="number" min="0"
                                                        wire:model.lazy="reposicionesSeleccionadas.{{ $repo->id }}.cantidad_usada"
                                                        class="w-20 border border-cyan-300 rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-cyan-500"
                                                        placeholder="Usada" />

                                                    <input type="number" min="0"
                                                        wire:model.lazy="reposicionesSeleccionadas.{{ $repo->id }}.merma"
                                                        class="w-20 border border-red-300 rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-red-400"
                                                        placeholder="Merma" />

                                                    <button type="button" wire:click="toggleReposicion({{ $repo->id }})"
                                                        class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs hover:bg-red-600 shadow-sm transition">
                                                        Quitar
                                                    </button>
                                                </div>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path d="M10 10l4 4m0 -4l-4 4" />
                                <circle cx="12" cy="12" r="9" />
                            </svg>
                            CERRAR
                        </button>
                        <button type="button" wire:click="guardar" class="btn-cyan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
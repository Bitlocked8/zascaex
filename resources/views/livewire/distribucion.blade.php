<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3 class="inline-block bg-teal-700 text-white px-5 py-2 rounded-full text-xl font-bold uppercase shadow-md">
            Distribuciones
        </h3>


        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por código, personal o coche..."
                class="input-minimal w-full" />
            <button wire:click="$set('modalDistribucion', true)" class="btn-cyan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                    <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                </svg>
                añadir
            </button>
        </div>

        @forelse($distribuciones as $dist)
        <div class="card-teal">
            <div class="col-span-8 flex flex-col gap-1">
                <p><strong>Código:</strong> {{ $dist->codigo }}</p>
                <p><strong>Personal:</strong> {{ $dist->personal->nombres ?? 'N/A' }}</p>
                <p><strong>Coche:</strong> {{ $dist->coche->placa ?? 'N/A' }}</p>
                <p><strong>Pedidos:</strong> {{ $dist->pedidos->count() }}</p>
                <p><strong>Estado:</strong>
                    <span class="inline-block px-2 py-1 rounded-full text-sm font-semibold
                        {{ $dist->estado == 1 ? 'bg-emerald-600 text-white' : 'bg-gray-400 text-white' }}">
                        {{ $dist->estado == 1 ? 'Activa' : 'Finalizada' }}
                    </span>
                </p>
            </div>

            <div class="flex flex-col items-end gap-4 col-span-3">
                <button wire:click="editarDistribucion({{ $dist->id }})" class="btn-cyan" title="Editar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                        <path d="M16 5l3 3" />
                    </svg>
                    editar
                </button>
                <button wire:click="verPedidos({{ $dist->id }})" class="btn-circle btn-cyan" title="Ver pedidos">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16v16H4z" />
                    </svg>
                </button>
            </div>

        </div>
        @empty
        <div class="col-span-full text-center py-4 text-gray-600">
            No hay distribuciones registradas.
        </div>
        @endforelse

    </div>

    @if($modalDistribucion)
    <div class="modal-overlay">
        <div class="modal-box w-full max-w-3xl">
            <div class="modal-content flex flex-col gap-4">
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-4">
                    <div>
                        <span class="text-u">
                            Código: {{ $codigo ?? 'Se generará automáticamente' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-u">
                            Fecha asignado: {{ $fecha_asignacion ? \Carbon\Carbon::parse($fecha_asignacion)->format('d/m/Y H:i:s') : 'Se generará al guardar' }}
                        </span>
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Fecha de Entrega</label>
                        <div class="flex items-center gap-2">
                            <input
                                type="text"
                                wire:model.lazy="fecha_entrega"
                                class="input-minimal flex-1"
                                placeholder="MM/DD/YY HH:mm:ss">

                            <button
                                type="button"
                                wire:click="establecerFechaActual"
                                class="btn-cyan"
                                title="Usar fecha y hora actual">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                Ahora
                            </button>
                        </div>

                    </div>
                    <div class="text-center">
                        <label class="font-semibold text-sm mb-2 block">Estado</label>
                        <div class="flex justify-center gap-3">
                            <button type="button" wire:click="$set('estado', 1)"
                                class="btn-cyan {{ $estado == 1 ? 'ring-2 ring-cyan-200' : 'opacity-40' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <path d="M5 13l4 4L19 7" />
                                </svg>
                                en entrega
                            </button>

                            <button type="button" wire:click="$set('estado', 0)"
                                class="btn-cyan {{ $estado == 0 ? 'ring-2 ring-cyan-200' : 'opacity-40' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <path d="M18 6L6 18" />
                                    <path d="M6 6l12 12" />
                                </svg>
                                Finalizados
                            </button>
                        </div>
                    </div>

                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Personal</label>
                        <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-md p-2 bg-white">
                            <select wire:model="personal_id" class="input-minimal w-full bg-transparent border-0 focus:ring-0">
                                <option value="">-- Seleccionar --</option>
                                @foreach($personals as $p)
                                <option value="{{ $p->id }}">{{ $p->nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-2 block">Coche</label>
                        <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-md p-2 bg-white">
                            <select wire:model="coche_id" class="input-minimal w-full bg-transparent border-0 focus:ring-0">
                                <option value="">-- Seleccionar --</option>
                                @foreach($coches as $c)
                                <option value="{{ $c->id }}">{{ $c->placa }} — {{ $c->marca }} {{ $c->modelo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>


                <div class="mb-4">
                    <label class="font-semibold text-sm mb-2 block">Pedidos disponibles</label>
                    <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-md p-2 bg-white">
                        @php
                        $pedidosDisponibles = $pedidos->filter(fn($p) => !in_array($p->id, $pedidos_seleccionados))->values();
                        @endphp

                        @forelse($pedidosDisponibles as $pedido)
                        <div class="flex justify-between items-center mb-1">
                            <div class="flex flex-col">
                                <span class="text-u">{{ $pedido->cliente->nombre ?? 'Cliente N/A' }}</span>
                                <span class="text-gray-800 text-sm">{{ $pedido->codigo }}</span>
                            </div>
                            <button wire:click.prevent="agregarPedido({{ $pedido->id }})" class="btn-cyan">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                    <path d="M9 12h6" />
                                    <path d="M12 9v6" />
                                </svg>
                                Añadir
                            </button>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm text-center py-2">No hay pedidos disponibles</p>
                        @endforelse
                    </div>
                </div>
                <div class="mb-4">
                    <label class="font-semibold text-sm mb-2 block">Pedidos asignados</label>
                    <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-md p-2 bg-white">
                        @forelse($pedidosAsignados as $pedido)
                        <div class="flex justify-between items-center mb-1">
                            <div class="flex flex-col">
                                <span class="text-u">{{ $pedido->cliente->nombre ?? 'Cliente N/A' }}</span>
                                <span class="text-gray-800 text-sm">{{ $pedido->codigo }}</span>
                            </div>
                            <button type="button" wire:click="quitarPedido({{ $pedido->id }})" class="btn-cyan" title="Eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 7l16 0" />
                                    <path d="M10 11l0 6" />
                                    <path d="M14 11l0 6" />
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                            </button>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm text-center py-2">No hay pedidos asignados</p>
                        @endforelse
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold text-sm mb-1 block">Observaciones</label>
                        <textarea wire:model="observaciones" rows="3" class="input-minimal w-full" placeholder="sin observaciones"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button wire:click="cerrarModal" class="btn-cyan" title="Cerrar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M10 10l4 4m0 -4l-4 4" />
                            <circle cx="12" cy="12" r="9" />
                        </svg>
                        CERRAR
                    </button>

                    <button wire:click="guardarDistribucion" class="btn-cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
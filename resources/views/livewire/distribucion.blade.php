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
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                    <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                </svg>
                añadir
            </button>
        </div>

        @forelse($distribuciones as $dist)
        <div class="card-teal flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <p class="text-u">{{ $dist->codigo }}</p>
                <p><strong>Personal:</strong> {{ $dist->personal->nombres ?? 'N/A' }}</p>
                <p><strong>Coche:</strong> {{ $dist->coche->placa ?? 'N/A' }} — {{ $dist->coche->marca ?? '' }} {{ $dist->coche->modelo ?? '' }}</p>
                <p><strong>Pedidos asignados:</strong> {{ $dist->pedidos->count() }}</p>
                <p><strong>Estado:</strong>
                    <span class="inline-block px-2 py-1 rounded-full text-sm font-semibold
          {{ $dist->estado == 1 ? 'bg-emerald-600 text-white' : 'bg-gray-400 text-white' }}">
                        {{ $dist->estado == 1 ? 'Activa' : 'Finalizada' }}
                    </span>
                </p>
            </div>

            <div class="flex gap-2 overflow-x-auto no-scrollbar border-t border-gray-200 pt-3 pb-1 justify-start md:justify-between">
                <button wire:click="editarDistribucion({{ $dist->id }})"
                    class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Editar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                        <path d="M16 5l3 3" />
                    </svg>
                    Editar
                </button>

                <button wire:click="verPedidos({{ $dist->id }})"
                    class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Ver pedidos">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 4h16v16H4z" />
                    </svg>
                    Pedidos
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
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-4">
                    <div class="grid grid-cols-1 gap-2 mt-2">
                        <div>
                            <label class="font-semibold text-sm mb-2 block">Personal (requerido)</label>
                            <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[300px]">
                                @foreach($personals as $p)
                                <button type="button"
                                    wire:click="$set('personal_id', {{ $p->id }})"
                                    class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                       {{ $personal_id == $p->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}
                                          bg-white">
                                    <span class="font-semibold text-u">{{ $p->nombres }}</span>
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 gap-2 mt-2">
                        <div>
                            <label class="font-semibold text-sm mb-2 block">Coche (requerido)</label>
                            <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[500px]">
                                @foreach($coches as $c)
                                <button type="button"
                                    wire:click="$set('coche_id', {{ $c->id }})"
                                    class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                      {{ $coche_id == $c->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}
                                          bg-white">
                                    <span class="font-semibold text-u">{{ $c->placa }}</span>
                                    <span class="text-sm text-gray-600">{{ $c->marca }} {{ $c->modelo }}</span>
                                    @if($c->color)
                                    <span class="text-xs text-gray-500">Color: {{ $c->color }}</span>
                                    @endif
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>


                <div class="grid grid-cols-1 gap-2 mt-4">
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Pedidos disponibles</label>

                        @php
                        $pedidosDisponibles = $pedidos->filter(fn($p) => !in_array($p->id, $pedidos_seleccionados))->values();
                        @endphp

                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 overflow-y-auto max-h-[500px]">
                            @forelse($pedidosDisponibles as $pedido)
                            <div
                                class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                        hover:border-cyan-600 hover:text-cyan-600 border-gray-300 bg-white shadow-sm relative">
                                <span class="font-semibold text-u">{{ $pedido->cliente->nombre ?? 'Cliente N/A' }}</span>
                                <span class="text-xs text-gray-500 mt-1">Código: {{ $pedido->codigo }}</span>
                                <span class="text-xs text-gray-500 mt-1">Fecha: {{ $pedido->fecha_pedido ?? 'N/D' }}</span>
                                <br>
                                <button
                                    wire:click.prevent="agregarPedido({{ $pedido->id }})"
                                    class="btn-cyan">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                                        <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                                    </svg>
                                    añadir
                                </button>
                            </div>
                            @empty
                            <p class="text-gray-500 text-sm text-center py-2 col-span-full">
                                No hay pedidos disponibles
                            </p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 mt-4">
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Pedidos asignados</label>

                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 overflow-y-auto max-h-[500px]">
                            @forelse($pedidosAsignados as $pedido)
                            <div
                                class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                      hover:border-cyan-600 hover:text-cyan-600 border-gray-300 bg-white shadow-sm relative">
                                <span class="font-semibold text-u">{{ $pedido->cliente->nombre ?? 'Cliente N/A' }}</span>
                                <span class="text-xs text-gray-500 mt-1">Código: {{ $pedido->codigo }}</span>
                                <span class="text-xs text-gray-500 mt-1">Fecha: {{ $pedido->fecha_pedido ?? 'N/D' }}</span>

                                <button type="button" wire:click="quitarPedido({{ $pedido->id }})"
                                    class="absolute top-2 right-2 p-1.5 rounded-full bg-red-500 hover:bg-red-600 text-white shadow transition"
                                    title="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M18 6L6 18" />
                                        <path d="M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            @empty
                            <p class="text-gray-500 text-sm text-center py-2 col-span-full">
                                No hay pedidos asignados
                            </p>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-2 block">Observaciones</label>
                        <textarea wire:model="observaciones"
                            rows="3"
                            class="w-full border-2 border-gray-300 rounded-lg p-3 text-sm text-gray-800 bg-white focus:border-cyan-600 focus:ring-2 focus:ring-cyan-200 transition resize-none"
                            placeholder="Sin observaciones..."></textarea>
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
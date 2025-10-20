<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3 class="col-span-full text-center bg-cyan-700 text-white px-6 py-3 rounded-full text-3xl font-bold uppercase shadow-md">
            Distribuciones
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por código, personal o coche..."
                class="input-minimal w-full" />
            <button wire:click="$set('modalDistribucion', true)" class="btn-circle btn-cyan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 5v14m-7-7h14" />
                </svg>
            </button>
        </div>

        @forelse($distribuciones as $dist)
        <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
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
                <button wire:click="editarDistribucion({{ $dist->id }})" class="btn-circle btn-cyan" title="Editar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                    </svg>
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

                <!-- Información básica -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Código</label>
                        <span class="block w-full bg-gray-100 text-gray-700 border border-gray-300 rounded-md px-3 py-2">
                            {{ $codigo ?? 'Se generará automáticamente' }}
                        </span>
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Fecha de Asignación</label>
                        <input type="datetime-local"
                            wire:model="fecha_asignacion"
                            class="input-minimal w-full"
                            value="{{ $fecha_asignacion ? \Carbon\Carbon::parse($fecha_asignacion)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i') }}">
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Fecha de Entrega</label>
                        <input type="datetime-local"
                            wire:model="fecha_entrega"
                            class="input-minimal w-full"
                            value="{{ $fecha_entrega ? \Carbon\Carbon::parse($fecha_entrega)->format('Y-m-d\TH:i') : '' }}">
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Estado</label>
                        <select wire:model="estado" class="input-minimal w-full">
                            <option value="1">Activa</option>
                            <option value="0">Finalizada</option>
                        </select>
                    </div>
                </div>

                <!-- Personal y coche -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Personal</label>
                        <select wire:model="personal_id" class="input-minimal w-full">
                            <option value="">-- Seleccionar --</option>
                            @foreach($personals as $p)
                            <option value="{{ $p->id }}">{{ $p->nombres }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Coche</label>
                        <select wire:model="coche_id" class="input-minimal w-full">
                            <option value="">-- Seleccionar --</option>
                            @foreach($coches as $c)
                            <option value="{{ $c->id }}">{{ $c->placa }} — {{ $c->marca }} {{ $c->modelo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="mb-4">
                    <label class="font-semibold text-sm mb-1 block">Observaciones</label>
                    <textarea wire:model="observaciones" rows="3" class="input-minimal w-full"></textarea>
                </div>

                <!-- Pedidos disponibles -->
                <div class="mb-4">
                    <label class="font-semibold text-sm mb-2 block">Pedidos disponibles</label>
                    <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-md p-2 bg-white">
                        @forelse($pedidos as $pedido)
                        @if(!in_array($pedido->id, $pedidos_seleccionados))
                        <div class="flex justify-between items-center mb-1">
                            <span>{{ $pedido->codigo }} — {{ $pedido->cliente->nombres ?? 'Cliente N/A' }}</span>
                            <button wire:click.prevent="agregarPedido({{ $pedido->id }})"
                                class="bg-cyan-600 hover:bg-cyan-700 text-white px-2 py-1 rounded text-sm">
                                Añadir
                            </button>
                        </div>
                        @endif
                        @empty
                        <p class="text-gray-500 text-sm text-center py-2">No hay pedidos disponibles</p>
                        @endforelse
                    </div>
                </div>

                <!-- Pedidos asignados -->
                <div class="mb-4">
                    <label class="font-semibold text-sm mb-2 block">Pedidos asignados</label>
                    <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-md p-2 bg-white">
                        @forelse($pedidosAsignados as $pedido)
                        <div class="flex justify-between items-center mb-1">
                            <span>{{ $pedido->codigo }} — {{ $pedido->cliente->nombres ?? 'Cliente N/A' }}</span>
                            <button type="button" wire:click="quitarPedido({{ $pedido->id }})" class="text-red-500">Quitar</button>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm text-center py-2">No hay pedidos asignados</p>
                        @endforelse

                       
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end gap-3 pt-2 border-t">
                    <button wire:click="cerrarModal"
                        class="bg-gray-400 hover:bg-gray-500 text-white font-semibold px-4 py-2 rounded-lg">
                        Cerrar
                    </button>
                    <button wire:click="guardarDistribucion"
                        class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold px-4 py-2 rounded-lg">
                        Guardar
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endif


</div>
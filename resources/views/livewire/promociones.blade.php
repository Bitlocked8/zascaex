<div class="p-4 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-bold mb-6 text-cyan-900">Promociones por Código</h1>

    <button wire:click="abrirModal" class="bg-cyan-500 text-white px-4 py-2 rounded mb-6">
        Crear Lote
    </button>

    @forelse($itemPromos->groupBy('codigo') as $codigo => $items)
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex justify-between items-center mb-2">
            <div>
                <h2 class="text-lg font-semibold text-cyan-800">Código: {{ $codigo }}</h2>
                <span class="text-gray-500 text-sm">Fecha: {{ $items->first()->fecha_asignacion?->format('d/m/Y') }}</span>
            </div>

            <div class="flex gap-2">
                <!-- Botón Editar -->
                <button
                    wire:click="editarLote('{{ $codigo }}')"
                    class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">
                    Editar
                </button>
                <button
                    wire:click="eliminarLote('{{ $codigo }}')"
                    onclick="return confirm('¿Estás seguro de eliminar este lote?')"
                    class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                    Eliminar
                </button>
            </div>
        </div>

        <div class="space-y-2">
            @foreach($items->groupBy('cliente_id') as $clienteId => $clienteItems)
            <div class="p-2 border rounded bg-gray-50">
                <h3 class="font-medium text-cyan-700">
                    Cliente: {{ $clienteItems->first()->cliente->nombre ?? 'N/A' }}
                </h3>

                <ul class="list-disc list-inside ml-4 mt-1">
                    @foreach($clienteItems as $item)
                    <li>
                        Promo: {{ $item->promo->nombre ?? 'N/A' }}
                        <span class="text-sm text-gray-500">
                            ({{ $item->promo->tipo_descuento }} - {{ $item->promo->valor_descuento }})
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <p class="text-gray-500 text-center">No hay promociones asignadas.</p>
    @endforelse

    <!-- Modal -->
    @if($modal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">
                    {{ $editando ? 'Editar Lote de Promociones' : 'Crear Lote de Promociones' }}
                </h2>

                <!-- Código y Fecha -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block font-medium mb-1">Código del Lote</label>
                        <input type="text" wire:model="codigo" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Fecha de Asignación</label>
                        <input type="date" wire:model="fechaAsignacion" class="w-full border rounded px-3 py-2">
                    </div>
                </div>

                <!-- Clientes -->
                <div class="mb-4">
                    <label class="block font-medium mb-2">Clientes</label>

                    <!-- Clientes disponibles -->
                    <div class="mb-2 grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-40 overflow-y-auto border rounded p-2">
                        @foreach($clientes as $cliente)
                        @if(!in_array($cliente->id, $clientesSeleccionados))
                        <div class="flex justify-between items-center p-1 bg-gray-50 rounded hover:bg-gray-100">
                            <span>{{ $cliente->nombre }}</span>
                            <button wire:click.prevent="agregarCliente({{ $cliente->id }})"
                                class="bg-cyan-500 text-white px-2 py-1 rounded text-sm hover:bg-cyan-600">
                                Añadir
                            </button>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <!-- Clientes seleccionados -->
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-40 overflow-y-auto border rounded p-2 bg-gray-50">
                        @foreach($clientes->whereIn('id', $clientesSeleccionados) as $cliente)
                        <div class="flex justify-between items-center p-1 bg-cyan-100 rounded">
                            <span>{{ $cliente->nombre }}</span>
                            <button wire:click.prevent="quitarCliente({{ $cliente->id }})"
                                class="bg-red-500 text-white px-2 py-1 rounded text-sm hover:bg-red-600">
                                Quitar
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Promociones -->
                <div class="mb-4">
                    <label class="block font-medium mb-2">Promociones</label>

                    <!-- Promociones disponibles -->
                    <div class="mb-2 grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-40 overflow-y-auto border rounded p-2">
                        @foreach($promos as $promo)
                        @if(!in_array($promo->id, $promosSeleccionadas))
                        <div class="flex justify-between items-center p-1 bg-gray-50 rounded hover:bg-gray-100">
                            <span>{{ $promo->nombre }} ({{ $promo->tipo_descuento }} - {{ $promo->valor_descuento }})</span>
                            <button wire:click.prevent="agregarPromo({{ $promo->id }})"
                                class="bg-cyan-500 text-white px-2 py-1 rounded text-sm hover:bg-cyan-600">
                                Añadir
                            </button>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <!-- Promociones seleccionadas -->
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-40 overflow-y-auto border rounded p-2 bg-gray-50">
                        @foreach($promos->whereIn('id', $promosSeleccionadas) as $promo)
                        <div class="flex justify-between items-center p-1 bg-yellow-100 rounded">
                            <span>{{ $promo->nombre }} ({{ $promo->tipo_descuento }} - {{ $promo->valor_descuento }})</span>
                            <button wire:click.prevent="quitarPromo({{ $promo->id }})"
                                class="bg-red-500 text-white px-2 py-1 rounded text-sm hover:bg-red-600">
                                Quitar
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>


                <!-- Botones -->
                <div class="flex justify-end gap-2 mt-4">
                    <button wire:click="cerrarModal" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                        Cancelar
                    </button>

                    @if($editando)
                    <button wire:click="actualizarLote" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                        Actualizar Lote
                    </button>
                    @else
                    <button wire:click="guardarLote" class="bg-cyan-500 text-white px-4 py-2 rounded hover:bg-cyan-600">
                        Guardar Lote
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
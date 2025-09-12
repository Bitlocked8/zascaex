<div class="p-4 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-bold mb-6 text-cyan-900">Promociones por Código</h1>

    <button wire:click="abrirModal" class="bg-cyan-500 text-white px-4 py-2 rounded mb-6">
        Crear Lote
    </button>

    @forelse($itemPromos->groupBy('codigo') as $codigo => $items)
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-semibold text-cyan-800">Código: {{ $codigo }}</h2>
                <span class="text-gray-500 text-sm">Fecha: {{ $items->first()->fecha_asignacion?->format('d/m/Y') }}</span>
            </div>

            <div class="space-y-2">
                @foreach($items->groupBy('cliente_id') as $clienteId => $clienteItems)
                    <div class="p-2 border rounded bg-gray-50">
                        <h3 class="font-medium text-cyan-700">
                            Cliente: {{ $clienteItems->first()->cliente->nombre ?? 'N/A' }}
                        </h3>

                        <ul class="list-disc list-inside ml-4 mt-1">
                            @foreach($clienteItems as $item)
                                <li>Promo: {{ $item->promo->nombre ?? 'N/A' }}</li>
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
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
                <h2 class="text-xl font-bold mb-4">Crear Lote de Promociones</h2>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Código del Lote</label>
                    <input type="text" wire:model="codigo" class="w-full border rounded px-3 py-2" readonly>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Fecha de Asignación</label>
                    <input type="date" wire:model="fechaAsignacion" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Clientes</label>
                    <select wire:model="clientesSeleccionados" multiple class="w-full border rounded px-3 py-2">
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Promociones</label>
                    <select wire:model="promosSeleccionadas" multiple class="w-full border rounded px-3 py-2">
                        @foreach($promos as $promo)
                            <option value="{{ $promo->id }}">{{ $promo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button wire:click="cerrarModal" class="bg-gray-400 text-white px-4 py-2 rounded">Cancelar</button>
                    <button wire:click="guardarLote" class="bg-cyan-500 text-white px-4 py-2 rounded">Guardar Lote</button>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Buscar Cliente -->
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="searchCliente"
                placeholder="Buscar por nombre de cliente..."
                class="flex-1 border rounded px-3 py-2" />
        </div>

        @forelse ($clientes as $cliente)
        <div class="bg-white shadow rounded-lg p-4 flex flex-col justify-between">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-cyan-950">
                    Cliente: {{ $cliente->nombre }}
                </h3>

                @if($cliente->itemPromos->count())
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($cliente->itemPromos as $item)
                    <button
                        class="rounded-xl p-2 w-12 h-12 flex items-center justify-center
        {{ $item->estado === 'activo' ? 'bg-green-500 hover:bg-green-600 text-white' : '' }}
        {{ $item->estado === 'usado' ? 'bg-blue-500 hover:bg-blue-600 text-white' : '' }}
        {{ $item->estado === 'expirado' ? 'bg-gray-500 opacity-50 cursor-not-allowed text-white' : '' }}
        {{ $item->estado === 'cancelado' ? 'bg-red-500 opacity-50 cursor-not-allowed text-white' : '' }}"
                        @if($item->estado !== 'expirado' && $item->estado !== 'cancelado')
                        wire:click="verDetalle({{ $item->id }})"
                        @endif
                        title="{{ $item->promo->nombre ?? 'N/A' }}"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="text-white">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12.01 2.011c.852 0 1.668 .34 2.267 .942l.698 .698a1.2 1.2 0 0 0 .845 .349h1a3.2 3.2 0 0 1 3.2 3.2v1c0 .316 .126 .62 .347 .843l.698 .698a3.2 3.2 0 0 1 .002 4.536l-.698 .698a1.2 1.2 0 0 0 -.349 .845v1a3.2 3.2 0 0 1 -3.2 3.2h-1a1.2 1.2 0 0 0 -.843 .347l-.698 .698a3.2 3.2 0 0 1 -4.536 .002l-.698 -.698a1.2 1.2 0 0 0 -.845 -.349h-1a3.2 3.2 0 0 1 -3.2 -3.2v-1a1.2 1.2 0 0 0 -.347 -.843l-.698 -.698a3.2 3.2 0 0 1 -.002 -4.536l.698 -.698a1.2 1.2 0 0 0 .349 -.845v-1l.005 -.182a3.2 3.2 0 0 1 3.195 -3.018h1a1.2 1.2 0 0 0 .843 -.347l.698 -.698a3.2 3.2 0 0 1 2.269 -.944m2.49 10.989a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0 -3m1.207 -4.707a1 1 0 0 0 -1.414 0l-6 6a1 1 0 0 0 1.414 1.414l6 -6a1 1 0 0 0 0 -1.414m-6.207 -.293a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0 -3" />
                        </svg>
                    </button>

                    @endforeach
                </div>
                @else
                <p class="text-gray-500 mt-2">No tiene promociones asignadas</p>
                @endif

            </div>
        </div>
        @empty
        <p class="col-span-full text-center text-gray-500">No hay clientes registrados</p>
        @endforelse

    </div>

    <!-- Modal de detalle de promo -->
    @if($modalVisible && $promoSeleccionada)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <h2 class="text-xl font-bold mb-4">Detalle de Promo</h2>

            <p><strong>Cliente:</strong> {{ $promoSeleccionada->cliente->nombre ?? 'N/A' }}</p>
            <p><strong>Promo:</strong> {{ $promoSeleccionada->promo->nombre ?? 'N/A' }}</p>
            <p><strong>Usos Realizados:</strong> {{ $promoSeleccionada->usos_realizados }}</p>
            <p><strong>Uso Máximo:</strong> {{ $promoSeleccionada->uso_maximo ?? 'Ilimitado' }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($promoSeleccionada->estado) }}</p>
            <p><strong>Fecha Asignada:</strong> {{ $promoSeleccionada->fecha_asignada?->format('d/m/Y') ?? '-' }}</p>
            <p><strong>Fecha Expiración:</strong> {{ $promoSeleccionada->fecha_expiracion?->format('d/m/Y') ?? '-' }}</p>

            <button wire:click="cerrarModal" class="mt-4 bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded">Cerrar</button>
        </div>
    </div>
    @endif
</div>
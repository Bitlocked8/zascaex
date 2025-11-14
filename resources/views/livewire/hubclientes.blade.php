<div class="p-6 bg-gray-100 min-h-screen mt-20">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-teal-700">🛍️ Catálogo de Productos</h1>
        <div class="flex gap-3">
            <button wire:click="verMisPedidos"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 shadow-md">
                📦 Mis Solicitudes
            </button>

            <button wire:click="$toggle('mostrarCarrito')"
                class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 shadow-md">
                🛒 Ver Carrito ({{ count($carrito) }})
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($productos as $producto)
            <div class="bg-white shadow-md rounded-2xl overflow-hidden hover:shadow-xl transition">
                @if ($producto->imagen)
                    <img src="{{ asset('storage/' . $producto->imagen) }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                        Sin imagen
                    </div>
                @endif

                <div class="p-4">
                    <h2 class="text-lg font-bold text-gray-800">{{ $producto->descripcion }}</h2>
                    @if ($producto->unidad)
                        <p class="text-sm text-gray-500">Unidad: {{ $producto->unidad }}</p>
                    @endif
                    @if ($producto->capacidad)
                        <p class="text-sm text-gray-500">Capacidad: {{ $producto->capacidad }}</p>
                    @endif
                    <p class="text-teal-700 font-semibold text-xl mt-2">
                        Bs {{ number_format($producto->precioReferencia, 2) }}
                    </p>

                    <div class="mt-2 flex items-center gap-2">
                        <input type="number" min="1" wire:model="cantidades.{{ $producto->id }}"
                            class="w-20 border rounded px-2 py-1 text-center" placeholder="1">
                        <button wire:click="agregarAlCarrito({{ $producto->id }})"
                            class="flex-1 bg-teal-600 text-white py-2 rounded-xl hover:bg-teal-700">
                            ➕ Agregar
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-500">No hay productos disponibles</p>
        @endforelse
    </div>

    @if($mostrarCarrito)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white w-96 rounded-xl shadow-lg p-6 relative">
                <button wire:click="$set('mostrarCarrito', false)"
                    class="absolute top-2 right-2 text-red-600 font-bold text-xl">✕</button>

                <h2 class="text-2xl font-bold mb-4">🛒 Tu Carrito</h2>

                @if(empty($carrito))
                    <p class="text-gray-600 text-center">Tu carrito está vacío.</p>
                @else
                    <div class="space-y-4 max-h-96 overflow-auto">
                        @foreach($carrito as $item)
                            <div class="bg-gray-100 p-3 rounded-lg flex justify-between items-center">
                                <div>
                                    <p class="font-bold">{{ $item['descripcion'] }}</p>
                                    <p class="text-sm text-gray-600">Bs {{ $item['precio'] }}</p>
                                    <p class="text-sm">Cantidad: {{ $item['cantidad'] }}</p>
                                </div>
                                <button wire:click="eliminarDelCarrito({{ $item['id'] }})"
                                    class="text-red-600 font-bold text-lg">✕</button>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <p class="text-xl font-bold text-right">
                            Total: Bs {{ number_format(collect($carrito)->sum(fn($i) => $i['precio'] * $i['cantidad']), 2) }}
                        </p>
                    </div>

                    <button wire:click="hacerPedido"
                        class="w-full mt-4 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                        ✔️ Enviar Solicitud de Pedido
                    </button>
                @endif
            </div>
        </div>
    @endif

  @if($modalPedidosCliente)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-6 relative">
            <button wire:click="cerrarModalPedidos"
                class="absolute top-2 right-2 text-red-600 font-bold text-xl">✕</button>

            <h2 class="text-2xl font-bold text-teal-700 mb-4">📦 Mis Solicitudes</h2>

            @forelse ($pedidosCliente as $pedido)
                <div class="border rounded-lg p-4 mb-4 shadow-sm bg-gray-50 relative">

                    <!-- BOTÓN ELIMINAR SOLICITUD -->
                    <button wire:click="eliminarSolicitud({{ $pedido->id }})"
                        class="absolute top-3 right-3 text-red-600 hover:text-red-800 font-bold text-lg">
                        ✕
                    </button>

                    <p class="font-bold text-lg">Código: {{ $pedido->codigo }}</p>
                    <p class="text-sm text-gray-600">Estado: {{ ucfirst($pedido->estado) }}</p>

                    <h3 class="mt-2 font-semibold">Productos:</h3>
                    <ul class="ml-4 list-disc text-sm">
                        @foreach ($pedido->detalles as $detalle)
                            <li>{{ $detalle->producto->descripcion }} (x{{ $detalle->cantidad }}) - Bs {{ number_format($detalle->precio,2) }}</li>
                        @endforeach
                    </ul>
                </div>
            @empty
                <p class="text-center text-gray-500">No tienes solicitudes.</p>
            @endforelse
        </div>
    </div>
@endif

</div>

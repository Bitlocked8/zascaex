<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3
            class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto">
            Productos
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <button wire:click="verMisPedidos"
                class="bg-blue-600 text-white px-5 py-2 rounded-xl hover:bg-blue-700 shadow-md transition">
                📦 Mis Solicitudes
            </button>
            <button wire:click="$toggle('mostrarCarrito')"
                class="bg-teal-600 text-white px-5 py-2 rounded-xl hover:bg-teal-700 shadow-md transition">
                🛒 Ver Carrito ({{ count($carrito) }})
            </button>
        </div>

        @forelse ($productos as $p)
            <div
                class="bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-1 flex flex-col">
                <div class="flex justify-center items-center h-48 bg-gray-50">
                    @if(!empty($p['imagen']))
                        <img src="{{ asset('storage/' . $p['imagen']) }}" class="h-full object-contain">
                    @else
                        <span class="text-gray-400">Sin imagen</span>
                    @endif
                </div>

                <div class="p-5 text-center flex flex-col gap-2 flex-1">
                    <h2 class="text-lg font-bold text-gray-800">{{ $p['descripcion'] }}</h2>

                    @if(!empty($p['capacidad']))
                        <p class="text-sm text-gray-500">Capacidad: {{ $p['capacidad'] }} {{ $p['unidad'] }}</p>
                    @endif

                    <p class="text-teal-700 font-semibold text-xl">Bs {{ number_format($p['precio'], 2) }} / unidad</p>

                    @if(!empty($p['paquete']) && !empty($p['unidad']))
                        <p class="text-sm text-gray-600">Paquete: {{ $p['paquete'] }} × {{ $p['unidad'] }}</p>
                    @endif

                    <div class="flex flex-col gap-2 mt-4">
                        <button wire:click="abrirModalProducto('{{ $p['uid'] }}')"
                            class="w-full bg-teal-600 text-white py-2 rounded-lg hover:bg-teal-700 transition flex justify-center items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                                <path
                                    d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                            </svg>
                            Seleccionar
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-500 mt-10 text-lg">No hay productos disponibles</p>
        @endforelse
    </div>

    @if($modalProducto)
        <div class="modal-overlay">
            <div class="modal-box max-w-4xl w-11/12 sm:w-4/5 md:w-3/4 lg:w-2/3">
                <div class="modal-content flex flex-col gap-4">
                    <div class="flex justify-between items-center">
                        <h2 class="text-u sm:text-2xl font-bold">{{ $productoSeleccionado['descripcion'] ?? '' }}</h2>
                    </div>
                    <div class="flex justify-center mb-4">
                        @if(!empty($productoSeleccionado['imagen']))
                            <img src="{{ asset('storage/' . $productoSeleccionado['imagen']) }}"
                                class="h-48 sm:h-64 md:h-80 w-full object-contain border rounded-lg p-1">
                        @else
                            <div
                                class="h-48 sm:h-64 md:h-80 w-full flex items-center justify-center bg-gray-200 text-gray-500 text-sm border rounded-lg">
                                No hay imagen
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="text-sm font-medium mb-1 block">Cantidad de paquetes:</label>
                        <input type="number" min="1" wire:model="cantidadSeleccionada"
                            class="input-minimal">
                    </div>

                    <h3>
                        <label class="text-u">personaliza tu botella</label>
                    </h3>
                    <div>
                        <label class="text-u">elige una Tapa:</label>
                        <div class="flex overflow-x-auto gap-4 py-2">
                            @forelse($tapas as $tapa)
                                <div wire:click="$set('tapaSeleccionada', {{ $tapa->id }})"
                                    class="flex-shrink-0 border rounded-lg cursor-pointer p-2 transition
                                                                @if($tapaSeleccionada == $tapa->id) border-teal-600 ring-2 ring-teal-400 @endif">
                                    @if(!empty($tapa->imagen))
                                        <img src="{{ asset('storage/' . $tapa->imagen) }}" alt="{{ $tapa->descripcion }}"
                                            class="h-32 sm:h-40 md:h-48 w-32 sm:w-40 md:w-48 object-contain mx-auto border rounded-lg p-1">
                                    @else
                                        <div
                                            class="h-32 sm:h-40 md:h-48 w-32 sm:w-40 md:w-48 flex items-center justify-center bg-gray-200 text-gray-500 text-xs mx-auto border rounded-lg">
                                            No hay imagen
                                        </div>
                                    @endif
                                    <p class="text-xs sm:text-sm text-center mt-1">{{ $tapa->descripcion }}</p>
                                </div>
                            @empty
                                <p>No hay tapas disponibles</p>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <label class="text-u">elige una Etiqueta:</label>
                        <div class="flex overflow-x-auto gap-4 py-2">
                            @forelse($etiquetas as $etiqueta)
                                <div wire:click="$set('etiquetaSeleccionada', {{ $etiqueta->id }})"
                                    class="flex-shrink-0 border rounded-lg cursor-pointer p-2 transition
                                                                @if($etiquetaSeleccionada == $etiqueta->id) border-teal-600 ring-2 ring-teal-400 @endif">
                                    @if(!empty($etiqueta->imagen))
                                        <img src="{{ asset('storage/' . $etiqueta->imagen) }}" alt="{{ $etiqueta->descripcion }}"
                                            class="h-32 sm:h-40 md:h-48 w-32 sm:w-40 md:w-48 object-contain mx-auto border rounded-lg p-1">
                                    @else
                                        <div
                                            class="h-32 sm:h-40 md:h-48 w-32 sm:w-40 md:w-48 flex items-center justify-center bg-gray-200 text-gray-500 text-xs mx-auto border rounded-lg">
                                            No hay imagen
                                        </div>
                                    @endif
                                    <p class="text-xs sm:text-sm text-center mt-1">{{ $etiqueta->descripcion }}</p>
                                </div>
                            @empty
                                <p>No hay etiquetas disponibles</p>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer ">
                    <button wire:click="$set('modalProducto', false)"
                        class="btn-cyan flex items-center gap-1">CERRAR</button>
                    <button wire:click="agregarAlCarritoDesdeModal" class="btn-cyan flex items-center gap-1">AÑADIR</button>
                </div>
            </div>
        </div>
    @endif




    @if($mostrarCarrito)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white w-96 rounded-2xl shadow-2xl p-6 relative flex flex-col">
                <button wire:click="$set('mostrarCarrito', false)"
                    class="absolute top-3 right-3 text-red-600 font-bold text-xl hover:text-red-800 transition">✕</button>

                <h2 class="text-2xl font-bold text-center mb-4">🛒 Tu Carrito</h2>

                @if(empty($carrito))
                    <p class="text-gray-600 text-center mt-10">Tu carrito está vacío.</p>
                @else
                    <div class="space-y-4 max-h-96 overflow-auto">
                        @foreach($carrito as $item)
                            @php
                                $totalItem = $item['precio'] * $item['cantidad'] * ($item['paquete'] ?? 1);
                            @endphp
                            <div
                                class="bg-gray-100 p-3 rounded-lg flex justify-between items-start shadow-sm hover:shadow-md transition">
                                <div class="flex flex-col gap-1">
                                    <p class="font-bold text-gray-800">{{ $item['descripcion'] }}</p>
                                    <p class="text-sm text-gray-600">Bs {{ number_format($item['precio'], 2) }} / unidad</p>

                                    @if(!empty($item['paquete']))
                                        <p class="text-sm text-gray-600">Paquetes: {{ $item['cantidad'] }} × {{ $item['paquete'] }}</p>
                                    @endif

                                    @if(!empty($item['tapa_descripcion']))
                                        <p class="text-sm text-gray-600">Tapa: {{ $item['tapa_descripcion'] }}</p>
                                    @endif

                                    @if(!empty($item['etiqueta_descripcion']))
                                        <div class="flex items-center gap-2">
                                            @if(!empty($item['etiqueta_imagen']))
                                                <img src="{{ asset('storage/' . $item['etiqueta_imagen']) }}"
                                                    alt="{{ $item['etiqueta_descripcion'] }}" class="h-6 w-6 object-contain rounded">
                                            @endif
                                            <p class="text-sm text-gray-600">{{ $item['etiqueta_descripcion'] }}</p>
                                        </div>
                                    @endif

                                    <p class="text-sm font-semibold text-teal-700">Total: Bs {{ number_format($totalItem, 2) }}</p>
                                </div>

                                <button wire:click="eliminarDelCarrito('{{ $item['uid'] }}')"
                                    class="text-red-600 font-bold text-lg hover:text-red-800 transition">✕</button>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 border-t pt-4">
                        <p class="text-xl font-bold text-right">
                            Total: Bs
                            {{ number_format(collect($carrito)->sum(fn($i) => $i['precio'] * $i['cantidad'] * ($i['paquete'] ?? 1)), 2) }}
                        </p>
                    </div>

                    <button wire:click="hacerPedido"
                        class="w-full mt-4 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                        ✔️ Enviar Solicitud de Pedido
                    </button>
                @endif
            </div>
        </div>
    @endif
    @if($modalPedidosCliente)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-xl w-3/4 shadow-xl relative max-h-[90vh] overflow-auto">
                <button wire:click="cerrarModalPedidos"
                    class="absolute top-2 right-2 text-red-600 font-bold text-xl">✕</button>
                <h2 class="text-2xl font-bold mb-4">📦 Mis Solicitudes</h2>

                @forelse ($pedidosCliente as $pedido)
                    <div class="border p-4 rounded-lg mb-4 bg-gray-100">
                        <p><strong>Código:</strong> {{ $pedido['codigo'] }}</p>
                        <p><strong>Estado:</strong>
                            @if($pedido['estado'] == 0) Pendiente
                            @elseif($pedido['estado'] == 1) Aprobado
                            @else Rechazado @endif
                        </p>

                        <h4 class="font-bold mt-2">Detalles:</h4>

                        @foreach ($pedido['detalles'] as $det)
                            <div class="ml-4 mb-4 border-b pb-2">
                                <p>- {{ $det['descripcion'] }} (x{{ $det['cantidad'] }})</p>

                                @if($det['paquete'] > 1)
                                    <p>- Paquetes: {{ $det['cantidad'] }} × {{ $det['paquete'] }}</p>
                                @endif

                                {{-- Mostrar tapa --}}
                                @if(!empty($det['tapa_descripcion']))
                                    <div class="flex items-center gap-2 mt-1">
                                        <span>- Tapa: {{ $det['tapa_descripcion'] }}</span>
                                        @if(!empty($det['tapa_imagen']))
                                            <img src="{{ asset('storage/' . $det['tapa_imagen']) }}"
                                                class="h-16 w-16 object-contain rounded border p-1">
                                        @endif
                                    </div>
                                @endif

                                {{-- Mostrar etiquetas con imagen --}}
                                @if(!empty($det['etiquetas_info']))
                                    <div class="flex items-center gap-2 mt-1">
                                        <span>- Etiquetas:</span>
                                        @foreach($det['etiquetas_info'] as $et)
                                            <span>{{ $et['descripcion'] }}</span>
                                            @if(!empty($et['imagen']))
                                                <img src="{{ asset('storage/' . $et['imagen']) }}"
                                                    class="h-16 w-16 object-contain rounded border p-1">
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                <p>- Bs {{ number_format($det['precio_unitario'], 2) }}</p>
                                <p>- Total: Bs {{ number_format($det['total'], 2) }}</p>
                            </div>
                        @endforeach

                        <p class="mt-2 font-semibold text-teal-700 text-right">
                            Total del pedido: Bs {{ number_format(collect($pedido['detalles'])->sum('total'), 2) }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-600">No tienes solicitudes aún.</p>
                @endforelse

            </div>
        </div>
    @endif




</div>
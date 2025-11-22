<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3
            class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full">
            Productos
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <button wire:click="verMisPedidos"
                class="bg-blue-600 text-white px-5 py-2 rounded-xl hover:bg-blue-700 shadow-md transition">
                Mis Solicitudes
            </button>

            <button wire:click="$toggle('mostrarCarrito')"
                class="bg-teal-600 text-white px-5 py-2 rounded-xl hover:bg-teal-700 shadow-md transition">
                Ver Carrito ({{ count($carrito) }})
            </button>
        </div>

        @forelse ($productos as $p)
            @php
                $modelo = $p['modelo'];
                $sucursal = $modelo->existencias->first()->sucursal->nombre ?? 'Sin sucursal';
            @endphp

            <div
                class="bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-1 flex flex-col">

                <div class="flex justify-center items-center h-48 bg-gray-50">
                    @if(!empty($modelo->imagen))
                        <img src="{{ asset('storage/' . $modelo->imagen) }}" class="h-full object-contain">
                    @else
                        <span class="text-gray-400">Sin imagen</span>
                    @endif
                </div>

                <div class="p-5 text-center flex flex-col gap-2 flex-1">
                    <h2 class="text-lg font-bold text-gray-800">{{ $modelo->descripcion }}</h2>

                    <p class="text-sm text-gray-600">
                        Sucursal: <span class="font-semibold">{{ $sucursal }}</span>
                    </p>

                    <button wire:click="abrirModalProducto('{{ $p['uid'] }}')"
                        class="w-full bg-teal-600 text-white py-2 rounded-lg hover:bg-teal-700 transition flex justify-center items-center gap-2 mt-3">
                        Seleccionar
                    </button>
                </div>

            </div>
        @empty
            <p class="col-span-full text-center text-gray-500 mt-10 text-lg">No hay productos disponibles</p>
        @endforelse

    </div>

    @if($modalProducto)
        @php
            $modelo = $productoSeleccionado['modelo'];
            $sucursalProducto = $modelo->existencias->first()->sucursal->nombre ?? 'Sin sucursal';
        @endphp

        <div class="modal-overlay">
            <div class="modal-box max-w-4xl">
                <div class="modal-content flex flex-col gap-4">

                    <!-- Producto -->
                    <h2 class="text-xl sm:text-2xl font-bold text-center">{{ $modelo->descripcion }}</h2>
                    <p class="text-center text-gray-700 text-sm">
                        Sucursal: <span class="font-semibold">{{ $sucursalProducto }}</span>
                    </p>

                    <div class="flex justify-center mb-4">
                        @if(!empty($modelo->imagen))
                            <img src="{{ asset('storage/' . $modelo->imagen) }}"
                                class="h-48 sm:h-64 md:h-80 w-full object-contain border rounded-lg p-1">
                        @else
                            <div
                                class="h-48 sm:h-64 md:h-80 w-full flex items-center justify-center bg-gray-200 text-gray-500 text-sm border rounded-lg">
                                No hay imagen
                            </div>
                        @endif
                    </div>

                    <!-- Cantidad -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium">Cantidad de paquetes:</label>
                        <input type="number" min="1" wire:model="cantidadSeleccionada" class="input-minimal w-24">
                    </div>

                    <!-- Tapa -->
                    <div class="border rounded-lg p-3 bg-white">
                        <h3 class="text-center font-semibold mb-3">Elige una Tapa</h3>
                        <div class="flex overflow-x-auto gap-4 py-2">
                            @forelse($tapas as $tapa)
                                @php
                                    $sucursalTapa = $tapa->existencias->first()->sucursal->nombre ?? 'Sin sucursal';
                                @endphp
                                <div wire:click="$set('tapaSeleccionada', {{ $tapa->id }})"
                                    class="flex-shrink-0 border rounded-lg cursor-pointer p-2 transition
                                                                                                                                                                                                                @if($tapaSeleccionada == $tapa->id) border-cyan-600 ring-2 ring-cyan-400 @endif">

                                    @if(!empty($tapa->imagen))
                                        <img src="{{ asset('storage/' . $tapa->imagen) }}"
                                            class="h-32 sm:h-40 md:h-48 w-32 sm:w-40 md:w-48 object-contain mx-auto border rounded-lg p-1">
                                    @else
                                        <div
                                            class="h-32 sm:h-40 md:h-48 w-32 sm:w-40 md:w-48 flex items-center justify-center bg-gray-200 text-gray-500 text-xs mx-auto border rounded-lg">
                                            No hay imagen
                                        </div>
                                    @endif

                                    <p class="text-xs sm:text-sm text-center mt-1">{{ $tapa->descripcion }}</p>
                                    <p class="text-xs text-gray-500 text-center mt-1">Sucursal: {{ $sucursalTapa }}</p>
                                </div>
                            @empty
                                <p class="text-center text-gray-500">No hay tapas disponibles</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Etiqueta -->
                    <div class="border rounded-lg p-3 bg-white">
                        <h3 class="text-center font-semibold mb-3">Elige una Etiqueta</h3>
                        <div class="flex overflow-x-auto gap-4 py-2">
                            @forelse($etiquetas as $etiqueta)
                                @php
                                    $sucursalEtiqueta = $etiqueta->existencias->first()->sucursal->nombre ?? 'Sin sucursal';
                                @endphp
                                <div wire:click="$set('etiquetaSeleccionada', {{ $etiqueta->id }})"
                                    class="flex-shrink-0 border rounded-lg cursor-pointer p-2 transition
                                                                                                                                                                                                                @if($etiquetaSeleccionada == $etiqueta->id) border-cyan-600 ring-2 ring-cyan-400 @endif">

                                    @if(!empty($etiqueta->imagen))
                                        <img src="{{ asset('storage/' . $etiqueta->imagen) }}"
                                            class="h-32 sm:h-40 md:h-48 w-32 sm:w-40 md:w-48 object-contain mx-auto border rounded-lg p-1">
                                    @else
                                        <div
                                            class="h-32 sm:h-40 md:h-48 w-32 sm:w-40 md:w-48 flex items-center justify-center bg-gray-200 text-gray-500 text-xs mx-auto border rounded-lg">
                                            No hay imagen
                                        </div>
                                    @endif

                                    <p class="text-xs sm:text-sm text-center mt-1">{{ $etiqueta->descripcion }}</p>
                                    <p class="text-xs text-gray-500 text-center mt-1">Sucursal: {{ $sucursalEtiqueta }}</p>
                                </div>
                            @empty
                                <p class="text-center text-gray-500">No hay etiquetas disponibles</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="modal-footer flex gap-2 justify-end mt-3">
                        <button wire:click="$set('modalProducto', false)" class="btn-cyan flex items-center gap-2">
                            CERRAR
                        </button>
                        <button wire:click="agregarAlCarritoDesdeModal" class="btn-cyan flex items-center gap-2">
                            AÑADIR AL CARRITO
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif
    @if($mostrarCarrito)
        <div class="modal-overlay fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
            <div class="modal-box max-w-3xl w-full p-4 bg-white rounded-3xl shadow-2xl">
                <div class="modal-content flex flex-col gap-4">
                    <h2 class="text-2xl font-bold text-center mb-4">🛒 Carrito ({{ count($carrito) }})</h2>

                    @if(count($carrito) > 0)
                        <div class="flex flex-col gap-4 max-h-96 overflow-y-auto">
                            @foreach($carrito as $item)
                                @php
                                    $modelo = $item['modelo'];
                                    $tapa = $item['tapa_id'] ? $tapas->firstWhere('id', $item['tapa_id']) : null;
                                    $etiqueta = $item['etiqueta_id'] ? $etiquetas->firstWhere('id', $item['etiqueta_id']) : null;
                                    $imagenProd = $modelo->imagen ?? null;
                                    $descripcion = $modelo->descripcion ?? '';
                                @endphp

                                <div class="flex flex-col border rounded-lg p-3 bg-gray-50 gap-2">
                                    <div class="flex items-center gap-2">
                                        @if($imagenProd)
                                            <img src="{{ asset('storage/' . $imagenProd) }}"
                                                class="h-16 w-16 object-contain border rounded-lg">
                                        @endif
                                        <p class="font-semibold">{{ $descripcion }}</p>
                                    </div>

                                    <p class="text-sm text-gray-600">Cantidad: {{ $item['cantidad'] }}</p>

                                    @if($tapa)
                                        <div class="flex items-center gap-2">
                                            @if($tapa->imagen)
                                                <img src="{{ asset('storage/' . $tapa->imagen) }}"
                                                    class="h-10 w-10 object-contain border rounded-lg">
                                            @endif
                                            <p class="text-sm text-gray-600">Tapa: {{ $tapa->descripcion }}</p>
                                        </div>
                                    @endif

                                    @if($etiqueta)
                                        <div class="flex items-center gap-2">
                                            @if($etiqueta->imagen)
                                                <img src="{{ asset('storage/' . $etiqueta->imagen) }}"
                                                    class="h-10 w-10 object-contain border rounded-lg">
                                            @endif
                                            <p class="text-sm text-gray-600">Etiqueta: {{ $etiqueta->descripcion }}</p>
                                        </div>
                                    @endif

                                    <div class="flex justify-end mt-2">
                                        <button wire:click="eliminarDelCarrito('{{ $item['uid'] }}')"
                                            class="text-red-600 font-semibold hover:text-red-800 transition px-2 py-1 border border-red-600 rounded-md">
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="modal-footer flex justify-end mt-4 gap-2">
                            <button wire:click="$set('mostrarCarrito', false)" class="btn-cyan flex items-center gap-2">
                                CERRAR
                            </button>
                            <button wire:click="hacerPedido" class="btn-cyan flex items-center gap-2">
                                REALIZAR PEDIDO
                            </button>
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-10">El carrito está vacío.</p>
                        <div class="modal-footer flex justify-center mt-4">
                            <button wire:click="$set('mostrarCarrito', false)" class="btn-cyan flex items-center gap-2">
                                CERRAR
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif


    @if($modalPedidosCliente)
        <div class="modal-overlay">
            <div class="modal-box w-full max-w-3xl">
                <div class="modal-content flex flex-col gap-4">
                    <h2 class="text-2xl font-bold text-center mb-4">Mis Pedidos ({{ count($pedidosCliente) }})</h2>

                    @if(count($pedidosCliente) > 0)
                        <div class="flex flex-col gap-4 max-h-96 overflow-y-auto">
                            @foreach($pedidosCliente as $pedido)
                                <div class="border rounded-lg p-3 bg-gray-50 flex flex-col gap-2">
                                    <p class="text-sm">
                                        <strong>Estado:</strong>
                                        <span
                                            class="inline-block px-2 py-1 rounded-full text-sm font-semibold {{ $pedido['estado'] == 0 ? 'bg-yellow-200 text-yellow-800' : ($pedido['estado'] == 1 ? 'bg-green-200 text-green-800' : ($pedido['estado'] == 2 ? 'bg-blue-200 text-blue-800' : 'bg-gray-200 text-gray-800')) }}">
                                            {{ $pedido['estado'] == 0 ? 'Pendiente de pago' : ($pedido['estado'] == 1 ? 'Pagado' : ($pedido['estado'] == 2 ? 'Entregado' : 'Desconocido')) }}
                                        </span>
                                    </p>

                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 mt-1">
                                        <p class="text-sm text-gray-600">Selecciona un método de Pago:</p>
                                        <div class="flex flex-wrap gap-2">
                                            <button wire:click="actualizarMetodoPago({{ $pedido['id'] }}, 0)"
                                                class="px-3 py-1 rounded text-sm font-semibold transition {{ $pedido['metodo_pago'] == 0 ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">QR</button>
                                            <button wire:click="actualizarMetodoPago({{ $pedido['id'] }}, 1)"
                                                class="px-3 py-1 rounded text-sm font-semibold transition {{ $pedido['metodo_pago'] == 1 ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }}">Efectivo</button>
                                            <button wire:click="actualizarMetodoPago({{ $pedido['id'] }}, 2)"
                                                class="px-3 py-1 rounded text-sm font-semibold transition {{ $pedido['metodo_pago'] == 2 ? 'bg-purple-500 text-white' : 'bg-gray-200 text-gray-700' }}">Crédito</button>
                                        </div>
                                    </div>

                                    @if(!empty($pedido['detalles']))
                                        <div class="flex flex-col gap-2 mt-2">
                                            @foreach($pedido['detalles'] as $detalle)
                                                @php
                                                    $producto = $detalle['producto'] ?? null;
                                                    $otro = $detalle['otro'] ?? null;
                                                    $tapa = $detalle['tapa'] ?? null;
                                                    $etiqueta = $detalle['etiqueta'] ?? null;
                                                    $descripcion = $producto['descripcion'] ?? $otro['descripcion'] ?? '';
                                                    $imagenProd = $producto['imagen'] ?? $otro['imagen'] ?? null;
                                                    $imagenTapa = $tapa['imagen'] ?? null;
                                                    $imagenEtiqueta = $etiqueta['imagen'] ?? null;
                                                @endphp
                                                <div class="flex flex-col gap-1 border rounded-lg p-2 bg-white">
                                                    <div class="flex items-center gap-2">
                                                        @if($imagenProd)
                                                            <img src="{{ asset('storage/' . $imagenProd) }}"
                                                                class="h-16 w-16 object-contain border rounded-lg">
                                                        @endif
                                                        <p class="font-semibold">{{ $descripcion }}</p>
                                                    </div>
                                                    <p class="text-sm text-gray-600">Cantidad: {{ $detalle['cantidad'] }}</p>
                                                    @if($tapa)
                                                        <div class="flex items-center gap-2 mt-1">
                                                            @if($imagenTapa)
                                                                <img src="{{ asset('storage/' . $imagenTapa) }}"
                                                                    class="h-10 w-10 object-contain border rounded-lg">
                                                            @endif
                                                            <p class="text-sm text-gray-600">Tapa: {{ $tapa['descripcion'] ?? '-' }}</p>
                                                        </div>
                                                    @endif
                                                    @if($etiqueta)
                                                        <div class="flex items-center gap-2 mt-1">
                                                            @if($imagenEtiqueta)
                                                                <img src="{{ asset('storage/' . $imagenEtiqueta) }}"
                                                                    class="h-10 w-10 object-contain border rounded-lg">
                                                            @endif
                                                            <p class="text-sm text-gray-600">Etiqueta: {{ $etiqueta['descripcion'] ?? '-' }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if(!empty($pedido['pedido']['pago_pedidos']))
                                        <div class="flex flex-col gap-2 mt-2 p-2 border-t border-gray-300">
                                            <h4 class="font-semibold text-gray-700">Pagos realizados:</h4>
                                            @foreach($pedido['pedido']['pago_pedidos'] as $pago)
                                                <div
                                                    class="flex flex-col sm:flex-row sm:items-center justify-between bg-gray-100 p-2 rounded-lg gap-2">
                                                    <div class="flex items-center gap-2">
                                                        <p class="text-sm font-medium">Monto: {{ number_format($pago['monto'], 2) }}</p>
                                                        <p class="text-sm text-gray-600">Método:
                                                            {{ $pago['metodo'] == 0 ? 'QR' : ($pago['metodo'] == 1 ? 'Efectivo' : 'Crédito') }}
                                                        </p>
                                                        @if(!empty($pago['imagen_comprobante']))
                                                            <img src="{{ asset('storage/' . $pago['imagen_comprobante']) }}"
                                                                class="h-12 w-12 object-contain border rounded-lg">
                                                        @endif
                                                    </div>
                                                    @if(empty($pago['imagen_comprobante']))
                                                        <div class="flex items-center gap-2">
                                                            <input type="file" wire:model="archivoPago" class="text-sm">
                                                            <button wire:click="subirComprobante({{ $pago['id'] }})"
                                                                wire:loading.attr="disabled"
                                                                class="px-2 py-1 bg-green-500 text-white rounded text-sm">Subir</button>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="flex justify-end mt-2">
                                        <button wire:click="eliminarSolicitud({{ $pedido['id'] }})"
                                            class="text-red-600 font-semibold hover:text-red-800 transition px-2 py-1 border border-red-600 rounded-md">Eliminar
                                            Pedido</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="modal-footer mt-4">
                            <button wire:click="$set('modalPedidosCliente', false)"
                                class="btn-cyan flex items-center gap-2">CERRAR</button>
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-10">No tienes pedidos.</p>
                        <div class="modal-footer flex justify-center mt-4">
                            <button wire:click="$set('modalPedidosCliente', false)"
                                class="btn-cyan flex items-center gap-2">CERRAR</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif



</div>
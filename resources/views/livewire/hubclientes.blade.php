<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <div class="flex justify-center items-center gap-2 mb-4 col-span-full">
            <button wire:click="verMisPedidos" class="btn-cyan" title="Ver detalle">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                    <path d="M11.5 17h-5.5v-14h-2" />
                    <path d="M6 5l14 1l-1 7h-13" />
                    <path d="M15 19l2 2l4 -4" />
                </svg>
                Ver mis compras
            </button>

            <button wire:click="$toggle('mostrarCarrito')" class="btn-cyan" title="Agregar producto">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                    <path
                        d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                </svg>
                Productos Añadidos
                ({{ count($carrito) }})
            </button>

            <button type="button" wire:click="$set('sucursalFiltro', null)"
                class="px-4 py-2 rounded-lg border text-sm font-semibold transition
            {{ is_null($sucursalFiltro) ? 'bg-cyan-600 text-white border-cyan-700 shadow-md' : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                Todas las sucursales
            </button>

            @forelse($sucursales as $s)
                <button type="button" wire:click="$set('sucursalFiltro', {{ $s->id }})"
                    class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                                                                {{ $sucursalFiltro == $s->id ? 'bg-cyan-600 text-white border-cyan-700 shadow-md' : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                    <p class="font-semibold text-sm {{ $sucursalFiltro == $s->id ? 'text-white' : 'text-gray-800' }}">
                        {{ $s->nombre }}
                    </p>
                    <p class="text-xs {{ $sucursalFiltro == $s->id ? 'text-cyan-100' : 'text-emerald-600' }}">
                        {{ $s->empresa?->nombre ?? 'Sin empresa' }}
                    </p>
                    <p class="text-xs {{ $sucursalFiltro == $s->id ? 'text-cyan-100' : 'text-emerald-600' }}">
                        {{ $s->telefono ?? 'Sin teléfono' }}
                    </p>
                </button>
            @empty
                <p class="text-center text-gray-500 py-3 text-sm">No hay sucursales disponibles</p>
            @endforelse


        </div>

        <div class="flex justify-center items-center gap-2 mb-4 col-span-full">
            <p class=" text-u font-semiboldp-2 rounded-md shadow mt-2 text-xl">
                Selecciona la sucursal Cochabamba para envíos a todos los departamentos excepto Beni,
                y Santa Cruz solo para envíos a Beni.
            </p>

        </div>


        @forelse ($productos as $p)
            @php
                $modelo = $p['modelo'];
                $existencia = $modelo->existencias->firstWhere('sucursal_id', $sucursalFiltro);
                $sucursal = $existencia->sucursal->nombre ?? $modelo->existencias->first()->sucursal->nombre ?? 'Sin sucursal';

                $precioAprox = ($modelo->precioReferencia && $modelo->paquete)
                    ? $modelo->precioReferencia * $modelo->paquete
                    : null;
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
                    <h2 class="font-bold text-u text-3xl">{{ $modelo->descripcion }} {{ $modelo->unidad }}</h2>

                    <p class="text-sm text-u font-semibold">
                        Precio Aproximado por paquete:
                        <span class="text-gray-800 font-bold">
                            @if($precioAprox)
                                {{ number_format($precioAprox, 2) }} Bs
                            @else
                                -
                            @endif
                        </span>
                    </p>
                    <p class="text-sm text-gray-600">
                        Tipo de contenido: <span class="font-semibold">{{ $modelo->tipoContenido ?? '-' }}</span>
                    </p>

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
            <div class="modal-box">
                <div class="modal-content flex flex-col gap-4">
                    <h2 class="text-u sm:text-2xl font-bold text-center">{{ $modelo->descripcion }}
                        {{ $modelo->unidad ?? '-' }}
                    </h2>
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
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-white border rounded-lg p-4">
                        <p><span class="font-semibold">Contenido en ml.lt , etc:</span> {{ $modelo->unidad ?? '-' }}</p>
                        <p><span class="font-semibold">Tipo de contenido:</span> {{ $modelo->tipoContenido ?? '-' }}</p>
                        <p><span class="font-semibold">Tipo de producto:</span> {{ $modelo->tipoProducto ?? '-' }}</p>
                        <p><span class="font-semibold">Capacidad:</span> {{ $modelo->capacidad ?? '-' }}</p>
                        <p><span class="font-semibold">Precio por unidad aproximado.:</span>
                            {{ $modelo->precioReferencia ? number_format($modelo->precioReferencia, 2) : '-' }} Bs
                        </p>
                        <p><span class="font-semibold">Paquete:</span> {{ $modelo->paquete ?? '-' }}</p>
                        <p class="font-semibold text-cyan-700">
                            Precio aproximado por paquete:
                            <span class="font-normal text-black">
                                @if($modelo->precioReferencia && $modelo->paquete)
                                    {{ number_format($modelo->precioReferencia * $modelo->paquete, 2) }} Bs
                                @else
                                    -
                                @endif
                            </span>
                        </p>
                        <p class="col-span-full">
                            <span class="font-semibold">Observaciones:</span>
                            {{ $modelo->observaciones ?? 'Sin observaciones' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-u text-center font-medium">coloca una Cantidad de paquetes:</label>
                        <input type="number" wire:model="cantidadSeleccionada" class="input-minimal">
                    </div>
                    <div class="border rounded-lg p-3 bg-white">
                        <h3 class="text-center font-semibold mb-3">Elige una Tapa</h3>
                        <div class="flex overflow-x-auto gap-4 py-2">
                            @forelse($tapas as $tapa)
                                @php
                                    $sucursalTapa = $tapa->existencias->first()->sucursal->nombre ?? 'Sin sucursal';
                                @endphp

                                <div wire:click="$set('tapaSeleccionada', {{ $tapa->id }})"
                                    class="flex-shrink-0 border rounded-lg cursor-pointer p-2 transition @if($tapaSeleccionada == $tapa->id) border-cyan-600 ring-2 ring-cyan-400 @endif">

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
                    <div class="border rounded-lg p-3 bg-white">
                        <h3 class="text-center font-semibold mb-3">Elige una Etiqueta</h3>
                        <div class="flex overflow-x-auto gap-4 py-2">
                            @forelse($etiquetas as $etiqueta)
                                @php
                                    $sucursalEtiqueta = $etiqueta->existencias->first()->sucursal->nombre ?? 'Sin sucursal';
                                @endphp

                                <div wire:click="$set('etiquetaSeleccionada', {{ $etiqueta->id }})"
                                    class="flex-shrink-0 border rounded-lg cursor-pointer p-2 transition @if($etiquetaSeleccionada == $etiqueta->id) border-cyan-600 ring-2 ring-cyan-400 @endif">

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
                    <div class="modal-footer">
                        <button wire:click="$set('modalProducto', false)" class="btn-cyan" title="Cerrar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                                <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
                            </svg>
                            CERRAR
                        </button>
                        <button wire:click="agregarAlCarritoDesdeModal" class="btn-cyan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                <path d="M11.5 17h-5.5v-14h-2" />
                                <path d="M6 5l14 1l-1 7h-13" />
                                <path d="M15 19l2 2l4 -4" />
                            </svg>
                            añadir al carrito
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif


    @if($mostrarCarrito)
        <div class="modal-overlay">
            <div class="modal-box">
                <div class="modal-content flex flex-col gap-4">
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

                        <div class="modal-footer">
                            <button wire:click="$set('mostrarCarrito', false)" class="btn-cyan" title="Cerrar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                                    <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
                                </svg>
                                CERRAR
                            </button>
                            <button wire:click="hacerPedido" class="btn-cyan">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M14 4l0 4l-6 0l0 -4" />
                                </svg>
                                Realiza Pedido
                            </button>
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-10">El carrito está vacío.</p>
                        <div class="modal-footer flex justify-center mt-4">
                            <button wire:click="$set('mostrarCarrito', false)" class="btn-cyan" title="Cerrar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                                    <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
                                </svg>
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
            <div class="modal-box">
                <div class="modal-content flex flex-col gap-4">

                    <h2 class="text-2xl font-bold text-center text-teal-700 mb-4">
                        Mis Pedidos ({{ count($pedidosCliente) }})
                    </h2>
                    @if(count($pedidosCliente) > 0)
                        <div class="flex flex-col gap-4 max-h-[80vh] overflow-y-auto pr-2">

                            @foreach($pedidosCliente as $pedido)
                                    @php
                                        $pedidoId = $pedido['id'];
                                        $expandido = $expandidoPedidos[$pedidoId] ?? false;
                                    @endphp

                                    <div class="border rounded-xl p-4 bg-white shadow-sm flex flex-col gap-2">

                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="font-semibold text-gray-700">Código: {{ $pedido['codigo'] }}</p>
                                                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $pedido['estado'] == 0 ? 'bg-yellow-200 text-yellow-800' :
                                ($pedido['estado'] == 1 ? 'bg-green-200 text-green-800' :
                                    ($pedido['estado'] == 2 ? 'bg-blue-200 text-blue-800' :
                                        'bg-gray-200 text-gray-800')) }}">
                                                    {{ $pedido['estado'] == 0 ? 'Pendiente de pago' :
                                ($pedido['estado'] == 1 ? 'Pagado' :
                                    ($pedido['estado'] == 2 ? 'Entregado' : 'Desconocido')) }}
                                                </span>
                                            </div>
                                            <button wire:click="$toggle('expandidoPedidos.{{ $pedidoId }}')"
                                                class="px-3 py-1 bg-teal-500 text-white rounded hover:bg-teal-600 transition">
                                                {{ $expandido ? 'Ocultar detalles' : 'Ver detalles' }}
                                            </button>
                                        </div>

                                        @if($expandido)
                                            <div class="mt-4 flex flex-col gap-4">
                                                <div class="flex flex-col gap-2">
                                                    <p class="text-sm font-medium text-gray-600">Método de pago:</p>
                                                    <div class="flex flex-wrap gap-2">
                                                        <button wire:click="actualizarMetodoPago({{ $pedido['id'] }}, 0)"
                                                            class="px-3 py-1 rounded-md text-sm font-semibold transition {{ $pedido['metodo_pago'] == 0 ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                                                            QR
                                                        </button>
                                                        <button wire:click="actualizarMetodoPago({{ $pedido['id'] }}, 1)"
                                                            class="px-3 py-1 rounded-md text-sm font-semibold transition {{ $pedido['metodo_pago'] == 1 ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                                                            Efectivo
                                                        </button>
                                                        <button wire:click="actualizarMetodoPago({{ $pedido['id'] }}, 2)"
                                                            class="px-3 py-1 rounded-md text-sm font-semibold transition {{ $pedido['metodo_pago'] == 2 ? 'bg-purple-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                                                            Crédito
                                                        </button>
                                                    </div>
                                                </div>

                                                @if(!empty($pedido['detalles']))
                                                    <div class="flex flex-col gap-4 pt-2 border-t">
                                                        @foreach($pedido['detalles'] as $detalle)
                                                            @php
                                                                $producto = $detalle['producto'] ?? $detalle['otro'] ?? null;
                                                                $descripcion = $producto['descripcion'] ?? '';
                                                                $imagenProd = $producto['imagen'] ?? null;
                                                                $tapa = $detalle['tapa'] ?? null;
                                                                $imagenTapa = $tapa['imagen'] ?? null;
                                                                $etiqueta = $detalle['etiqueta'] ?? null;
                                                                $imagenEtiqueta = $etiqueta['imagen'] ?? null;
                                                            @endphp

                                                            <div class="flex flex-col gap-4 border rounded-lg p-3 bg-gray-50">
                                                                @if($imagenProd)
                                                                    <img src="{{ asset('storage/' . $imagenProd) }}"
                                                                        class="w-full max-h-[400px] rounded-lg border object-contain">
                                                                @endif
                                                                <p class="font-semibold text-gray-800 text-lg">{{ $descripcion }}</p>
                                                                <p class="text-sm text-gray-600">Cantidad: {{ $detalle['cantidad'] }}</p>

                                                                @if($tapa)
                                                                    <div class="flex flex-col md:flex-row items-center gap-4">
                                                                        @if($imagenTapa)
                                                                            <img src="{{ asset('storage/' . $imagenTapa) }}"
                                                                                class="w-full md:w-1/3 max-h-[300px] rounded-lg border object-contain">
                                                                        @endif
                                                                        <p class="text-sm text-gray-600">Tapa: {{ $tapa['descripcion'] ?? '-' }}</p>
                                                                    </div>
                                                                @endif

                                                                @if($etiqueta)

                                                                    <div class="flex flex-col md:flex-row items-center gap-4">
                                                                        @if($imagenEtiqueta)
                                                                            <img src="{{ asset('storage/' . $imagenEtiqueta) }}"
                                                                                class="w-full md:w-1/3 max-h-[300px] rounded-lg border object-contain">
                                                                        @endif
                                                                        <p class="text-sm text-gray-600">Etiqueta: {{ $etiqueta['descripcion'] ?? '-' }}
                                                                        </p>
                                                                    </div>

                                                                @endif

                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                @if(!empty($pedido['pedido']['pago_pedidos']))
                                                    <div class="flex flex-col gap-3 pt-4 border-t">
                                                        <h4 class="font-semibold text-gray-700">Pagos realizados:</h4>
                                                        @foreach($pedido['pedido']['pago_pedidos'] as $pago)
                                                            <div class="flex flex-col gap-3 bg-gray-100 p-3 rounded-lg shadow-sm">

                                                                <div class="flex flex-col md:flex-row items-center justify-between gap-4">

                                                                    <div class="flex items-center gap-3 flex-wrap">
                                                                        <p class="text-sm font-medium">Monto: {{ number_format($pago['monto'], 2) }}
                                                                        </p>
                                                                        <p class="text-sm text-gray-600">Método:
                                                                            {{ $pago['metodo'] == 0 ? 'QR' : ($pago['metodo'] == 1 ? 'Efectivo' : 'Crédito') }}
                                                                        </p>

                                                                        @if(!empty($pago['imagen_comprobante']))
                                                                            <img src="{{ asset('storage/' . $pago['imagen_comprobante']) }}"
                                                                                class="w-full md:w-1/3 max-h-[400px] rounded-lg border object-contain"
                                                                                alt="Comprobante">
                                                                        @endif
                                                                    </div>

                                                                    @if($pago['metodo'] == 0 && !empty($pago['sucursal_pago']['imagen_qr']))
                                                                        <button wire:click="verQr({{ $pago['id'] }})"
                                                                            class="px-4 py-2 bg-blue-500 text-white rounded text-sm mt-2 md:mt-0">
                                                                            Ver QR
                                                                        </button>
                                                                    @endif
                                                                </div>

                                                                <div class="flex flex-wrap gap-2 mt-2 items-center">
                                                                    @if(empty($pago['imagen_comprobante']))
                                                                        <input type="file" wire:model="archivoPago"
                                                                            wire:click="$set('pagoSeleccionado', {{ $pago['id'] }})" class="text-sm">
                                                                        <button wire:click="subirComprobante({{ $pago['id'] }})"
                                                                            class="px-3 py-1 bg-green-500 text-white rounded text-sm">
                                                                            Subir
                                                                        </button>
                                                                    @else
                                                                        <button wire:click="eliminarComprobante({{ $pago['id'] }})"
                                                                            class="px-3 py-1 bg-red-500 text-white rounded text-sm">
                                                                            Eliminar
                                                                        </button>
                                                                        <div class="flex items-center gap-2">
                                                                            <input type="file" wire:model="archivoPago"
                                                                                wire:click="$set('pagoSeleccionado', {{ $pago['id'] }})"
                                                                                class="text-sm">
                                                                            <button wire:click="subirComprobante({{ $pago['id'] }})"
                                                                                class="px-3 py-1 bg-blue-500 text-white rounded text-sm">
                                                                                Reemplazar
                                                                            </button>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <div class="flex justify-center pt-2">
                                                    <button wire:click="eliminarSolicitud({{ $pedido['id'] }})"
                                                        class="text-red-600 font-semibold hover:text-red-800 transition px-3 py-1 border border-red-600 rounded-md">
                                                        Eliminar Pedido
                                                    </button>
                                                </div>

                                            </div>
                                        @endif

                                    </div>
                            @endforeach

                        </div>

                        <div class="modal-footer">
                            <button wire:click="$set('modalPedidosCliente', false)" class="btn-cyan" title="Cerrar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                                    <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
                                </svg>
                                CERRAR
                            </button>
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-10">No tienes pedidos.</p>
                    @endif
                </div>
            </div>
        </div>
    @endif


    @if($modalVerQR && $qrSeleccionado)
        <div class="modal-overlay">
            <div class="modal-box max-w-3xl w-full p-6 flex flex-col items-center gap-4">

                <h3 class="text-2xl font-bold text-teal-700 text-center">QR de Pago</h3>

                <div class="w-full flex justify-center">
                    <img src="{{ asset('storage/' . $qrSeleccionado) }}"
                        class="w-full max-w-[500px] max-h-[500px] object-contain border rounded-lg shadow-lg"
                        alt="QR de Pago">
                </div>

                <div class="flex gap-4 mt-4">
                    <button wire:click="$set('modalVerQR', false)"
                        class="px-6 py-2 bg-cyan-500 text-white font-semibold rounded-lg hover:bg-cyan-600 transition">
                        Cerrar
                    </button>

                    <a href="{{ asset('storage/' . $qrSeleccionado) }}" download
                        class="px-6 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition">
                        Descargar QR
                    </a>
                </div>

            </div>
        </div>
    @endif




</div>
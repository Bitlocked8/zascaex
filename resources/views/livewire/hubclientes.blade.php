<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl mx-auto px-4">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6
                    bg-cyan-600 text-white rounded-3xl px-6 py-5 shadow-lg">
            <div class="flex flex-wrap justify-center xl:justify-start gap-3">
                <button wire:click="verMisPedidos"
                    class="px-4 py-2 rounded-xl bg-cyan-700 hover:bg-cyan-800 transition font-semibold shadow">
                    Ver mis compras
                </button>

                <button wire:click="$toggle('mostrarCarrito')"
                    class="px-4 py-2 rounded-xl bg-cyan-700 hover:bg-cyan-800 transition font-semibold shadow">
                    Productos añadidos ({{ count($carrito) }})
                </button>
            </div>
            <div class="flex flex-wrap justify-center gap-3">
                @forelse($sucursales as $s)
                            <button type="button" wire:click="$set('sucursalFiltro', {{ $s->id }})" class="px-4 py-3 rounded-xl border border-white/40 text-sm font-semibold transition
                                                            {{ $sucursalFiltro == $s->id
                    ? 'bg-white text-cyan-700 shadow-lg'
                    : 'bg-cyan-700 text-white hover:bg-cyan-800' }}">
                                <div class="flex flex-col text-center">
                                    <span class="font-bold">{{ $s->nombre }}</span>
                                    <span class="text-xs opacity-90">{{ $s->empresa?->correo ?? 'Sin empresa' }}</span>
                                    <span class="text-xs opacity-90">{{ $s->telefono ?? 'Sin teléfono' }}</span>
                                </div>
                            </button>
                @empty
                    <span class="text-sm opacity-80">No hay sucursales</span>
                @endforelse
            </div>
            <div class="flex flex-col gap-2 text-center xl:text-right text-sm font-semibold">
                <span>
                    📦 <strong>Cochabamba:</strong> envíos excepto Beni y Santa Cruz
                </span>
                <span>
                    🚚 <strong>Santa Cruz:</strong> solo Beni y Santa Cruz
                </span>
                <span>
                    ⏰ <strong>Horario de atencion:</strong> 08:00 a 14:00
                </span>
            </div>

        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-6">

            @forelse ($productos as $p)
                @php
                    $modelo = $p['modelo'];
                    $existencia = $modelo->existencias->firstWhere('sucursal_id', $sucursalFiltro);
                    $sucursal = $existencia->sucursal->nombre
                        ?? $modelo->existencias->first()?->sucursal->nombre
                        ?? 'Sin sucursal';

                    $precioAprox = ($modelo->precioReferencia && $modelo->paquete)
                        ? $modelo->precioReferencia * $modelo->paquete
                        : null;
                @endphp

                <div class="bg-white rounded-3xl shadow-md border border-cyan-100
                                hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col">

                    <div class="flex justify-center items-center h-44 bg-cyan-50">
                        @if($modelo->imagen)
                            <img src="{{ asset('storage/' . $modelo->imagen) }}" class="h-full object-contain p-4">
                        @else
                            <span class="text-cyan-300 text-sm font-semibold">Sin imagen</span>
                        @endif
                    </div>

                    <div class="p-4 text-center flex flex-col gap-3 flex-1">
                        <h2 class="font-extrabold text-cyan-700 text-lg leading-tight">
                            {{ $modelo->descripcion }}
                            <span class="text-cyan-500 text-sm">{{ $modelo->unidad }}</span>
                        </h2>

                        <p class="text-sm font-semibold text-cyan-800">
                            Precio aprox:
                            <span class="block text-gray-800 text-base font-bold">
                                {{ $precioAprox ? number_format($precioAprox, 2) . ' Bs' : '-' }}
                            </span>
                        </p>

                        <p class="text-xs text-gray-600">
                            Sucursal: <span class="font-semibold">{{ $sucursal }}</span>
                        </p>

                        <button wire:click="abrirModalProducto('{{ $p['uid'] }}')" class="mt-auto w-full bg-cyan-500 text-white py-2 rounded-xl
                                        hover:bg-cyan-600 transition font-semibold">
                            Seleccionar
                        </button>
                    </div>
                </div>

            @empty
                <p class="col-span-full text-center text-gray-500 text-lg">
                    No hay productos disponibles
                </p>
            @endforelse

        </div>
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
                        <label class="text-u text-center font-bold">coloca una Cantidad de paquetes(minimo 7):</label>
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
                            <button wire:click="cotizar" class="btn-cyan">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m0 0l-6-6m6 6H3" />
                                </svg>
                                Cotizar
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
                <button wire:click="$set('modalPedidosCliente', false)" class="absolute top-4 right-4 text-cyan-600 hover:text-red-600
                   bg-white rounded-full p-2 shadow transition" title="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6L6 18" />
                        <path d="M6 6l12 12" />
                    </svg>
                </button>
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

                                    $estado = $pedido['estado_real'] ?? $pedido['estado'];
                                    $estadoTexto = $estado == 0 ? 'En Revisión' : ($estado == 1 ? 'Asignacion de Pagos' : ($estado == 2 ? 'Completado' : 'Desconocido'));
                                    $estadoColor = $estado == 0 ? 'text-yellow-800' : ($estado == 1 ? 'text-blue-800' : ($estado == 2 ? 'text-green-800' : 'text-gray-800'));
                                @endphp

                                <div class="border rounded-xl p-4 bg-white shadow-sm flex flex-col gap-2">

                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-semibold text-gray-700">Código: {{ $pedido['codigo'] }}</p>
                                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $estadoColor }}">
                                                {{ $estadoTexto }}
                                            </span>
                                        </div>

                                        <button wire:click="$toggle('expandidoPedidos.{{ $pedidoId }}')" class="btn-cyan">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M13 5h8" />
                                                <path d="M13 9h5" />
                                                <path d="M13 15h8" />
                                                <path d="M13 19h5" />
                                                <path
                                                    d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                                <path
                                                    d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                            </svg>
                                            {{ $expandido ? 'Ocultar detalles' : 'Ver detalles' }}
                                        </button>
                                    </div>

                                    @if($expandido)
                                        <div class="mt-4 flex flex-col gap-4">
                                            @if($estado == 0)
                                                <div class="flex flex-col gap-2 items-center">
                                                    <p class="text-sm font-medium text-gray-600 text-center">Selecciona un método de pago
                                                        inicial:
                                                    </p>

                                                    <div class="flex flex-wrap gap-2 justify-center">
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
                                            @endif
                                            @if(!empty($pedido['detalles']))
                                                <div
                                                    class="grid gap-6 pt-4 border-t {{ count($pedido['detalles']) == 1 ? 'grid-cols-1 place-items-center' : 'grid-cols-1 md:grid-cols-2' }}">
                                                    @foreach($pedido['detalles'] as $detalle)
                                                        @php
                                                            $producto = $detalle['producto'] ?? $detalle['otro'] ?? null;
                                                            $descripcion = $producto['descripcion'] ?? '';
                                                            $imagenProd = $producto['imagen'] ?? null;
                                                            $sucursalProd = $producto['existencias'][0]['sucursal']['nombre'] ?? '-';

                                                            $tapa = $detalle['tapa'] ?? null;
                                                            $imagenTapa = $tapa['imagen'] ?? null;

                                                            $etiqueta = $detalle['etiqueta'] ?? null;
                                                            $imagenEtiqueta = $etiqueta['imagen'] ?? null;
                                                        @endphp

                                                        <div
                                                            class="border rounded-xl p-4 bg-white shadow-sm flex flex-col gap-5 w-full max-w-xl">
                                                            <div class="flex flex-col items-center gap-3">
                                                                @if($imagenProd)
                                                                    <img src="{{ asset('storage/' . $imagenProd) }}"
                                                                        class="w-40 h-40 object-contain rounded-lg border">
                                                                @endif
                                                                <p class="font-semibold text-u text-center text-xl">{{ $descripcion }}</p>
                                                                <p class="text-sm text-gray-600"><span class="font-semibold">Sucursal:</span>
                                                                    {{ $sucursalProd }}</p>
                                                                <p class="text-sm text-gray-600">Cantidad: {{ $detalle['cantidad'] }} paquetes
                                                                </p>
                                                            </div>

                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-4">
                                                                @if($tapa)
                                                                    <div class="flex flex-col items-center gap-2">
                                                                        @if($imagenTapa)
                                                                            <img src="{{ asset('storage/' . $imagenTapa) }}"
                                                                                class="w-32 h-32 object-contain rounded-lg border">
                                                                        @endif
                                                                        <p class="text-sm font-semibold">Tapa</p>
                                                                        <p class="text-sm">{{ $tapa['descripcion'] }}</p>
                                                                    </div>
                                                                @endif

                                                                @if($etiqueta)
                                                                    <div class="flex flex-col items-center gap-2">
                                                                        @if($imagenEtiqueta)
                                                                            <img src="{{ asset('storage/' . $imagenEtiqueta) }}"
                                                                                class="w-32 h-32 object-contain rounded-lg border">
                                                                        @endif
                                                                        <p class="text-sm font-semibold">Etiqueta</p>
                                                                        <p class="text-sm">{{ $etiqueta['descripcion'] }}</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if(!empty($pedido['pedido']['pago_pedidos']))
                                                <div class="flex flex-col gap-3 pt-4 border-t items-center w-full">
                                                    <h4 class="font-semibold text-gray-700 text-center w-full">Pagos realizados:</h4>

                                                    @foreach($pedido['pedido']['pago_pedidos'] as $pago)
                                                        @php
                                                            $pagoEstadoTexto = $pago['estado'] == 0 ? 'Pago En revision' : 'Pagado';
                                                            $pagoEstadoColor = $pago['estado'] == 0 ? 'text-yellow-800' : 'text-green-800';
                                                        @endphp

                                                        <div class="flex flex-col gap-3 bg-gray-100 p-3 rounded-lg shadow-sm w-full max-w-2xl">
                                                            <div class="flex flex-col md:flex-row items-center justify-between gap-4">

                                                                <div
                                                                    class="flex items-center gap-3 flex-wrap justify-center md:justify-start w-full">
                                                                    <p class="text-u font-bold">Monto: {{ number_format($pago['monto'], 2) }}
                                                                    </p>


                                                                    <p class="text-sm text-gray-600">
                                                                        Método:
                                                                        {{ $pago['metodo'] == 0 ? 'QR' : ($pago['metodo'] == 1 ? 'Efectivo' : 'Crédito') }}
                                                                    </p>

                                                                    <p class="text-sm font-semibold {{ $pagoEstadoColor }}">
                                                                        Estado: {{ $pagoEstadoTexto }}
                                                                    </p>

                                                                    @if(!empty($pago['imagen_comprobante']))
                                                                        <div class="w-full flex justify-center">
                                                                            <img src="{{ asset('storage/' . $pago['imagen_comprobante']) }}"
                                                                                class="w-full md:w-1/2 max-h-[400px] rounded-lg border object-contain"
                                                                                alt="Comprobante">
                                                                        </div>
                                                                    @endif

                                                                    @if($pago['metodo'] == 0 && !empty($pago['sucursal_pago']['imagen_qr']))
                                                                        <button wire:click="verQr({{ $pago['id'] }})" class="btn-cyan">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                                <path d="M13 5h8" />
                                                                                <path d="M13 9h5" />
                                                                                <path d="M13 15h8" />
                                                                                <path d="M13 19h5" />
                                                                                <path
                                                                                    d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                                                                <path
                                                                                    d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                                                            </svg>
                                                                            Ver QR
                                                                        </button>
                                                                    @endif
                                                                </div>


                                                            </div>

                                                            @if($pago['metodo'] == 0 && $pago['estado'] == 0)
                                                                <div class="flex flex-wrap gap-2 mt-2 items-center justify-center">
                                                                    @if(empty($pago['imagen_comprobante']))
                                                                        <input type="file" wire:model="archivoPago"
                                                                            wire:click="$set('pagoSeleccionado', {{ $pago['id'] }})" class="text-sm">
                                                                        <button wire:click="subirComprobante({{ $pago['id'] }})"
                                                                            class="px-3 py-1 bg-green-500 text-white rounded text-sm">
                                                                            Subir imagen
                                                                        </button>
                                                                    @else
                                                                        <button wire:click="eliminarComprobante({{ $pago['id'] }})"
                                                                            class="px-3 py-1 bg-red-500 text-white rounded text-sm">
                                                                            Eliminar imagen
                                                                        </button>

                                                                        <div class="flex items-center gap-2 justify-center">
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
                                                            @endif

                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif



                                            @if($estado == 0)
                                                <div class="flex justify-center pt-2">
                                                    <button wire:click="eliminarSolicitud({{ $pedido['id'] }})"
                                                        class="text-red-600 font-semibold hover:text-red-800 transition px-3 py-1 border border-red-600 rounded-md">
                                                        Eliminar Pedido
                                                    </button>
                                                </div>
                                            @endif
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
            <div class="modal-box">
                <div class="modal-content flex flex-col gap-4">
                    <h3 class="text-2xl font-bold text-teal-700 text-center">
                        QR de Pago
                    </h3>
                    <div class="w-full flex justify-center">
                        <img src="{{ asset('storage/' . $qrSeleccionado) }}"
                            class="w-100 h-100 object-contain rounded-xl shadow-md border border-cyan-300" alt="QR de Pago">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="$set('modalVerQR', false)" class="btn-cyan" title="Cerrar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                            <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
                        </svg>
                        CERRAR
                    </button>
                    <a href="{{ asset('storage/' . $qrSeleccionado) }}" download class="btn-cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            <path d="M12 17v-6" />
                            <path d="M9.5 14.5l2.5 2.5l2.5 -2.5" />
                        </svg>
                        Descargar QR
                    </a>

                </div>
            </div>
        </div>
    @endif

    @if($modalCotizacion)
        <div class="modal-overlay">
            <div class="modal-box max-w-lg">
                <div class="modal-content flex flex-col gap-4">

                    <h2 class="text-xl font-bold text-center text-slate-800">
                        Cotización
                    </h2>
                    <div class="flex flex-col gap-4 max-h-80 overflow-y-auto">
                        @foreach($carrito as $item)
                            @php
                                $modelo = $item['modelo'];
                                $cantidadPaquetes = $item['cantidad'];
                                $unidadesPorPaquete = $modelo->paquete ?? 1;
                                $precioUnitario = $modelo->precioReferencia ?? 0;
                                $precioPaquete = $precioUnitario * $unidadesPorPaquete;
                                $totalItem = $precioPaquete * $cantidadPaquetes;
                            @endphp

                            <div class="flex flex-col border rounded-lg p-3 bg-gray-50 gap-2">
                                <span class="font-semibold text-sm">{{ $modelo->descripcion }}</span>
                                <span class="text-xs text-gray-600">Sucursal:
                                    {{ $modelo->existencias->first()->sucursal->nombre ?? 'Sin sucursal' }}</span>
                                <span class="text-xs text-gray-600">Contenido: {{ $modelo->tipoContenido ?? '-' }}</span>
                                <span class="text-xs text-gray-600">Tipo de producto: {{ $modelo->tipoProducto ?? '-' }}</span>
                                <span class="text-xs text-gray-600">Capacidad:
                                    {{ number_format($modelo->capacidad ?? 0, 2) }}</span>
                                <span class="text-xs text-gray-600">Precio unitario aproximado: Bs
                                    {{ number_format($precioUnitario, 2) }}</span>
                                <span class="text-xs text-gray-600">Unidades por paquete: {{ $unidadesPorPaquete }}</span>
                                <span class="text-xs text-gray-600">Precio aproximado por paquete: Bs
                                    {{ number_format($precioPaquete, 2) }}</span>
                                <span class="text-xs text-gray-600">Observaciones:
                                    {{ $modelo->observaciones ?? 'Sin observaciones' }}</span>
                                <span class="text-xs text-gray-600">Cantidad de paquetes: {{ $cantidadPaquetes }}</span>
                                <span class="font-semibold text-right text-slate-800">Total: Bs
                                    {{ number_format($totalItem, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t pt-4 flex flex-col gap-2">
                        <div class="flex justify-between text-sm">
                            <span class="font-medium text-gray-600">Subtotal</span>
                            <span class="font-semibold">
                                Bs {{ number_format($cotizacion['subtotal'], 2) }}
                            </span>
                        </div>

                        <div class="flex justify-between text-lg font-bold text-slate-900">
                            <span>Total</span>
                            <span>
                                Bs {{ number_format($cotizacion['total'], 2) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex justify-center gap-3 mt-4">
                        <button wire:click="$set('modalCotizacion', false)"
                            class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800 hover:bg-gray-300 transition">
                            Cerrar
                        </button>

                        <button wire:click="descargarCotizacionPdf"
                            class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 16v-4m0 0V8m0 4h4m-4 0H8m10 4a2 2 0 002-2V7a2 2 0 00-2-2h-4l-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2h12z" />
                            </svg>

                            Descargar PDF
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif



</div>
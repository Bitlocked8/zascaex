<div class="p-2 mt-20 flex justify-center bg-transparent">
    <div class="w-full max-w-screen-xl mx-auto px-4">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6
                    bg-transparent text-white rounded-3xl px-6 py-5 shadow-lg">
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
            <div class="flex flex-col gap-2 text-center xl:text-right text-sm font-semibold">
                <span>
                    <strong>Horarios de atencion de pedidos:</strong> 08:00 a 16:00
                </span>
            </div>

        </div>
        <br>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @forelse ($productos as $p)
            @php
            $modelo = $p['modelo'];
            $existencia = $modelo->existencias->first();
            $sucursal = $existencia?->sucursal->nombre ?? 'Sin sucursal';

            $precioNormal = $modelo->precioReferencia ?? null;
            $precioFacturado = $modelo->precioAlternativo ?? null;

            $precioPaqueteNormal = ($precioNormal && $modelo->paquete) ? $precioNormal * $modelo->paquete : null;
            $precioPaqueteFacturado = ($precioFacturado && $modelo->paquete) ? $precioFacturado * $modelo->paquete : null;
            @endphp

            <div class="bg-white rounded-2xl shadow-md border border-cyan-100 flex flex-col">

                <div class="flex justify-center items-center h-44 bg-cyan-50 rounded-t-2xl">

                    @if($modelo->imagen)
                    <img src="{{ asset('storage/' . $modelo->imagen) }}" class="h-full object-contain p-4">
                    @else
                    <span class="text-cyan-300 text-sm font-semibold">Sin imagen</span>
                    @endif
                </div>
                <div class="p-3 text-center flex flex-col gap-2 flex-1 text-sm">

                    <h2 class="font-bold text-cyan-700 leading-tight">
                        {{ $modelo->descripcion }}
                        <span class="text-cyan-500">{{ $modelo->unidad }}</span>
                    </h2>

                    <p class="text-gray-800">
                        <strong>Precio por unidad:</strong> {{ $precioNormal ? number_format($precioNormal, 2) . ' Bs' : '-' }}
                    </p>
                    <p class="text-gray-800">
                        <strong>Precio por unidad facturado:</strong> {{ $precioFacturado ? number_format($precioFacturado, 2) . ' Bs' : '-' }}
                    </p>

                    @if($modelo->paquete)
                    <p class="text-gray-600">
                        <strong>Por paquete:</strong> bas: {{ $precioPaqueteNormal ? number_format($precioPaqueteNormal, 2) . ' Bs' : '-' }},
                        fac: {{ $precioPaqueteFacturado ? number_format($precioPaqueteFacturado, 2) . ' Bs' : '-' }}
                    </p>
                    @endif

                    <p class="text-gray-500">
                        Sucursal: <span class="font-semibold">{{ $sucursal }}</span>
                    </p>

                    <button wire:click="abrirModalProducto('{{ $p['uid'] }}')"
                        class="mt-auto w-full bg-cyan-500 text-white py-2 rounded-xl hover:bg-cyan-600 transition font-semibold text-sm">
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

        <button
            onclick="scrollToTop()"
            class="fixed bottom-24 right-4 z-50 bg-cyan-600 hover:bg-cyan-700 text-white
           p-3 rounded-full shadow-lg transition"
            title="Subir arriba">
            arriba
        </button>
        <button
            onclick="scrollToBottom()"
            class="fixed bottom-8 right-4 z-50 bg-cyan-600 hover:bg-cyan-700 text-white
           p-3 rounded-full shadow-lg transition"
            title="Bajar abajo">
            abajo
        </button>


    </div>




    @if($modalProducto)
    @php
    $modelo = $productoSeleccionado['modelo'];
    $sucursalProducto = $modelo->existencias->first()?->sucursal->nombre ?? 'Sin sucursal';

    $precioNormal = $modelo->precioReferencia ?? null;
    $precioFacturado = $modelo->precioAlternativo ?? null;

    $precioPaqueteNormal = ($precioNormal && $modelo->paquete) ? $precioNormal * $modelo->paquete : null;
    $precioPaqueteFacturado = ($precioFacturado && $modelo->paquete) ? $precioFacturado * $modelo->paquete : null;
    @endphp

    <div class="modal-overlay">
        <div class="modal-box max-w-3xl w-full p-4 sm:p-6">
            <div class="modal-content flex flex-col gap-4">

                <h2 class="text-center text-lg sm:text-2xl font-bold text-cyan-700">
                    {{ $modelo->descripcion }}
                    <span class="text-sm text-cyan-500">{{ $modelo->unidad ?? '-' }}</span>
                </h2>
                <p class="text-center text-gray-600 text-sm">
                    Sucursal: <span class="font-semibold">{{ $sucursalProducto }}</span>
                </p>
                <div class="flex justify-center">
                    @if(!empty($modelo->imagen))
                    <img
                        src="{{ asset('storage/' . $modelo->imagen) }}"
                        class="w-full max-w-3xl h-auto object-contain border rounded-lg p-2"
                        alt="Imagen del modelo">
                    @else
                    <div class="w-full max-w-3xl h-96 flex items-center justify-center bg-gray-200 text-gray-500 text-sm border rounded-lg p-2">
                        No hay imagen
                    </div>
                    @endif
                </div>


                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 bg-gray-50 border rounded-lg p-3 text-sm">
                    <p><span class="font-semibold">Contenido:</span> {{ $modelo->unidad ?? '-' }}</p>
                    <p><span class="font-semibold">Tipo de contenido:</span> {{ $modelo->tipoContenido ?? '-' }}</p>
                    <p><span class="font-semibold">Tipo de producto:</span> {{ $modelo->tipoProducto ?? '-' }}</p>
                    <p><span class="font-semibold">Capacidad:</span> {{ $modelo->capacidad ?? '-' }}</p>

                    <p><span class="font-semibold text-cyan-700">Precio normal:</span>
                        <span class="text-gray-800">{{ $precioNormal ? number_format($precioNormal, 2) . ' Bs' : '-' }}</span>
                    </p>
                    <p><span class="font-semibold text-cyan-700">Precio facturado:</span>
                        <span class="text-gray-800">{{ $precioFacturado ? number_format($precioFacturado, 2) . ' Bs' : '-' }}</span>
                    </p>

                    @if($modelo->paquete)
                    <p><span class="font-semibold text-cyan-700">Por paquete normal:</span>
                        <span class="text-gray-800">{{ $precioPaqueteNormal ? number_format($precioPaqueteNormal, 2) . ' Bs' : '-' }}</span>
                    </p>
                    <p><span class="font-semibold text-cyan-700">Por paquete facturado:</span>
                        <span class="text-gray-800">{{ $precioPaqueteFacturado ? number_format($precioPaqueteFacturado, 2) . ' Bs' : '-' }}</span>
                    </p>
                    @endif

                    <p class="col-span-full"><span class="font-semibold">Observaciones:</span> {{ $modelo->observaciones ?? 'Sin observaciones' }}</p>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <label class="font-semibold text-sm">Cantidad de paquetes (mínimo 5)</label>
                    <input type="number" wire:model="cantidadSeleccionada" min="5" class="input-minimal w-24 text-center">
                </div>
                @if($productoSeleccionado['tipo_modelo'] === 'producto')
                <div class="flex flex-col gap-3">
                    @if($tapas->count())
                    <div>
                        <h3 class="text-center font-semibold mb-1 text-sm">Elige una Tapa</h3>
                        <div class="flex overflow-x-auto gap-2 py-2">
                            @foreach($tapas as $tapa)
                            @php $sucursalTapa = $tapa->existencias->first()?->sucursal->nombre ?? 'Sin sucursal'; @endphp
                            <div wire:click="$set('tapaSeleccionada', {{ $tapa->id }})"
                                class="flex-shrink-0 border rounded-lg cursor-pointer p-1 transition {{ $tapaSeleccionada == $tapa->id ? 'border-cyan-600 ring-2 ring-cyan-400' : '' }}">
                                <img src="{{ $tapa->imagen ? asset('storage/' . $tapa->imagen) : '' }}"
                                    class="h-24 w-24 sm:h-28 sm:w-28 object-contain mx-auto border rounded-lg p-1"
                                    alt="{{ $tapa->descripcion }}">
                                <p class="text-xs text-center mt-1">{{ $tapa->descripcion }}</p>
                                <p class="text-xs text-gray-500 text-center">{{ $sucursalTapa }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($etiquetas->count())
                    <div>
                        <h3 class="text-center font-semibold mb-1 text-sm">Elige una Etiqueta</h3>
                        <div class="flex overflow-x-auto gap-2 py-2">
                            @foreach($etiquetas as $etiqueta)
                            @php $sucursalEtiqueta = $etiqueta->existencias->first()?->sucursal->nombre ?? 'Sin sucursal'; @endphp
                            <div wire:click="$set('etiquetaSeleccionada', {{ $etiqueta->id }})"
                                class="flex-shrink-0 border rounded-lg cursor-pointer p-1 transition {{ $etiquetaSeleccionada == $etiqueta->id ? 'border-cyan-600 ring-2 ring-cyan-400' : '' }}">
                                <img src="{{ $etiqueta->imagen ? asset('storage/' . $etiqueta->imagen) : '' }}"
                                    class="h-24 w-24 sm:h-28 sm:w-28 object-contain mx-auto border rounded-lg p-1"
                                    alt="{{ $etiqueta->descripcion }}">
                                <p class="text-xs text-center mt-1">{{ $etiqueta->descripcion }}</p>
                                <p class="text-xs text-gray-500 text-center">{{ $sucursalEtiqueta }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif

            </div>

            <!-- Footer botones -->
            <div class="modal-footer flex justify-center gap-2 mt-3">
                <button type="button" wire:click="$set('modalProducto', false)" class="btn-cyan">
                    CERRAR
                </button>
                <button type="button" wire:click="agregarAlCarritoDesdeModal" class="btn-cyan">
                    AÑADIR AL CARRITO
                </button>
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
        <div class="modal-box relative">
            <button wire:click="$set('modalPedidosCliente', false)"
                class="absolute top-4 right-4 text-cyan-600 hover:text-red-600   bg-white rounded-full p-2 shadow transition" title="Cerrar">
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
                <h3 class="text-center text-sm text-yellow-700 mb-4">
                    🔔 Recordatorio: los pedidos que no se atiendan serán eliminados automáticamente después de 12 horas.
                </h3>


                @if(count($pedidosCliente) > 0)
                <div class="flex flex-col gap-4 max-h-[80vh] overflow-y-auto pr-2">

                    @foreach($pedidosCliente as $pedido)
                    @php
                    $pedidoId = $pedido['id'];
                    $expandido = $expandidoPedidos[$pedidoId] ?? false;

                    $estado = $pedido['estado_real'] ?? $pedido['estado'];
                    $estadoTexto = $estado == 0 ? 'En Revisión' : ($estado == 1 ? 'Asignación de Pagos' : ($estado == 2 ? 'Completado' : 'Desconocido'));
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
                                {{ $expandido ? 'Ocultar detalles' : 'Ver detalles' }}
                            </button>
                        </div>
                        @if($expandido)
                        <div class="mt-4 flex flex-col gap-6">
                            @if($estado == 0)
                            <div class="flex flex-col items-center gap-3 p-4 bg-white rounded-xl shadow-md border">
                                <p class="text-sm font-medium text-gray-700 text-center">
                                    Selecciona un método de pago:
                                </p>
                                <div class="flex gap-3 mt-2">
                                    <button wire:click="actualizarMetodoPago({{ $pedido['id'] }}, 0)"
                                        class="px-4 py-2 rounded-md font-semibold transition {{ $pedido['metodo_pago'] == 0 ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                                        QR
                                    </button>
                                    <button wire:click="actualizarMetodoPago({{ $pedido['id'] }}, 1)"
                                        class="px-4 py-2 rounded-md font-semibold transition {{ $pedido['metodo_pago'] == 1 ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                                        Transferencia Bancaria
                                    </button>
                                </div>
                            </div>
                            @endif
                            @if(!empty($pedido['detalles']))
                            <div class="flex flex-col gap-4">
                                @foreach($pedido['detalles'] as $detalle)
                                @php
                                $producto = $detalle['producto'] ?? $detalle['otro'] ?? null;
                                $descripcion = $producto['descripcion'] ?? '';
                                $sucursalProd = $producto['existencias'][0]['sucursal']['nombre'] ?? '-';
                                $tapa = $detalle['tapa'] ?? null;
                                $etiqueta = $detalle['etiqueta'] ?? null;
                                @endphp

                                <div class="bg-white rounded-xl shadow-md border p-4 flex flex-col gap-4">
                                    <div class="text-center">
                                        <p class="text-xl font-semibold text-gray-800">{{ $descripcion }}</p>
                                        <p class="text-sm text-gray-600"><span class="font-semibold">Sucursal:</span> {{ $sucursalProd }}</p>
                                        <p class="text-sm text-gray-600"><span class="font-semibold">Cantidad:</span> {{ $detalle['cantidad'] }} paquetes</p>
                                    </div>

                                    @if($tapa || $etiqueta)
                                    <div class="flex flex-col gap-2">
                                        @if($tapa)
                                        <p class="text-sm"><span class="font-semibold">Tapa:</span> {{ $tapa['descripcion'] }}</p>
                                        @endif
                                        @if($etiqueta)
                                        <p class="text-sm"><span class="font-semibold">Etiqueta:</span> {{ $etiqueta['descripcion'] }}</p>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @endif
                            @if(!empty($pedido['pedido']['pagos']))
                            <div class="flex flex-col gap-3 mt-4 p-4 bg-gray-50 rounded-xl border">
                                <h3 class="font-semibold text-gray-700 mb-2">Pagos del pedido:</h3>
                                @foreach($pedido['pedido']['pagos'] as $pago)
                                <div class="flex justify-between items-center p-2 border rounded-md bg-white">
                                    <div class="flex flex-col">
                                        <span>Monto: {{ number_format($pago['monto'], 2) }}</span>
                                        <span>Estado: {{ $pago['estado'] ? 'Pagado' : 'Sin pagar' }}</span>
                                        <span>Método: {{ $pago['metodo'] == 0 ? 'QR' : 'Transferencia' }}</span>
                                    </div>
                                    @if($pago['metodo'] == 0 && !empty($pago['sucursal_pago']['imagen_qr']))
                                    <button wire:click="verQr({{ $pago['id'] }})"
                                        class="px-4 py-2 rounded-md bg-cyan-500 text-white font-semibold hover:bg-cyan-600 transition">
                                        Ver QR
                                    </button>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @endif
                            @if($estado == 0)
                            <div class="flex justify-center mt-4">
                                <button wire:click="eliminarSolicitud({{ $pedido['id'] }})"
                                    class="px-4 py-2 border border-red-600 text-red-600 font-semibold rounded-md hover:bg-red-600 hover:text-white transition">
                                    Eliminar Pedido
                                </button>
                            </div>
                            @endif

                        </div>
                        @endif

                    </div>
                    @endforeach
                </div>

                <div class="modal-footer mt-4 flex justify-center">
                    <button wire:click="$set('modalPedidosCliente', false)" class="btn-cyan" title="Cerrar">
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

    @if($modalVerQR && $pagoSeleccionado)
    <div class="modal-overlay">
        <div class="modal-box max-w-md">

            <div class="modal-content flex flex-col gap-4">

                <h3 class="text-2xl font-bold text-teal-700 text-center">
                    QR de Pago
                </h3>
                <div class="w-full flex justify-center">
                    <img
                        src="{{ asset('storage/' . $qrSeleccionado) }}"
                        class="w-64 h-64 object-contain rounded-xl shadow-md border"
                        alt="QR de Pago">
                </div>
                <div class="border-t pt-4 flex flex-col gap-3">
                    <label class="font-semibold text-gray-700">
                        Comprobante de pago
                    </label>

                    <input
                        type="file"
                        wire:model="archivoPago"
                        class="input-minimal w-full">
                    @php
                    $pago = \App\Models\PagoPedido::find($pagoSeleccionado);
                    @endphp

                    @if($archivoPago)
                    <img
                        src="{{ $archivoPago->temporaryUrl() }}"
                        class="max-w-xs rounded shadow mt-2">
                    @elseif($pago && $pago->archivo_comprobante)
                    <img
                        src="{{ Storage::url($pago->archivo_comprobante) }}"
                        class="max-w-xs rounded shadow mt-2">
                    @endif
                    <div class="flex gap-2 mt-2">
                        <button
                            wire:click="subirComprobante({{ $pagoSeleccionado }})"
                            class="btn-cyan flex-1">
                            Guardar comprobante
                        </button>

                        @if($pago && $pago->archivo_comprobante)
                        <button
                            wire:click="eliminarComprobante({{ $pagoSeleccionado }})"
                            class="px-4 py-2 bg-red-500 text-white rounded-md">
                            Eliminar
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer mt-4 flex justify-center gap-2">
                <button
                    wire:click="$set('modalVerQR', false)"
                    class="btn-cyan">
                    Cerrar
                </button>

                <a
                    href="{{ asset('storage/' . $qrSeleccionado) }}"
                    download
                    class="btn-cyan">
                    Descargar QR
                </a>
                @if($pago && $pago->archivo_factura)
                <a href="{{ Storage::url($pago->archivo_factura) }}" download class="btn-cyan">
                    Descargar factura
                </a>
                @endif
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
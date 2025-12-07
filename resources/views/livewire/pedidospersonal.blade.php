<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3
            class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto">
            Mis Pedidos Asignados
        </h3>
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="search"
                placeholder="Buscar por código de pedido, solicitud o cliente..." class="input-minimal w-full" />
        </div>

        @php
            $pedidosTotales = $distribuciones->flatMap(function ($dist) {
                return $dist->pedidos->filter(function ($pedido) {
                    $search = strtolower($this->search);
                    $codigoPedido = strtolower($pedido->codigo);
                    $codigoSolicitud = strtolower($pedido->solicitudPedido->codigo ?? '');
                    $nombreCliente = strtolower($pedido->solicitudPedido->cliente->nombre ?? '');

                    return str_contains($codigoPedido, $search)
                        || str_contains($codigoSolicitud, $search)
                        || str_contains($nombreCliente, $search);
                });
            });
        @endphp

        @if($pedidosTotales->isEmpty())
            <div class="col-span-full text-center py-4 text-gray-600">
                No hay pedidos que coincidan con la búsqueda.
            </div>
        @else
            @foreach($distribuciones as $dist)
                @php
                    $pedidosFiltrados = $dist->pedidos->filter(function ($pedido) {
                        $search = strtolower($this->search);
                        $codigoPedido = strtolower($pedido->codigo);
                        $codigoSolicitud = strtolower($pedido->solicitudPedido->codigo ?? '');
                        $nombreCliente = strtolower($pedido->solicitudPedido->cliente->nombre ?? '');

                        return str_contains($codigoPedido, $search)
                            || str_contains($codigoSolicitud, $search)
                            || str_contains($nombreCliente, $search);
                    });
                @endphp

                @foreach($pedidosFiltrados as $pedido)
                    <div class="card-teal flex flex-col gap-4 p-4">

                        <p class="text-cyan-700 uppercase font-semibold">
                            Pedido: {{ $pedido->codigo }}
                        </p>

                        <p><strong>Cliente:</strong> {{ $pedido->solicitudPedido->cliente->nombre ?? 'N/A' }}</p>

                        <p><strong>Código Solicitud:</strong> {{ $pedido->solicitudPedido->codigo ?? 'N/A' }}</p>

                        <p><strong>Coche:</strong> {{ $dist->coche->placa ?? 'N/A' }}</p>

                        <div class="mt-2">
                            <h5 class="font-semibold text-teal-600">Productos:</h5>
                            @foreach($pedido->detalles as $detalle)
                                <div class="mt-1 p-2 border-b">
                                    <p><strong>Producto:</strong> {{ $detalle->existencia->existenciable->descripcion ?? 'N/A' }}
                                    </p>
                                    <p><strong>Cantidad:</strong> {{ $detalle->cantidad }}
                                    </p>
                                    <p><strong>Tipo:</strong> {{ class_basename($detalle->existencia->existenciable_type ?? 'N/A') }}
                                    </p>
                                    <p>
                                        <strong>Sucursal:</strong> {{ $detalle->existencia->sucursal->nombre ?? 'N/A' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex gap-2 mt-3">
                            <button wire:click="cambiarEstadoPedido({{ $pedido->id }}, 0)" class="px-3 py-1 rounded-lg text-sm font-semibold border transition
                                {{ $pedido->estado_pedido == 0
                            ? 'bg-yellow-500 text-white border-yellow-600 shadow'
                            : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                                Preparando
                            </button>
                            <button wire:click="cambiarEstadoPedido({{ $pedido->id }}, 1)" class="px-3 py-1 rounded-lg text-sm font-semibold border transition
                                {{ $pedido->estado_pedido == 1
                            ? 'bg-blue-500 text-white border-blue-600 shadow'
                            : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                                En Revisión
                            </button>
                            <button wire:click="cambiarEstadoPedido({{ $pedido->id }}, 2)" class="px-3 py-1 rounded-lg text-sm font-semibold border transition
                                {{ $pedido->estado_pedido == 2
                            ? 'bg-green-600 text-white border-green-700 shadow'
                            : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                                Completado
                            </button>

                        </div>


                        <div class="flex justify-center mt-2">
                            <button wire:click="abrirModalPagosPedido({{ $pedido->id }})" class="btn-cyan"
                                title="Ver/Agregar Pagos">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M14.8 9a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1" />
                                    <path d="M12 7v10" />
                                </svg>
                                Pagos
                            </button>
                        </div>

                    </div>
                @endforeach
            @endforeach
        @endif

    </div>

    @if($modalPagos)
        <div class="modal-overlay">
            <div class="modal-box">
                <div class="modal-content flex flex-col gap-4">
                    <div class="space-y-4">

                        @foreach($pagos as $index => $pago)
                                    <div class="border p-4 rounded flex flex-col gap-2">


                                        <div class="flex justify-between items-center">
                                            <strong>Código: {{ $pago['codigo_pago'] }}</strong>
                                            <p class="text-sm text-gray-600">
                                                Fecha: {{ $pago['fecha_pago'] }}
                                            </p>

                                            <button type="button" wire:click="eliminarPagoPedido({{ $index }})" class="btn-cyan"
                                                title="Eliminar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 7l16 0" />
                                                    <path d="M10 11l0 6" />
                                                    <path d="M14 11l0 6" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                                Eliminar pago
                                            </button>
                                        </div>


                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                                            <div class="sm:col-span-2">
                                                <label class="font-semibold text-u">Monto (Requerido)</label>
                                                <input type="number" wire:model="pagos.{{ $index }}.monto" class="input-minimal"
                                                    min="0">
                                            </div>

                                            <div class="sm:col-span-2">
                                                <label class="font-semibold text-sm">Método de Pago</label>

                                                <div class="flex justify-center gap-3 mt-2">
                                                    <button type="button" wire:click="$set('pagos.{{ $index }}.metodo', 0)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition {{ $pagos[$index]['metodo'] === 0
                            ? 'bg-blue-700 text-white border-blue-800 shadow-md'
                            : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                                                        QR
                                                    </button>
                                                    <button type="button" wire:click="$set('pagos.{{ $index }}.metodo', 1)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition {{ $pagos[$index]['metodo'] === 1
                            ? 'bg-blue-700 text-white border-blue-800 shadow-md'
                            : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                                                        Efectivo
                                                    </button>
                                                    <button type="button" wire:click="$set('pagos.{{ $index }}.metodo', 2)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition {{ $pagos[$index]['metodo'] === 2
                            ? 'bg-blue-700 text-white border-blue-800 shadow-md'
                            : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                                                        Crédito
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="sm:col-span-2 mt-3">
                                                <label class="font-semibold text-sm block text-center">Pago Confirmado</label>

                                                <div class="flex justify-center mt-2">
                                                    <button type="button"
                                                        wire:click="$set('pagos.{{ $index }}.estado', {{ $pago['estado'] ? 0 : 1 }})"
                                                        class="px-6 py-2 rounded-lg text-white font-semibold border shadow-md transition {{ $pagos[$index]['estado']
                            ? 'bg-green-600 border-green-700 hover:bg-green-700'
                            : 'bg-gray-500 border-gray-600 hover:bg-gray-600' }}">
                                                        {{ $pagos[$index]['estado'] ? 'PAGADO' : 'NO PAGADO' }}
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="sm:col-span-2">
                                                <label class="text-u">Método QR auxiliar (Sucursal)</label>

                                                <div
                                                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-[180px] overflow-y-auto">


                                                    <button type="button" wire:click="$set('pagos.{{ $index }}.sucursal_pago_id', null)"
                                                        class="w-full p-4 rounded-lg border-2 text-center {{ empty($pagos[$index]['sucursal_pago_id']) ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800' }}">
                                                        <span class="font-medium">Ninguno </span>
                                                    </button>

                                                    @foreach($sucursalesPago as $sp)
                                                        <button type="button"
                                                            wire:click="$set('pagos.{{ $index }}.sucursal_pago_id', {{ $sp->id }})"
                                                            class="w-full p-4 rounded-lg border-2 text-center {{ $pagos[$index]['sucursal_pago_id'] == $sp->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800' }}">
                                                            <span class="font-medium">{{ $sp->nombre }}</span>

                                                            @if($sp->tipo)
                                                                <span class="bg-gray-600 text-white text-xs px-2 py-0.5 rounded-full mt-1">
                                                                    {{ $sp->tipo }}
                                                                </span>
                                                            @endif

                                                            @if($sp->sucursal)
                                                                <span class="text-gray-500 text-xs mt-1">(Sucursal:
                                                                    {{ $sp->sucursal->nombre }})</span>
                                                            @endif
                                                        </button>
                                                    @endforeach
                                                </div>

                                                @if(isset($pagos[$index]['sucursal_pago_id']) && $pagos[$index]['sucursal_pago_id'])
                                                    @php $metodo = $sucursalesPago->firstWhere('id', $pagos[$index]['sucursal_pago_id']); @endphp

                                                    @if($metodo && $metodo->imagen_qr)
                                                        <div class="mt-3 flex flex-col items-center space-y-2">
                                                            <img src="{{ Storage::url($metodo->imagen_qr) }}"
                                                                class="w-48 h-48 object-cover rounded shadow cursor-pointer"
                                                                wire:click="$set('imagenPreviewModal', '{{ Storage::url($metodo->imagen_qr) }}'); $set('modalImagenAbierta', true)">
                                                            <p class="text-sm text-gray-600 text-center">
                                                                {{ $metodo->nombre }} — {{ $metodo->tipo }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>


                                            <div class="sm:col-span-2">
                                                <label class="font-semibold text-sm">Imagen del comprobante</label>
                                                <input type="file" wire:model="pagos.{{ $index }}.imagen_comprobante"
                                                    class="input-minimal">

                                                @php
                                                    $imagenUrl = isset($pagos[$index]['imagen_comprobante'])
                                                        ? (is_string($pagos[$index]['imagen_comprobante'])
                                                            ? Storage::url($pagos[$index]['imagen_comprobante'])
                                                            : $pagos[$index]['imagen_comprobante']->temporaryUrl())
                                                        : null;
                                                @endphp

                                                @if($imagenUrl)
                                                    <div class="mt-2 flex flex-col items-center space-y-2">

                                                        <img src="{{ $imagenUrl }}"
                                                            class="w-80 h-80 object-cover rounded-lg shadow cursor-pointer"
                                                            wire:click="$set('imagenPreviewModal', '{{ $imagenUrl }}'); $set('modalImagenAbierta', true)">

                                                        @if(is_string($pagos[$index]['imagen_comprobante']))
                                                            <a href="{{ $imagenUrl }}" download class="btn-cyan">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                                    <path d="M7 11l5 5l5 -5" />
                                                                    <path d="M12 4l0 12" />
                                                                </svg>

                                                            </a>
                                                        @endif

                                                    </div>

                                                @endif

                                            </div>

                                        </div>
                                    </div>
                        @endforeach
                    </div>


                    <div class="modal-footer">

                        <button type="button" wire:click="agregarPagoPedido" class="btn-cyan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                                <path
                                    d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                            </svg>
                            añadir pago
                        </button>
                        <button type="button" wire:click="guardarPagosPedido" class="btn-cyan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M14 4l0 4l-6 0l0 -4" />
                            </svg>
                            guardar pago
                        </button>

                        <button type="button" wire:click="$set('modalPagos', false)" class="btn-cyan" title="Cerrar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                                <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
                            </svg>
                            CERRAR
                        </button>

                    </div>

                </div>
            </div>
        </div>
    @endif


</div>
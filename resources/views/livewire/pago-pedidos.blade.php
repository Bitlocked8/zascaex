<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl">

        <h3 class="text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto mb-4">
            Ajuste de precios
        </h3>

        <div class="flex items-center gap-2 mb-4 flex-wrap">
            <input type="text" wire:model.live="searchCliente" placeholder="Buscar por código o cliente..."
                class="input-minimal w-full sm:w-auto flex-1" />
        </div>

        <div class="overflow-auto max-h-[500px] border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-teal-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Código Pedido</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Cliente y Productos</th>
                        <th class="px-4 py-2 text-center text-teal-700 font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pedidos as $pedido)
                    <tr class="hover:bg-teal-50 align-top">
                        <td class="px-4 py-2">{{ $pedido->codigo }}</td>
                        <td class="px-4 py-2">
                            <div class="font-semibold">{{ $pedido->cliente?->nombre ?? $pedido->solicitudPedido?->cliente?->nombre ?? 'Sin cliente' }}</div>
                            @foreach($pedido->detalles as $detalle)
                            <div class="mt-1">
                                <span class="font-medium">{{ $detalle->existencia->existenciable->descripcion ?? 'Sin producto' }}</span>
                                <div class="text-sm text-gray-600">
                                    {{ $detalle->cantidad == floor($detalle->cantidad) ? intval($detalle->cantidad) : number_format($detalle->cantidad,2) }} unidades
                                </div>
                            </div>
                            @endforeach
                        </td>
                        <td class="px-4 py-2 flex justify-center gap-1">
                            <button wire:click="abrirModal({{ $pedido->id }})" class="btn-cyan" title="Editar Pago">Editar Costo</button>
                            <button wire:click="abrirModalPagoPedido({{ $pedido->id }})" class="btn-cyan" title="Registrar Pago">Registrar Pago</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-gray-600">No hay pedidos disponibles.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($modalAbierto && $pedidoSeleccionado)
    <div class="modal-overlay flex justify-center items-center p-4">
        <div class="modal-box max-w-2xl w-full overflow-auto p-4">
            <div class="modal-content flex flex-col gap-3">

                <h3 class="text-lg font-bold mb-2 text-center">
                    Editar Pago de {{ $pedidoSeleccionado->codigo }}
                </h3>

                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-teal-50 sticky top-0 z-10">
                        <tr>
                            <th class="px-2 py-1 text-left">Producto</th>
                            <th class="px-2 py-1 text-left">Cantidad</th>
                            <th class="px-2 py-1 text-left">Precio Base</th>
                            <th class="px-2 py-1 text-left">Precio Aplicado</th>
                            <th class="px-2 py-1 text-left">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $total = 0; @endphp
                        @foreach($pedidoSeleccionado->detalles as $detalle)
                        @php
                        $cantidad = (float) $detalle->cantidad;
                        $precioBase = (float) ($detalle->existencia->existenciable->precioReferencia ?? 0);
                        $precioAplicado = $detallesPago[$detalle->id]['precio_aplicado'] ?? $precioBase;
                        if ($precioAplicado === null || $precioAplicado <= 0) $precioAplicado=$precioBase;
                            $subtotal=$cantidad * $precioAplicado;
                            $total +=$subtotal;
                            @endphp
                            <tr class="hover:bg-teal-50">
                            <td class="px-2 py-1">{{ $detalle->existencia->existenciable->descripcion ?? 'Sin producto' }}</td>
                            <td class="px-2 py-1 text-center">{{ $cantidad == floor($cantidad) ? intval($cantidad) : number_format($cantidad, 2) }}</td>
                            <td class="px-2 py-1 text-right font-semibold">
                                Bs {{ number_format($precioBase, 2) }}
                            </td>
                            <td class="px-1 py-0.5">
                                <input type="number" step="1"
                                    wire:model.lazy="detallesPago.{{ $detalle->id }}.precio_aplicado"
                                    wire:change="actualizarSubtotal({{ $detalle->id }})"
                                    class="w-full border rounded-full text-sm px-1 py-0.5 text-right">
                            </td>
                            <td class="px-2 py-1 text-right font-semibold">{{ number_format($subtotal, 2) }}</td>
                            </tr>

                            @endforeach
                    </tbody>
                </table>

                <div class="text-right font-bold text-teal-700 mt-2 text-sm">
                    Total: Bs {{ number_format($total, 2) }}
                </div>

                <div class="modal-footer flex justify-center gap-2 mt-3 flex-wrap">
                    <button wire:click="cerrarModal" class="btn-cyan text-sm px-4 py-1">Cancelar</button>
                    <button wire:click="guardarDetalles" class="btn-green text-sm px-4 py-1">Guardar</button>
                </div>

            </div>
        </div>
    </div>
    @endif


    @if($modalPagoPedido && $pedidoSeleccionado)
    <div class="modal-overlay flex justify-center items-center p-4">
        <div class="modal-box max-w-3xl w-full overflow-auto p-4">
            <div class="modal-content flex flex-col gap-4 items-center w-full">

                <div class="w-full space-y-3">
                    @foreach($pagos as $index => $pago)
                    <div class="border p-3 rounded flex flex-col gap-2 w-full">

                        <div class="flex justify-between items-center mb-2">
                            <strong class="text-sm">{{ $pago['codigo_factura'] ?? 'PAGO-' . ($index + 1) }}</strong>
                            <button type="button"
                                wire:click="eliminarPago({{ $index }})"
                                class="btn-cyan text-xs px-2 py-1">
                                QUITAR
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs font-semibold">Monto</label>
                                <input type="number" min="0" step="0.01"
                                    wire:model="pagos.{{ $index }}.monto"
                                    class="input-minimal text-sm py-1 w-full min-w-0 block">
                            </div>

                            <div>
                                <label class="text-xs font-semibold">Fecha</label>
                                <input type="datetime-local"
                                    wire:model="pagos.{{ $index }}.fecha"
                                    class="input-minimal text-sm py-1 w-full min-w-0 block">
                            </div>

                            <div>
                                <label class="text-xs font-semibold">Código Factura</label>
                                <input type="text"
                                    wire:model="pagos.{{ $index }}.codigo_factura"
                                    class="input-minimal text-sm py-1 w-full min-w-0 block">
                            </div>

                            <div>
                                <label class="text-xs font-semibold">Referencia</label>
                                <input type="text"
                                    wire:model="pagos.{{ $index }}.referencia"
                                    class="input-minimal text-sm py-1 w-full min-w-0 block">
                            </div>

                            <div class="sm:col-span-2 flex flex-col sm:flex-row justify-center gap-2 mt-1">
                                <button type="button"
                                    wire:click="$set('pagos.{{ $index }}.metodo', 1)"
                                    class="btn-cyan text-xs {{ ($pago['metodo'] ?? 0) == 1 ? 'opacity-100' : 'opacity-50' }}">
                                    QR
                                </button>
                                <button type="button"
                                    wire:click="$set('pagos.{{ $index }}.metodo', 2)"
                                    class="btn-cyan text-xs {{ ($pago['metodo'] ?? 0) == 2 ? 'opacity-100' : 'opacity-50' }}">
                                    Efectivo
                                </button>
                                <button type="button"
                                    wire:click="$set('pagos.{{ $index }}.metodo', 3)"
                                    class="btn-cyan text-xs {{ ($pago['metodo'] ?? 0) == 3 ? 'opacity-100' : 'opacity-50' }}">
                                    Transferencia
                                </button>
                            </div>

                            <div class="sm:col-span-2 flex flex-col sm:flex-row justify-center gap-2 mt-1">
                                <button type="button"
                                    wire:click="$set('pagos.{{ $index }}.estado', 1)"
                                    class="btn-green text-xs {{ ($pago['estado'] ?? false) ? 'opacity-100' : 'opacity-50' }}">
                                    Pagado
                                </button>
                                <button type="button"
                                    wire:click="$set('pagos.{{ $index }}.estado', 0)"
                                    class="btn-cyan text-xs {{ !($pago['estado'] ?? false) ? 'opacity-100' : 'opacity-50' }}">
                                    Pendiente
                                </button>
                            </div>

                            <div class="sm:col-span-2 flex flex-col sm:flex-row justify-center gap-4 mt-2">
                                <div class="flex flex-col items-center w-full sm:w-1/2">
                                    <label class="text-xs font-semibold">Factura</label>
                                    <input type="file"
                                        wire:model="pagos.{{ $index }}.archivoFactura"
                                        class="input-minimal text-sm py-1 w-full min-w-0 block">

                                    @if(isset($pago['archivoFactura']))
                                    <img src="{{ is_object($pago['archivoFactura']) ? $pago['archivoFactura']->temporaryUrl() : asset('storage/' . $pago['archivoFactura']) }}"
                                        class="max-h-24 rounded border mt-1" alt="Factura">
                                    @if(!is_object($pago['archivoFactura']))
                                    <a href="{{ asset('storage/' . $pago['archivoFactura']) }}"
                                        download
                                        class="btn-cyan text-xs mt-1 px-2 py-1">
                                        Descargar Factura
                                    </a>
                                    @endif
                                    @endif
                                </div>

                                <div class="flex flex-col items-center w-full sm:w-1/2">
                                    <label class="text-xs font-semibold">Comprobante</label>
                                    <input type="file"
                                        wire:model="pagos.{{ $index }}.archivoComprobante"
                                        class="input-minimal text-sm py-1 w-full min-w-0 block">

                                    @if(isset($pago['archivoComprobante']))
                                    <img src="{{ is_object($pago['archivoComprobante']) ? $pago['archivoComprobante']->temporaryUrl() : asset('storage/' . $pago['archivoComprobante']) }}"
                                        class="max-h-24 rounded border mt-1" alt="Comprobante">
                                    @if(!is_object($pago['archivoComprobante']))
                                    <a href="{{ asset('storage/' . $pago['archivoComprobante']) }}"
                                        download
                                        class="btn-cyan text-xs mt-1 px-2 py-1">
                                        Descargar Comprobante
                                    </a>
                                    @endif
                                    @endif
                                </div>
                            </div>

                            <div class="sm:col-span-2 flex justify-center mt-2">
                                <input type="text"
                                    wire:model="pagos.{{ $index }}.observaciones"
                                    class="input-minimal w-full min-w-0 block"
                                    placeholder="Observaciones">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="modal-footer">
                    <button type="button" wire:click="agregarPago" class="btn-cyan text-sm px-4 py-1">
                        Añadir
                    </button>
                    <button type="button" wire:click="guardarPagos" class="btn-cyan text-sm px-4 py-1">
                        Guardar
                    </button>
                    <button type="button" wire:click="$set('modalPagoPedido', false)" class="btn-cyan text-sm px-4 py-1">
                        Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endif


</div>
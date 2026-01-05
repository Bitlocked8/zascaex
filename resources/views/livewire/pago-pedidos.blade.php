<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl">

        <h3 class="text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto mb-4">
            Ajuste de precios
        </h3>

        <div class="flex items-center gap-2 mb-4 flex-wrap">
            <input type="text" wire:model.live="search" placeholder="Buscar por código o cliente..."
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
    <div class="modal-overlay">
        <div class="modal-box max-w-3xl overflow-auto">
            <div class="modal-content flex flex-col gap-4">
                <h3 class="text-xl font-bold mb-4">Editar Pago de {{ $pedidoSeleccionado->codigo }}</h3>

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
                        @foreach($pedidoSeleccionado->detalles as $detalle)
                        @php
                        $cantidad = (float) $detalle->cantidad;
                        $precioBase = (float) ($detalle->existencia->existenciable->precioReferencia ?? 0);
                        $precioAplicado = $detallesPago[$detalle->id]['precio_aplicado'] ?? $precioBase;

                        if ($precioAplicado === null || $precioAplicado <= 0) {
                            $precioAplicado=$precioBase;
                            }

                            $subtotal=$cantidad * $precioAplicado;
                            @endphp
                            <tr class="hover:bg-teal-50">
                            <td class="px-2 py-1">{{ $detalle->existencia->existenciable->descripcion ?? 'Sin producto' }}</td>
                            <td class="px-2 py-1">{{ $cantidad == floor($cantidad) ? intval($cantidad) : number_format($cantidad, 2) }}</td>
                            <td class="px-2 py-1">
                                <input type="number" step="0.01" wire:model.defer="detallesPago.{{ $detalle->id }}.precio_base" class="w-full border px-1 py-0.5 rounded">
                            </td>
                            <td class="px-2 py-1">
                                <input type="number" step="0.01" wire:model.lazy="detallesPago.{{ $detalle->id }}.precio_aplicado" wire:change="actualizarSubtotal({{ $detalle->id }})" class="w-full border px-1 py-0.5 rounded">
                            </td>
                            <td class="px-2 py-1">{{ number_format($subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>

                <div class="modal-footer mt-4 flex gap-2">
                    <button wire:click="cerrarModal" class="btn-cyan">Cancelar</button>
                    <button wire:click="guardarDetalles" class="btn-cyan">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    @endif



    @if($modalPagoPedido && $pedidoSeleccionado)
    <div class="modal-overlay">
        <div class="modal-box max-w-3xl overflow-auto">
            <div class="modal-content flex flex-col gap-4">

                <h3 class="text-xl font-bold">
                    Registrar Pagos de {{ $pedidoSeleccionado->codigo }}
                </h3>

                @php
                $totalSubtotales = 0;
                foreach($pedidoSeleccionado->detalles as $detalle){
                $cantidad = (float) $detalle->cantidad;
                $precio = $detallesPago[$detalle->id]['precio_aplicado']
                ?? ((float)$detalle->existencia->existenciable->precioReferencia ?? 0);
                if($precio <= 0){
                    $precio=(float)$detalle->existencia->existenciable->precioReferencia ?? 0;
                    }
                    $totalSubtotales += $cantidad * $precio;
                    }
                    @endphp

                    <div class="text-right font-bold text-lg text-teal-700">
                        Total a pagar: Bs {{ number_format($totalSubtotales, 2) }}
                    </div>

                    <div class="space-y-4">
                        @foreach($pagos as $index => $pago)
                        <div class="border p-4 rounded flex flex-col gap-3">

                            <div class="flex justify-between items-center">
                                <strong>{{ $pago['codigo_factura'] ?? 'PAGO-' . ($index + 1) }}</strong>
                                <button type="button"
                                    wire:click="eliminarPago({{ $index }})"
                                    class="btn-circle btn-cyan">
                                    X
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">

                                <div>
                                    <label>Monto</label>
                                    <input type="number" min="0" step="0.01"
                                        wire:model="pagos.{{ $index }}.monto"
                                        class="input-minimal">
                                </div>

                                <div>
                                    <label>Fecha</label>
                                    <input type="datetime-local"
                                        wire:model="pagos.{{ $index }}.fecha"
                                        class="input-minimal">
                                </div>

                                <div>
                                    <label>Código Factura</label>
                                    <input type="text"
                                        wire:model="pagos.{{ $index }}.codigo_factura"
                                        class="input-minimal">
                                </div>

                                <div>
                                    <label>Referencia</label>
                                    <input type="text"
                                        wire:model="pagos.{{ $index }}.referencia"
                                        class="input-minimal">
                                </div>

                                <div class="sm:col-span-2">
                                    <label>Método de Pago</label>
                                    <div class="flex gap-2">
                                        <button type="button"
                                            wire:click="$set('pagos.{{ $index }}.metodo', 1)"
                                            class="btn-cyan {{ ($pago['metodo'] ?? 0) == 1 ? 'opacity-100' : 'opacity-50' }}">
                                            QR
                                        </button>
                                        <button type="button"
                                            wire:click="$set('pagos.{{ $index }}.metodo', 2)"
                                            class="btn-cyan {{ ($pago['metodo'] ?? 0) == 2 ? 'opacity-100' : 'opacity-50' }}">
                                            Efectivo
                                        </button>
                                        <button type="button"
                                            wire:click="$set('pagos.{{ $index }}.metodo', 3)"
                                            class="btn-cyan {{ ($pago['metodo'] ?? 0) == 3 ? 'opacity-100' : 'opacity-50' }}">
                                            Transferencia
                                        </button>
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label>Estado</label>
                                    <div class="flex gap-2">
                                        <button type="button"
                                            wire:click="$set('pagos.{{ $index }}.estado', 1)"
                                            class="btn-green {{ ($pago['estado'] ?? false) ? 'opacity-100' : 'opacity-50' }}">
                                            Pagado
                                        </button>
                                        <button type="button"
                                            wire:click="$set('pagos.{{ $index }}.estado', 0)"
                                            class="btn-cyan {{ !($pago['estado'] ?? false) ? 'opacity-100' : 'opacity-50' }}">
                                            Pendiente
                                        </button>
                                    </div>
                                </div>

                                <div class="sm:col-span-2">
                                    <label>Archivo Factura</label>
                                    <input type="file"
                                        wire:model="pagos.{{ $index }}.archivo_factura"
                                        class="input-minimal">
                                </div>

                                <div class="sm:col-span-2">
                                    <label>Comprobante</label>
                                    <input type="file"
                                        wire:model="pagos.{{ $index }}.archivo_comprobante"
                                        class="input-minimal">
                                </div>

                                <div class="sm:col-span-2">
                                    <label>Observaciones</label>
                                    <input type="text"
                                        wire:model="pagos.{{ $index }}.observaciones"
                                        class="input-minimal">
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="modal-footer flex gap-2 mt-4">
                        <button type="button" wire:click="agregarPago" class="btn-cyan">
                            Añadir Pago
                        </button>
                        <button type="button" wire:click="guardarPagos" class="btn-green">
                            Guardar Pagos
                        </button>
                        <button type="button" wire:click="$set('modalPagoPedido', false)" class="btn-cyan">
                            Cerrar
                        </button>
                    </div>

            </div>
        </div>
    </div>
    @endif


</div>
<div class="p-2 mt-20 flex justify-center bg-transparent">
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
                        <td class="px-4 py-2 align-top">
                            <div class="font-semibold">
                                {{ $pedido->codigo }}
                            </div>

                            @php
                            $pagos = $pedido->detalles->flatMap(fn ($d) => $d->pagoDetalles);

                            $nuncaGuardado = $pagos->isEmpty();

                            $ultimaFechaPago = $pagos->max('updated_at');
                            $ultimaFechaDetalle = $pedido->detalles->max('updated_at');

                            $pedidoModificado = $ultimaFechaPago
                            && $ultimaFechaDetalle
                            && $ultimaFechaDetalle->gt($ultimaFechaPago);


                            $totalPedido = $pagos->sum('subtotal');
                            @endphp

                            @if ($nuncaGuardado)
                            <div class="text-xs text-orange-600 mt-1 flex items-center gap-1">
                                ⚠️ <span>Aún no se han guardado precios</span>
                            </div>
                            @elseif ($pedidoModificado)
                            <div class="text-xs text-red-600 mt-1 flex items-center gap-1">
                                🔄 <span>El pedido fue modificado, debe volver a guardar</span>
                            </div>
                            @endif


                            @if ($totalPedido > 0)
                            <div class="mt-2 flex items-center gap-2 text-sm">
                                <span class="font-semibold text-teal-700">
                                    Bs {{ number_format($totalPedido, 2) }}
                                </span>

                                <button
                                    type="button"
                                    class="text-xs px-2 py-0.5 rounded bg-gray-200 hover:bg-gray-300"
                                    onclick="navigator.clipboard.writeText('{{ number_format($totalPedido, 2, '.', '') }}')"
                                    title="Copiar monto">
                                    📋
                                </button>
                            </div>
                            @endif
                        </td>
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
                    <button wire:click="cerrarModal" class="btn-cyan">Cancelar</button>
                    <button wire:click="guardarDetalles" class="btn-cyan">Guardar</button>
                </div>

            </div>
        </div>
    </div>
    @endif



</div>
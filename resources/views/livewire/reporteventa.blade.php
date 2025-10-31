<div class="p-4 mt-16 bg-white rounded-xl shadow-md">
    <h3 class="text-xl font-bold text-cyan-700 mb-4">Reporte de Pedidos</h3>

    {{-- FILTROS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div>
            <label class="font-semibold text-sm">Código</label>
            <input type="text" wire:model.live="codigo" placeholder="Ej: PED-001" class="input-minimal w-full">
        </div>

        <div>
            <label class="font-semibold text-sm">Cliente</label>
            <select wire:model.live="cliente_id" class="input-minimal w-full">
                <option value="">Todos</option>
                @foreach($clientes as $c)
                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-semibold text-sm">Personal</label>
            <select wire:model.live="personal_id" class="input-minimal w-full">
                <option value="">Todos</option>
                @foreach($personales as $p)
                    <option value="{{ $p->id }}">{{ $p->nombres }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-semibold text-sm">Estado Pedido</label>
            <select wire:model.live="estado_pedido" class="input-minimal w-full">
                <option value="">Todos</option>
                <option value="0">Pendiente</option>
                <option value="1">Completado</option>
                <option value="2">Cancelado</option>
            </select>
        </div>

        <div>
            <label class="font-semibold text-sm">Estado Pago</label>
            <select wire:model.live="estado_pago" class="input-minimal w-full">
                <option value="">Todos</option>
                <option value="1">QR</option>
                <option value="2">Efectivo</option>
                <option value="3">Crédito</option>
            </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 col-span-full">
            <div>
                <label class="font-semibold text-sm">Fecha y hora inicio</label>
                <input type="datetime-local" wire:model="fechaInicio"
                    class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label class="font-semibold text-sm">Fecha y hora fin</label>
                <input type="datetime-local" wire:model="fechaFin"
                    class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-blue-300">
            </div>
        </div>
    </div>

    {{-- TABLA --}}
    <table class="min-w-full border border-gray-300 rounded-lg overflow-hidden">
        <thead class="bg-cyan-700 text-white">
            <tr>
                <th class="px-3 py-2 text-left">Código Pedido</th>
                <th class="px-3 py-2 text-left">Cliente</th>
                <th class="px-3 py-2 text-left">Personal</th>
                <th class="px-3 py-2 text-left">Fecha</th>
                <th class="px-3 py-2 text-left">Producto</th>
                <th class="px-3 py-2 text-right">Cantidad</th>
                <th class="px-3 py-2 text-right">Precio Unitario (Bs.)</th>
                <th class="px-3 py-2 text-right">Subtotal (Bs.)</th>
                <th class="px-3 py-2 text-left">Estado Pedido</th>
                <th class="px-3 py-2 text-left">Tipo de Pago</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalGeneralCantidad = 0;
                $totalGeneralMonto = 0;
                $totalGeneralPagado = 0;
                $totalGeneralPendiente = 0;
            @endphp

            @forelse($pedidos as $pedido)
                @php
                    $montoPedido = $pedido->detalles->sum(fn($d) => $d->cantidad * ($d->existencia->existenciable->precioReferencia ?? 0));
                    $pago = $pedido->pagoPedidos->first();
                    $montoPagado = $pago->monto ?? 0;
                    $montoPendiente = max($montoPedido - $montoPagado, 0);

                    $totalGeneralPagado += $montoPagado;
                    $totalGeneralPendiente += $montoPendiente;
                @endphp

                {{-- DETALLES DE PRODUCTOS --}}
                @foreach($pedido->detalles as $detalle)
                    @php
                        $producto = $detalle->existencia->existenciable ?? null;
                        $precio = $producto->precioReferencia ?? 0;
                        $subtotal = $detalle->cantidad * $precio;
                        $totalGeneralCantidad += $detalle->cantidad;
                        $totalGeneralMonto += $subtotal;
                    @endphp
                    <tr class="border-b hover:bg-gray-100">
                        <td class="px-3 py-2">{{ $pedido->codigo }}</td>
                        <td class="px-3 py-2">{{ $pedido->cliente->nombre ?? 'N/A' }}</td>
                        <td class="px-3 py-2">{{ $pedido->personal->nombres ?? 'N/A' }}</td>
                        <td class="px-3 py-2">{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') }}</td>
                        <td class="px-3 py-2">{{ $producto->descripcion ?? 'N/A' }}</td>
                        <td class="px-3 py-2 text-right text-cyan-700 font-semibold">{{ number_format($detalle->cantidad, 2, ',', '.') }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($precio, 2, ',', '.') }}</td>
                        <td class="px-3 py-2 text-right font-bold text-emerald-700">{{ number_format($subtotal, 2, ',', '.') }}</td>

                        {{-- Estado Pedido --}}
                        <td class="px-3 py-2">
                            @if($pedido->estado_pedido == 0)
                                <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs">Pendiente</span>
                            @elseif($pedido->estado_pedido == 1)
                                <span class="bg-emerald-600 text-white px-2 py-1 rounded-full text-xs">Completado</span>
                            @else
                                <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs">Cancelado</span>
                            @endif
                        </td>

                        {{-- Tipo de pago --}}
                        <td class="px-3 py-2">
                            @if(!$pago)
                                <span class="bg-gray-400 text-white px-2 py-1 rounded-full text-xs">Sin pago</span>
                            @elseif($pago->estado == 1)
                                <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs">QR</span>
                            @elseif($pago->estado == 2)
                                <span class="bg-green-600 text-white px-2 py-1 rounded-full text-xs">Efectivo</span>
                            @elseif($pago->estado == 3)
                                <span class="bg-purple-600 text-white px-2 py-1 rounded-full text-xs">Crédito</span>
                            @endif
                        </td>
                    </tr>
                @endforeach

                {{-- RESUMEN POR PEDIDO --}}
                <tr class="bg-gray-50 font-semibold text-sm text-right">
                    <td colspan="7"></td>
                    <td colspan="2" class="px-3 py-2 text-cyan-700">Total Pedido:</td>
                    <td class="px-3 py-2 text-emerald-700">{{ number_format($montoPedido, 2, ',', '.') }} Bs</td>
                </tr>
                <tr class="bg-gray-50 font-semibold text-sm text-right">
                    <td colspan="7"></td>
                    <td colspan="2" class="px-3 py-2 text-cyan-700">Monto Pagado:</td>
                    <td class="px-3 py-2 text-blue-700">{{ number_format($montoPagado, 2, ',', '.') }} Bs</td>
                </tr>
                <tr class="bg-gray-50 font-semibold text-sm text-right border-b-4 border-cyan-700">
                    <td colspan="7"></td>
                    <td colspan="2" class="px-3 py-2 text-cyan-700">Saldo Pendiente:</td>
                    <td class="px-3 py-2 text-red-600">{{ number_format($montoPendiente, 2, ',', '.') }} Bs</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center py-3 text-gray-500">
                        No se encontraron pedidos con los filtros seleccionados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- TOTALES GENERALES --}}
    <div class="mt-4 text-right font-bold text-cyan-700 space-y-1">
        <p>Total general: {{ number_format($totalGeneralCantidad, 2, ',', '.') }} unidades</p>
        <p>Total general en Bs.: {{ number_format($totalGeneralMonto, 2, ',', '.') }}</p>
        <p>Total pagado: <span class="text-blue-700">{{ number_format($totalGeneralPagado, 2, ',', '.') }} Bs</span></p>
        <p>Saldo pendiente: <span class="text-red-600">{{ number_format($totalGeneralPendiente, 2, ',', '.') }} Bs</span></p>
    </div>
</div>

<div class="p-4 mt-16 bg-white rounded-xl shadow-md">
    <h3 class="text-xl font-bold text-cyan-700 mb-4">Reporte de Pedidos</h3>

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

        <div>
            <label class="font-semibold text-sm">Fecha inicio</label>
            <input type="date" wire:model.live="fechaInicio" class="input-minimal w-full">
        </div>

        <div>
            <label class="font-semibold text-sm">Fecha fin</label>
            <input type="date" wire:model.live="fechaFin" class="input-minimal w-full">
        </div>
    </div>

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
                <th class="px-3 py-2 text-left">Estado Pago</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalGeneralCantidad = 0;
                $totalGeneralMonto = 0;
            @endphp

            @forelse($pedidos as $pedido)
                @foreach($pedido->detalles as $detalle)
                    @php
                        $producto = $detalle->existencia->existenciable ?? null;
                        $precio = $producto->precioReferencia ?? 0;
                        $subtotal = $detalle->cantidad * $precio;
                        $totalGeneralCantidad += $detalle->cantidad;
                        $totalGeneralMonto += $subtotal;
                        $pago = $pedido->pagoPedidos->first() ?? null;
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
                        <td class="px-3 py-2">
                            @if($pedido->estado_pedido == 0)
                                <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs">Pendiente</span>
                            @elseif($pedido->estado_pedido == 1)
                                <span class="bg-emerald-600 text-white px-2 py-1 rounded-full text-xs">Completado</span>
                            @else
                                <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs">Cancelado</span>
                            @endif
                        </td>
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
            @empty
                <tr>
                    <td colspan="10" class="text-center py-3 text-gray-500">No se encontraron pedidos con los filtros seleccionados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4 text-right font-bold text-cyan-700 space-y-1">
        <p>Total general: {{ number_format($totalGeneralCantidad, 2, ',', '.') }} unidades</p>
        <p>Total general en Bs.: {{ number_format($totalGeneralMonto, 2, ',', '.') }}</p>
    </div>
</div>

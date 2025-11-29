<div class="p-4 bg-gray-50 min-h-screen mt-20">
    <h2 class="text-2xl font-bold mb-4">Reporte de Ventas</h2>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <input type="date" wire:model.live="fechaInicio" class="border p-2 rounded" />
        <input type="date" wire:model.live="fechaFin" class="border p-2 rounded" />
        <input type="text" wire:model.live="producto" placeholder="Buscar por producto" class="border p-2 rounded" />
        <select wire:model.live="cliente_id" class="border p-2 rounded">
            <option value="">Todos los clientes</option>
            @foreach($clientes as $cliente)
                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
            @endforeach
        </select>
        <select wire:model.live="personal_id" class="border p-2 rounded">
            <option value="">Todos los vendedores</option>
            @foreach($personales as $personal)
                <option value="{{ $personal->id }}">{{ $personal->nombres }}</option>
            @endforeach
        </select>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">Código</th>
                    <th class="px-4 py-2 border">Cliente</th>
                    <th class="px-4 py-2 border">Vendedor</th>
                    <th class="px-4 py-2 border">Fecha y Hora</th>
                    <th class="px-4 py-2 border">Producto</th>
                    <th class="px-4 py-2 border">Cantidad</th>
                    <th class="px-4 py-2 border">Monto</th>
                    <th class="px-4 py-2 border">Estado Pago</th>
                    <th class="px-4 py-2 border">Método Pago</th>
                    <th class="px-4 py-2 border">Saldo Pendiente</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pedidos as $pedido)
                    @php
                        // Filtrar productos según búsqueda
                        $detalles = $pedido->detalles->filter(fn($d) => $this->producto === '' || str_contains(strtolower($d->existencia?->existenciable?->descripcion ?? ''), strtolower($this->producto)));

                        // Pagos
                        $pagos = $pedido->pagoPedidos->isEmpty() ? collect([null]) : $pedido->pagoPedidos;

                        // Créditos y pagos
                        $creditos = $pagos->where('metodo', 2);
                        $totalCredito = $creditos->sum('monto');
                        $pagosConfirmados = $pagos->where('metodo', '<>', 2);
                        $totalPagado = $pagosConfirmados->where('estado', 1)->sum('monto');
                        $saldoPendiente = max($totalCredito - $totalPagado, 0);
                        $baseSaldo = $totalCredito;

                        // Determinar filas
                        $rowspan = max($detalles->count(), $pagos->count());
                    @endphp

                    @for($i = 0; $i < $rowspan; $i++)
                        <tr class="text-sm align-top">
                            <td class="px-4 py-2 border">{{ $i === 0 ? $pedido->codigo : '' }}</td>
                            <td class="px-4 py-2 border">
                                {{ $i === 0 ? $pedido->solicitudPedido?->cliente?->nombre ?? 'N/A' : '' }}</td>
                            <td class="px-4 py-2 border">{{ $i === 0 ? $pedido->personal?->nombres ?? 'N/A' : '' }}</td>
                            <td class="px-4 py-2 border">
                                {{ $i === 0 ? ($pedido->fecha_pedido ? date('d/m/Y H:i:s', strtotime($pedido->fecha_pedido)) : 'N/D') : '' }}
                            </td>

                            <td class="px-4 py-2 border">
                                {{ $detalles->get($i)?->existencia?->existenciable?->descripcion ?? '' }}

                            </td>
                            <td class="px-4 py-2 border">{{ $detalles->get($i)?->cantidad ?? '' }}</td>

                            @php $pago = $pagos->get($i); @endphp
                            <td class="px-4 py-2 border">{{ $pago?->monto ? 'Bs ' . number_format($pago->monto, 2) : '' }}</td>

                            <td class="px-4 py-2 border">
                                @if($pago)
                                    @if($pago->estado == 0)
                                        <span class="px-2 py-1 rounded bg-red-500 text-white text-xs">Sin pagar</span>
                                    @elseif($pago->estado == 1)
                                        <span class="px-2 py-1 rounded bg-green-500 text-white text-xs">Pagado</span>
                                    @endif
                                @else
                                    <span class="px-2 py-1 rounded bg-gray-400 text-white text-xs">Sin pagos</span>
                                @endif
                            </td>

                            <td class="px-4 py-2 border">
                                @if($pago)
                                    @if($pago->metodo == 0)
                                        <span class="px-2 py-1 rounded bg-cyan-500 text-white text-xs">QR</span>
                                    @elseif($pago->metodo == 1)
                                        <span class="px-2 py-1 rounded bg-yellow-500 text-white text-xs">Efectivo</span>
                                    @elseif($pago->metodo == 2)
                                        <span class="px-2 py-1 rounded bg-purple-500 text-white text-xs">Crédito</span>
                                    @endif
                                @else
                                    <span class="px-2 py-1 rounded bg-gray-400 text-white text-xs">-</span>
                                @endif
                            </td>

                            <td class="px-4 py-2 border">{{ $i === 0 ? 'Bs ' . number_format($saldoPendiente, 2) : '' }}</td>
                        </tr>
                    @endfor
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-2 text-center text-gray-500">No hay pedidos</td>
                    </tr>
                @endforelse
            </tbody>
<tfoot class="bg-gray-100 font-semibold">
    <tr>
        <td colspan="5" class="px-4 py-2 border text-right">Total Cantidad:</td>
        <td class="px-4 py-2 border">{{ $totalCantidad }}</td>
        <td colspan="4" class="px-4 py-2 border"></td>
    </tr>

    <tr>
        <td colspan="5" class="px-4 py-2 border text-right">Total Monto Pagado:</td>
        <td colspan="5" class="px-4 py-2 border">
            Bs {{
                number_format(
                    $pedidos->sum(function ($pedido) {
                        // Solo pagos confirmados que NO sean crédito
                        return $pedido->pagoPedidos
                            ->where('estado', 1)
                            ->where('metodo', '<>', 2)
                            ->sum('monto');
                    }),
                    2
                )
            }}
        </td>
    </tr>

    <tr>
        <td colspan="5" class="px-4 py-2 border text-right">Total Saldo Pendiente:</td>
        <td colspan="5" class="px-4 py-2 border">
            Bs {{
                number_format(
                    $pedidos->sum(function ($pedido) {
                        $totalCredito = $pedido->pagoPedidos->where('metodo', 2)->sum('monto');
                        $pagosConfirmados = $pedido->pagoPedidos->where('estado', 1)->where('metodo', '<>', 2)->sum('monto');

                        if ($totalCredito > 0) {
                            // Crédito pendiente = total crédito - pagos confirmados que se aplican al crédito
                            return max($totalCredito - $pagosConfirmados, 0);
                        } else {
                            // Sin crédito, saldo pendiente = pagos pendientes de cualquier método
                            return $pedido->pagoPedidos->where('estado', 0)->sum('monto');
                        }
                    }),
                    2
                )
            }}
        </td>
    </tr>
</tfoot>



        </table>
    </div>
</div>
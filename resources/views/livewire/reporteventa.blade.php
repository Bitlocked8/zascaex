<div class="p-4 bg-transparent min-h-screen mt-20">
    <h2 class="text-2xl font-bold mb-6 text-center text-teal-700">Reporte de Ventas</h2>

    <div class="flex flex-col gap-4 mb-6">
        <!-- Fechas de PEDIDOS -->
        <div class="flex flex-wrap justify-center gap-2 mt-4">
            <label class="font-semibold w-full text-center mb-1">Fecha Inicio Pedido:</label>
            <input type="number" min="1" max="31" wire:model.live="fecha_inicio_dia" placeholder="Día"
                class="w-16 p-2 rounded text-center border">
            <input type="number" min="1" max="12" wire:model.live="fecha_inicio_mes" placeholder="Mes"
                class="w-16 p-2 rounded text-center border">
            <input type="number" min="2000" max="2100" wire:model.live="fecha_inicio_ano" placeholder="Año"
                class="w-20 p-2 rounded text-center border">
            <input type="number" min="0" max="23" wire:model.live="fecha_inicio_hora" placeholder="Hora"
                class="w-16 p-2 rounded text-center border">
            <input type="number" min="0" max="59" wire:model.live="fecha_inicio_min" placeholder="Minuto"
                class="w-16 p-2 rounded text-center border">
        </div>

        <div class="flex flex-wrap justify-center gap-2 mt-2">
            <label class="font-semibold w-full text-center mb-1">Fecha Fin Pedido:</label>
            <input type="number" min="1" max="31" wire:model.live="fecha_fin_dia" placeholder="Día"
                class="w-16 p-2 rounded text-center border">
            <input type="number" min="1" max="12" wire:model.live="fecha_fin_mes" placeholder="Mes"
                class="w-16 p-2 rounded text-center border">
            <input type="number" min="2000" max="2100" wire:model.live="fecha_fin_ano" placeholder="Año"
                class="w-20 p-2 rounded text-center border">
            <input type="number" min="0" max="23" wire:model.live="fecha_fin_hora" placeholder="Hora"
                class="w-16 p-2 rounded text-center border">
            <input type="number" min="0" max="59" wire:model.live="fecha_fin_min" placeholder="Minuto"
                class="w-16 p-2 rounded text-center border">
        </div>

        <div class="flex flex-wrap justify-center gap-2 mt-6">
            <label class="font-semibold w-full text-center mb-1">Fecha Inicio Pago:</label>
            <input type="number" min="1" max="31" wire:model.live="fecha_pago_inicio_dia" placeholder="Día"
                class="w-16 p-2 rounded text-center border">
            <input type="number" min="1" max="12" wire:model.live="fecha_pago_inicio_mes" placeholder="Mes"
                class="w-16 p-2 rounded text-center border">
            <input type="number" min="2000" max="2100" wire:model.live="fecha_pago_inicio_ano" placeholder="Año"
                class="w-20 p-2 rounded text-center border">
            <input type="number" min="0" max="23" wire:model.live="fecha_pago_inicio_hora" placeholder="Hora"
                class="w-16 p-2 rounded text-center border">
            <input type="number" min="0" max="59" wire:model.live="fecha_pago_inicio_min" placeholder="Minuto"
                class="w-16 p-2 rounded text-center border">
        </div>

        <div class="flex flex-wrap justify-center gap-2 mt-2">
            <label class="font-semibold w-full text-center mb-1">Fecha Fin Pago:</label>
            <input type="number" min="1" max="31" wire:model.live="fecha_pago_fin_dia" placeholder="Día"
                class="w-16 p-2 rounded text-center border">
            <input type="number" min="1" max="12" wire:model.live="fecha_pago_fin_mes" placeholder="Mes"
                class="w-16 p-2 rounded text-center border">
            <input type="number" min="2000" max="2100" wire:model.live="fecha_pago_fin_ano" placeholder="Año"
                class="w-20 p-2 rounded text-center border">
            <input type="number" min="0" max="23" wire:model.live="fecha_pago_fin_hora" placeholder="Hora"
                class="w-16 p-2 rounded text-center border">
            <input type="number" min="0" max="59" wire:model.live="fecha_pago_fin_min" placeholder="Minuto"
                class="w-16 p-2 rounded text-center border">
        </div>

        <div class="flex flex-wrap justify-center gap-4 mt-2">
            <input type="text" wire:model.live="producto" placeholder="Buscar por producto"
                class="border p-2 rounded text-center w-40">
        </div>

        <div class="flex flex-wrap justify-center gap-4 mt-2">
            <div class="w-40">
                <label class="font-semibold text-sm mb-2 block">Cliente</label>
                <div
                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white max-h-[170px] overflow-y-auto">

                    <button type="button" wire:click="$set('cliente_id', '')" class="w-full px-3 py-2 rounded-md border text-left transition
                {{ $cliente_id == ''
    ? 'bg-cyan-600 text-white border-cyan-700 shadow'
    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                        <p class="font-semibold text-sm">Todos</p>
                    </button>

                    @foreach($clientes as $cliente)
                                    <button type="button" wire:click="$set('cliente_id', {{ $cliente->id }})" class="w-full px-3 py-2 rounded-md border text-left transition
                                                    {{ $cliente_id == $cliente->id
                        ? 'bg-cyan-600 text-white border-cyan-700 shadow'
                        : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                        <p class="font-semibold text-sm">{{ $cliente->nombre }}</p>
                                    </button>
                    @endforeach

                </div>
            </div>
            <div class="w-40">
                <label class="font-semibold text-sm mb-2 block">Vendedor</label>
                <div
                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white max-h-[170px] overflow-y-auto">

                    <button type="button" wire:click="$set('personal_id', '')" class="w-full px-3 py-2 rounded-md border text-left transition
                {{ $personal_id == ''
    ? 'bg-cyan-600 text-white border-cyan-700 shadow'
    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                        <p class="font-semibold text-sm">Todos</p>
                    </button>

                    @foreach($personales as $personal)
                                    <button type="button" wire:click="$set('personal_id', {{ $personal->id }})" class="w-full px-3 py-2 rounded-md border text-left transition
                                                    {{ $personal_id == $personal->id
                        ? 'bg-cyan-600 text-white border-cyan-700 shadow'
                        : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                        <p class="font-semibold text-sm">{{ $personal->nombres }}</p>
                                    </button>
                    @endforeach

                </div>
            </div>
            <div class="w-40">
                <label class="font-semibold text-sm mb-2 block">Estado Pago</label>
                <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white">

                    <button type="button" wire:click="$set('filtroEstadoPago', '')" class="w-full px-3 py-2 rounded-md border text-left transition
                {{ $filtroEstadoPago === ''
    ? 'bg-cyan-600 text-white border-cyan-700 shadow'
    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                        Todos
                    </button>

                    <button type="button" wire:click="$set('filtroEstadoPago', '1')" class="w-full px-3 py-2 rounded-md border text-left transition
                {{ $filtroEstadoPago === '1'
    ? 'bg-cyan-600 text-white border-cyan-700 shadow'
    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                        Pagados
                    </button>

                    <button type="button" wire:click="$set('filtroEstadoPago', '0')" class="w-full px-3 py-2 rounded-md border text-left transition
                {{ $filtroEstadoPago === '0'
    ? 'bg-cyan-600 text-white border-cyan-700 shadow'
    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                        Sin pagar
                    </button>

                </div>
            </div>

            {{-- Método de Pago --}}
            <div class="w-40">
                <label class="font-semibold text-sm mb-2 block">Método Pago</label>
                <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white">

                    <button type="button" wire:click="$set('filtroMetodoPago', '')" class="w-full px-3 py-2 rounded-md border text-left transition
                {{ $filtroMetodoPago === ''
    ? 'bg-cyan-600 text-white border-cyan-700 shadow'
    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                        Todos
                    </button>

                    <button type="button" wire:click="$set('filtroMetodoPago', '0')" class="w-full px-3 py-2 rounded-md border text-left transition
                {{ $filtroMetodoPago === '0'
    ? 'bg-cyan-600 text-white border-cyan-700 shadow'
    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                        QR
                    </button>

                    <button type="button" wire:click="$set('filtroMetodoPago', '1')" class="w-full px-3 py-2 rounded-md border text-left transition
                {{ $filtroMetodoPago === '1'
    ? 'bg-cyan-600 text-white border-cyan-700 shadow'
    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                        Efectivo
                    </button>

                    <button type="button" wire:click="$set('filtroMetodoPago', '2')" class="w-full px-3 py-2 rounded-md border text-left transition
                {{ $filtroMetodoPago === '2'
    ? 'bg-cyan-600 text-white border-cyan-700 shadow'
    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                        Crédito
                    </button>

                </div>
            </div>
            <div class="w-40">
                <label class="font-semibold text-sm mb-2 block">Sucursal</label>
                <div
                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white max-h-[170px] overflow-y-auto">

                    <button type="button" wire:click="$set('sucursal_id', '')" class="w-full px-3 py-2 rounded-md border text-left transition
                {{ $sucursal_id == ''
    ? 'bg-cyan-600 text-white border-cyan-700 shadow'
    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                        <p class="font-semibold text-sm">Todas</p>
                    </button>

                    @foreach($sucursales as $sucursal)
                                    <button type="button" wire:click="$set('sucursal_id', {{ $sucursal->id }})" class="w-full px-3 py-2 rounded-md border text-left transition
                                                    {{ $sucursal_id == $sucursal->id
                        ? 'bg-cyan-600 text-white border-cyan-700 shadow'
                        : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                        <p class="font-semibold text-sm">{{ $sucursal->nombre }}</p>
                                    </button>
                    @endforeach

                </div>
            </div>

        </div>

        <div class="flex justify-center mb-6">
            <button wire:click="descargarPDF"
                class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 transition">
                Descargar PDF
            </button>
        </div>

    </div>

    <div class="overflow-x-auto mb-6">
        <h3 class="text-xl font-semibold mb-2 text-center">Productos por Pedido</h3>
        <table class="min-w-full bg-white border rounded text-center">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">Código</th>
                    <th class="px-4 py-2 border">Cliente</th>
                    <th class="px-4 py-2 border">Vendedor</th>
                    <th class="px-4 py-2 border">Fecha</th>
                    <th class="px-4 py-2 border">Producto</th>
                    <th class="px-4 py-2 border">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pedidos as $pedido)
                    @foreach($pedido->detalles as $detalle)
                        @if($this->producto === '' || str_contains(strtolower($detalle->existencia?->existenciable?->descripcion ?? ''), strtolower($this->producto)))
                            <tr class="text-sm">
                                <td class="px-2 py-1 border text-center">{{ $pedido->codigo }}</td>
                                <td class="px-2 py-1 border text-center">{{ $pedido->solicitudPedido?->cliente?->nombre ?? 'N/A' }}

                                </td>
                                <td class="px-2 py-1 border text-center">{{ $pedido->personal?->nombres ?? 'N/A' }}

                                </td>
                                <td class="px-2 py-1 border text-center">
                                    {{ $pedido->fecha_pedido ? date('d/m/Y H:i:s', strtotime($pedido->fecha_pedido)) : 'N/D' }}

                                </td>
                                <td class="px-2 py-1 border text-center">
                                    <div>{{ $detalle->existencia?->existenciable?->descripcion ?? '' }}</div>

                                    @if(!empty($detalle->existencia?->existenciable?->tipoContenido))
                                        <div class="text-sm text-gray-500">
                                            {{ $detalle->existencia->existenciable->tipoContenido }}
                                        </div>
                                    @endif

                                    <div class="text-sm text-gray-600 mt-1">
                                        Sucursal: {{ $detalle->existencia?->sucursal?->nombre ?? 'N/A' }}
                                    </div>
                                </td>


                                <td class="px-2 py-1 border text-center">{{ $detalle->cantidad }}

                                </td>
                            </tr>
                        @endif
                    @endforeach
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-2">No hay pedidos</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="overflow-x-auto mb-6">
        <h3 class="text-xl font-semibold mb-2 text-center">Pagos por Pedido</h3>
        <table class="min-w-full bg-white border rounded text-center">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">Código Pedido</th>
                    <th class="px-4 py-2 border">Monto</th>
                    <th class="px-4 py-2 border">Estado Pago</th>
                    <th class="px-4 py-2 border">Fecha Pago</th> <!-- NUEVA -->
                    <th class="px-4 py-2 border">Método Pago</th>
                    <th class="px-4 py-2 border">Deuda Crédito</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pedidos as $pedido)
                    @php
                        $creditoBase = $pedido->pagoPedidos->where('metodo', 2)->sum('monto');
                        $pagosNoCredito = $pedido->pagoPedidos->where('metodo', '!=', 2)->where('estado', 1)->sum('monto');
                        $deudaTotalCredito = max($creditoBase - $pagosNoCredito, 0);
                    @endphp

                    @foreach($pedido->pagoPedidos as $pago)
                        @if(
                                ($this->filtroEstadoPago === '' || $pago->estado == (int) $this->filtroEstadoPago) &&
                                ($this->filtroMetodoPago === '' || $pago->metodo == (int) $this->filtroMetodoPago)
                            )
                            <tr class="text-sm">
                                <td class="px-2 py-1 border">{{ $pedido->codigo }}</td>
                                <td class="px-2 py-1 border">Bs {{ number_format($pago->monto, 2) }}</td>

                                <td class="px-2 py-1 border">
                                    @if($pago->estado == 0)
                                        <span class="px-2 py-1 rounded bg-red-500 text-white text-xs">Sin pagar</span>
                                    @else
                                        <span class="px-2 py-1 rounded bg-green-500 text-white text-xs">Pagado</span>
                                    @endif
                                </td>

                                <!-- FECHA DE PAGO -->
                                <td class="px-2 py-1 border text-center">
                                    {{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') : '-' }}
                                </td>

                                <td class="px-2 py-1 border">
                                    @if($pago->metodo == 0)
                                        <span class="px-2 py-1 rounded bg-cyan-500 text-white text-xs">QR</span>
                                    @elseif($pago->metodo == 1)
                                        <span class="px-2 py-1 rounded bg-yellow-500 text-white text-xs">Efectivo</span>
                                    @elseif($pago->metodo == 2)
                                        <span class="px-2 py-1 rounded bg-purple-500 text-white text-xs">Crédito</span>
                                    @endif
                                </td>

                                <td class="px-2 py-1 border">
                                    @if($pago->metodo == 2)
                                        Bs {{ number_format($deudaTotalCredito, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-2">No hay pagos</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>


    <div class="mt-6 p-4 bg-white rounded-lg shadow">
        <h3 class="text-xl font-semibold mb-4 text-center">Resumen de Pagos</h3>

        @php
            $totalPagado = 0;
            $totalSinPagar = 0;
            $totalDeudaCredito = 0;
            $resumenMetodos = ['QR' => 0, 'Efectivo' => 0, 'Crédito' => 0];

            foreach ($pedidos as $pedido) {
                $creditoBase = $pedido->pagoPedidos->where('metodo', 2)->sum('monto');
                $pagosRealizados = $pedido->pagoPedidos->where('metodo', '!=', 2)->where('estado', 1)->sum('monto');
                $totalDeudaCredito += max($creditoBase - $pagosRealizados, 0);

                foreach ($pedido->pagoPedidos as $pago) {
                    if (
                        ($this->filtroEstadoPago === '' || $pago->estado == (int) $this->filtroEstadoPago) &&
                        ($this->filtroMetodoPago === '' || $pago->metodo == (int) $this->filtroMetodoPago)
                    ) {
                        if ($pago->estado == 1 && $pago->metodo != 2) {
                            $totalPagado += $pago->monto;
                        } elseif ($pago->estado == 0) {
                            $totalSinPagar += $pago->monto;
                        }

                        if ($pago->metodo == 0)
                            $resumenMetodos['QR'] += $pago->monto;
                        if ($pago->metodo == 1)
                            $resumenMetodos['Efectivo'] += $pago->monto;
                        if ($pago->metodo == 2)
                            $resumenMetodos['Crédito'] += $pago->monto;
                    }
                }
            }
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="p-4 bg-green-100 rounded text-center">
                <span class="font-semibold">Total Pagado:</span> Bs {{ number_format($totalPagado, 2) }}
            </div>
            <div class="p-4 bg-red-100 rounded text-center">
                <span class="font-semibold">Total Sin Pagar:</span> Bs {{ number_format($totalSinPagar, 2) }}
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @foreach($resumenMetodos as $metodo => $monto)
                <div class="p-4 bg-gray-100 rounded text-center">
                    <span class="font-semibold">{{ $metodo }}:</span> Bs {{ number_format($monto, 2) }}
                </div>
            @endforeach
            <div class="p-4 bg-purple-100 rounded text-center">
                <span class="font-semibold">Deuda (Crédito pendiente):</span> Bs
                {{ number_format($totalDeudaCredito, 2) }}
            </div>
        </div>
    </div>
</div>
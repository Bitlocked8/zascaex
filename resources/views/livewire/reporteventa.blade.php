<div class="p-4 sm:p-6 mt-16 bg-white rounded-xl shadow-md">

    <h3 class="text-xl font-bold text-cyan-700 mb-4">Reporte de Pedidos</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div>
            <label class="font-semibold text-sm">Código</label>
            <input type="text" wire:model.live="codigo" placeholder="Ej: PED-001" class="input-minimal w-full">
        </div>

        <div class="grid grid-cols-1 gap-2 mt-2">
            <div>
                <label class="text-u">Cliente</label>
                <div
                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                    <button type="button" wire:click="$set('cliente_id', null)"
                        class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center{{ $cliente_id === null ? 'border-cyan-600 text-cyan-600 bg-white' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 bg-white' }}">
                        <span class="font-medium">Todos</span>
                    </button>
                    @foreach($clientes as $c)
                        <button type="button" wire:click="$set('cliente_id', {{ $c->id }})"
                            class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                                                {{ $cliente_id == $c->id ? 'border-cyan-600 text-cyan-600 bg-white' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 bg-white' }}">
                            <span class="font-medium">{{ $c->nombre }}</span>
                            @if(!empty($c->tipo))
                                <span
                                    class="bg-gray-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold uppercase mt-2">
                                    {{ $c->tipo }}
                                </span>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        </div>


        <div class="grid grid-cols-1 gap-2 mt-2">
            <div>
                <label class=" text-u ">Personal</label>
                <div
                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                    <button type="button" wire:click="$set('personal_id', null)"
                        class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                {{ $personal_id === null ? 'border-cyan-600 text-cyan-600 bg-white' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 bg-white' }}">
                        <span class="font-medium">Todos</span>
                    </button>
                    @foreach($personales as $p)
                        <button type="button" wire:click="$set('personal_id', {{ $p->id }})"
                            class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                                                    {{ $personal_id == $p->id ? 'border-cyan-600 text-cyan-600 bg-white' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 bg-white' }}">
                            <span class="font-medium">{{ $p->nombres }}</span>
                            @if(!empty($p->cargo))
                                <span
                                    class="bg-gray-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold uppercase mt-2">
                                    {{ $p->cargo }}
                                </span>
                            @endif
                        </button>
                    @endforeach

                </div>
            </div>
        </div>


        <div class="grid grid-cols-1 gap-2 mt-2">
            <div>
                <label class="text-u">Estado Pedido</label>

                <div
                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                    <button type="button" wire:click="$set('estado_pedido', null)"
                        class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                    {{ is_null($estado_pedido) ? 'border-cyan-600 text-cyan-600 bg-white' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 bg-white' }}">
                        <span class="font-medium">Todos</span>
                    </button>
                    @php
                        $estados = [
                            0 => ['nombre' => 'Pendiente', 'color' => 'bg-yellow-500 text-white'],
                            1 => ['nombre' => 'Completado', 'color' => 'bg-emerald-600 text-white'],
                            2 => ['nombre' => 'Cancelado', 'color' => 'bg-red-500 text-white'],
                        ];
                    @endphp

                    @foreach($estados as $key => $estado)
                        <button type="button" wire:click="$set('estado_pedido', {{ $key }})"
                            class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                                        {{ $estado_pedido === $key ? 'border-cyan-600 text-cyan-600 bg-white' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 bg-white' }}">
                            <span class="font-medium">{{ $estado['nombre'] }}</span>
                            <span
                                class="{{ $estado['color'] }} text-xs px-2 py-0.5 rounded-full font-semibold uppercase mt-2">
                                {{ $estado['nombre'] }}
                            </span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-2 mt-2">
            <div>
                <label class="font-semibold text-sm mb-2 block">Estado Pago</label>
                <div
                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                    <button type="button" wire:click="$set('estado_pago', null)"
                        class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                {{ $estado_pago === null ? 'border-cyan-600 text-cyan-600 bg-white' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 bg-white' }}">
                        <span class="font-medium">Todos</span>
                    </button>
                    @php
                        $tiposPago = [
                            1 => ['nombre' => 'QR', 'color' => 'bg-blue-500 text-white'],
                            2 => ['nombre' => 'Efectivo', 'color' => 'bg-green-600 text-white'],
                            3 => ['nombre' => 'Crédito', 'color' => 'bg-purple-600 text-white'],
                        ];
                    @endphp
                    @foreach($tiposPago as $key => $tipo)
                        <button type="button" wire:click="$set('estado_pago', {{ $key }})"
                            class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                                                            {{ $estado_pago == $key ? 'border-cyan-600 text-cyan-600 bg-white' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 bg-white' }}">
                            <span class="font-medium">{{ $tipo['nombre'] }}</span>
                            <span
                                class="{{ $tipo['color'] }} text-xs px-2 py-0.5 rounded-full font-semibold uppercase mt-2">
                                {{ $tipo['nombre'] }}
                            </span>
                        </button>
                    @endforeach

                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-2 mt-2">
            <div>
                <label class="font-semibold text-sm mb-1 block">Fecha y hora inicio</label>
                <div class="flex gap-1">
                    <input type="text" maxlength="2" wire:model.live="inicioDia" class="input-minimal" placeholder="Dia"
                        oninput="if(this.value.length==this.maxLength) this.nextElementSibling.focus()">

                    <input type="text" maxlength="2" wire:model.live="inicioMes" class="input-minimal" placeholder="Mes"
                        oninput="if(this.value.length==this.maxLength) this.nextElementSibling.focus()">

                    <input type="text" maxlength="4" wire:model.live="inicioAnio" class="input-minimal"
                        placeholder="Año"
                        oninput="if(this.value.length==this.maxLength) this.nextElementSibling.focus()">

                    <input type="text" maxlength="2" wire:model.live="inicioHora" class="input-minimal"
                        placeholder="Hora"
                        oninput="if(this.value.length==this.maxLength) this.nextElementSibling.focus()">

                    <input type="text" maxlength="2" wire:model.live="inicioMin" class="input-minimal"
                        placeholder="Min">
                </div>
            </div>
            <br>
            <div>
                <label class="font-semibold text-sm mb-1 block">Fecha y hora fin</label>
                <div class="flex gap-1">
                    <input type="text" maxlength="2" wire:model.live="finDia" class="input-minimal" placeholder="Dia"
                        oninput="if(this.value.length==this.maxLength) this.nextElementSibling.focus()">

                    <input type="text" maxlength="2" wire:model.live="finMes" class="input-minimal" placeholder="MM"
                        oninput="if(this.value.length==this.maxLength) this.nextElementSibling.focus()">

                    <input type="text" maxlength="4" wire:model.live="finAnio" class="input-minimal" placeholder="Año"
                        oninput="if(this.value.length==this.maxLength) this.nextElementSibling.focus()">

                    <input type="text" maxlength="2" wire:model.live="finHora" class="input-minimal" placeholder="Hora"
                        oninput="if(this.value.length==this.maxLength) this.nextElementSibling.focus()">

                    <input type="text" maxlength="2" wire:model.live="finMin" class="input-minimal" placeholder="Min">
                </div>
            </div>
        </div>


        <div class="flex justify-center mb-4 col-span-full">
            <button wire:click="generarPDF"
                class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                📄 Generar PDF
            </button>
        </div>
    </div>

    @php
        $totalGeneralCantidad = 0;
        $totalGeneralMonto = 0;
        $totalGeneralPagado = 0;
        $totalGeneralPendiente = 0;
        $totalesPorPago = [
            '1' => ['nombre' => 'QR', 'monto' => 0, 'pedidos' => 0],
            '2' => ['nombre' => 'Efectivo', 'monto' => 0, 'pedidos' => 0],
            '3' => ['nombre' => 'Crédito', 'monto' => 0, 'pedidos' => 0],
            '0' => ['nombre' => 'Sin pago', 'monto' => 0, 'pedidos' => 0],
        ];
    @endphp

    <div class="overflow-x-auto hidden sm:block">
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
                @forelse($pedidos as $pedido)
                    @php
                        $pago = $pedido->pagoPedidos->first();
                        $montoPedido = $pedido->detalles->sum(fn($d) => $d->cantidad * ($d->existencia->existenciable->precioReferencia ?? 0));
                        $montoPagado = $pago->monto ?? 0;
                        $montoPendiente = max($montoPedido - $montoPagado, 0);
                        $totalGeneralPagado += $montoPagado;
                        $totalGeneralPendiente += $montoPendiente;
                        $tipoPago = $pago->estado ?? 0;
                        $totalesPorPago[$tipoPago]['monto'] += $montoPagado;
                        $totalesPorPago[$tipoPago]['pedidos']++;
                    @endphp
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
                            <td class="px-3 py-2">{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') }}

                            </td>
                            <td class="px-3 py-2">{{ $producto->descripcion ?? 'N/A' }}</td>
                            <td class="px-3 py-2 text-right text-cyan-700 font-semibold">
                                {{ number_format($detalle->cantidad, 2, ',', '.') }}
                            </td>
                            <td class="px-3 py-2 text-right">{{ number_format($precio, 2, ',', '.') }}</td>
                            <td class="px-3 py-2 text-right font-bold text-emerald-700">
                                {{ number_format($subtotal, 2, ',', '.') }}
                            </td>
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
                        <td colspan="10" class="text-center py-3 text-gray-500">No se encontraron pedidos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="sm:hidden space-y-4">
        @forelse($pedidos as $pedido)
            @php
                $pago = $pedido->pagoPedidos->first();
            @endphp
            @foreach($pedido->detalles as $detalle)
                @php
                    $producto = $detalle->existencia->existenciable ?? null;
                    $precio = $producto->precioReferencia ?? 0;
                    $subtotal = $detalle->cantidad * $precio;
                @endphp
                <div class="p-4 bg-white rounded-xl shadow-md">
                    <p class="font-bold text-cyan-700 mb-1">{{ $pedido->codigo }}</p>
                    <p><span class="font-semibold">Cliente:</span> {{ $pedido->cliente->nombre ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Personal:</span> {{ $pedido->personal->nombres ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Fecha:</span>
                        {{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') }}</p>
                    <p><span class="font-semibold">Producto:</span> {{ $producto->descripcion ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Cantidad:</span> {{ number_format($detalle->cantidad, 2, ',', '.') }}</p>
                    <p><span class="font-semibold">Precio Unitario:</span> {{ number_format($precio, 2, ',', '.') }} Bs</p>
                    <p class="font-bold text-emerald-700"><span class="font-semibold">Subtotal:</span>
                        {{ number_format($subtotal, 2, ',', '.') }} Bs</p>
                    <p>
                        <span class="font-semibold">Estado Pedido:</span>
                        @if($pedido->estado_pedido == 0)
                            <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs">Pendiente</span>
                        @elseif($pedido->estado_pedido == 1)
                            <span class="bg-emerald-600 text-white px-2 py-1 rounded-full text-xs">Completado</span>
                        @else
                            <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs">Cancelado</span>
                        @endif
                    </p>
                    <p>
                        <span class="font-semibold">Tipo Pago:</span>
                        @if(!$pago)
                            <span class="bg-gray-400 text-white px-2 py-1 rounded-full text-xs">Sin pago</span>
                        @elseif($pago->estado == 1)
                            <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs">QR</span>
                        @elseif($pago->estado == 2)
                            <span class="bg-green-600 text-white px-2 py-1 rounded-full text-xs">Efectivo</span>
                        @elseif($pago->estado == 3)
                            <span class="bg-purple-600 text-white px-2 py-1 rounded-full text-xs">Crédito</span>
                        @endif
                    </p>
                </div>
            @endforeach
        @empty
            <p class="text-center text-gray-500">No se encontraron pedidos.</p>
        @endforelse
    </div>

    <div class="mt-6 text-right font-bold text-cyan-700 space-y-1">
        <p>Total general: {{ number_format($totalGeneralCantidad, 2, ',', '.') }} unidades</p>
        <p>Total general en Bs.: {{ number_format($totalGeneralMonto, 2, ',', '.') }}</p>
        <p>Total pagado: <span class="text-blue-700">{{ number_format($totalGeneralPagado, 2, ',', '.') }} Bs</span></p>
        <p>Saldo pendiente: <span class="text-red-600">{{ number_format($totalGeneralPendiente, 2, ',', '.') }}
                Bs</span></p>
    </div>

    <div class="mt-6 border-t pt-4">
        <h4 class="text-lg font-bold text-cyan-700 mb-2">Totales por tipo de pago</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            @foreach($totalesPorPago as $tipo)
                <div class="p-3 bg-gray-100 rounded-lg shadow-sm text-center">
                    <p class="text-sm font-semibold text-gray-600">{{ $tipo['nombre'] }}</p>
                    <p class="text-lg font-bold text-cyan-700">{{ number_format($tipo['monto'], 2, ',', '.') }} Bs</p>
                    <p class="text-xs text-gray-500">({{ $tipo['pedidos'] }} pedidos)</p>
                </div>
            @endforeach
        </div>
    </div>

</div>
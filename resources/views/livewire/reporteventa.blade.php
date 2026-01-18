<div class="p-2 mt-20 flex justify-center bg-transparent">
    <div class="w-full max-w-screen-xl">

        <h3 class="text-center text-xl sm:text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-4 sm:px-6 py-1 sm:py-2 rounded-full mx-auto shadow-sm mb-4">
            Pedidos
        </h3>

        <div class="flex flex-col sm:flex-row gap-2 mb-4 items-center">
            <div class="mb-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Personal</label>
                <div class="border rounded-md h-40 overflow-y-auto bg-white scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    <button
                        wire:click="$set('filtroPersonal', null)"
                        class="w-full text-left px-2 py-1 text-sm border-b {{ $filtroPersonal === null ? 'bg-teal-500 text-white' : 'bg-white text-gray-700' }}">
                        Todos
                    </button>

                    @foreach($personales as $p)
                    <button
                        wire:click="$set('filtroPersonal', {{ $p->id }})"
                        class="w-full text-left px-2 py-1 text-sm border-b {{ $filtroPersonal == $p->id ? 'bg-teal-500 text-white' : 'bg-white text-gray-700' }}">
                        {{ $p->nombres }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="mb-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Fechas</label>

                <div class="border rounded-md h-40 overflow-y-auto bg-white p-2 flex flex-col gap-2 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    <button
                        wire:click="toggleHoy"
                        class="px-2 py-1 text-sm {{ $hoy ? 'bg-teal-500 text-white' : 'bg-white text-gray-700' }}">
                        {{ $hoy ? 'Todos' : 'Hoy' }}
                    </button>

                    <div class="flex items-center gap-2">
                        <label class="text-gray-700 text-sm">Inicio:</label>
                        <input type="text"
                            wire:model.live="fechaInicio"
                            placeholder="dd/mm/yyyy"
                            class="border rounded px-2 py-1 text-sm w-full"
                            maxlength="10"
                            oninput="this.value = this.value
                       .replace(/\D/g,'')
                       .replace(/^(\d{2})(\d)/,'$1/$2')
                       .replace(/^(\d{2}\/\d{2})(\d)/,'$1/$2');">
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="text-gray-700 text-sm">Fin:</label>
                        <input type="text"
                            wire:model.live="fechaFin"
                            placeholder="dd/mm/yyyy"
                            class="border rounded px-2 py-1 text-sm w-full"
                            maxlength="10"
                            oninput="this.value = this.value
                       .replace(/\D/g,'')
                       .replace(/^(\d{2})(\d)/,'$1/$2')
                       .replace(/^(\d{2}\/\d{2})(\d)/,'$1/$2');">
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 mb-4">
                <div class="flex-1">
                    <input type="text" wire:model.live="searchCliente"
                        placeholder="Buscar cliente..."
                        class="border rounded px-2 py-1 w-full text-sm">
                </div>
                <div class="flex-1">
                    <input type="text" wire:model.live="searchExistencia"
                        placeholder="Buscar producto..."
                        class="border rounded px-2 py-1 w-full text-sm">
                </div>
            </div>

            <button
                wire:click="exportarPDF"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm shadow">
                Exportar PDF
            </button>
        </div>

        <div class="overflow-auto max-h-[500px] border border-gray-200 rounded-md p-3 bg-gray-50">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">

                <div class="bg-white border rounded-md p-3 shadow-sm flex flex-col">
                    <span class="text-gray-600 font-semibold text-sm">Ventas QR</span>
                    <span class="text-green-600 font-bold text-lg">
                        Bs {{ number_format($ventasPorMetodo['qr'], 2) }}
                    </span>
                </div>

                <div class="bg-white border rounded-md p-3 shadow-sm flex flex-col">
                    <span class="text-gray-600 font-semibold text-sm">Ventas Efectivo</span>
                    <span class="text-green-600 font-bold text-lg">
                        Bs {{ number_format($ventasPorMetodo['efectivo'], 2) }}
                    </span>
                </div>

                <div class="bg-white border rounded-md p-3 shadow-sm flex flex-col">
                    <span class="text-gray-600 font-semibold text-sm">Ventas Crédito</span>
                    <span class="text-green-600 font-bold text-lg">
                        Bs {{ number_format($ventasPorMetodo['credito'], 2) }}
                    </span>
                </div>

                <div class="bg-white border rounded-md p-3 shadow-sm flex flex-col">
                    <span class="text-gray-600 font-semibold text-sm">Gastos (solo Efectivo)</span>
                    <span class="text-red-600 font-bold text-lg">
                        Bs {{ number_format($totalGastos, 2) }}
                    </span>
                </div>

                <div class="bg-white border rounded-md p-3 shadow-sm flex flex-col">
                    <span class="text-gray-600 font-semibold text-sm">Total Ventas</span>
                    <span class="text-teal-700 font-bold text-lg">
                        Bs {{ number_format(
                    $ventasPorMetodo['qr'] +
                    ($ventasPorMetodo['efectivo'] - $totalGastos) +
                    $ventasPorMetodo['credito'],
                2) }}
                    </span>
                </div>

            </div>
        </div>

        <br>

        <br>

        <div class="overflow-auto max-h-[300px] border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">
                <thead class="bg-teal-50 sticky top-0 z-10 text-xs sm:text-sm">
                    <tr>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-left text-teal-700 font-semibold">Fecha</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-left text-teal-700 font-semibold">Personal</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-left text-teal-700 font-semibold">Descripción</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-right text-teal-700 font-semibold">Monto</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($gastos as $gasto)
                    <tr class="hover:bg-teal-50 text-xs sm:text-sm">
                        <td class="px-2 sm:px-4 py-1 sm:py-2 whitespace-nowrap">
                            {{ $gasto->fecha ? \Carbon\Carbon::parse($gasto->fecha)->format('d/m/Y H:i') : '-' }}
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2">
                            {{ $gasto->personal?->nombres ?? '-' }}
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2 truncate">
                            {{ $gasto->descripcion ?? '-' }}
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2 text-right font-bold text-red-500">
                            {{ $gasto->monto ? 'Bs '.number_format($gasto->monto, 2) : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-2 text-gray-500 italic">No hay gastos registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <br>

        @php
        $resumenExistencias = [];
        foreach ($pedidos as $pedido) {
        foreach ($pedido->detalles as $detalle) {
        $existencia = $detalle->existencia;
        $pagoDetalle = $detalle->pagoDetalles->first();

        if (!$existencia || !$pagoDetalle) continue;

        $key = $existencia->id;

        if (!isset($resumenExistencias[$key])) {
        $resumenExistencias[$key] = [
        'codigo' => $existencia->codigo ?? $existencia->id,
        'descripcion' => $existencia->existenciable?->descripcion ?? 'Sin descripción',
        'cantidad' => 0,
        'subtotal' => 0,
        ];
        }

        $resumenExistencias[$key]['cantidad'] += $detalle->cantidad;
        $resumenExistencias[$key]['subtotal'] += $pagoDetalle->subtotal;
        }
        }
        @endphp

        <div class="overflow-auto max-h-[400px] border border-gray-200 rounded-md mb-4">
            <table class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">
                <thead class="bg-teal-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-left text-teal-700 font-semibold">Código</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-left text-teal-700 font-semibold">Existencia</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-right text-teal-700 font-semibold">Cantidad total</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-right text-teal-700 font-semibold">Subtotal total</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-right text-teal-700 font-semibold">Precio unitario</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($resumenExistencias as $existencia)
                    @php
                    $precioUnitario = $existencia['cantidad'] > 0
                    ? $existencia['subtotal'] / $existencia['cantidad']
                    : 0;
                    @endphp

                    <tr class="hover:bg-teal-50">
                        <td class="px-2 sm:px-4 py-1 sm:py-2 font-semibold">
                            {{ $existencia['codigo'] }}
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2">
                            {{ $existencia['descripcion'] }}
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2 text-right font-semibold">
                            {{ $existencia['cantidad'] }}
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2 text-right font-bold text-teal-700">
                            Bs {{ number_format($existencia['subtotal'], 2) }}
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2 text-right">
                            Bs {{ number_format($precioUnitario, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-2 text-gray-500 italic">
                            No hay existencias para agrupar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="overflow-auto max-h-[500px] border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">
                <thead class="bg-teal-50 sticky top-0 z-10 text-xs sm:text-sm">
                    <tr>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-left text-teal-700 font-semibold">Fecha</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-left text-teal-700 font-semibold">Cliente</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-left text-teal-700 font-semibold">Vendedor</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-left text-teal-700 font-semibold">Producto</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-right text-teal-700 font-semibold">Cant.</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-right text-teal-700 font-semibold">Precio N.</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-right text-teal-700 font-semibold">Precio A.</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-right text-teal-700 font-semibold">Subtotal</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-left text-teal-700 font-semibold">Método</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-left text-teal-700 font-semibold">N° factura(s)</th>
                        <th class="px-2 sm:px-4 py-1 sm:py-2 text-left text-teal-700 font-semibold">
                            N° recibo
                        </th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pedidos as $pedido)
                    @php
                    $pago = $pedido->pagos->first();
                    @endphp

                    @forelse($pedido->detalles as $detalle)
                    @php
                    $pagoDetalle = $detalle->pagoDetalles->first();
                    @endphp

                    <tr class="hover:bg-teal-50 text-xs sm:text-sm">
                        <td class="px-2 sm:px-4 py-1 sm:py-2 whitespace-nowrap">
                            {{ $pedido->fecha_pedido ? \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') : 'Sin fecha' }}
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2">
                            {{ $pedido->cliente?->nombre ?? 'Sin cliente' }}
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2">
                            {{ $pedido->cliente?->personal?->nombres ?? 'Sin vendedor' }}
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2 truncate">
                            {{ $detalle->existencia?->existenciable?->descripcion ?? 'Sin producto' }}
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2 text-right font-semibold">
                            {{ $detalle->cantidad }}
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2 text-right text-gray-500">
                            {{ $pagoDetalle ? 'Bs '.number_format($pagoDetalle->precio_base, 2) : '-' }}
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2 text-right font-semibold text-teal-700">
                            {{ $pagoDetalle ? 'Bs '.number_format($pagoDetalle->precio_aplicado, 2) : '-' }}
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2 text-right font-bold">
                            {{ $pagoDetalle ? 'Bs '.number_format($pagoDetalle->subtotal, 2) : '-' }}
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2">
                            @if($pago)
                            <span class="{{ $pago->estado == 0 ? 'text-red-500 font-bold' : 'text-green-500 font-bold' }}">
                                @switch($pago->metodo)
                                @case(0) QR @break
                                @case(1) Efectivo @break
                                @case(2) Crédito/Contrato @break
                                @default Otro
                                @endswitch
                            </span>
                            @else
                            -
                            @endif
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2 bg-white">
                            @if($pago)
                            <span class="{{ $pago->estado == 0 ? 'text-red-500' : 'text-green-500 font-semibold' }}">
                                {{ $pago->codigo_factura ?? '-' }}
                            </span>
                            @else
                            -
                            @endif
                        </td>

                        <td class="px-2 sm:px-4 py-1 sm:py-2">
                            @if($pago)
                            {{ $pago->referencia ?? '-' }}
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center py-2 text-gray-500 italic">
                            Pedido sin detalles
                        </td>
                    </tr>
                    @endforelse
                    @empty
                    <tr>
                        <td colspan="12" class="text-center py-2 text-gray-600">
                            No hay pedidos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>
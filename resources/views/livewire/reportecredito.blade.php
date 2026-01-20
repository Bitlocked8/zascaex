<div class="p-2 mt-20 flex justify-center bg-transparent">
    <div class="w-full max-w-screen-xl">

        <h3 class="text-center text-xl sm:text-2xl font-bold uppercase text-indigo-700 bg-indigo-100
                   px-4 sm:px-6 py-1 sm:py-2 rounded-full mx-auto shadow-sm mb-4">
            Reporte Créditos
        </h3>

        <div class="flex flex-col sm:flex-row gap-2 mb-4 items-center">
            <div class="mb-2">
                <label class="block text-sm font-semibold text-white">Fechas</label>

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

            <!-- NUEVOS FILTROS DE CRÉDITO -->
            <div class="mb-2">
                <label class="block text-sm font-semibold text-white mb-1">Método de pago</label>
                <div class="border rounded-md h-40 overflow-y-auto bg-white scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    <button wire:click="$set('filtroMetodo', null)"
                        class="w-full text-left px-2 py-1 text-sm border-b {{ $filtroMetodo === null ? 'bg-teal-500 text-white' : 'bg-white text-gray-700' }}">
                        Todos
                    </button>
                    <button wire:click="$set('filtroMetodo', 0)"
                        class="w-full text-left px-2 py-1 text-sm border-b {{ $filtroMetodo === 0 ? 'bg-teal-500 text-white' : 'bg-white text-gray-700' }}">
                        QR
                    </button>
                    <button wire:click="$set('filtroMetodo', 1)"
                        class="w-full text-left px-2 py-1 text-sm border-b {{ $filtroMetodo === 1 ? 'bg-teal-500 text-white' : 'bg-white text-gray-700' }}">
                        Efectivo
                    </button>
                    <button wire:click="$set('filtroMetodo', 2)"
                        class="w-full text-left px-2 py-1 text-sm border-b {{ $filtroMetodo === 2 ? 'bg-teal-500 text-white' : 'bg-white text-gray-700' }}">
                        Crédito
                    </button>
                </div>
            </div>

            <div class="mb-2">
                <label class="block text-sm font-semibold text-white mb-1">Estado del pago</label>
                <div class="border rounded-md h-40 overflow-y-auto bg-white scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    <button wire:click="$set('filtroEstadoPago', null)"
                        class="w-full text-left px-2 py-1 text-sm border-b {{ $filtroEstadoPago === null ? 'bg-teal-500 text-white' : 'bg-white text-gray-700' }}">
                        Todos
                    </button>
                    <button wire:click="$set('filtroEstadoPago', 0)"
                        class="w-full text-left px-2 py-1 text-sm border-b {{ $filtroEstadoPago === 0 ? 'bg-teal-500 text-white' : 'bg-white text-gray-700' }}">
                        Sin pagar
                    </button>
                    <button wire:click="$set('filtroEstadoPago', 1)"
                        class="w-full text-left px-2 py-1 text-sm border-b {{ $filtroEstadoPago === 1 ? 'bg-teal-500 text-white' : 'bg-white text-gray-700' }}">
                        Pagado
                    </button>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 mb-4">
                <div class="flex-1">
                    <input type="text" wire:model.live="searchCliente"
                        placeholder="Buscar cliente..."
                        class="border rounded px-2 py-1 w-full text-sm">
                </div>
            </div>

            <button wire:click="exportarPDF"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm shadow">
                Exportar PDF
            </button>
        </div>

        <div class="overflow-auto max-h-[200px] border border-gray-200 rounded-md p-3 bg-gray-50 mb-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">

                <div class="bg-white border rounded-md p-3 shadow-sm flex flex-col">
                    <span class="text-gray-600 font-semibold text-sm">Pagos de Pedidos</span>
                    <span class="text-indigo-600 font-bold text-lg">
                        {{ $pedidos->count() }}
                    </span>
                </div>

                <div class="bg-white border rounded-md p-3 shadow-sm flex flex-col">
                    <span class="text-gray-600 font-semibold text-sm">QR</span>
                    <span class="text-indigo-700 font-bold text-sm">
                        Sin pagar: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo', 0)->where('estado', 0)->count()) }}
                        <span class="text-gray-400">/</span>
                        Pagado: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo', 0)->where('estado', 1)->count()) }}
                    </span>
                </div>

                <div class="bg-white border rounded-md p-3 shadow-sm flex flex-col">
                    <span class="text-gray-600 font-semibold text-sm">Efectivo</span>
                    <span class="text-indigo-700 font-bold text-sm">
                        Sin pagar: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo', 1)->where('estado', 0)->count()) }}
                        <span class="text-gray-400">/</span>
                        Pagado: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo', 1)->where('estado', 1)->count()) }}
                    </span>
                </div>

                <div class="bg-white border rounded-md p-3 shadow-sm flex flex-col">
                    <span class="text-gray-600 font-semibold text-sm">Crédito/Contrato</span>
                    <span class="text-indigo-700 font-bold text-sm">
                        Sin pagar: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo', 2)->where('estado', 0)->count()) }}
                        <span class="text-gray-400">/</span>
                        Pagado: {{ $pedidos->sum(fn($p) => $p->pagos->where('metodo', 2)->where('estado', 1)->count()) }}
                    </span>
                </div>

            </div>
        </div>




        <div class="overflow-auto max-h-[400px] border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">
                <thead class="bg-indigo-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-2 sm:px-4 py-2 text-left text-indigo-700 font-semibold">Fecha</th>
                        <th class="px-2 sm:px-4 py-2 text-left text-indigo-700 font-semibold">Pedido</th>
                        <th class="px-2 sm:px-4 py-2 text-left text-indigo-700 font-semibold">Cliente</th>
                        <th class="px-2 sm:px-4 py-2 text-left text-indigo-700 font-semibold">Estado</th>
                        <th class="px-2 sm:px-4 py-2 text-left text-indigo-700 font-semibold">Pagos</th>
                        <th class="px-2 sm:px-4 py-2 text-right text-indigo-700 font-semibold">Total Crédito</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pedidos as $pedido)
                    <tr class="hover:bg-indigo-50 align-top">
                        <td class="px-2 sm:px-4 py-2 whitespace-nowrap">
                            {{ $pedido->fecha_pedido
                            ? \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i')
                            : '-' }}
                        </td>

                        <td class="px-2 sm:px-4 py-2 font-semibold break-all">
                            {{ $pedido->codigo }}
                        </td>

                        <td class="px-2 sm:px-4 py-2 break-words whitespace-normal">
                            <div class="font-semibold">
                                {{ $pedido->cliente->nombre ?? 'Sin cliente' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $pedido->cliente->codigo ?? '' }}
                            </div>
                        </td>

                        <td class="px-2 sm:px-4 py-2">
                            @if($pedido->estado_pedido == 0)
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-700">
                                Pendiente
                            </span>
                            @elseif($pedido->estado_pedido == 1)
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-700">
                                En espera de pago
                            </span>
                            @elseif($pedido->estado_pedido == 2)
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-700">
                                Entregado
                            </span>
                            @else
                            <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>

                        <td class="px-2 sm:px-4 py-2">
                            @if($pedido->pagos->isEmpty())
                            <span class="text-gray-400 text-xs italic">Sin pagos</span>
                            @else
                            @foreach($pedido->pagos as $pago)
                            @php
                            $metodo = match($pago->metodo) {
                            0 => 'QR',
                            1 => 'Efectivo',
                            2 => 'Crédito',
                            default => '—'
                            };

                            $estadoPago = $pago->estado ? 'Pagado' : 'Sin Pagar';
                            $claseEstado = $pago->estado ? 'text-green-600' : 'text-red-600';
                            @endphp

                            <div class="border-b pb-1 mb-1">
                                <div class="text-xs font-semibold">
                                    {{ $metodo }} ·
                                    <span class="{{ $claseEstado }}">
                                        {{ $estadoPago }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-600">
                                    Bs {{ number_format($pago->monto, 2) }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $pago->fecha ? \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y H:i') : '' }}
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </td>

                        @php
                        $baseCredito = $pedido->pagos->where('metodo', 2)->sum('monto');

                        if ($baseCredito > 0) {
                        $pagosNoCreditoPagados = $pedido->pagos
                        ->whereIn('metodo', [0, 1])
                        ->where('estado', 1)
                        ->sum('monto');

                        $totalNeto = $baseCredito - $pagosNoCreditoPagados;
                        } else {
                        $totalNeto = 0;
                        }
                        @endphp


                        <td class="px-2 sm:px-4 py-2 text-right font-bold text-red-600 whitespace-nowrap">
                            Bs {{ number_format($totalNeto, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500 italic">
                            No hay pedidos a crédito
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


    </div>
</div>
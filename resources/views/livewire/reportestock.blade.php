<div class="p-4 mt-20 bg-gray-100 min-h-screen">

    <div class="flex justify-center gap-4 mb-6">
        <button wire:click="mostrarTabla('asignados')"
            class="px-4 py-2 rounded-lg font-semibold {{ $tablaActiva == 'asignados' ? 'bg-cyan-600 text-white' : 'bg-white border' }}">
            Asignaciones
        </button>
        <button wire:click="mostrarTabla('reposicions')"
            class="px-4 py-2 rounded-lg font-semibold {{ $tablaActiva == 'reposicions' ? 'bg-cyan-600 text-white' : 'bg-white border' }}">
            Reposiciones
        </button>
        <button wire:click="mostrarTabla('asignado_reposicions')"
            class="px-4 py-2 rounded-lg font-semibold {{ $tablaActiva == 'asignado_reposicions' ? 'bg-cyan-600 text-white' : 'bg-white border' }}">
            Asignado - Reposiciones
        </button>
        <button wire:click="deseleccionarTabla"
            class="px-4 py-2 rounded-lg font-semibold {{ is_null($tablaActiva) ? 'bg-cyan-600 text-white' : 'bg-white border' }}">
            Ninguna
        </button>
        <button wire:click="mostrarTabla('kardex')" class="px-4 py-2 bg-indigo-500 text-white rounded">Kardex</button>
    </div>
    <div class="w-full max-w-screen-xl mx-auto">
        @if($tablaActiva == 'kardex')
        <div class="mt-6 bg-white shadow rounded p-4 mx-auto">
            <h2 class="font-semibold mb-2 text-center">Reposiciones y Asignaciones</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 text-center">

                    @php
                    function formatoColorDecimal($numero, $decimales = 2) {
                    $numeroFormateado = number_format($numero, $decimales, '.', '');
                    $partes = explode('.', $numeroFormateado);
                    $entero = $partes[0];
                    $decimal = $partes[1] ?? '';

                    if ($decimal == '' || $decimal == str_repeat('0', $decimales)) {
                    return '<span class="text-right block text-green-600 font-semibold">'.$entero.'</span>';
                    } else {
                    return '<span class="text-right block">'.$entero.'<span class="text-red-500">,'.$decimal.'</span></span>';
                    }
                    }
                    @endphp

                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-1 border-b">Fecha</th>
                            <th class="px-2 py-1 border-b">Entrada</th>
                            <th class="px-2 py-1 border-b">Entrada/Salida</th>
                            <th class="px-2 py-1 border-b text-right">Cantidad Entrada</th>
                            <th class="px-2 py-1 border-b text-right">Pago Entrada</th>
                            <th class="px-2 py-1 border-b text-right">Cantidad Salida</th>
                            <th class="px-2 py-1 border-b text-right">Pago Salida</th>
                            <th class="px-2 py-1 border-b text-right">Cantidad Total</th>
                            <th class="px-2 py-1 border-b text-right">Saldo Total</th>
                        </tr>
                    </thead>

                    @php
                    $cantidadTotal = 0;
                    $saldoTotal = 0;
                    @endphp

                    <tbody>
                        @foreach($reposicions as $r)
                        @php
                        $cantidadTotal += $r->cantidad_inicial;
                        $saldoTotal += $r->comprobantes->sum('monto');
                        @endphp
                        <tr class="hover:bg-gray-100">
                            <td class="px-2 py-1 border-b">{{ $r->fecha }}</td>
                            <td class="px-2 py-1 border-b">{{ $r->codigo }}</td>
                            <td class="px-2 py-1 border-b">Entrada</td>
                            <td class="px-2 py-1 border-b text-right">{!! formatoColorDecimal($r->cantidad_inicial, 0) !!}</td>
                            <td class="px-2 py-1 border-b text-right">{!! formatoColorDecimal($r->comprobantes->sum('monto'), 2) !!}</td>
                            <td class="px-2 py-1 border-b"></td>
                            <td class="px-2 py-1 border-b"></td>
                            <td class="px-2 py-1 border-b text-right">{!! formatoColorDecimal($cantidadTotal, 0) !!}</td>
                            <td class="px-2 py-1 border-b text-right">{!! formatoColorDecimal($saldoTotal, 2) !!}</td>
                        </tr>
                        @endforeach

                        @foreach($asignados as $a)
                        @php
                        $cantidadSalida = $a->cantidad;
                        $montoSalida = 0;
                        foreach($a->reposiciones as $r) {
                        $cantidadAsignada = $r->pivot->cantidad;
                        $montoTotal = $r->comprobantes->sum('monto');
                        $cantidadLote = $r->cantidad_inicial ?: 1;
                        $montoSalida += $cantidadAsignada * $montoTotal / $cantidadLote;
                        }
                        $cantidadTotal -= $cantidadSalida;
                        $saldoTotal -= $montoSalida;
                        @endphp

                        <tr class="hover:bg-gray-100">
                            <td class="px-2 py-1 border-b">{{ $a->fecha }}</td>
                            <td class="px-2 py-1 border-b">{{ $a->codigo }}</td>
                            <td class="px-2 py-1 border-b">Salida</td>
                            <td class="px-2 py-1 border-b"></td>
                            <td class="px-2 py-1 border-b"></td>
                            <td class="px-2 py-1 border-b">{!! formatoColorDecimal($cantidadSalida) !!}</td>
                            <td class="px-2 py-1 border-b">{!! formatoColorDecimal($montoSalida) !!}</td>
                            <td class="px-2 py-1 border-b">{!! formatoColorDecimal($cantidadTotal) !!}</td>
                            <td class="px-2 py-1 border-b">{!! formatoColorDecimal($saldoTotal) !!}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
        @endif

        @if($tablaActiva == 'asignados')
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-xl font-semibold mb-4">Asignaciones</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 border-b text-center">ID</th>
                            <th class="px-4 py-2 border-b text-center">Código</th>
                            <th class="px-4 py-2 border-b text-center">Cantidad</th>
                            <th class="px-4 py-2 border-b text-center">Cantidad Asignada</th>
                            <th class="px-4 py-2 border-b text-center">Personal</th>
                            <th class="px-4 py-2 border-b text-center">Fecha</th>
                            <th class="px-4 py-2 border-b text-center">monto total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asignados as $a)
                        @php
                        $totalPrecio = 0;
                        @endphp

                        @foreach($a->reposiciones as $r)
                        @php
                        $cantidadAsignada = $r->pivot->cantidad;
                        $montoTotal = $r->comprobantes->sum('monto');
                        $cantidadLote = $r->cantidad_inicial ?: 1;
                        $totalPrecio += $cantidadAsignada * $montoTotal / $cantidadLote;
                        @endphp
                        @endforeach

                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border-b text-center">{{ $a->id }}</td>
                            <td class="px-4 py-2 border-b text-center">{{ $a->codigo }}</td>
                            <td class="px-4 py-2 border-b text-center">{{ $a->cantidad }}</td>
                            <td class="px-4 py-2 border-b text-center">{{ $a->cantidad_original }}</td>
                            <td class="px-4 py-2 border-b text-center">{{ $a->personal->nombres ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border-b text-center">{{ $a->fecha }}</td>
                            <td class="px-4 py-2 border-b text-center">{{ number_format($totalPrecio, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif


        @if($tablaActiva == 'reposicions')
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-xl font-semibold mb-4">Reposiciones</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg text-center">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 border-b">ID</th>
                            <th class="px-4 py-2 border-b">Código</th>
                            <th class="px-4 py-2 border-b">Cantidad Disponible</th>
                            <th class="px-4 py-2 border-b">Cantidad de entrada</th>
                            <th class="px-4 py-2 border-b">Personal</th>
                            <th class="px-4 py-2 border-b">Proveedor</th>
                            <th class="px-4 py-2 border-b">Fecha</th>
                            <th class="px-4 py-2 border-b text-center">Pago</th>
                            <th class="px-4 py-2 border-b text-center">Precio por unidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reposicions as $r)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border-b">{{ $r->id }}</td>
                            <td class="px-4 py-2 border-b">{{ $r->codigo }}</td>
                            <td class="px-4 py-2 border-b">{{ $r->cantidad }}</td>
                            <td class="px-4 py-2 border-b">{{ $r->cantidad_inicial }}</td>
                            <td class="px-4 py-2 border-b">{{ $r->personal->nombres ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border-b">{{ $r->proveedor->razonSocial ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border-b">{{ $r->fecha }}</td>
                            <td class="px-4 py-2 border-b text-center">{{ $r->comprobantes->sum('monto') }}</td>
                            <td class="px-4 py-2 border-b text-center">
                                {{ number_format($r->comprobantes->sum('monto') > 0 ? $r->comprobantes->sum('monto') / $r->cantidad_inicial : 0, 3) }}

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        @if($tablaActiva == 'asignado_reposicions')
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-xl font-semibold mb-4">Asignaciones por Reposición</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg text-center">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 border-b">ID</th>
                            <th class="px-4 py-2 border-b">Código Asignación</th>
                            <th class="px-4 py-2 border-b">Código Reposición</th>
                            <th class="px-4 py-2 border-b">Cantidad asignada</th>
                            <th class="px-4 py-2 border-b">Personal</th>
                            <th class="px-4 py-2 border-b">Fecha</th>
                            <th class="px-4 py-2 border-b text-center">Precio completo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asignados as $a)
                        @foreach($a->reposiciones as $r)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border-b">{{ $r->pivot->id }}</td>
                            <td class="px-4 py-2 border-b">{{ $a->codigo }}</td>
                            <td class="px-4 py-2 border-b">{{ $r->codigo }}</td>
                            <td class="px-4 py-2 border-b">{{ $r->pivot->cantidad }}</td>
                            <td class="px-4 py-2 border-b">{{ $a->personal->nombres ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border-b">{{ $a->fecha }}</td>
                            <td class="px-4 py-2 border-b text-center">
                                {{ number_format(
                                  $r->cantidad_inicial && $r->comprobantes->sum('monto') > 0 
                                  ? ($r->pivot->cantidad * $r->comprobantes->sum('monto')) / $r->cantidad_inicial 
                                  : 0
                                     , 2) }}
                            </td>

                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>




        @endif


    </div>
</div>
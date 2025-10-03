<div class="p-4 mt-16">

    <div class="bg-white shadow-md rounded-xl p-4 mb-6">
        <div class="flex flex-wrap justify-center gap-6 items-end mb-6">
            <!-- Fecha Inicio -->
            <div class="flex flex-col">
                <label for="fechaInicio" class="text-sm font-medium text-gray-600 mb-1">Desde</label>
                <input type="date" wire:model.live="fechaInicio" id="fechaInicio"
                    class="border rounded-lg p-2 w-48 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Fecha Fin -->
            <div class="flex flex-col">
                <label for="fechaFin" class="text-sm font-medium text-gray-600 mb-1">Hasta</label>
                <input type="date" wire:model.live="fechaFin" id="fechaFin"
                    class="border rounded-lg p-2 w-48 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Ítem -->
            <div class="flex flex-col">
                <label for="existenciableId" class="text-sm font-medium text-gray-600 mb-1">Ítem</label>
                <select wire:model.live="existenciableId" id="existenciableId"
                    class="border rounded-lg p-2 w-64 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Todos --</option>
                    @foreach($existenciables as $ex)
                    <option value="{{ $ex->id }}">
                        {{ $ex->existenciable->descripcion ?? 'Sin nombre' }}
                        ({{ class_basename($ex->existenciable_type) }})
                    </option>
                    @endforeach
                </select>
            </div>

            <button wire:click="aplicarFiltros"
                class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition">
                Aplicar
            </button>
            <button wire:click="limpiarFiltros"
                class="bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-600 transition">
                Limpiar
            </button>
        </div>
        <div class="flex flex-wrap justify-center gap-4">
            <button wire:click="mostrarTabla('asignados')"
                class="px-5 py-2 rounded-lg font-semibold {{ $tablaActiva == 'asignados' ? 'bg-cyan-600 text-white' : 'bg-white border' }} transition">
                Asignaciones
            </button>
            <button wire:click="mostrarTabla('reposicions')"
                class="px-5 py-2 rounded-lg font-semibold {{ $tablaActiva == 'reposicions' ? 'bg-cyan-600 text-white' : 'bg-white border' }} transition">
                Reposiciones
            </button>
            <button wire:click="mostrarTabla('asignado_reposicions')"
                class="px-5 py-2 rounded-lg font-semibold {{ $tablaActiva == 'asignado_reposicions' ? 'bg-cyan-600 text-white' : 'bg-white border' }} transition">
                Asignado - Reposiciones
            </button>

            <button wire:click="mostrarTabla('kardex')"
                class="px-5 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition">
                Kardex
            </button>
            <button wire:click="deseleccionarTabla"
                class="px-5 py-2 rounded-lg font-semibold {{ is_null($tablaActiva) ? 'bg-cyan-600 text-white' : 'bg-white border' }} transition">
                Ninguna
            </button>
        </div>
    </div>
    <div class="w-full max-w-screen-xl mx-auto">
        @if($tablaActiva == 'kardex')
        <div class="mt-6 bg-white shadow rounded p-4 mx-auto">
            <h2 class="font-semibold mb-2 text-center">Reposiciones y Asignaciones</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 text-center">

                    @php
                    $acumuladoCantidad = 0;
                    $acumuladoMonto = 0;
                    @endphp

                    @foreach($reposicions as $r)
                    @php
                    $cantidadAsignada = $r->asignados->sum(function($a) use ($r) {
                    $rel = $a->reposiciones->find($r->id);
                    return $rel ? $rel->pivot->cantidad : 0;
                    });
                    $disponible = $r->cantidad_inicial - $cantidadAsignada;
                    $montoTotal = $r->comprobantes->sum('monto');
                    $precioUnitario = $r->cantidad_inicial ? $montoTotal / $r->cantidad_inicial : 0;
                    $valorDisponible = $disponible * $precioUnitario;

                    $acumuladoCantidad += $r->cantidad_inicial; // sumas entrada
                    $acumuladoMonto += $montoTotal;
                    @endphp

                    <tr class="hover:bg-gray-100">
                        <td>{{ $r->fecha }}</td>
                        <td>{{ $r->codigo }}</td>
                        <td>Entrada</td>
                        <td class="text-right">{{ $r->cantidad_inicial }}</td>
                        <td class="text-right">{{ $montoTotal }}</td>
                        <td></td>
                        <td></td>
                        <td class="text-right">{{ $acumuladoCantidad }}</td>
                        <td class="text-right">{{ $acumuladoMonto }}</td>
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

                    $acumuladoCantidad -= $cantidadSalida;
                    $acumuladoMonto -= $montoSalida;
                    @endphp

                    <tr class="hover:bg-gray-100">
                        <td>{{ $a->fecha }}</td>
                        <td>{{ $a->codigo }}</td>
                        <td>Salida</td>
                        <td></td>
                        <td></td>
                        <td class="text-right">{{ $cantidadSalida }}</td>
                        <td class="text-right">{{ $montoSalida }}</td>
                        <td class="text-right">{{ $acumuladoCantidad }}</td>
                        <td class="text-right">{{ $acumuladoMonto }}</td>
                    </tr>
                    @endforeach

                    @php
                    $acumuladoDisponibleCantidad = $acumuladoCantidad; // después de salidas
                    $acumuladoDisponibleMonto = $acumuladoMonto; // después de salidas
                    @endphp

                    @foreach($reposicions as $r)
                    @php
                    $cantidadAsignada = $r->asignados->sum(function($a) use ($r) {
                    $rel = $a->reposiciones->find($r->id);
                    return $rel ? $rel->pivot->cantidad : 0;
                    });
                    $disponibleCantidad = $r->cantidad_inicial - $cantidadAsignada;

                    $montoTotal = $r->comprobantes->sum('monto');
                    $precioUnitario = $r->cantidad_inicial ? $montoTotal / $r->cantidad_inicial : 0;
                    $disponibleMonto = $disponibleCantidad * $precioUnitario;

                    if ($disponibleCantidad > 0) {
                    $acumuladoDisponibleCantidad -= $disponibleCantidad;
                    $acumuladoDisponibleMonto -= $disponibleMonto;
                    }
                    @endphp

                    @if($disponibleCantidad > 0)
                    <tr class="hover:bg-gray-100 bg-yellow-50">
                        <td>{{ $r->fecha }}</td>
                        <td>{{ $r->codigo }}</td>
                        <td>Disponible</td>
                        <td></td>
                        <td></td>
                        <td class="text-right">{{ $disponibleCantidad }}</td>
                        <td class="text-right">{{ number_format($disponibleMonto, 2) }}</td>
                        <td class="text-right">{{ $acumuladoDisponibleCantidad }}</td>
                        <td class="text-right">{{ number_format($acumuladoDisponibleMonto, 2) }}</td>
                    </tr>
                    @endif
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
        <div class="bg-white shadow-lg rounded-xl p-4">
            <h2 class="text-lg font-semibold mb-3 text-center">Asignaciones por Reposición (Kardex)</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-800 text-center table-auto text-xs rounded-lg overflow-hidden">
                    <thead class="bg-indigo-900 text-white text-xs">
                        <tr>
                            <th class="px-2 py-1 border-b">Ítem</th>
                            <th class="px-2 py-1 border-b">Proveedor</th>
                            <th class="px-2 py-1 border-b">Código Asignación</th>
                            <th class="px-2 py-1 border-b">Código Reposición</th>
                            <th class="px-2 py-1 border-b">Cantidad inicial</th>
                            <th class="px-2 py-1 border-b">Cantidad asignada</th>
                            <th class="px-2 py-1 border-b">Cantidad restante</th>
                            <th class="px-2 py-1 border-b">Monto inicial</th>
                            <th class="px-2 py-1 border-b">Monto asignado</th>
                            <th class="px-2 py-1 border-b">Monto restante</th>
                            <th class="px-2 py-1 border-b">Personal</th>
                            <th class="px-2 py-1 border-b">Fecha</th>
                            <th class="px-2 py-1 border-b">Acumulado</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        @foreach($asignados as $a)
                        @foreach($a->reposiciones as $r)
                        @php
                        $cantidadInicial = $r->cantidad_inicial;
                        $cantidadRestante = $cantidadInicial;
                        $montoInicial = $r->comprobantes->sum('monto');
                        $montoRestante = $montoInicial;
                        $precioUnitario = $cantidadInicial > 0 ? $montoInicial / $cantidadInicial : 0;
                        $acumuladoMonto = 0;
                        @endphp

                        {{-- Fila de reposición (entrada) --}}
                        <tr class="bg-indigo-700 text-white font-semibold text-xs rounded-t-lg">
                            <td class="px-2 py-1 border-b">{{ class_basename($r->existencia->existenciable_type) }} - {{ $r->existencia->existenciable->descripcion ?? 'N/A' }}</td>
                            <td class="px-2 py-1 border-b">{{ $r->proveedor?->razonSocial ?? 'Sin proveedor' }}</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">{{ $r->codigo }}</td>
                            <td class="px-2 py-1 border-b">{{ $cantidadInicial }}</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">{{ number_format($montoInicial, 2) }}</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                        </tr>

                        {{-- Filas de asignaciones (salidas) --}}
                        @foreach($r->asignados as $asign)
                        @php
                        $cantidadAsignada = $asign->pivot->cantidad;
                        $cantidadRestante -= $cantidadAsignada;
                        $montoAsignado = $cantidadAsignada * $precioUnitario;
                        $montoRestante -= $montoAsignado;
                        $acumuladoMonto += $montoAsignado;
                        @endphp
                        <tr class="hover:bg-gray-50 text-xs">
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">{{ $asign->codigo }}</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">{{ $cantidadAsignada }}</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">{{ number_format($montoAsignado, 2) }}</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">{{ $asign->personal->nombres ?? 'N/A' }}</td>
                            <td class="px-2 py-1 border-b">{{ $asign->fecha }}</td>
                            <td class="px-2 py-1 border-b">-</td>
                        </tr>

                        {{-- Fila separada: cantidad restante, monto restante y acumulado --}}
                        <tr class="bg-indigo-100 text-xs font-semibold rounded-b-lg">
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">{{ $cantidadRestante }}</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">{{ number_format($montoRestante, 2) }}</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">{{ number_format($acumuladoMonto, 2) }}</td>
                        </tr>
                        @endforeach
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
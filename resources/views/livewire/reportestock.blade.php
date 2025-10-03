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
            <button wire:click="toggleCantidades"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                {{ $ocultarCantidades ? 'Mostrar Cantidades' : 'Ocultar Cantidades' }}
            </button>

            <button wire:click="toggleMontos"
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                {{ $ocultarMontos ? 'Mostrar Montos' : 'Ocultar Montos' }}
            </button>
        </div>
    </div>
    <div class="w-full max-w-screen-xl mx-auto">
        @if($tablaActiva == 'kardex')
        <div class="mt-6 bg-white shadow-lg rounded-xl p-4 mx-auto">
            <h2 class="font-semibold mb-3 text-center text-lg">Reposiciones y Asignaciones (Kardex)</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-800 text-center table-auto text-xs rounded-lg overflow-hidden">
                    <thead class="bg-indigo-900 text-white text-xs">
                        <tr>
                            <th class="px-2 py-1 border-b">Fecha</th>
                            <th class="px-2 py-1 border-b">Código</th>
                            <th class="px-2 py-1 border-b">Tipo</th>
                            <th class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">Entrada</th>
                            <th class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">Monto Entrada</th>
                            <th class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">Salida Cantidad</th>
                            <th class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">Salida Monto</th>
                            <th class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">Acumulado Cantidad</th>
                            <th class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">Acumulado Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $acumuladoCantidad = 0;
                        $acumuladoMonto = 0;
                        @endphp

                        {{-- Entradas --}}
                        @foreach($reposicions as $r)
                        @php
                        $cantidadAsignada = $r->asignados->sum(function($a) use ($r) {
                        $rel = $a->reposiciones->find($r->id);
                        return $rel ? $rel->pivot->cantidad : 0;
                        });
                        $disponible = $r->cantidad_inicial - $cantidadAsignada;
                        $montoTotal = $r->comprobantes->sum('monto');
                        $precioUnitario = $r->cantidad_inicial ? $montoTotal / $r->cantidad_inicial : 0;

                        $acumuladoCantidad += $r->cantidad_inicial;
                        $acumuladoMonto += $montoTotal;
                        @endphp
                        <tr class="hover:bg-gray-50 bg-indigo-100 font-semibold">
                            <td>{{ $r->fecha }}</td>
                            <td>{{ $r->codigo }}</td>
                            <td>Entrada</td>
                            <td class="{{ $ocultarCantidades ? 'hidden' : '' }}">{{ $r->cantidad_inicial }}</td>
                            <td class="{{ $ocultarMontos ? 'hidden' : '' }}">{{ number_format($montoTotal,2) }}</td>
                            <td class="{{ $ocultarCantidades ? 'hidden' : '' }}"></td>
                            <td class="{{ $ocultarMontos ? 'hidden' : '' }}"></td>
                            <td class="{{ $ocultarCantidades ? 'hidden' : '' }}">{{ $acumuladoCantidad }}</td>
                            <td class="{{ $ocultarMontos ? 'hidden' : '' }}">{{ number_format($acumuladoMonto,2) }}</td>
                        </tr>
                        @endforeach

                        {{-- Salidas --}}
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
                        <tr class="hover:bg-gray-50">
                            <td>{{ $a->fecha }}</td>
                            <td>{{ $a->codigo }}</td>
                            <td>Salida</td>
                            <td class="{{ $ocultarCantidades ? 'hidden' : '' }}"></td>
                            <td class="{{ $ocultarMontos ? 'hidden' : '' }}"></td>
                            <td class="{{ $ocultarCantidades ? 'hidden' : '' }}">{{ $cantidadSalida }}</td>
                            <td class="{{ $ocultarMontos ? 'hidden' : '' }}">{{ number_format($montoSalida,2) }}</td>
                            <td class="{{ $ocultarCantidades ? 'hidden' : '' }}">{{ $acumuladoCantidad }}</td>
                            <td class="{{ $ocultarMontos ? 'hidden' : '' }}">{{ number_format($acumuladoMonto,2) }}</td>
                        </tr>
                        @endforeach

                        {{-- Disponibles --}}
                        @php
                        $acumuladoDisponibleCantidad = $acumuladoCantidad;
                        $acumuladoDisponibleMonto = $acumuladoMonto;
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
                        <tr class="hover:bg-gray-50 bg-yellow-50">
                            <td>{{ $r->fecha }}</td>
                            <td>{{ $r->codigo }}</td>
                            <td>Disponible</td>
                            <td class="{{ $ocultarCantidades ? 'hidden' : '' }}"></td>
                            <td class="{{ $ocultarMontos ? 'hidden' : '' }}"></td>
                            <td class="{{ $ocultarCantidades ? 'hidden' : '' }}">{{ $disponibleCantidad }}</td>
                            <td class="{{ $ocultarMontos ? 'hidden' : '' }}">{{ number_format($disponibleMonto,2) }}</td>
                            <td class="{{ $ocultarCantidades ? 'hidden' : '' }}">{{ $acumuladoDisponibleCantidad }}</td>
                            <td class="{{ $ocultarMontos ? 'hidden' : '' }}">{{ number_format($acumuladoDisponibleMonto,2) }}</td>
                        </tr>
                        @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        @endif


        @if($tablaActiva == 'asignados')
        <div class="bg-white shadow-lg rounded-xl p-4">
            <h2 class="text-lg font-semibold mb-3 text-center">Asignaciones</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-800 text-center table-auto text-xs rounded-lg overflow-hidden">
                    <thead class="bg-indigo-900 text-white text-xs">
                        <tr>
                            <th class="px-2 py-1 border-b">ID</th>
                            <th class="px-2 py-1 border-b">Código</th>
                            <th class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">Cantidad</th>
                            <th class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">Cantidad Asignada</th>
                            <th class="px-2 py-1 border-b">Personal</th>
                            <th class="px-2 py-1 border-b">Fecha</th>
                            <th class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">Monto Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        @foreach($asignados as $a)
                        @php
                        $totalPrecio = 0;
                        foreach($a->reposiciones as $r){
                        $cantidadAsignada = $r->pivot->cantidad;
                        $montoTotal = $r->comprobantes->sum('monto');
                        $cantidadLote = $r->cantidad_inicial ?: 1;
                        $totalPrecio += $cantidadAsignada * $montoTotal / $cantidadLote;
                        }
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-1 border-b">{{ $a->id }}</td>
                            <td class="px-2 py-1 border-b">{{ $a->codigo }}</td>
                            <td class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">{{ $a->cantidad }}</td>
                            <td class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">{{ $a->cantidad_original }}</td>
                            <td class="px-2 py-1 border-b">{{ $a->personal->nombres ?? 'N/A' }}</td>
                            <td class="px-2 py-1 border-b">{{ $a->fecha }}</td>
                            <td class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">{{ number_format($totalPrecio, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif



        @if($tablaActiva == 'reposicions')
        <div class="bg-white shadow-lg rounded-xl p-4">
            <h2 class="text-lg font-semibold mb-3 text-center">Reposiciones</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-800 text-center table-auto text-xs rounded-lg overflow-hidden">
                    <thead class="bg-indigo-900 text-white text-xs">
                        <tr>
                            <th class="px-2 py-1 border-b">ID</th>
                            <th class="px-2 py-1 border-b">Código</th>
                            <th class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">Cantidad Disponible</th>
                            <th class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">Cantidad de entrada</th>
                            <th class="px-2 py-1 border-b">Personal</th>
                            <th class="px-2 py-1 border-b">Proveedor</th>
                            <th class="px-2 py-1 border-b">Fecha</th>
                            <th class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">Pago</th>
                            <th class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">Precio por unidad</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        @foreach($reposicions as $r)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-1 border-b">{{ $r->id }}</td>
                            <td class="px-2 py-1 border-b">{{ $r->codigo }}</td>
                            <td class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">{{ $r->cantidad }}</td>
                            <td class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">{{ $r->cantidad_inicial }}</td>
                            <td class="px-2 py-1 border-b">{{ $r->personal->nombres ?? 'N/A' }}</td>
                            <td class="px-2 py-1 border-b">{{ $r->proveedor->razonSocial ?? 'N/A' }}</td>
                            <td class="px-2 py-1 border-b">{{ $r->fecha }}</td>
                            <td class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">{{ number_format($r->comprobantes->sum('monto'), 2) }}</td>
                            <td class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">
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

                            <th class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">Cantidad inicial</th>
                            <th class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">Cantidad asignada</th>
                            <th class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">Cantidad restante</th>

                            <th class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">Monto inicial</th>
                            <th class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">Monto asignado</th>
                            <th class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">Monto restante</th>

                            <th class="px-2 py-1 border-b">Personal</th>
                            <th class="px-2 py-1 border-b">Fecha</th>
                            <th class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">Acumulado</th>
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

                            <td class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">{{ $cantidadInicial }}</td>
                            <td class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">-</td>
                            <td class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">-</td>

                            <td class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">{{ number_format($montoInicial, 2) }}</td>
                            <td class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">-</td>
                            <td class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">-</td>

                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">-</td>
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

                            <td class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">{{ $cantidadAsignada }}</td>
                            <td class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">{{ $cantidadAsignada }}</td>
                            <td class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">-</td>

                            <td class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">{{ number_format($montoAsignado, 2) }}</td>
                            <td class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">-</td>
                            <td class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">-</td>

                            <td class="px-2 py-1 border-b">{{ $asign->personal->nombres ?? 'N/A' }}</td>
                            <td class="px-2 py-1 border-b">{{ $asign->fecha }}</td>
                            <td class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">-</td>
                        </tr>

                        {{-- Fila separada: cantidad restante, monto restante y acumulado --}}
                        <tr class="bg-indigo-100 text-xs font-semibold rounded-b-lg">
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>

                            <td class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">-</td>
                            <td class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">-</td>
                            <td class="px-2 py-1 border-b {{ $ocultarCantidades ? 'hidden' : '' }}">{{ $cantidadRestante }}</td>

                            <td class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">-</td>
                            <td class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">-</td>
                            <td class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">{{ number_format($montoRestante, 2) }}</td>

                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b">-</td>
                            <td class="px-2 py-1 border-b {{ $ocultarMontos ? 'hidden' : '' }}">{{ number_format($acumuladoMonto, 2) }}</td>
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
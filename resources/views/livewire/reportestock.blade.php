<div class="p-4 mt-10 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto bg-white shadow-lg rounded-2xl p-6">
        <h2 class="text-3xl font-bold text-center text-teal-700 mb-6 uppercase">
            Reporte de Stock General
        </h2>

        <div class="overflow-x-auto rounded-xl border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-teal-600 text-white">
                    <tr class="text-center">
                        <th class="py-3 px-2">Fecha</th>
                        <th class="py-3 px-2">Código</th>
                        <th class="py-3 px-2">Tipo</th>
                        <th class="py-3 px-2">Elemento</th>
                        <th class="py-3 px-2">Compatibilidad</th>
                        <th class="py-3 px-2">Cantidad</th>
                        <th class="py-3 px-2">Costo Unitario</th>
                        <th class="py-3 px-2">Costo Total</th>
                        <th class="py-3 px-2">Sucursal</th>
                        <th class="py-3 px-2">Proveedor</th>
                        <th class="py-3 px-2">Personal</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-center">
                    @forelse($movimientos as $m)
                        <tr class="hover:bg-gray-100 transition duration-150">
                            <!-- Fecha -->
                            <td class="py-3 px-2 text-gray-700">
                                {{ \Carbon\Carbon::parse($m['fecha'])->format('d/m/Y') }}
                            </td>

                            <!-- Código -->
                            <td class="py-3 px-2 font-semibold text-gray-800">
                                {{ $m['codigo'] ?? '-' }}
                            </td>

                            <!-- Tipo (Entrada / Salida / Proceso) -->
                            <td class="py-3 px-2">
                                @php
                                    $color = $m['tipo'] === 'Entrada'
                                        ? 'bg-green-100 text-green-700 border-green-400'
                                        : ($m['tipo'] === 'Salida'
                                            ? 'bg-red-100 text-red-700 border-red-400'
                                            : 'bg-blue-100 text-blue-700 border-blue-400');
                                @endphp
                                <span class="px-3 py-1 rounded-full border {{ $color }} font-semibold">
                                    {{ $m['tipo'] }}
                                </span>
                            </td>

                            <!-- Elemento -->
                            <td class="py-3 px-2 text-gray-800 font-medium">
                                <div class="flex flex-col items-center">
                                    <span>{{ $m['nombre'] ?? '-' }}</span>
                                    <span class="text-xs text-gray-500">
                                        ({{ $m['existencia_type'] ?? '-' }})
                                    </span>
                                </div>
                            </td>

                            <!-- Compatibilidad -->
                            <td class="py-3 px-2 text-gray-700 italic">
                                {{ $m['compatibilidad'] ?? '-' }}
                            </td>

                            <!-- Cantidad -->
                            <td class="py-3 px-2 font-bold text-gray-800">
                                {{ $m['cantidad'] }}
                            </td>

                            <!-- Precio Unitario -->
                            <td class="py-3 px-2 text-gray-700">
                                @if($m['precio_unitario'] > 0)
                                    {{ number_format($m['precio_unitario'], 2) }} Bs
                                @else
                                    -
                                @endif
                            </td>

                            <!-- Monto Total -->
                            <td class="py-3 px-2 text-gray-800">
                                @if($m['monto_total'] > 0)
                                    {{ number_format($m['monto_total'], 2) }} Bs
                                @else
                                    -
                                @endif
                            </td>

                            <!-- Sucursal -->
                            <td class="py-3 px-2 text-gray-700">
                                {{ $m['sucursal'] ?? '-' }}
                            </td>

                            <!-- Proveedor -->
                            <td class="py-3 px-2 text-gray-700">
                                {{ $m['proveedor'] ?? '-' }}
                            </td>

                            <!-- Personal -->
                            <td class="py-3 px-2 text-gray-700">
                                {{ $m['personal'] ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-6 text-gray-500 text-center">
                                No hay movimientos registrados todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 text-right">
            <span class="text-gray-500 text-sm">
                Total movimientos: {{ $movimientos->count() }}
            </span>
        </div>
    </div>
</div>

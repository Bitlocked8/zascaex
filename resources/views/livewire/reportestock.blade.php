<div class="p-4 mt-16">
    <h2 class="text-2xl font-bold mb-4 text-teal-700">Reporte General de Movimientos</h2>

    @if($movimientos->isEmpty())
        <p class="text-red-600 font-semibold">No hay movimientos registrados.</p>
    @else
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2 text-left">Tipo</th>
                        <th class="border px-4 py-2 text-left">Código</th>
                        <th class="border px-4 py-2 text-left">Fecha</th>
                        <th class="border px-4 py-2 text-left">Base/Producto</th>
                        <th class="border px-4 py-2 text-left">Cantidad Inicial</th>
                        <th class="border px-4 py-2 text-left">Cantidad</th>
                        <th class="border px-4 py-2 text-left">Precio Unitario</th>
                        <th class="border px-4 py-2 text-left">Monto Total</th>
                        <th class="border px-4 py-2 text-left">Sucursal</th>
                        <th class="border px-4 py-2 text-left">Personal</th>
                        <th class="border px-4 py-2 text-left">Proveedor</th>


                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($movimientos as $m)
                        @php
                            $basename = $m['existencia_type'] ?? '-';
                        @endphp
                        @if(!$m['es_asignacion'])
                            <!-- Fila principal: Reposición -->
                            <tr class="hover:bg-gray-50 bg-green-50">
                                <td class="border px-4 py-2 font-semibold text-green-700">{{ $m['tipo'] }}</td>
                                <td class="border px-4 py-2">{{ $m['codigo'] }}</td>
                                <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($m['fecha'])->format('d/m/Y H:i') }}</td>
                                <td class="border px-4 py-2">{{ $basename }}</td>
                                <td class="border px-4 py-2">{{ $m['cantidad_inicial'] ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ $m['cantidad'] ?? '-' }}</td>
                                <td class="border px-4 py-2">Bs {{ number_format($m['precio_unitario'] ?? 0, 2) }}</td>
                                <td class="border px-4 py-2">Bs {{ number_format($m['monto_total'] ?? 0, 2) }}</td>
                                <td class="border px-4 py-2">{{ $m['sucursal'] }}</td>
                                <td class="border px-4 py-2">{{ $m['personal'] }}</td>
                                <td class="border px-4 py-2">{{ $m['proveedor'] }}</td>


                            </tr>
                        @else
                            <!-- Subfila: Asignación dependiente -->
                            <tr class="hover:bg-gray-50 bg-red-50">
                                <td class="border px-4 py-2 font-semibold text-red-700">{{ $m['tipo'] }}</td>
                                <td class="border px-4 py-2 pl-8">↳ {{ $m['codigo'] }}</td>
                                <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($m['fecha'])->format('d/m/Y H:i') }}</td>
                                <td class="border px-4 py-2">{{ $basename }}</td>
                                <td class="border px-4 py-2">{{ $m['cantidad_inicial'] ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ $m['cantidad'] ?? '-' }}</td>
                                <td class="border px-4 py-2">Bs {{ number_format($m['precio_unitario'] ?? 0, 2) }}</td>
                                <td class="border px-4 py-2">Bs {{ number_format($m['monto_total'] ?? 0, 2) }}</td>
                                <td class="border px-4 py-2">{{ $m['sucursal'] }}</td>
                                <td class="border px-4 py-2">{{ $m['personal'] }}</td>
                                <td class="border px-4 py-2">{{ $m['proveedor'] }}</td>

                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
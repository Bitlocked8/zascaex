<div class="p-2 mt-20 flex justify-center bg-gray-100 min-h-screen">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 gap-6">
        
        <!-- Card Asignaciones -->
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-xl font-semibold mb-4">Asignaciones</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 border-b">ID</th>
                            <th class="px-4 py-2 border-b">Código</th>
                            <th class="px-4 py-2 border-b">Cantidad</th>
                               <th class="px-4 py-2 border-b">Cantidad Asginada</th>
                            <th class="px-4 py-2 border-b">Personal</th>
                            <th class="px-4 py-2 border-b">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asignados as $a)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border-b">{{ $a->id }}</td>
                            <td class="px-4 py-2 border-b">{{ $a->codigo }}</td>
                            <td class="px-4 py-2 border-b">{{ $a->cantidad }}</td>
                             <td class="px-4 py-2 border-b">{{ $a->cantidad_original }}</td>
                            <td class="px-4 py-2 border-b">{{ $a->personal_id }}</td>
                            <td class="px-4 py-2 border-b">{{ $a->fecha }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Card Reposiciones -->
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-xl font-semibold mb-4">Reposiciones</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 border-b">ID</th>
                            <th class="px-4 py-2 border-b">Código</th>
                            <th class="px-4 py-2 border-b">Cantidad</th>
                            <th class="px-4 py-2 border-b">Cantidad de entrada</th>
                            <th class="px-4 py-2 border-b">Personal</th>
                            <th class="px-4 py-2 border-b">Proveedor</th>
                            <th class="px-4 py-2 border-b">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reposicions as $r)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 border-b">{{ $r->id }}</td>
                            <td class="px-4 py-2 border-b">{{ $r->codigo }}</td>
                            <td class="px-4 py-2 border-b">{{ $r->cantidad }}</td>
                            <td class="px-4 py-2 border-b">{{ $r->cantidad_inicial }}</td>
                            <td class="px-4 py-2 border-b">{{ $r->personal_id }}</td>
                            <td class="px-4 py-2 border-b">{{ $r->proveedor_id ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border-b">{{ $r->fecha }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

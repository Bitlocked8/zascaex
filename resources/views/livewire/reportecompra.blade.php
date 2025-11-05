<div class="p-4 sm:p-6 mt-16 bg-white rounded-xl shadow-md">

    <h3 class="text-xl font-bold text-cyan-700 mb-4">📦 Reporte de Compras</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">

        <!-- Filtro por Sucursal -->
        <div>
            <label class="font-semibold text-sm mb-1 block">Sucursal</label>
            <div
                class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                <button type="button" wire:click="$set('sucursal_id', '')"
                    class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                    {{ $sucursal_id === '' ? 'border-cyan-600 text-cyan-600 bg-white' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 bg-white' }}">
                    <span class="font-medium">Todas</span>
                </button>
                @foreach($sucursales as $s)
                    <button type="button" wire:click="$set('sucursal_id', {{ $s->id }})"
                        class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                        {{ $sucursal_id == $s->id ? 'border-cyan-600 text-cyan-600 bg-white' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 bg-white' }}">
                        <span class="font-medium">{{ $s->nombre }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Ordenar por cantidad -->
        <div>
            <label class="font-semibold text-sm mb-1 block">Ordenar por Cantidad</label>
            <div
                class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-2 gap-2">
                <button type="button" wire:click="$set('ordenCantidad', 'desc')"
                    class="p-3 rounded-lg border-2 text-center font-medium transition
                    {{ $ordenCantidad === 'desc' ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 hover:border-cyan-600 hover:text-cyan-600' }}">
                    Más Pedidos
                </button>
                <button type="button" wire:click="$set('ordenCantidad', 'asc')"
                    class="p-3 rounded-lg border-2 text-center font-medium transition
                    {{ $ordenCantidad === 'asc' ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 hover:border-cyan-600 hover:text-cyan-600' }}">
                    Menos Pedidos
                </button>
            </div>
        </div>

        <!-- Fecha Inicio -->
        <div>
            <label class="font-semibold text-sm mb-1 block">Fecha Inicio</label>
            <div class="flex gap-1">
                <input type="text" maxlength="2" wire:model.live="inicioDia" class="input-minimal" placeholder="Día"
                    oninput="if(this.value.length==this.maxLength) this.nextElementSibling.focus()">
                <input type="text" maxlength="2" wire:model.live="inicioMes" class="input-minimal" placeholder="Mes"
                    oninput="if(this.value.length==this.maxLength) this.nextElementSibling.focus()">
                <input type="text" maxlength="4" wire:model.live="inicioAnio" class="input-minimal" placeholder="Año">
            </div>
        </div>

        <!-- Fecha Fin -->
        <div>
            <label class="font-semibold text-sm mb-1 block">Fecha Fin</label>
            <div class="flex gap-1">
                <input type="text" maxlength="2" wire:model.live="finDia" class="input-minimal" placeholder="Día"
                    oninput="if(this.value.length==this.maxLength) this.nextElementSibling.focus()">
                <input type="text" maxlength="2" wire:model.live="finMes" class="input-minimal" placeholder="Mes"
                    oninput="if(this.value.length==this.maxLength) this.nextElementSibling.focus()">
                <input type="text" maxlength="4" wire:model.live="finAnio" class="input-minimal" placeholder="Año">
            </div>
        </div>

        <!-- Botón PDF -->
        <div class="flex justify-center mb-4 col-span-full">
            <button wire:click="generarPDF"
                class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg shadow">
                📄 Generar PDF
            </button>
        </div>
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto hidden sm:block">
        <table class="min-w-full border border-gray-300 rounded-lg overflow-hidden">
            <thead class="bg-cyan-700 text-white">
                <tr>
                    <th class="px-3 py-2 text-left">Sucursal</th>
                    <th class="px-3 py-2 text-left">Producto</th>
                    <th class="px-3 py-2 text-right">Cantidad</th>
                    <th class="px-3 py-2 text-right">Precio Unitario (Bs.)</th>
                    <th class="px-3 py-2 text-right">Subtotal (Bs.)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productosPedidos as $producto)
                    <tr class="border-b hover:bg-gray-100">
                        <td class="px-3 py-2">{{ $producto['sucursal'] }}</td>
                        <td class="px-3 py-2">{{ $producto['producto'] }}</td>
                        <td class="px-3 py-2 text-right text-cyan-700 font-semibold">
                            {{ number_format($producto['cantidad'], 2, ',', '.') }}
                        </td>
                        <td class="px-3 py-2 text-right">
                            {{ number_format($producto['precio'], 2, ',', '.') }}
                        </td>
                        <td class="px-3 py-2 text-right font-bold text-emerald-700">
                            {{ number_format($producto['subtotal'], 2, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-3 text-gray-500">No se encontraron compras.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Totales -->
    <div class="mt-4 flex flex-col sm:flex-row justify-between items-center">
        <span class="text-cyan-700 font-semibold">
            Total Cantidad: {{ number_format($totalCantidad, 2, ',', '.') }}
        </span>
        <span class="text-emerald-700 font-bold">
            Total Monto: Bs. {{ number_format($totalMonto, 2, ',', '.') }}
        </span>
    </div>

</div>

<div class="p-text p-2 mt-10 flex justify-center">
  <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
    <div>
      <h6 class="text-xl font-bold mb-4 px-4 p-text">Reporte de Ventas Pendientes de Pago</h6>

      <!-- Search Bar -->
      <div class="flex justify-center items-center gap-4 w-full max-w-2xl mx-auto">
        <input type="text" wire:model.live="search" placeholder="Buscar cliente..." class="input-g w-auto sm:w-64" />
      </div>

      <!-- Table -->
      <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left border border-slate-200 dark:border-cyan-200 rounded-lg border-collapse">
          <thead class="text-x uppercase color-bg">
            <tr>
              <th scope="col" class="px-6 py-3 p-text text-left">Cliente</th>
              <th scope="col" class="px-6 py-3 p-text text-left">Ventas Pendientes</th>
              <th scope="col" class="px-6 py-3 p-text text-right">Total Pendiente (Bs)</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($clientes as $cliente)
            <tr class="color-bg border border-slate-200">
              <td class="px-6 py-4 p-text text-left">
                <div class="font-semibold">{{ $cliente->empresa }}</div>
              </td>
              <td class="px-6 py-4 p-text text-left">
                @foreach ($cliente->ventas as $venta)
                <div class="mb-2">
                  <span class="font-semibold">Venta #{{ $venta->id }}:</span>
                  <span>Fecha: {{ $venta->fechaPedido }}</span>,
                  <span>Fecha Máxima: {{ $venta->fechaMaxima }}</span>,
                  <span>
                    Monto Pendiente: Bs
                    {{ number_format($venta->total - $venta->pagos->sum('monto'), 2, ',', '.') }}
                  </span>
                </div>
                @endforeach
              </td>
              <td class="px-6 py-4 p-text text-right">
                Bs {{ number_format($cliente->totalPendiente, 2, ',', '.') }}
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="3" class="text-left py-4 text-gray-600 dark:text-gray-400">
                No hay ventas pendientes de pago.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-4 flex justify-center">
        {{-- {{ $clientes->links() }} --}}
      </div>
    </div>
  </div>
</div>
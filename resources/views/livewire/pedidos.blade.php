<div class="p-2 mt-20 flex justify-center bg-gray-100">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    @foreach ($ventas as $venta)
    <div class="bg-white shadow rounded-lg p-4 flex flex-col justify-between">

      <!-- Encabezado del card -->
      <div class="flex justify-between items-center mb-2">
        <h3 class="text-lg font-semibold text-cyan-950">Venta #{{ $venta->id }}</h3>
        <span class="text-gray-500">{{ $venta->fechaPedido ?? 'N/A' }}</span>
      </div>

      <!-- Cliente -->
      <p class="text-cyan-950 mb-2"><strong>Cliente:</strong> {{ $venta->cliente->nombre ?? 'N/A' }}</p>

      <!-- Tabla de Productos con Precio y Subtotal -->
      <div class="mb-4">
        <p class="text-cyan-950 font-medium mb-1">Productos:</p>
        <table class="min-w-full border border-gray-200 text-cyan-950">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-2 py-1 border text-left">Producto</th>
              <th class="px-2 py-1 border text-left">Cantidad</th>
              <th class="px-2 py-1 border text-left">Precio</th>
              <th class="px-2 py-1 border text-left">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @php $totalVenta = 0; @endphp
            @foreach($venta->itemventas as $item)
            @php
            $subtotal = ($item->precio ?? 0) * $item->cantidad;
            $totalVenta += $subtotal;
            @endphp
            <tr>
              <td class="px-2 py-1 border">{{ $item->existencia->existenciable->nombre ?? 'Sin nombre' }}</td>
              <td class="px-2 py-1 border">{{ $item->cantidad }}</td>
              <td class="px-2 py-1 border">Bs. {{ number_format($item->precio ?? 0, 2) }}</td>
              <td class="px-2 py-1 border">Bs. {{ number_format($subtotal, 2) }}</td>
            </tr>
            @endforeach
            <tr>
              <td colspan="3" class="px-2 py-1 border font-semibold text-right">Total:</td>
              <td class="px-2 py-1 border font-semibold">Bs. {{ number_format($totalVenta, 2) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagos como bloques individuales -->
      <div class="mt-2">
        <p class="text-cyan-950 font-medium mb-1">Pagos:</p>
        @forelse($venta->pagos as $pago)
        <div class="border rounded p-2 mb-2 bg-gray-50">
          <p class="text-cyan-950"><strong>Tipo:</strong> {{ $pago->tipo }}</p>
          <p class="text-cyan-950"><strong>Monto:</strong> Bs. {{ number_format($pago->monto, 2) }}</p>
          <p class="text-cyan-950"><strong>Fecha:</strong> {{ $pago->fechaPago }}</p>
          <p class="text-cyan-950"><strong>Código:</strong> {{ $pago->codigo ?? 'N/A' }}</p>
          <p class="text-cyan-950"><strong>Observaciones:</strong> {{ $pago->observaciones ?? 'N/A' }}</p>
        </div>
        @empty
        <p class="text-cyan-950">Sin pagos registrados</p>
        @endforelse
      </div>

    </div>
    @endforeach

  </div>
</div>
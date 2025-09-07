<div class="p-text p-2 mt-10 flex justify-center">
  <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
    <div>
      <h6 class="text-xl font-bold mb-4 px-4 p-text">Reporte de Movimiento de Inventario</h6>

      <!-- Date and Sucursal Filters -->
      <div class="flex flex-col sm:flex-row justify-center items-center gap-4 w-full max-w-2xl mx-auto mb-4">
        <input type="date" wire:model.live="fechaInicio" class="input-g w-full sm:w-64" />
        <input type="date" wire:model.live="fechaFinal" class="input-g w-full sm:w-64" />
        <select wire:model.live="sucursalId" class="input-g w-full sm:w-64">
          <option value="">Seleccione una sucursal</option>
          @foreach ($sucursales as $sucursal)
            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
          @endforeach
        </select>
        <button wire:click="generarReporte" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
          Generar Reporte
        </button>
      </div>

      @if ($generarReporte && $sucursalId)
        <!-- Depuración: Mostrar conteo de datos -->
        <div class="mb-4 p-4 bg-gray-100 rounded-lg">
          <p>Elaboraciones: {{ count($elaboraciones) }}</p>
          <p>Embotellados: {{ count($embotellados) }}</p>
          <p>Etiquetados: {{ count($etiquetados) }}</p>
        </div>

        
        <!-- Tabla Proceso de Soplado -->
        @if (!empty($elaboraciones))
          <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
            <h6 class="text-lg font-semibold mb-2">Proceso de Soplado</h6>
            <table class="w-full text-sm text-left border border-slate-200 rounded-lg border-collapse">
              <thead class="text-x uppercase color-bg">
                <tr>
                  <th class="px-6 py-3 p-text">Preforma</th>
                  <th class="px-6 py-3 p-text">Cant. Entrada</th>
                  <th class="px-6 py-3 p-text">Merma</th>
                  <th class="px-6 py-3 p-text">Base (Salida)</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($elaboraciones as $elaboracion)
                  <tr class="color-bg border border-slate-200">
                    <td class="px-6 py-4">{{ $elaboracion['existencia_entrada']['existenciable']['insumo'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                      <span class="@if ($elaboracion['cantidad_entrada'] > ($elaboracion['existencia_entrada']['cantidad_minima'] * 2)) text-green-500
                                   @elseif ($elaboracion['cantidad_entrada'] >= $elaboracion['existencia_entrada']['cantidad_minima'] && $elaboracion['cantidad_entrada'] <= ($elaboracion['existencia_entrada']['cantidad_minima'] * 2)) text-yellow-500
                                   @else text-red-500 @endif">
                        {{ number_format($elaboracion['cantidad_entrada']) . '/' . ($elaboracion['existencia_entrada']['cantidad_minima'] ?? 0) }}
                      </span>
                    </td>
                    <td class="px-6 py-4">{{ number_format($elaboracion['merma']) }}</td>
                    <td class="px-6 py-4">{{ $elaboracion['existencia_salida']['existenciable']['insumo'] ?? 'N/A' }}</td>
                  </tr>
                @endforeach
                @if (empty($elaboraciones))
                  <tr>
                    <td colspan="4" class="text-center py-4 text-gray-600">No hay movimientos de soplado.</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        @else
          <p class="text-red-500">No se encontraron datos para el proceso de soplado.</p>
        @endif

        <!-- Tabla Proceso de Embotellado -->
        @if (!empty($embotellados))
          <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
            <h6 class="text-lg font-semibold mb-2">Proceso de Embotellado</h6>
            <table class="w-full text-sm text-left border border-slate-200 rounded-lg border-collapse">
              <thead class="text-x uppercase color-bg">
                <tr>
                  <th class="px-6 py-3 p-text">Base</th>
                  <th class="px-6 py-3 p-text">Cant. Entrada</th>
                  <th class="px-6 py-3 p-text">Merma</th>
                  <th class="px-6 py-3 p-text">Tapa</th>
                  <th class="px-6 py-3 p-text">Cant.</th>
                  <th class="px-6 py-3 p-text">Merma</th>
                  <th class="px-6 py-3 p-text">Producto (Salida)</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($embotellados as $embotellado)
                  <tr class="color-bg border border-slate-200">
                    <td class="px-6 py-4">{{ $embotellado['existencia_base']['existenciable']['insumo'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                      <span class="@if ($embotellado['cantidad_base_usada'] > ($embotellado['existencia_base']['cantidad_minima'] * 2)) text-green-500
                                   @elseif ($embotellado['cantidad_base_usada'] >= $embotellado['existencia_base']['cantidad_minima'] && $embotellado['cantidad_base_usada'] <= ($embotellado['existencia_base']['cantidad_minima'] * 2)) text-yellow-500
                                   @else text-red-500 @endif">
                        {{ number_format($embotellado['cantidad_base_usada']) . '/' . ($embotellado['existencia_base']['cantidad_minima'] ?? 0) }}
                      </span>
                    </td>
                    <td class="px-6 py-4">{{ number_format($embotellado['merma_base']) }}</td>
                    <td class="px-6 py-4">{{ $embotellado['existencia_tapa']['existenciable']['insumo'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                      <span class="@if ($embotellado['cantidad_tapa_usada'] > ($embotellado['existencia_tapa']['cantidad_minima'] * 2)) text-green-500
                                   @elseif ($embotellado['cantidad_tapa_usada'] >= $embotellado['existencia_tapa']['cantidad_minima'] && $embotellado['cantidad_tapa_usada'] <= ($embotellado['existencia_tapa']['cantidad_minima'] * 2)) text-yellow-500
                                   @else text-red-500 @endif">
                        {{ number_format($embotellado['cantidad_tapa_usada']) . '/' . ($embotellado['existencia_tapa']['cantidad_minima'] ?? 0) }}
                      </span>
                    </td>
                    <td class="px-6 py-4">{{ number_format($embotellado['merma_tapa']) }}</td>
                    <td class="px-6 py-4">{{ $embotellado['existencia_producto']['existenciable']['insumo'] ?? 'N/A' }}</td>
                  </tr>
                @endforeach
                @if (empty($embotellados))
                  <tr>
                    <td colspan="7" class="text-center py-4 text-gray-600">No hay movimientos de embotellado.</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        @else
          <p class="text-red-500">No se encontraron datos para el proceso de embotellado.</p>
        @endif

        <!-- Tabla Proceso de Etiquetado -->
        @if (!empty($etiquetados))
          <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
            <h6 class="text-lg font-semibold mb-2">Proceso de Etiquetado</h6>
            <table class="w-full text-sm text-left border border-slate-200 rounded-lg border-collapse">
              <thead class="text-x uppercase color-bg">
                <tr>
                  <th class="px-6 py-3 p-text">Producto</th>
                  <th class="px-6 py-3 p-text">Cant. Entrada</th>
                  <th class="px-6 py-3 p-text">Merma</th>
                  <th class="px-6 py-3 p-text">Etiqueta</th>
                  <th class="px-6 py-3 p-text">Cant.</th>
                  <th class="px-6 py-3 p-text">Merma</th>
                  <th class="px-6 py-3 p-text">Stock (Salida)</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($etiquetados as $etiquetado)
                  <tr class="color-bg border border-slate-200">
                    <td class="px-6 py-4">{{ $etiquetado['existencia_producto']['existenciable']['insumo'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                      <span class="@if ($etiquetado['cantidad_producto_usado'] > ($etiquetado['existencia_producto']['cantidad_minima'] * 2)) text-green-500
                                   @elseif ($etiquetado['cantidad_producto_usado'] >= $etiquetado['existencia_producto']['cantidad_minima'] && $etiquetado['cantidad_producto_usado'] <= ($etiquetado['existencia_producto']['cantidad_minima'] * 2)) text-yellow-500
                                   @else text-red-500 @endif">
                        {{ number_format($etiquetado['cantidad_producto_usado']) . '/' . ($etiquetado['existencia_producto']['cantidad_minima'] ?? 0) }}
                      </span>
                    </td>
                    <td class="px-6 py-4">{{ number_format($etiquetado['merma_producto']) }}</td>
                    <td class="px-6 py-4">{{ $etiquetado['existencia_etiqueta']['existenciable']['insumo'] ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                      <span class="@if ($etiquetado['cantidad_etiqueta_usada'] > ($etiquetado['existencia_etiqueta']['cantidad_minima'] * 2)) text-green-500
                                   @elseif ($etiquetado['cantidad_etiqueta_usada'] >= $etiquetado['existencia_etiqueta']['cantidad_minima'] && $etiquetado['cantidad_etiqueta_usada'] <= ($etiquetado['existencia_etiqueta']['cantidad_minima'] * 2)) text-yellow-500
                                   @else text-red-500 @endif">
                        {{ number_format($etiquetado['cantidad_etiqueta_usada']) . '/' . ($etiquetado['existencia_etiqueta']['cantidad_minima'] ?? 0) }}
                      </span>
                    </td>
                    <td class="px-6 py-4">{{ number_format($etiquetado['merma_etiqueta']) }}</td>
                    <td class="px-6 py-4">{{ $etiquetado['existencia_stock']['existenciable']['insumo'] ?? 'N/A' }}</td>
                  </tr>
                @endforeach
                @if (empty($etiquetados))
                  <tr>
                    <td colspan="7" class="text-center py-4 text-gray-600">No hay movimientos de etiquetado.</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>
        @else
          <p class="text-red-500">No se encontraron datos para el proceso de etiquetado.</p>
        @endif
      @endif
    </div>
  </div>
</div>
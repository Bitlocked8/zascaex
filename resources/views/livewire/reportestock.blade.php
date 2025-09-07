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

      @if ($mostrarReporte && $sucursalId)
        <!-- Depuración: Mostrar conteo de datos -->
        <div class="mb-4 p-4 bg-gray-100 rounded-lg">
          <p>Elaboraciones: {{ count($elaboraciones) }}</p>
          <p>Embotellados: {{ count($embotellados) }}</p>
          <p>Etiquetados: {{ count($etiquetados) }}</p>
          <p>Preformas: {{ count($preformas) }}</p>
          <p>Bases: {{ count($bases) }}</p>
          <p>Tapas: {{ count($tapas) }}</p>
          <p>Productos: {{ count($productos) }}</p>
          <p>Etiquetas: {{ count($etiquetas) }}</p>
          <p>Stocks: {{ count($stocks) }}</p>
        </div>

        <!-- Tabla Preformas -->
        @if (!empty($preformas))
          <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
            <h6 class="text-lg font-semibold mb-2">Preformas</h6>
            <table class="w-full text-sm text-left border border-slate-200 rounded-lg border-collapse">
              <thead class="text-x uppercase color-bg">
                <tr>
                  <th class="px-6 py-3 p-text">Preforma</th>
                  <th class="px-6 py-3 p-text">Usadas</th>
                  <th class="px-6 py-3 p-text">Mermas</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($preformas as $preforma)
                  <tr class="color-bg border border-slate-200">
                    <td class="px-6 py-4">[{{ $preforma['id'] }}] {{ $preforma['insumo'] }}</td>
                    <td class="px-6 py-4">{{ number_format($preforma['usadas']) }}</td>
                    <td class="px-6 py-4">{{ number_format($preforma['mermas']) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <p class="text-red-500">No se encontraron datos para preformas.</p>
        @endif

        <!-- Tabla Bases -->
        @if (!empty($bases))
          <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
            <h6 class="text-lg font-semibold mb-2">Bases</h6>
            <table class="w-full text-sm text-left border border-slate-200 rounded-lg border-collapse">
              <thead class="text-x uppercase color-bg">
                <tr>
                  <th class="px-6 py-3 p-text">Base</th>
                  <th class="px-6 py-3 p-text">Producidas</th>
                  <th class="px-6 py-3 p-text">Usadas</th>
                  <th class="px-6 py-3 p-text">Mermas</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($bases as $base)
                  <tr class="color-bg border border-slate-200">
                    <td class="px-6 py-4">[{{ $base['id'] }}] {{ $base['insumo'] }}</td>
                    <td class="px-6 py-4">{{ number_format($base['producidas']) }}</td>
                    <td class="px-6 py-4">{{ number_format($base['usadas']) }}</td>
                    <td class="px-6 py-4">{{ number_format($base['mermas']) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <p class="text-red-500">No se encontraron datos para bases.</p>
        @endif

        <!-- Tabla Tapas -->
        @if (!empty($tapas))
          <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
            <h6 class="text-lg font-semibold mb-2">Tapas</h6>
            <table class="w-full text-sm text-left border border-slate-200 rounded-lg border-collapse">
              <thead class="text-x uppercase color-bg">
                <tr>
                  <th class="px-6 py-3 p-text">Tapa</th>
                  <th class="px-6 py-3 p-text">Usadas</th>
                  <th class="px-6 py-3 p-text">Mermas</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($tapas as $tapa)
                  <tr class="color-bg border border-slate-200">
                    <td class="px-6 py-4">[{{ $tapa['id'] }}] {{ $tapa['insumo'] }}</td>
                    <td class="px-6 py-4">{{ number_format($tapa['usadas']) }}</td>
                    <td class="px-6 py-4">{{ number_format($tapa['mermas']) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <p class="text-red-500">No se encontraron datos para tapas.</p>
        @endif

        <!-- Tabla Productos -->
        @if (!empty($productos))
          <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
            <h6 class="text-lg font-semibold mb-2">Productos</h6>
            <table class="w-full text-sm text-left border border-slate-200 rounded-lg border-collapse">
              <thead class="text-x uppercase color-bg">
                <tr>
                  <th class="px-6 py-3 p-text">Producto</th>
                  <th class="px-6 py-3 p-text">Producidos</th>
                  <th class="px-6 py-3 p-text">Usados</th>
                  <th class="px-6 py-3 p-text">Mermas</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($productos as $producto)
                  <tr class="color-bg border border-slate-200">
                    <td class="px-6 py-4">[{{ $producto['id'] }}] {{ $producto['insumo'] }}</td>
                    <td class="px-6 py-4">{{ number_format($producto['producidos']) }}</td>
                    <td class="px-6 py-4">{{ number_format($producto['usados']) }}</td>
                    <td class="px-6 py-4">{{ number_format($producto['mermas']) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <p class="text-red-500">No se encontraron datos para productos.</p>
        @endif

        <!-- Tabla Etiquetas -->
        @if (!empty($etiquetas))
          <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
            <h6 class="text-lg font-semibold mb-2">Etiquetas</h6>
            <table class="w-full text-sm text-left border border-slate-200 rounded-lg border-collapse">
              <thead class="text-x uppercase color-bg">
                <tr>
                  <th class="px-6 py-3 p-text">Etiqueta</th>
                  <th class="px-6 py-3 p-text">Usadas</th>
                  <th class="px-6 py-3 p-text">Mermas</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($etiquetas as $etiqueta)
                  <tr class="color-bg border border-slate-200">
                    <td class="px-6 py-4">[{{ $etiqueta['id'] }}] {{ $etiqueta['insumo'] }}</td>
                    <td class="px-6 py-4">{{ number_format($etiqueta['usadas']) }}</td>
                    <td class="px-6 py-4">{{ number_format($etiqueta['mermas']) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <p class="text-red-500">No se encontraron datos para etiquetas.</p>
        @endif

        <!-- Tabla Stocks -->
        @if (!empty($stocks))
          <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
            <h6 class="text-lg font-semibold mb-2">Stocks</h6>
            <table class="w-full text-sm text-left border border-slate-200 rounded-lg border-collapse">
              <thead class="text-x uppercase color-bg">
                <tr>
                  <th class="px-6 py-3 p-text">Stock</th>
                  <th class="px-6 py-3 p-text">Producidos</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($stocks as $stock)
                  <tr class="color-bg border border-slate-200">
                    <td class="px-6 py-4">[{{ $stock['id'] }}] {{ $stock['insumo'] }}</td>
                    <td class="px-6 py-4">{{ number_format($stock['producidos']) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <p class="text-red-500">No se encontraron datos para stocks.</p>
        @endif

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
                  <th class="px-6 py-3 p-text">Cant. Salida</th>
                  <th class="px-6 py-3 p-text">Base (Salida)</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($elaboraciones as $elaboracion)
                  <tr class="color-bg border border-slate-200">
                    <td class="px-6 py-4">[{{ $elaboracion->existenciaEntrada->existenciable->id }}] {{ $elaboracion->existenciaEntrada->existenciable->insumo }}</td>
                    <td class="px-6 py-4">{{ number_format($elaboracion->cantidad_entrada) }}</td>
                    <td class="px-6 py-4">{{ number_format($elaboracion->merma) }}</td>
                    <td class="px-6 py-4">{{ number_format($elaboracion->cantidad_salida) }}</td>
                    <td class="px-6 py-4">[{{ $elaboracion->existenciaSalida->existenciable->id }}] {{ $elaboracion->existenciaSalida->existenciable->insumo }}</td>
                  </tr>
                @endforeach
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
                  <th class="px-6 py-3 p-text">Cant. Entrada</th>
                  <th class="px-6 py-3 p-text">Merma</th>
                  <th class="px-6 py-3 p-text">Cant. Salida</th>
                  <th class="px-6 py-3 p-text">Producto (Salida)</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($embotellados as $embotellado)
                  <tr class="color-bg border border-slate-200">
                    <td class="px-6 py-4">[{{ $embotellado->existenciaBase->existenciable->id }}] {{ $embotellado->existenciaBase->existenciable->insumo }}</td>
                    <td class="px-6 py-4">{{ number_format($embotellado->cantidad_base_usada) }}</td>
                    <td class="px-6 py-4">{{ number_format($embotellado->merma_base) }}</td>
                    <td class="px-6 py-4">[{{ $embotellado->existenciaTapa->existenciable->id }}] {{ $embotellado->existenciaTapa->existenciable->insumo }}</td>
                    <td class="px-6 py-4">{{ number_format($embotellado->cantidad_tapa_usada) }}</td>
                    <td class="px-6 py-4">{{ number_format($embotellado->merma_tapa) }}</td>
                    <td class="px-6 py-4">{{ number_format($embotellado->cantidad_producida) }}</td>
                    <td class="px-6 py-4">[{{ $embotellado->existenciaProducto->existenciable->id }}] {{ $embotellado->existenciaProducto->existenciable->insumo }}</td>
                  </tr>
                @endforeach
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
                  <th class="px-6 py-3 p-text">Cant. Entrada</th>
                  <th class="px-6 py-3 p-text">Merma</th>
                  <th class="px-6 py-3 p-text">Cant. Salida</th>
                  <th class="px-6 py-3 p-text">Stock (Salida)</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($etiquetados as $etiquetado)
                  <tr class="color-bg border border-slate-200">
                    <td class="px-6 py-4">[{{ $etiquetado->existenciaProducto->existenciable->id }}] {{ $etiquetado->existenciaProducto->existenciable->insumo }}</td>
                    <td class="px-6 py-4">{{ number_format($etiquetado->cantidad_producto_usado) }}</td>
                    <td class="px-6 py-4">{{ number_format($etiquetado->merma_producto) }}</td>
                    <td class="px-6 py-4">[{{ $etiquetado->existenciaEtiqueta->existenciable->id }}] {{ $etiquetado->existenciaEtiqueta->existenciable->insumo }}</td>
                    <td class="px-6 py-4">{{ number_format($etiquetado->cantidad_etiqueta_usada) }}</td>
                    <td class="px-6 py-4">{{ number_format($etiquetado->merma_etiqueta) }}</td>
                    <td class="px-6 py-4">{{ number_format($etiquetado->cantidad_stock) }}</td>
                    <td class="px-6 py-4">[{{ $etiquetado->existenciaStock->existenciable->id }}] {{ $etiquetado->existenciaStock->existenciable->insumo }}</td>
                  </tr>
                @endforeach
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
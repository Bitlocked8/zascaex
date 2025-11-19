<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <h3
      class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto">
      Pedidos
    </h3>

    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input type="text" wire:model.live="search" placeholder="Buscar por código, cliente o personal..."
        class="input-minimal w-full" />
      <button wire:click="$set('modalPedido', true)" class="btn-cyan">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path
            d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
          <path
            d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
        </svg>
        añadir
      </button>
    </div>

    @forelse($pedidos as $pedido)
      @php
        $totalGeneral = 0;
        foreach ($pedido->detalles as $detalle) {
          $producto = $detalle->existencia->existenciable ?? null;
          $precioUnitario = $producto->precioReferencia ?? 0;
          $totalGeneral += $precioUnitario * $detalle->cantidad;
        }
        $pagoPedidos = $pedido->pagoPedidos ?? collect();
        $montoTotalPagado = $pagoPedidos->sum('monto');
        $faltante = $totalGeneral - $montoTotalPagado;
      @endphp

      <div class="card-teal flex flex-col gap-4">

        <div class="flex flex-col gap-2">
          <p class="text-emerald-600 uppercase font-semibold">
            {{ $pedido->solicitudPedido?->cliente?->nombre ?? 'Sin cliente' }}
          </p>

          <p class="text-slate-600">{{ $pedido->codigo }}</p>

          <p><strong>Persona de atencion:</strong> {{ $pedido->personal->nombres ?? 'N/A' }}</p>

          <p class="mt-1 text-sm font-semibold">
            @php
              $colores = [
                0 => 'text-gray-600',
                1 => 'text-blue-500',
                2 => 'text-yellow-500',
                3 => 'text-green-500',
                4 => 'text-teal-600',
              ];
              $labels = [
                0 => 'Pendiente',
                1 => 'Preparando',
                2 => 'Pago Pendiente',
                3 => 'Pagado',
                4 => 'Completado',
              ];
            @endphp
            <span class="{{ $colores[$pedido->estado_pedido] ?? 'text-gray-400' }}">
              {{ $labels[$pedido->estado_pedido] ?? 'Desconocido' }}
            </span>
          </p>



          <p class="mt-1 text-sm font-semibold">
            @if($pedido->adornados->count())
              <span class="inline-block px-2 py-1 rounded-full bg-cyan-600 text-white text-sm font-semibold">
                Etiquetado y Empaquetado
              </span>
            @else
              <span class="inline-block px-2 py-1 rounded-full bg-gray-300 text-gray-700 text-sm font-semibold">
                No Adornado
              </span>
            @endif
          </p>

          <p><strong>Productos añadidos:</strong> {{ $pedido->detalles->count() }}</p>

          <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div
              class="flex justify-between items-center bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 shadow-sm">
              <span class="text-sm font-medium text-gray-700">Total:</span>
              <span class="text-sm font-semibold text-gray-900">{{ number_format($totalGeneral, 2, ',', '.') }} Bs</span>
            </div>
            <div
              class="flex justify-between items-center bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-2 shadow-sm">
              <span class="text-sm font-medium text-emerald-700">Pagado:</span>
              <span class="text-sm font-semibold text-emerald-900">{{ number_format($montoTotalPagado, 2, ',', '.') }}
                Bs</span>
            </div>
            <div class="flex justify-between items-center bg-red-50 border border-red-200 rounded-lg px-4 py-2 shadow-sm">
              <span class="text-sm font-medium text-red-700">Faltante:</span>
              <span class="text-sm font-semibold text-red-900">{{ number_format($faltante, 2, ',', '.') }} Bs</span>
            </div>
          </div>
        </div>

        <div class="flex flex-wrap justify-center md:justify-center gap-2 border-t border-gray-200 pt-3 pb-2">
          <button wire:click="editarPedido({{ $pedido->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0"
            title="Editar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
              <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
              <path d="M16 5l3 3" />
            </svg>
            Editar
          </button>
          <button wire:click="abrirModalPagosPedido({{ $pedido->id }})"
            class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Pagos">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M12 19h-6a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v4.5" />
              <path d="M3 10h18" />
              <path d="M16 19h6" />
              <path d="M19 16l3 3l-3 3" />
              <path d="M7.005 15h.005" />
              <path d="M11 15h2" />
            </svg>
            Pagos
          </button>
          <button wire:click="abrirModalDetallePedido({{ $pedido->id }})" class="btn-cyan" title="Ver detalle">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
              <path d="M8 12l0 .01" />
              <path d="M12 12l0 .01" />
              <path d="M16 12l0 .01" />
            </svg>
            Ver mas
          </button>
          <button wire:click="eliminarPedido({{ $pedido->id }})" class="btn-cyan" title="Eliminar"
            onclick="confirm('¿Estás seguro de eliminar este pedido?') || event.stopImmediatePropagation()">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M4 7h16" />
              <path d="M10 11v6" />
              <path d="M14 11v6" />
              <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
              <path d="M9 7v-3h6v3" />
            </svg>
            Eliminar
          </button>

        </div>
      </div>
    @empty
      <div class="col-span-full text-center py-4 text-gray-600">
        No hay pedidos registrados.
      </div>
    @endforelse
  </div>

  @if($modalPedido)
    <div class="modal-overlay">
      <div class="modal-box">
        <div class="modal-content flex flex-col gap-4">
        <div>
    <label class="text-u">Seleccionar Solicitud de Pedido (Requerido)</label>

    @if($solicitud_pedido_id)

        @php
            $solicitud = $solicitudPedidos->firstWhere('id', $solicitud_pedido_id);
            $detallesSolicitud = $solicitud?->detalles ?? collect();
            $totalPaquetes = $detallesSolicitud->sum('cantidad');
            $totalUnidades = $detallesSolicitud->sum(function ($d) {
                return $d->cantidad * ($d->paquete ?? 1);
            });
        @endphp

        <div class="w-full border border-gray-300 rounded-md p-3 bg-gray-100">
            <p class="font-semibold text-gray-800 mb-1">
                Solicitud asignada:
            </p>

            <p class="font-medium text-gray-900">
                {{ $solicitud->codigo }} — {{ $solicitud->cliente->nombre ?? 'Sin cliente' }}
            </p>

            <p class="text-xs text-gray-500">
                {{ $solicitud->created_at->format('d/m/Y') }}
            </p>

            <div class="mt-3 text-xs space-y-3 text-gray-700">
                @foreach($detallesSolicitud as $detalle)
                    @php
                        $paquetes = $detalle->cantidad;
                        $unidadPaq = $detalle->paquete ?? 1;
                        $total = $paquetes * $unidadPaq;
                    @endphp

                    <div class="border-b pb-2">
                        <div class="flex justify-between mb-1">
                            <span class="font-medium">
                                {{ $detalle->descripcion ?? 'Sin descripción' }}
                            </span>
                            <span class="text-right">
                                <span class="font-semibold block">{{ $paquetes }} paquetes</span>
                                <span class="text-[11px] text-gray-600">{{ $total }} unidades</span>
                            </span>
                        </div>

                        @if($unidadPaq > 1)
                            <p class="text-[10px] bg-gray-200 px-1.5 py-0.5 rounded inline-block mb-1">
                                {{ $unidadPaq }} unidades por paquete
                            </p>
                        @endif

                        <!-- Mostrar Tipo de Contenido -->
                        @if(!empty($detalle->tipo_contenido))
                            <p class="text-[11px] text-gray-600">
                                <strong>Tipo:</strong> {{ $detalle->tipo_contenido }}
                            </p>
                        @endif

                        <!-- Mostrar Tapa con Imagen -->
                        @if(!empty($detalle->tapa_descripcion))
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[11px] text-gray-600">
                                    <strong>Tapa:</strong> {{ $detalle->tapa_descripcion }}
                                </span>
                                @if(!empty($detalle->tapa_imagen))
                                    <img src="{{ asset('storage/' . $detalle->tapa_imagen) }}" 
                                         class="h-12 w-12 object-contain rounded border p-1">
                                @endif
                            </div>
                        @endif

                        <!-- Mostrar Etiquetas con Imágenes -->
                        @if(!empty($detalle->etiqueta_descripcion))
                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                <span class="text-[11px] text-gray-600">
                                    <strong>Etiquetas:</strong>
                                </span>
                                @php
                                    $etiquetas_desc = explode('|', $detalle->etiqueta_descripcion);
                                    $etiquetas_imgs = !empty($detalle->etiqueta_imagen) ? explode('|', $detalle->etiqueta_imagen) : [];
                                @endphp
                                
                                @foreach($etiquetas_desc as $index => $etiqueta_desc)
                                    <div class="flex items-center gap-1">
                                        <span class="text-[10px] bg-blue-100 px-1.5 py-0.5 rounded">
                                            {{ $etiqueta_desc }}
                                        </span>
                                        @if(isset($etiquetas_imgs[$index]) && !empty($etiquetas_imgs[$index]))
                                            <img src="{{ asset('storage/' . $etiquetas_imgs[$index]) }}" 
                                                 class="h-10 w-10 object-contain rounded border p-1">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Precio Unitario y Total -->
                        <div class="mt-1 text-[11px] text-gray-700">
                            <p><strong>Precio unitario:</strong> Bs {{ number_format($detalle->precio_unitario, 2) }}</p>
                            <p><strong>Total:</strong> Bs {{ number_format($detalle->total, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="mt-2 text-xs font-medium text-gray-700">
                Total: {{ $totalPaquetes }} paquetes — {{ $totalUnidades }} unidades
            </p>

            <p class="mt-1 text-xs font-bold text-teal-700">
                Total del pedido: Bs {{ number_format($detallesSolicitud->sum('total'), 2) }}
            </p>

            <button type="button" wire:click="quitarSolicitud"
                class="mt-3 px-3 py-1 bg-red-500 text-white rounded-md text-xs hover:bg-red-600">
                Quitar solicitud
            </button>
        </div>

    @else

        <div class="w-full border border-gray-300 rounded-md p-2 bg-white max-h-[260px] overflow-y-auto">

            @forelse($solicitudPedidos as $solicitud)

                @php
                    $detallesSolicitud = $solicitud->detalles;
                    $totalPaquetes = $detallesSolicitud->sum('cantidad');
                    $totalUnidades = $detallesSolicitud->sum(function ($d) {
                        return $d->cantidad * ($d->paquete ?? 1);
                    });
                @endphp

                <button type="button" wire:click="seleccionarSolicitud({{ $solicitud->id }})"
                    class="w-full text-left p-3 mb-2 rounded-lg border-2 transition bg-white {{ $solicitudSeleccionadaId == $solicitud->id ? 'border-cyan-600' : 'border-gray-300 hover:border-cyan-600' }}">

                    <div class="flex justify-between items-center mb-2">
                        <span class="font-semibold text-gray-900">
                            {{ $solicitud->codigo }} — {{ $solicitud->cliente->nombre ?? 'Sin cliente' }}
                        </span>
                    </div>

                    <div class="text-xs space-y-2 text-gray-700">
                        @foreach($detallesSolicitud as $detalle)
                            @php
                                $paquetes = $detalle->cantidad;
                                $unidadPaq = $detalle->paquete ?? 1;
                                $total = $paquetes * $unidadPaq;
                            @endphp

                            <div class="border-b pb-2">
                                <div class="flex justify-between">
                                    <div class="flex-1">
                                        <span class="font-medium text-gray-900">
                                            {{ $detalle->descripcion ?? 'Sin descripción' }}
                                        </span>

                                        @if($unidadPaq > 1)
                                            <span class="ml-1 text-[10px] bg-gray-200 px-1.5 py-0.5 rounded">
                                                {{ $unidadPaq }} u/pq
                                            </span>
                                        @endif

                                        <!-- Info rápida de tapa y etiquetas -->
                                        @if(!empty($detalle->tapa_descripcion) || !empty($detalle->etiqueta_descripcion))
                                            <div class="mt-1 flex flex-wrap gap-1">
                                                @if(!empty($detalle->tapa_descripcion))
                                                    <span class="text-[9px] bg-green-100 px-1 py-0.5 rounded">
                                                        Tapa: {{ $detalle->tapa_descripcion }}
                                                    </span>
                                                @endif
                                                @if(!empty($detalle->etiqueta_descripcion))
                                                    @php
                                                        $etiquetas = explode('|', $detalle->etiqueta_descripcion);
                                                    @endphp
                                                    @foreach(array_slice($etiquetas, 0, 2) as $etiqueta)
                                                        <span class="text-[9px] bg-blue-100 px-1 py-0.5 rounded">
                                                            {{ $etiqueta }}
                                                        </span>
                                                    @endforeach
                                                    @if(count($etiquetas) > 2)
                                                        <span class="text-[9px] bg-blue-100 px-1 py-0.5 rounded">
                                                            +{{ count($etiquetas) - 2 }} más
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <div class="text-right ml-2">
                                        <span class="font-semibold block">{{ $paquetes }} paquetes</span>
                                        <span class="text-[11px] text-gray-600">{{ $total }} unidades</span>
                                        <span class="text-[10px] text-green-600 font-medium">
                                            Bs {{ number_format($detalle->total, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-2 text-xs flex justify-between items-center">
                        <span class="font-medium text-gray-800">
                            Total: {{ $totalPaquetes }} paquetes — {{ $totalUnidades }} unidades
                        </span>
                        <div class="text-right">
                            <span class="text-gray-500 block">
                                {{ $solicitud->created_at->format('d/m/Y') }}
                            </span>
                            <span class="font-bold text-teal-700">
                                Bs {{ number_format($detallesSolicitud->sum('total'), 2) }}
                            </span>
                        </div>
                    </div>

                </button>

            @empty
                <div class="text-center py-4 text-gray-600">No hay solicitudes registradas.</div>
            @endforelse

        </div>

    @endif
</div>




          <div class="mb-6">
            <div class="flex flex-wrap gap-3">
              @foreach($sucursales as $sucursal)
                <button type="button" wire:click="$set('sucursal_id', {{ $sucursal->id }})"
                  class="flex-1 sm:flex-auto px-4 py-2 rounded-lg text-sm font-medium transition {{ $sucursal_id == $sucursal->id ? 'bg-cyan-500 text-white shadow-lg border border-cyan-500' : 'bg-gray-100 text-gray-700 border border-gray-300 hover:bg-cyan-50 hover:text-cyan-600 hover:border-cyan-400' }}">
                  {{ $sucursal->nombre }}
                </button>
              @endforeach
            </div>
          </div>
          <div class="text-center mb-6">
            <div class="flex flex-col sm:flex-row justify-center flex-wrap gap-3">
              <button type="button" wire:click="$set('tipoProducto', 'producto')"
                class="flex-1 sm:flex-auto px-4 py-3 rounded-lg text-sm font-medium transition {{ $tipoProducto == 'producto' ? 'bg-teal-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-teal-50 hover:text-teal-600' }}">
                Productos
              </button>
              <button type="button" wire:click="$set('tipoProducto', 'otro')"
                class="flex-1 sm:flex-auto px-4 py-3 rounded-lg text-sm font-medium transition {{ $tipoProducto == 'otro' ? 'bg-emerald-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600' }}">
                Otros Ítems
              </button>
            </div>
          </div>
          <div class="grid grid-cols-1 gap-4 mb-6">
            <div
              class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[500px]">
              @if($tipoProducto === 'producto')
                @if($productos->count())
                  @foreach($productos as $producto)
                    @php
                      $cantidadesPorSucursal = [];
                      foreach ($producto->existencias as $existencia) {
                        $totalExistencia = $existencia->reposiciones->sum('cantidad');
                        if ($totalExistencia > 0) {
                          $cantidadesPorSucursal[$existencia->sucursal->nombre ?? 'Sin sucursal'] =
                            ($cantidadesPorSucursal[$existencia->sucursal->nombre ?? 'Sin sucursal'] ?? 0) + $totalExistencia;
                        }
                      }
                    @endphp
                    <button type="button" wire:click="$set('productoSeleccionado', {{ $producto->id }})"
                      class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center {{ $productoSeleccionado == $producto->id ? 'border-cyan-600 text-cyan-600 bg-cyan-50' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}">
                      <span class="font-semibold">{{ $producto->descripcion ?? 'Producto #' . $producto->id }}</span>
                      <div class="flex flex-wrap justify-center gap-3 mt-2">
                        @foreach($cantidadesPorSucursal as $sucursal => $cantidad)
                          <span class="text-xs bg-teal-600 text-white px-2 py-0.5 rounded-full font-semibold">{{ $sucursal }}:
                            {{ $cantidad }}</span>
                        @endforeach
                      </div>
                      <span class="text-sm mt-1">{{ $producto->precioReferencia ?? 'sin precio' }} BS</span>
                    </button>
                  @endforeach
                @else
                  <div class="text-center text-gray-500 py-4">
                    No hay productos disponibles.
                  </div>
                @endif

              @else
                @if($otros->count())
                  @foreach($otros as $otro)
                    @php
                      $cantidadesPorSucursal = [];
                      foreach ($otro->existencias as $existencia) {
                        $totalExistencia = $existencia->reposiciones->sum('cantidad');
                        if ($totalExistencia > 0) {
                          $cantidadesPorSucursal[$existencia->sucursal->nombre ?? 'Sin sucursal'] =
                            ($cantidadesPorSucursal[$existencia->sucursal->nombre ?? 'Sin sucursal'] ?? 0) + $totalExistencia;
                        }
                      }
                    @endphp
                    <button type="button" wire:click="$set('otroSeleccionado', {{ $otro->id }})"
                      class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center {{ $otroSeleccionado == $otro->id ? 'border-green-600 text-green-600 bg-green-50' : 'border-gray-300 text-gray-800 hover:border-green-600 hover:text-green-600' }}">
                      <span class="font-semibold">{{ $otro->descripcion ?? 'Item #' . $otro->id }}</span>
                      <div class="flex flex-wrap justify-center gap-2 mt-1">
                        @foreach($cantidadesPorSucursal as $sucursal => $cantidad)
                          <span class="text-xs bg-teal-600 text-white px-2 py-0.5 rounded-full font-semibold">{{ $sucursal }}:
                            {{ $cantidad }}</span>
                        @endforeach
                      </div>
                      <span class="text-sm mt-1">{{ $otro->precioReferencia ?? 'sin precio' }} BS</span>
                    </button>
                  @endforeach
                @else
                  <div class="text-center text-gray-500 py-4">
                    No hay otros ítems disponibles.
                  </div>
                @endif

              @endif
            </div>
          </div>
          <div class="flex flex-col md:flex-row items-center justify-center gap-4 mb-6 w-full">
            <input type="number" wire:model="cantidadSeleccionada" class="input-minimal text-center" min="1"
              placeholder="Cantidad" />
            <button wire:click="agregarProducto" class="btn-cyan">Añadir Item</button>
          </div>
          <div class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mt-3">
              @foreach($detalles as $index => $detalle)
                @if(!isset($detalle['eliminar']))
                  <div
                    class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center bg-white hover:border-cyan-600 hover:text-cyan-600 border-gray-300 shadow-sm {{ $detalle['tipo'] === 'producto' ? 'border-l-4 border-l-cyan-500' : 'border-l-4 border-l-green-500' }}">
                    <span
                      class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $detalle['tipo'] === 'producto' ? 'bg-cyan-100 text-cyan-800' : 'bg-green-100 text-green-800' }}">{{ ucfirst($detalle['tipo']) }}</span>
                    <span class="font-semibold mt-4">{{ $detalle['nombre'] }}</span>
                    <span
                      class="mt-2 bg-teal-600 text-white text-xs px-3 py-1 rounded-full font-semibold">{{ fmod($detalle['cantidad'], 1) == 0 ? intval($detalle['cantidad']) : number_format($detalle['cantidad'], 2) }}
                      Unidad(es)</span>
                    @if(!empty($detalle['sucursal_nombre']))
                      <span class="text-xs text-gray-500 mt-1">Sucursal: {{ $detalle['sucursal_nombre'] }}</span>
                    @endif
                    <button wire:click="eliminarDetalle({{ $index }})" class="btn-cyan mt-2">Eliminar</button>
                  </div>
                @endif
              @endforeach
            </div>
            @if(count($detalles) === 0)
              <p class="text-gray-500 text-sm italic mt-2">No hay items agregados.</p>
            @endif

            <div class="mb-4">
              <label class=" font-semibold">Observaciones (Opcional)</label>
              <textarea wire:model="observaciones" class="w-full border border-gray-300 rounded-md p-2 bg-white" rows="3"
                placeholder="Escribe observaciones del pedido..."></textarea>
            </div>
            <div class="mb-4">
              <label class="font-semibold">Estado del Pedido (Automático)</label>

              <div class="flex flex-wrap gap-2 mt-2">

                <button type="button" wire:click="$set('estado_pedido', 0)"
                  class="px-3 py-1.5 rounded-md border
                    {{ $estado_pedido == 0 ? 'bg-gray-600 text-white border-gray-700' : 'bg-white text-gray-700 border-gray-300' }}">
                  Pendiente
                </button>
                <button type="button" wire:click="$set('estado_pedido', 1)"
                  class="px-3 py-1.5 rounded-md border
                    {{ $estado_pedido == 1 ? 'bg-blue-500 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300' }}">
                  Preparando
                </button>
                <button type="button" wire:click="$set('estado_pedido', 2)"
                  class="px-3 py-1.5 rounded-md border
                    {{ $estado_pedido == 2 ? 'bg-yellow-500 text-white border-yellow-600' : 'bg-white text-gray-700 border-gray-300' }}">
                  Pago Pendiente
                </button>
                <button type="button" wire:click="$set('estado_pedido', 3)"
                  class="px-3 py-1.5 rounded-md border
                    {{ $estado_pedido == 3 ? 'bg-green-500 text-white border-green-600' : 'bg-white text-gray-700 border-gray-300' }}">
                  Pagado
                </button>
                <button type="button" wire:click="$set('estado_pedido', 4)"
                  class="px-3 py-1.5 rounded-md border
                    {{ $estado_pedido == 4 ? 'bg-teal-600 text-white border-teal-700' : 'bg-white text-gray-700 border-gray-300' }}">
                  Completado
                </button>
              </div>
            </div>



          </div>
          <div class="modal-footer">
            <button wire:click="cerrarModal" class="btn-cyan">Cerrar</button>
            <button wire:click="guardarPedido" class="btn-cyan">Guardar Pedido</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  @if($modalPagos)
    <div class="modal-overlay">
      <div class="modal-box">
        <div class="modal-content flex flex-col gap-4">
          <div class="space-y-4">
            @foreach($pagos as $index => $pago)
              <div class="border p-4 rounded flex flex-col gap-2">
                <div class="flex justify-between items-center">
                  <strong>Código: {{ $pago['codigo'] }}</strong>
                  <p class="text-sm text-gray-600">
                    Fecha: {{ $pago['fecha_pago'] }}
                  </p>
                  <button type="button" wire:click="eliminarPagoPedido({{ $index }})" class="btn-circle btn-cyan"
                    title="Eliminar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M4 7l16 0" />
                      <path d="M10 11l0 6" />
                      <path d="M14 11l0 6" />
                      <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                      <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg>
                  </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                  <div class="sm:col-span-2">
                    <label class="font-semibold text-u">Monto (Requerido)</label>
                    <input type="number" wire:model="pagos.{{ $index }}.monto" class="input-minimal" min="0">
                  </div>

                  <div class="sm:col-span-2">
                    <label class="font-semibold text-sm">Método (Opcional)</label>
                    <input type="text" wire:model="pagos.{{ $index }}.metodo" class="input-minimal">
                  </div>

                  <div class="sm:col-span-2">
                    <label class="font-semibold text-sm">Referencia (Opcional)</label>
                    <input type="text" wire:model="pagos.{{ $index }}.referencia" class="input-minimal">
                  </div>

                  <div class="sm:col-span-2">
                    <label class="font-semibold text-sm">Observaciones (Opcional)</label>
                    <input type="text" wire:model="pagos.{{ $index }}.observaciones" class="input-minimal">
                  </div>
                  <div class="sm:col-span-2">
                    <label class="font-semibold text-sm">Estado del Pago</label>
                    <div class="flex gap-2 mt-1">
                      <button type="button" wire:click="$set('pagos.{{ $index }}.estado', 1)"
                        class="btn-cyan {{ $pagos[$index]['estado'] === 1 ? 'bg-blue-700' : '' }}">
                        QR
                      </button>
                      <button type="button" wire:click="$set('pagos.{{ $index }}.estado', 2)"
                        class="btn-cyan {{ $pagos[$index]['estado'] === 2 ? 'bg-blue-700' : '' }}">
                        Efectivo
                      </button>
                      <button type="button" wire:click="$set('pagos.{{ $index }}.estado', 3)"
                        class="btn-cyan {{ $pagos[$index]['estado'] === 3 ? 'bg-blue-700' : '' }}">
                        Crédito
                      </button>
                    </div>
                  </div>


                  <div class="sm:col-span-2">
                    <label class="font-semibold text-sm">Imagen (Opcional)</label>
                    <input type="file" wire:model="pagos.{{ $index }}.imagen_comprobante" class="input-minimal">

                    @php
                      $imagenUrl = null;
                      if (isset($pagos[$index]['imagen_comprobante'])) {
                        $imagenUrl = is_string($pagos[$index]['imagen_comprobante'])
                          ? Storage::url($pagos[$index]['imagen_comprobante'])
                          : $pagos[$index]['imagen_comprobante']->temporaryUrl();
                      }
                    @endphp

                    @if($imagenUrl)
                      <div class="mt-2 flex flex-col items-center space-y-2">
                        <img src="{{ $imagenUrl }}" alt="Imagen"
                          class="w-80 h-80 object-cover rounded-lg shadow cursor-pointer"
                          wire:click="$set('imagenPreviewModal', '{{ $imagenUrl }}'); $set('modalImagenAbierta', true)">
                        @if(is_string($pagos[$index]['imagen_comprobante']))
                          <a href="{{ $imagenUrl }}" download class="btn-circle btn-cyan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                              <path d="M7 11l5 5l5 -5" />
                              <path d="M12 4l0 12" />
                            </svg>
                          </a>
                        @endif
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <div class="modal-footer">
            <button type="button" wire:click="agregarPagoPedido" class="btn-cyan">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                  d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                <path
                  d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
              </svg>
              añadir pago
            </button>

            <button type="button" wire:click="guardarPagosPedido" class="btn-cyan">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M14 4l0 4l-6 0l0 -4" />
              </svg>
              guardar pago
            </button>
            <button type="button" wire:click="$set('modalPagos', false)" class="btn-cyan" title="Cerrar">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
              </svg>
              CERRAR
            </button>
          </div>

        </div>
      </div>
    </div>
  @endif

  @if($modalDetallePedido && $pedidoDetalle)
    <div class="modal-overlay">
      <div class="modal-box max-w-3xl">
        <div class="modal-content flex flex-col gap-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div class="flex flex-col gap-3">
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Código:</span>
                <span class="badge-info">{{ $pedidoDetalle->codigo }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Empresa:</span>
                <span class="badge-info">{{ $pedidoDetalle->cliente->empresa ?? '-' }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Cliente:</span>
                <span class="badge-info">{{ $pedidoDetalle->cliente->nombre ?? '-' }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Direccion:</span>
                <span class="badge-info">{{ $pedidoDetalle->cliente->direccion ?? '-' }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Departamento:</span>
                <span class="badge-info">{{ $pedidoDetalle->cliente->departamento_localidad ?? '-' }}</span>
              </div>

              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Personal de atencion:</span>
                <span class="badge-info">{{ $pedidoDetalle->personal->nombres ?? '-' }}</span>
              </div>
            </div>

            <div class="flex flex-col gap-3">
              <span class="badge-info">
                {{ \Carbon\Carbon::parse($pedidoDetalle->fecha_pedido)->format('d M Y, H:i') }}
              </span>

              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Estado:</span>
                <span
                  class="inline-block px-2 py-1 rounded-full text-sm font-semibold  {{ $pedidoDetalle->estado_pedido == 0 ? 'bg-cyan-600 text-white' : ($pedidoDetalle->estado_pedido == 1 ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white') }}">
                  {{ $pedidoDetalle->estado_pedido == 0 ? 'Pendiente' : ($pedidoDetalle->estado_pedido == 1 ? 'Entregado' : 'Cancelado') }}
                </span>
              </div>

            </div>
          </div>
          <hr class="my-2">
          <h3 class="font-semibold text-lg">Productos Seleccionados:</h3>

          @php
            $totalGeneral = 0;
          @endphp

          <div class="divide-y divide-gray-200">
            @foreach($pedidoDetalle->detalles as $detalle)
              @php
                $producto = $detalle->existencia->existenciable ?? null;
                $sucursal = $detalle->existencia->sucursal ?? null;
                $precioUnitario = $producto->precioReferencia ?? 0;
                $totalProducto = $precioUnitario * $detalle->cantidad;
                $totalGeneral += $totalProducto;
              @endphp
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center py-2">
                <div class="flex flex-col gap-1">
                  <span class="font-medium">{{ $producto->descripcion ?? 'Sin nombre' }}</span>
                  <span class="font-medium">Precio unitario: {{ number_format($precioUnitario, 2) }} BS</span>
                  <span class="font-medium">Total: {{ number_format($totalProducto, 2) }} BS</span>
                  <span class="text-sm text-gray-500">Sucursal: {{ $sucursal->nombre ?? 'N/A' }}</span>
                  @if(isset($producto->imagen))
                    <img src="{{ asset('storage/' . $producto->imagen) }}" class="w-12 h-12 object-cover rounded shadow"
                      alt="Imagen Producto">
                  @endif
                </div>
                <div class="mt-2 md:mt-0">
                  <span class="badge-info">Cantidad: {{ $detalle->cantidad }}</span>
                </div>
              </div>
            @endforeach
          </div>
          <div class="mt-4 flex justify-end gap-2">
            <span class="font-semibold text-lg">Precio a pagar:</span>
            <span class="font-semibold text-lg">{{ number_format($totalGeneral) }} BS</span>
          </div>

          @php
            $pagoPedidos = $pedidoDetalle->pagoPedidos ?? collect();
            $montoTotalPagado = $pagoPedidos->sum('monto');
            $faltante = $totalGeneral - $montoTotalPagado;
          @endphp

          @if($pagoPedidos->count())
            <div class="mt-6 border-t pt-4">
              <h3 class="font-semibold text-lg mb-2">Pagos Registrados</h3>

              @foreach($pagoPedidos as $pago)
                <div class="border border-gray-200 rounded-lg p-3 mb-3 bg-gray-50">
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                    <div><strong>Código:</strong> {{ $pago->codigo ?? 'N/A' }}</div>
                    <div><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') }}</div>
                    <div><strong>Monto:</strong> {{ number_format($pago->monto, 2) }} BS</div>
                    <div><strong>Método:</strong> {{ ucfirst($pago->metodo ?? '-') }}</div>
                    <div><strong>Referencia:</strong> {{ $pago->referencia ?? '-' }}</div>
                    <div><strong>Observaciones:</strong> {{ $pago->observaciones ?? '-' }}</div>
                  </div>

                  @if($pago->imagen_comprobante)
                    <div class="mt-2">
                      <strong>Comprobante:</strong><br>
                      <img src="{{ asset('storage/' . $pago->imagen_comprobante) }}"
                        class="w-32 h-32 object-cover rounded-lg border shadow">
                    </div>
                  @endif
                </div>
              @endforeach

              <div class="mt-4 border-t pt-3">
                <div class="flex justify-between font-semibold text-lg">
                  <span>Total Pagado:</span>
                  <span>{{ number_format($montoTotalPagado, 2) }} BS</span>
                </div>
                <div class="flex justify-between font-semibold text-lg text-red-600">
                  <span>Faltante:</span>
                  <span>{{ number_format($faltante, 2) }} BS</span>
                </div>
              </div>
            </div>
          @endif


          <div>
            <span class="label-info block mb-1">Observaciones:</span>
            <div class="bg-gray-100 rounded p-2 text-sm text-gray-700">
              {{ $pedidoDetalle->observaciones ?? 'Sin observaciones' }}
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button wire:click="$set('modalDetallePedido', false)" class="btn-cyan" title="Cerrar">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
              <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
            </svg>
            CERRAR
          </button>
        </div>

      </div>
    </div>
  @endif

</div>
</div>
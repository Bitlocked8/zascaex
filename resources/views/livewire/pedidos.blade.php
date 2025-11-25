<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <h3 class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto">
      Pedidos
    </h3>

    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input type="text" wire:model.live="search" placeholder="Buscar por código o cliente..." class="input-minimal w-full" />
      <button wire:click="$set('modalPedido', true)" class="btn-cyan">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
          <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1"/>
          <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605"/>
        </svg>
        añadir
      </button>
    </div>

    @forelse($pedidos as $pedido)
      @php
        // Total del pedido (solo sumando productos)
        $totalGeneral = 0;
        foreach ($pedido->detalles as $detalle) {
          $producto = $detalle->existencia->existenciable ?? null;
          $precioUnitario = $producto->precioReferencia ?? 0;
          $totalGeneral += $precioUnitario * $detalle->cantidad;
        }

        $pagoPedidos = $pedido->pagoPedidos ?? collect();

        // Créditos (solo referencia)
        $creditos = $pagoPedidos->where('metodo', 2);
        $totalCredito = $creditos->sum('monto');

        // Total de pagos confirmados (no crédito)
        $totalPagado = $pagoPedidos->where('metodo', '<>', 2)->where('estado', 1)->sum('monto');

        // Saldo pendiente
        $saldoPendiente = max($totalCredito - $totalPagado, 0);
        if($totalCredito == 0){
            // si no hay crédito, se calcula como total general - pagos confirmados
            $saldoPendiente = max($totalGeneral - $totalPagado, 0);
        }
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
              $colores = [0 => 'text-blue-500', 1 => 'text-yellow-500', 2 => 'text-green-600'];
              $labels  = [0 => 'Preparando', 1 => 'En Revisión', 2 => 'Completado'];
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

          <div class="mt-3 p-3 bg-gray-100 rounded-lg text-sm space-y-1 border">
            <p><strong>Créditos registrados:</strong>
              @if($creditos->count())
                <ul class="flex flex-wrap gap-2 mt-1">
                  @foreach($creditos as $credito)
                    <li class="px-2 py-1 rounded-full text-xs bg-blue-600 text-white">
                      Bs {{ number_format($credito->monto, 2) }}
                    </li>
                  @endforeach
                </ul>
              @else
                Bs 0.00
              @endif
            </p>

            <p><strong>Total Pagado:</strong>
              <span class="text-green-700">Bs {{ number_format($totalPagado, 2) }}</span>
            </p>

            <p><strong>Saldo Pendiente:</strong>
              <span class="{{ $saldoPendiente > 0 ? 'text-red-600 font-bold' : 'text-emerald-600 font-bold' }}">
                Bs {{ number_format($saldoPendiente, 2) }}
              </span>
            </p>
          </div>

        </div>

        <div class="flex flex-wrap justify-center md:justify-center gap-2 border-t border-gray-200 pt-3 pb-2">
          <button wire:click="editarPedido({{ $pedido->id }})" class="btn-cyan">Editar</button>
          <button wire:click="abrirModalPagosPedido({{ $pedido->id }})" class="btn-cyan">Pagos</button>
          <button wire:click="abrirModalDetallePedido({{ $pedido->id }})" class="btn-cyan">Ver mas</button>
          <button wire:click="eliminarPedido({{ $pedido->id }})" class="btn-cyan"
            onclick="confirm('¿Estás seguro de eliminar este pedido?') || event.stopImmediatePropagation()">
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




  @if($modalPagos)
    <div class="modal-overlay">
      <div class="modal-box">
        <div class="modal-content flex flex-col gap-4">
          <div class="space-y-4">

            @foreach($pagos as $index => $pago)
              <div class="border p-4 rounded flex flex-col gap-2">


                <div class="flex justify-between items-center">
                  <strong>Código: {{ $pago['codigo_pago'] }}</strong>
                  <p class="text-sm text-gray-600">
                    Fecha: {{ $pago['fecha_pago'] }}
                  </p>

                  <button type="button" wire:click="eliminarPagoPedido({{ $index }})" class="btn-circle btn-cyan"
                    title="Eliminar">
                    🗑
                  </button>
                </div>


                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                  <div class="sm:col-span-2">
                    <label class="font-semibold text-u">Monto (Requerido)</label>
                    <input type="number" wire:model="pagos.{{ $index }}.monto" class="input-minimal" min="0">
                  </div>
                  <div class="sm:col-span-2">
                    <label class="font-semibold text-sm">Método de Pago</label>

                    <div class="flex gap-2 mt-1">
                      <button type="button" wire:click="$set('pagos.{{ $index }}.metodo', 0)"
                        class="btn-cyan {{ $pagos[$index]['metodo'] === 0 ? 'bg-blue-700 text-white' : '' }}">
                        QR
                      </button>

                      <button type="button" wire:click="$set('pagos.{{ $index }}.metodo', 1)"
                        class="btn-cyan {{ $pagos[$index]['metodo'] === 1 ? 'bg-blue-700 text-white' : '' }}">
                        Efectivo
                      </button>

                      <button type="button" wire:click="$set('pagos.{{ $index }}.metodo', 2)"
                        class="btn-cyan {{ $pagos[$index]['metodo'] === 2 ? 'bg-blue-700 text-white' : '' }}">
                        Crédito
                      </button>
                    </div>
                  </div>

                  <div class="sm:col-span-2">
                    <label class="font-semibold text-sm">Pago Confirmado</label>

                    <button type="button" wire:click="$set('pagos.{{ $index }}.estado', {{ $pago['estado'] ? 0 : 1 }})"
                      class="px-4 py-1 rounded-md border text-white
                                                        {{ $pagos[$index]['estado'] ? 'bg-green-600 border-green-700' : 'bg-gray-600 border-gray-700' }}">
                      {{ $pagos[$index]['estado'] ? '✔ Pagado' : '✖ No pagado' }}
                    </button>
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
                    <label class="text-u">Método QR auxiliar (Sucursal)</label>

                    <div
                      class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-[180px] overflow-y-auto">


                      <button type="button" wire:click="$set('pagos.{{ $index }}.sucursal_pago_id', null)"
                        class="w-full p-4 rounded-lg border-2 text-center
                                                          {{ empty($pagos[$index]['sucursal_pago_id']) ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800' }}">
                        <span class="font-medium">-- Ninguno --</span>
                      </button>


                      @foreach($sucursalesPago as $sp)
                        <button type="button" wire:click="$set('pagos.{{ $index }}.sucursal_pago_id', {{ $sp->id }})"
                          class="w-full p-4 rounded-lg border-2 text-center
                                                                                {{ $pagos[$index]['sucursal_pago_id'] == $sp->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800' }}">
                          <span class="font-medium">{{ $sp->nombre }}</span>

                          @if($sp->tipo)
                            <span class="bg-gray-600 text-white text-xs px-2 py-0.5 rounded-full mt-1">
                              {{ $sp->tipo }}
                            </span>
                          @endif

                          @if($sp->sucursal)
                            <span class="text-gray-500 text-xs mt-1">(Sucursal: {{ $sp->sucursal->nombre }})</span>
                          @endif
                        </button>
                      @endforeach
                    </div>

                    @if(isset($pagos[$index]['sucursal_pago_id']) && $pagos[$index]['sucursal_pago_id'])
                      @php $metodo = $sucursalesPago->firstWhere('id', $pagos[$index]['sucursal_pago_id']); @endphp

                      @if($metodo && $metodo->imagen_qr)
                        <div class="mt-3 flex flex-col items-center space-y-2">
                          <img src="{{ Storage::url($metodo->imagen_qr) }}"
                            class="w-48 h-48 object-cover rounded shadow cursor-pointer"
                            wire:click="$set('imagenPreviewModal', '{{ Storage::url($metodo->imagen_qr) }}'); $set('modalImagenAbierta', true)">
                          <p class="text-sm text-gray-600 text-center">
                            {{ $metodo->nombre }} — {{ $metodo->tipo }}
                          </p>
                        </div>
                      @endif
                    @endif
                  </div>


                  <div class="sm:col-span-2">
                    <label class="font-semibold text-sm">Imagen del comprobante</label>
                    <input type="file" wire:model="pagos.{{ $index }}.imagen_comprobante" class="input-minimal">

                    @php
                      $imagenUrl = isset($pagos[$index]['imagen_comprobante'])
                        ? (is_string($pagos[$index]['imagen_comprobante'])
                          ? Storage::url($pagos[$index]['imagen_comprobante'])
                          : $pagos[$index]['imagen_comprobante']->temporaryUrl())
                        : null;
                    @endphp

                    @if($imagenUrl)
                      <div class="mt-2 flex flex-col items-center space-y-2">
                        <img src="{{ $imagenUrl }}" class="w-80 h-80 object-cover rounded-lg shadow cursor-pointer"
                          wire:click="$set('imagenPreviewModal', '{{ $imagenUrl }}'); $set('modalImagenAbierta', true)">
                        @if(is_string($pagos[$index]['imagen_comprobante']))
                          <a href="{{ $imagenUrl }}" download class="btn-circle btn-cyan">⬇</a>
                        @endif
                      </div>
                    @endif

                  </div>

                </div>
              </div>
            @endforeach
          </div>


          <div class="modal-footer">

            <button type="button" wire:click="agregarPagoPedido" class="btn-cyan">➕ Añadir pago</button>

            <button type="button" wire:click="guardarPagosPedido" class="btn-cyan">💾 Guardar</button>

            <button type="button" wire:click="$set('modalPagos', false)" class="btn-cyan">❌ Cerrar</button>

          </div>

        </div>
      </div>
    </div>
  @endif

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
                $totalUnidades = $detallesSolicitud->sum(fn($d) => $d->cantidad * ($d->producto->paquete ?? 1));
                $totalPedido = $detallesSolicitud->sum(function ($d) {
                  $precioUnitario = $d->producto->precioReferencia ?? 0;
                  $unidades = $d->cantidad * ($d->producto->paquete ?? 1);
                  return $precioUnitario * $unidades;
                });
              @endphp

              <div class="w-full border border-gray-300 rounded-md p-3 bg-gray-100">
                <p class="font-semibold text-gray-800 mb-1">Solicitud asignada:</p>
                <p class="font-medium text-gray-900">{{ $solicitud->codigo }} —
                  {{ $solicitud->cliente->nombre ?? 'Sin cliente' }}
                </p>
                <p class="text-xs text-gray-500">{{ $solicitud->created_at->format('d/m/Y') }}</p>

                <div class="mt-3 text-xs space-y-3 text-gray-700">
                  @foreach($detallesSolicitud as $detalle)
                    @php
                      $paquetes = $detalle->cantidad;
                      $unidadPaq = $detalle->producto->paquete ?? 1;
                      $totalUnidadesDetalle = $paquetes * $unidadPaq;
                      $precioUnitario = $detalle->producto->precioReferencia ?? 0;
                      $precioTotal = $precioUnitario * $totalUnidadesDetalle;
                    @endphp

                    <div class="border-b pb-2">
                      <div class="flex justify-between mb-1">
                        <span class="font-medium">{{ $detalle->producto->descripcion ?? 'Sin descripción' }}</span>
                        <span class="text-right">
                          <span class="font-semibold block">{{ $paquetes }} paquetes</span>
                          <span class="text-[11px] text-gray-600">{{ $totalUnidadesDetalle }} unidades</span>
                        </span>
                      </div>

                      @if($unidadPaq > 1)
                        <p class="text-[10px] bg-gray-200 px-1.5 py-0.5 rounded inline-block mb-1">{{ $unidadPaq }} unidades por
                          paquete</p>
                      @endif

                      @if(!empty($detalle->tipo_contenido))
                        <p class="text-[11px] text-gray-600"><strong>Tipo:</strong> {{ $detalle->tipo_contenido }}</p>
                      @endif

                      @if(!empty($detalle->tapa_descripcion))
                        <div class="flex items-center gap-2 mt-1">
                          <span class="text-[11px] text-gray-600"><strong>Tapa:</strong> {{ $detalle->tapa_descripcion }}</span>
                          @if(!empty($detalle->tapa_imagen))
                            <img src="{{ asset('storage/' . $detalle->tapa_imagen) }}"
                              class="h-12 w-12 object-contain rounded border p-1">
                          @endif
                        </div>
                      @endif

                      @if(!empty($detalle->etiqueta_descripcion))
                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                          <span class="text-[11px] text-gray-600"><strong>Etiquetas:</strong></span>
                          @php
                            $etiquetas_desc = explode('|', $detalle->etiqueta_descripcion);
                            $etiquetas_imgs = !empty($detalle->etiqueta_imagen) ? explode('|', $detalle->etiqueta_imagen) : [];
                          @endphp
                          @foreach($etiquetas_desc as $index => $etiqueta_desc)
                            <div class="flex items-center gap-1">
                              <span class="text-[10px] bg-blue-100 px-1.5 py-0.5 rounded">{{ $etiqueta_desc }}</span>
                              @if(isset($etiquetas_imgs[$index]) && !empty($etiquetas_imgs[$index]))
                                <img src="{{ asset('storage/' . $etiquetas_imgs[$index]) }}"
                                  class="h-10 w-10 object-contain rounded border p-1">
                              @endif
                            </div>
                          @endforeach
                        </div>
                      @endif

                      <div class="mt-1 text-[11px] text-gray-700">
                        <p><strong>Precio unitario:</strong> Bs {{ number_format($precioUnitario, 2) }}</p>
                        <p><strong>Total:</strong> Bs {{ number_format($precioTotal, 2) }}</p>
                      </div>
                    </div>
                  @endforeach
                </div>

                <p class="mt-2 text-xs font-medium text-gray-700">Total: {{ $totalPaquetes }} paquetes —
                  {{ $totalUnidades }} unidades
                </p>
                <p class="mt-1 text-xs font-bold text-teal-700">Total del pedido: Bs {{ number_format($totalPedido, 2) }}
                </p>

                <p class="text-sm">
                  <strong>Método de pago:</strong>
                  <span class="font-semibold text-blue-700">
                    {{ $solicitud->metodo_pago == 0 ? 'QR' : ($solicitud->metodo_pago == 1 ? 'Efectivo' : 'Crédito') }}

                  </span>
                </p>

                <button type="button" wire:click="quitarSolicitud"
                  class="mt-3 px-3 py-1 bg-red-500 text-white rounded-md text-xs hover:bg-red-600">Quitar solicitud</button>
              </div>

            @else
              <div class="w-full border border-gray-300 rounded-md p-2 bg-white max-h-[260px] overflow-y-auto">
                @forelse($solicitudPedidos as $solicitud)
                  @php
                    $detallesSolicitud = $solicitud->detalles;
                    $totalPaquetes = $detallesSolicitud->sum('cantidad');
                    $totalUnidades = $detallesSolicitud->sum(fn($d) => $d->cantidad * ($d->producto->paquete ?? 1));
                    $totalPedido = $detallesSolicitud->sum(function ($d) {
                      $precioUnitario = $d->producto->precioReferencia ?? 0;
                      $unidades = $d->cantidad * ($d->producto->paquete ?? 1);
                      return $precioUnitario * $unidades;
                    });
                  @endphp

                  <button type="button" wire:click="seleccionarSolicitud({{ $solicitud->id }})"
                    class="w-full text-left p-3 mb-2 rounded-lg border-2 transition bg-white {{ $solicitudSeleccionadaId == $solicitud->id ? 'border-cyan-600' : 'border-gray-300 hover:border-cyan-600' }}">

                    <div class="flex justify-between items-center mb-2">
                      <span class="font-semibold text-gray-900">{{ $solicitud->codigo }} —
                        {{ $solicitud->cliente->nombre ?? 'Sin cliente' }}</span>
                    </div>

                    <div class="text-xs space-y-2 text-gray-700">
                      @foreach($detallesSolicitud as $detalle)
                        @php
                          $paquetes = $detalle->cantidad;
                          $unidadPaq = $detalle->producto->paquete ?? 1;
                          $totalUnidadesDetalle = $paquetes * $unidadPaq;
                          $precioUnitario = $detalle->producto->precioReferencia ?? 0;
                          $precioTotal = $precioUnitario * $totalUnidadesDetalle;
                        @endphp
                        <div class="border-b pb-2 flex justify-between">
                          <div class="flex-1">
                            <span
                              class="font-medium text-gray-900">{{ $detalle->producto->descripcion ?? 'Sin descripción' }}</span>
                            @if($unidadPaq > 1)
                              <span class="ml-1 text-[10px] bg-gray-200 px-1.5 py-0.5 rounded">{{ $unidadPaq }} u/pq</span>
                            @endif
                          </div>
                          <div class="text-right ml-2">
                            <span class="font-semibold block">{{ $paquetes }} paquetes</span>
                            <span class="text-[11px] text-gray-600">{{ $totalUnidadesDetalle }} unidades</span>
                            <span class="text-[10px] text-green-600 font-medium">Bs {{ number_format($precioTotal, 2) }}</span>
                          </div>
                        </div>
                      @endforeach
                    </div>

                    <div class="mt-2 text-xs flex justify-between items-center">
                      <span class="font-medium text-gray-800">Total: {{ $totalPaquetes }} paquetes — {{ $totalUnidades }}
                        unidades</span>

                      <div class="text-right">
                        <span class="text-gray-500 block">{{ $solicitud->created_at->format('d/m/Y') }}</span>
                        <span class="font-bold text-teal-700">Bs {{ number_format($totalPedido, 2) }}</span>
                      </div>
                      <p class="text-sm">
                        <strong>Método de pago:</strong>
                        <span class="font-semibold text-blue-700">
                          {{ $solicitud->metodo_pago == 0 ? 'QR' : ($solicitud->metodo_pago == 1 ? 'Efectivo' : 'Crédito') }}

                        </span>
                      </p>
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
              <label class="font-semibold">Estado del Pedido</label>

              <div class="flex flex-wrap gap-2 mt-2">

                <button type="button" wire:click="$set('estado_pedido', 0)" class="px-3 py-1.5 rounded-md border
                                {{ $estado_pedido == 0 ? 'bg-blue-500 text-white border-blue-600' :
      'bg-white text-gray-700 border-gray-300' }}">
                  Preparando
                </button>

                <button type="button" wire:click="$set('estado_pedido', 1)" class="px-3 py-1.5 rounded-md border
                                {{ $estado_pedido == 1 ? 'bg-yellow-500 text-white border-yellow-600' :
      'bg-white text-gray-700 border-gray-300' }}">
                  En Revisión
                </button>

                <button type="button" wire:click="$set('estado_pedido', 2)" class="px-3 py-1.5 rounded-md border
                                {{ $estado_pedido == 2 ? 'bg-green-500 text-white border-green-600' :
      'bg-white text-gray-700 border-gray-300' }}">
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
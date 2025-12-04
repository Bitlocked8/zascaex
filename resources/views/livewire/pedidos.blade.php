<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <h3
      class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto">
      Pedidos
    </h3>

    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input type="text" wire:model.live="search" placeholder="Buscar por código o cliente..."
        class="input-minimal w-full" />
      <button wire:click="$set('modalPedido', true)" class="btn-cyan" title="Agregar">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path
            d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
          <path
            d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
        </svg>
        Añadir
      </button>
    </div>

    @forelse($pedidos as $pedido)
      @php
        $pagoPedidos = $pedido->pagoPedidos ?? collect();

        $creditos = $pagoPedidos->where('metodo', 2);
        $totalCredito = $creditos->sum('monto');

        $pagosConfirmados = $pagoPedidos->where('metodo', '<>', 2);
        $totalPagado = $pagosConfirmados->where('estado', 1)->sum('monto');
        $usadoEnAdornado = \App\Models\Adornado::where('pedido_id', $pedido->id)->exists();

        if ($totalCredito > 0) {
          $saldoPendiente = max($totalCredito - $totalPagado, 0);
          $baseSaldo = $totalCredito;
        } else {
          $saldoPendiente = $totalPagado > 0 ? 0 : 0;
          $baseSaldo = 0;
        }
      @endphp

      <div class="card-teal flex flex-col gap-4">
        <div class="flex flex-col gap-2">
          @if($usadoEnAdornado)
            <p class="text-indigo-600 font-bold">Este pedido ya tiene etiquetas y esta empacado</p>
          @endif
          <p class="text-emerald-600 uppercase font-semibold">
            {{ $pedido->solicitudPedido?->cliente?->nombre ?? 'Sin cliente' }}
          </p>
          <p class="text-slate-600">{{ $pedido->codigo }}</p>

          <p><strong>Créditos registrados (base):</strong> Bs {{ number_format($totalCredito, 2) }}</p>

          <p><strong>Pagos:</strong>
            @if($pagosConfirmados->count())
              <ul class="flex flex-wrap gap-2 mt-1">
                @foreach($pagosConfirmados as $pago)
                  <li class="px-2 py-1 rounded-full  font-bold {{ $pago->estado ? ' text-emerald-700' : 'text-red-600' }}">
                    Bs {{ number_format($pago->monto, 2) }}
                    ({{ $pago->metodo == 0 ? 'QR' : ($pago->metodo == 1 ? 'Efectivo' : 'Otro') }})
                    {{ $pago->estado ? ' Pagado' : ' No pagado' }}
                  </li>
                @endforeach
              </ul>
            @else
            Bs 0.00
          @endif
          </p>

          <p><strong>Saldo Pendiente:</strong>
            <span class="text-indigo-600 font-bold">
              Bs {{ number_format($saldoPendiente, 2) }}
            </span>
            <br>
            <span class="text-gray-500 ">(Credito monto: Bs {{ number_format($baseSaldo, 2) }})</span>
          </p>
        </div>

        <div class="flex flex-wrap justify-center md:justify-center gap-2 border-t border-gray-200 pt-3 pb-2">
          <button wire:click="editarPedido({{ $pedido->id }})" class="btn-cyan" title="Editar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke="none" d="M0 0h24v24H0z" />
              <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
              <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
              <path d="M16 5l3 3" />
            </svg>
            Editar
          </button>
          <button wire:click="abrirModalPagosPedido({{ $pedido->id }})" class="btn-cyan" title="Ver/Agregar Pagos">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke="none" d="M0 0h24v24H0z" />
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
          <button wire:click="confirmarEliminarPedido({{ $pedido->id }})" class="btn-cyan" title="Eliminar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M4 7l16 0" />
              <path d="M10 11l0 6" />
              <path d="M14 11l0 6" />
              <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
              <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
            </svg>
            Eliminar
          </button>

        </div>
      </div>
    @empty
      <div class="col-span-full text-center py-4 text-gray-600">
        No hay pagos registrados.
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

                      <button type="button" wire:click="eliminarPagoPedido({{ $index }})" class="btn-cyan" title="Eliminar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                          stroke="currentColor" stroke-width="2">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <path d="M4 7l16 0" />
                          <path d="M10 11l0 6" />
                          <path d="M14 11l0 6" />
                          <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                          <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                        </svg>
                        Eliminar pago
                      </button>
                    </div>


                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                      <div class="sm:col-span-2">
                        <label class="font-semibold text-u">Monto (Requerido)</label>
                        <input type="number" wire:model="pagos.{{ $index }}.monto" class="input-minimal" min="0">
                      </div>

                      <div class="sm:col-span-2">
                        <label class="font-semibold text-sm">Método de Pago</label>

                        <div class="flex justify-center gap-3 mt-2">

                          {{-- QR --}}
                          <button type="button" wire:click="$set('pagos.{{ $index }}.metodo', 0)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition 
                                                                                                                                                                                                              {{ $pagos[$index]['metodo'] === 0
              ? 'bg-blue-700 text-white border-blue-800 shadow-md'
              : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                            QR
                          </button>

                          {{-- Efectivo --}}
                          <button type="button" wire:click="$set('pagos.{{ $index }}.metodo', 1)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition 
                                                                                                                                                                                                              {{ $pagos[$index]['metodo'] === 1
              ? 'bg-blue-700 text-white border-blue-800 shadow-md'
              : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                            Efectivo
                          </button>

                          {{-- Crédito --}}
                          <button type="button" wire:click="$set('pagos.{{ $index }}.metodo', 2)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition 
                                                                                                                                                                                                              {{ $pagos[$index]['metodo'] === 2
              ? 'bg-blue-700 text-white border-blue-800 shadow-md'
              : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                            Crédito
                          </button>

                        </div>
                      </div>


                      <div class="sm:col-span-2 mt-3">
                        <label class="font-semibold text-sm block text-center">Pago Confirmado</label>

                        <div class="flex justify-center mt-2">
                          <button type="button" wire:click="$set('pagos.{{ $index }}.estado', {{ $pago['estado'] ? 0 : 1 }})"
                            class="px-6 py-2 rounded-lg text-white font-semibold border shadow-md transition
                                                                                                                                                                                                              {{ $pagos[$index]['estado']
              ? 'bg-green-600 border-green-700 hover:bg-green-700'
              : 'bg-gray-500 border-gray-600 hover:bg-gray-600' }}">
                            {{ $pagos[$index]['estado'] ? 'PAGADO' : 'NO PAGADO' }}
                          </button>
                        </div>
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
                            class="w-full p-4 rounded-lg border-2 text-center {{ empty($pagos[$index]['sucursal_pago_id']) ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800' }}">
                            <span class="font-medium">Ninguno </span>
                          </button>

                          @foreach($sucursalesPago as $sp)
                            <button type="button" wire:click="$set('pagos.{{ $index }}.sucursal_pago_id', {{ $sp->id }})"
                              class="w-full p-4 rounded-lg border-2 text-center {{ $pagos[$index]['sucursal_pago_id'] == $sp->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800' }}">
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
                $totalUnidades = $detallesSolicitud->sum(fn($d) => $d->cantidad * (($d->producto->paquete ?? 1) ?: ($d->otro->paquete ?? 1)));
                $totalPedido = $detallesSolicitud->sum(function ($d) {
                  $item = $d->producto ?? $d->otro;
                  $precioUnitario = $item->precioReferencia ?? 0;
                  $unidades = $d->cantidad * ($item->paquete ?? 1);
                  return $precioUnitario * $unidades;
                });
              @endphp

              <div class="w-full border border-gray-300 rounded-md p-3 bg-gray-100">
                <p class="font-semibold text-gray-800 mb-1">Solicitud asignada:</p>
                <p class="font-semibold text-gray-900">{{ $solicitud->codigo }}</p>
                <p class="text-gray-600 text-sm">{{ $solicitud->cliente->nombre ?? 'Sin cliente' }}</p>

                <p class="text-xs text-gray-500">{{ $solicitud->created_at->format('d/m/Y') }}</p>

                <div class="mt-3 text-xs space-y-3 text-gray-700">
                  @foreach($detallesSolicitud as $detalle)
                    @php
                      $item = $detalle->producto ?? $detalle->otro;
                      $nombre = $item->descripcion ?? 'Sin descripción';
                      $unidadPaq = $item->paquete ?? 1;
                      $tipoContenido = $item->tipoContenido ?? null;
                      $precioUnitario = $item->precioReferencia ?? 0;
                      $totalUnidadesDetalle = $detalle->cantidad * $unidadPaq;
                      $precioTotal = $precioUnitario * $totalUnidadesDetalle;
                      $sucursal = $detalle->sucursal_nombre ?? 'Sin sucursal';
                    @endphp

                    <div class="border-b pb-2 flex justify-between">
                      <div class="flex-1">
                        <span class="font-medium text-gray-900">{{ $nombre }}</span>
                        <br>
                        <span class=" text-u">Sucursal: {{ $sucursal }}</span>

                        @if(!empty($tipoContenido))
                          <span class="text-indigo-600 -mt-1 block">
                            <strong>Contenido:</strong> {{ $tipoContenido }}
                          </span>
                        @endif

                        @if($unidadPaq > 1)
                          <span class="ml-1 text-[10px] bg-gray-200 px-1.5 py-0.5 rounded">{{ $unidadPaq }} u/pq</span>
                        @endif
                      </div>

                      <div class="text-right ml-2">
                        <span class="font-semibold block">{{ $detalle->cantidad }} paquetes</span>
                        <span class="text-[11px] text-gray-600">{{ $totalUnidadesDetalle }} unidades</span>
                        <span class="text-[10px] text-green-600 font-medium">Bs {{ number_format($precioTotal, 2) }}</span>
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
                    $totalUnidades = $detallesSolicitud->sum(fn($d) => $d->cantidad * (($d->producto->paquete ?? 1) ?: ($d->otro->paquete ?? 1)));
                    $totalPedido = $detallesSolicitud->sum(function ($d) {
                      $item = $d->producto ?? $d->otro;
                      $precioUnitario = $item->precioReferencia ?? 0;
                      $unidades = $d->cantidad * ($item->paquete ?? 1);
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
                          $item = $detalle->producto ?? $detalle->otro;
                          $nombre = $item->descripcion ?? 'Sin descripción';
                          $unidadPaq = $item->paquete ?? 1;
                          $tipoContenido = $item->tipoContenido ?? null;
                          $precioUnitario = $item->precioReferencia ?? 0;
                          $totalUnidadesDetalle = $detalle->cantidad * $unidadPaq;
                          $precioTotal = $precioUnitario * $totalUnidadesDetalle;
                        @endphp

                        <div class="border-b pb-2 flex justify-between">
                          <div class="flex-1">
                            <span class="font-medium text-gray-900">{{ $nombre }}</span>
                            @if(!empty($tipoContenido))
                              <span class="text-indigo-600 -mt-1"><strong>Contenido:</strong> {{ $tipoContenido }}</span>
                            @endif
                            @if($unidadPaq > 1)
                              <span class="ml-1 text-[10px] bg-gray-200 px-1.5 py-0.5 rounded">{{ $unidadPaq }} u/pq</span>
                            @endif
                          </div>
                          <div class="text-right ml-2">
                            <span class="font-semibold block">{{ $detalle->cantidad }} paquetes</span>
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
                        <span
                          class="font-semibold text-blue-700">{{ $solicitud->metodo_pago == 0 ? 'QR' : ($solicitud->metodo_pago == 1 ? 'Efectivo' : 'Crédito') }}</span>
                      </p>
                    </div>
                  </button>
                @empty
                  <div class="text-center py-4 text-gray-600">No hay solicitudes registradas.</div>
                @endforelse
              </div>
            @endif
          </div>

          @if(auth()->user()->rol_id !== 2)
            <div class="text-center mb-6">
              <label class="font-semibold text-sm">Sucursal</label>

              <div class="flex flex-col sm:flex-row justify-center flex-wrap gap-3 mt-2">
                @foreach($sucursales as $sucursal)
                      <button type="button" wire:click="$set('sucursal_id', {{ $sucursal->id }})" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                              {{ $sucursal_id === $sucursal->id
                  ? 'bg-blue-700 text-white border-blue-800 shadow-md'
                  : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                        {{ $sucursal->nombre }}
                      </button>
                @endforeach
              </div>
            </div>
          @endif



          <div class="text-center mb-6">
            <label class="font-semibold text-sm">Tipo de Ítem</label>

            <div class="flex flex-col sm:flex-row justify-center flex-wrap gap-3 mt-2">


              <button type="button" wire:click="$set('tipoProducto', 'producto')" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                              {{ $tipoProducto === 'producto'
      ? 'bg-blue-700 text-white border-blue-800 shadow-md'
      : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                Productos
              </button>

              <button type="button" wire:click="$set('tipoProducto', 'otro')" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                              {{ $tipoProducto === 'otro'
      ? 'bg-green-500 text-white border-green-600 shadow-md'
      : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
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
                      @if(!empty($producto->tipoContenido))
                        <span class=" text-indigo-600 -mt-1">
                          <strong>Contenido:</strong> {{ $producto->tipoContenido }}
                        </span>
                      @endif
                      <div class="flex flex-wrap justify-center gap-3 mt-2">

                        @foreach($cantidadesPorSucursal as $sucursal => $cantidad)
                          <span class="text-xs bg-teal-600 text-white px-2 py-0.5 rounded-full font-semibold">{{ $sucursal }}:
                            {{ $cantidad }}
                          </span>

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
              placeholder="Coloca una Cantidad" />
            <button wire:click="agregarProducto" class="btn-cyan"> <svg xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" viewBox="0 0 24 24" fill="currentColor">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                  d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                <path
                  d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
              </svg></button>
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
                    @if(!empty($detalle['tipo_contenido']))
                      <span class="text-indigo-600 -mt-1 block">
                        <strong>Contenido:</strong> {{ $detalle['tipo_contenido'] }}
                      </span>
                    @endif
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
            <div class="sm:col-span-2">
              <label class="font-semibold text-sm">Estado del Pedido</label>

              <div class="flex justify-center gap-3 mt-2">
                {{-- Preparando --}}
                <button type="button" wire:click="$set('estado_pedido', 0)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition 
                                              {{ $estado_pedido === 0
      ? 'bg-blue-700 text-white border-blue-800 shadow-md'
      : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                  Preparando
                </button>

                {{-- En Revisión --}}
                <button type="button" wire:click="$set('estado_pedido', 1)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition 
                                              {{ $estado_pedido === 1
      ? 'bg-yellow-500 text-white border-yellow-600 shadow-md'
      : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                  En Revisión
                </button>

                {{-- Completado --}}
                <button type="button" wire:click="$set('estado_pedido', 2)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition 
                                              {{ $estado_pedido === 2
      ? 'bg-green-500 text-white border-green-600 shadow-md'
      : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                  Completado
                </button>
              </div>
            </div>





          </div>
          <div class="modal-footer">
            <button wire:click="cerrarModal" class="btn-cyan" title="Cerrar">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
              </svg>
              CERRAR
            </button>
            <button wire:click="guardarPedido" class="btn-cyan">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" />
                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M14 4l0 4l-6 0l0 -4" />
              </svg>
              Guardar
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
                <span class="badge-info">{{ $pedidoDetalle->solicitudPedido->cliente->empresa ?? '-' }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Cliente:</span>
                <span class="badge-info">{{ $pedidoDetalle->solicitudPedido->cliente->nombre ?? '-' }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Dirección:</span>
                <span class="badge-info">{{ $pedidoDetalle->solicitudPedido->cliente->direccion ?? '-' }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Departamento:</span>
                <span
                  class="badge-info">{{ $pedidoDetalle->solicitudPedido->cliente->departamento_localidad ?? '-' }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Personal de atención:</span>
                <span class="badge-info">{{ $pedidoDetalle->personal->nombres ?? '-' }}</span>
              </div>
            </div>

            <div class="flex flex-col gap-3">
              <span
                class="badge-info">{{ \Carbon\Carbon::parse($pedidoDetalle->fecha_pedido)->format('d M Y, H:i') }}</span>
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Estado:</span>
                <span
                  class="inline-block px-2 py-1 rounded-full text-sm font-semibold
                                    {{ $pedidoDetalle->estado_pedido == 0 ? 'bg-blue-700 text-white' : ($pedidoDetalle->estado_pedido == 1 ? 'bg-yellow-500 text-white' : 'bg-green-600 text-white') }}">
                  {{ $pedidoDetalle->estado_pedido == 0 ? 'Preparando' : ($pedidoDetalle->estado_pedido == 1 ? 'En Revisión' : 'Completado') }}
                </span>
              </div>
            </div>
          </div>

          <hr class="my-2">
          <h3 class="font-semibold text-lg">Productos Seleccionados:</h3>
          @php $totalProductos = 0; @endphp
          <div class="divide-y divide-gray-200">
            @foreach($pedidoDetalle->detalles as $detalle)
              @php
                $producto = $detalle->existencia->existenciable ?? null;
                $sucursal = $detalle->existencia->sucursal ?? null;
                $precioUnitario = $producto->precioReferencia ?? 0;
                $totalProducto = $precioUnitario * $detalle->cantidad;
                $totalProductos += $totalProducto;
              @endphp
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center py-2">
                <div class="flex flex-col gap-1">
                  <span class="font-medium">{{ $producto->descripcion ?? 'Sin nombre' }}</span>
                  <span class="font-medium">Precio unitario: {{ number_format($precioUnitario, 2) }} BS</span>
                  <span class="font-medium">Total: {{ number_format($totalProducto, 2) }} BS</span>
                  <span class="text-sm text-gray-500">Sucursal: {{ $sucursal->nombre ?? 'N/A' }}</span>
                  @if(isset($producto->imagen))
                    <img src="{{ asset('storage/' . $producto->imagen) }}" class="w-60 h-60 object-cover rounded shadow"
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
            <span class="font-semibold text-lg">Total productos:</span>
            <span class="font-semibold text-lg">{{ number_format($totalProductos, 2) }} BS</span>
          </div>

          @php
            $pagoPedidos = $pedidoDetalle->pagoPedidos ?? collect();
            $creditos = $pagoPedidos->where('metodo', 2);
            $totalCredito = $creditos->sum('monto');
            $pagosConfirmados = $pagoPedidos->where('metodo', '<>', 2);
            $totalPagado = $pagosConfirmados->where('estado', 1)->sum('monto');
            $saldoPendiente = $totalCredito > 0 ? max($totalCredito - $totalPagado, 0) : 0;
            $baseSaldo = $totalCredito > 0 ? $totalCredito : 0;
          @endphp

          @if($pagoPedidos->count())
            <div class="mt-6 border-t pt-4">
              <h3 class="font-semibold text-lg mb-2">Pagos del Pedido</h3>

              <p><strong>Créditos registrados (base):</strong> Bs {{ number_format($totalCredito, 2) }}</p>

              <p><strong>Pagos:</strong></p>
              @if($pagosConfirmados->count())
                <ul class="flex flex-wrap gap-2 mt-1">
                  @foreach($pagosConfirmados as $pago)
                    <li
                      class="px-2 py-1 rounded-full text-xs {{ $pago->estado ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                      Bs {{ number_format($pago->monto, 2) }}
                      ({{ $pago->metodo == 0 ? 'QR' : ($pago->metodo == 1 ? 'Efectivo' : 'Otro') }})
                      {{ $pago->estado ? 'Pagado' : 'No pagado' }}
                    </li>
                  @endforeach
                </ul>
              @else
                Bs 0.00
              @endif

              <div class="mt-3">
                <span class="font-semibold">Saldo Pendiente:</span>
                <span class="text-indigo-600 font-bold">Bs {{ number_format($saldoPendiente, 2) }}</span>
                <span class="text-gray-500 text-xs">(Base: Bs {{ number_format($baseSaldo, 2) }})</span>
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

  @if($modalEliminarPedido)
    <div class="modal-overlay">
      <div class="modal-box">

        <div class="modal-content">
          <div class="flex flex-col gap-4 text-center">
            <h2 class="text-lg font-semibold">¿Eliminar pedido?</h2>
            <p class="text-gray-600">
              Esta acción no se puede deshacer.
            </p>
          </div>
        </div>

        <div class="modal-footer flex justify-center gap-2 mt-4">
          <button type="button" wire:click="eliminarPedidoConfirmado" class="btn-cyan flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" />
              <path d="M5 12l5 5l10 -10" />
            </svg>
            Confirmar
          </button>

          <button type="button" wire:click="$set('modalEliminarPedido', false)" class="btn-cyan flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path stroke="none" d="M0 0h24v24H0z" />
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
            Cancelar
          </button>
        </div>

      </div>
    </div>
  @endif
</div>
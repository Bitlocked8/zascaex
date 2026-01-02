<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl">

    <h3 class="text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full shadow-sm mb-4">
      Pedidos
    </h3>

    <div class="flex items-center gap-2 mb-4 flex-wrap">
      <input
        type="text"
        wire:model.live="search"
        placeholder="Buscar por código o cliente..."
        class="input-minimal w-full sm:w-auto flex-1" />
      <button wire:click="$set('modalPedido', true)" class="btn-cyan">
        Añadir
      </button>
    </div>

    <div class="overflow-auto max-h-[500px] border border-gray-200 rounded-md">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-teal-50 sticky top-0 z-10">
          <tr>
            <th class="px-4 py-2 text-left text-teal-700 font-semibold">Cliente</th>
            <th class="px-4 py-2 text-left text-teal-700 font-semibold">Código</th>
            <th class="px-4 py-2 text-right text-teal-700 font-semibold">Crédito</th>
            <th class="px-4 py-2 text-right text-teal-700 font-semibold">Pagado</th>
            <th class="px-4 py-2 text-right text-teal-700 font-semibold">Saldo</th>
            <th class="px-4 py-2 text-center text-teal-700 font-semibold">Estado</th>
            <th class="px-4 py-2 text-center text-teal-700 font-semibold">Acciones</th>
          </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($pedidos as $pedido)
          @php
          $pagos = $pedido->pagoPedidos ?? collect();
          $credito = $pagos->where('metodo', 2)->sum('monto');
          $pagado = $pagos->where('estado', 1)->where('metodo', '<>', 2)->sum('monto');
            $saldo = max($credito - $pagado, 0);
            $empacado = \App\Models\Adornado::where('pedido_id', $pedido->id)->exists();
            @endphp

            <tr class="hover:bg-teal-50">
              <td class="px-4 py-2 font-semibold text-teal-700">
                {{ $pedido->cliente?->nombre ?? $pedido->solicitudPedido?->cliente?->nombre ?? 'Sin cliente' }}

              </td>

              <td class="px-4 py-2">
                {{ $pedido->codigo }}
              </td>

              <td class="px-4 py-2 text-right">
                Bs {{ number_format($credito, 2) }}
              </td>

              <td class="px-4 py-2 text-right">
                Bs {{ number_format($pagado, 2) }}
              </td>

              <td class="px-4 py-2 text-right font-bold {{ $saldo > 0 ? 'text-indigo-600' : 'text-emerald-600' }}">
                Bs {{ number_format($saldo, 2) }}
              </td>

              <td class="px-4 py-2 text-center">
                @if($pedido->estado_pedido === 0)
                <span class="px-2 py-1 text-xs rounded-full bg-blue-700 text-white font-semibold">
                  Preparando productos
                </span>
                @elseif($pedido->estado_pedido === 1)
                <span class="px-2 py-1 text-xs rounded-full bg-orange-500 text-white font-semibold">
                  Espera de pago
                </span>
                @elseif($pedido->estado_pedido === 2)
                <span class="px-2 py-1 text-xs rounded-full bg-green-500 text-white font-semibold">
                  Pedido Completado
                </span>
                @else
                <span class="px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-700 font-semibold">
                  Desconocido
                </span>
                @endif
              </td>


              <td class="px-4 py-2 flex flex-wrap justify-center gap-1">
                <button wire:click="editarPedido({{ $pedido->id }})" class="btn-cyan text-xs">
                  Editar
                </button>
                <button wire:click="abrirModalPagosPedido({{ $pedido->id }})" class="btn-cyan text-xs">
                  Pagos
                </button>
                <button wire:click="abrirModalDetallePedido({{ $pedido->id }})" class="btn-cyan text-xs">
                  Ver
                </button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center py-4 text-gray-600">
                No hay pedidos registrados.
              </td>
            </tr>
            @endforelse
        </tbody>
      </table>
    </div>

    <div class="col-span-full text-center mt-4 flex justify-center gap-2">
      @if($pedidos->count() >= $cantidad)
      <button wire:click="cargarMas" class="btn-cyan px-4 py-2 rounded">
        Cargar más
      </button>
      @endif

      @if($cantidad > 50)
      <button wire:click="cargarMenos" class="btn-cyan px-4 py-2 rounded">
        Cargar menos
      </button>
      @endif
    </div>

  </div>


  @if($modalPedido)
  <div class="modal-overlay">
    <div class="modal-box">
      <div class="modal-content flex flex-col gap-4">
        <div class="sm:w-full w-auto">
          <label class="text-sm font-semibold text-gray-700">Solicitud de Pedido</label>

          @if($solicitud_pedido_id)
          @php
          $solicitud = $solicitudPedidos->firstWhere('id', $solicitud_pedido_id);
          $detallesSolicitud = $solicitud?->detalles ?? collect();
          $totalPaquetes = $detallesSolicitud->sum('cantidad');
          $totalUnidades = $detallesSolicitud->sum(fn($d) => $d->cantidad * (($d->producto->paquete ?? 1) ?: ($d->otro->paquete ?? 1)));
          $totalPedido = $detallesSolicitud->sum(fn($d) => (($d->producto ?? $d->otro)->precioReferencia ?? 0) * $d->cantidad * (($d->producto->paquete ?? 1) ?: ($d->otro->paquete ?? 1)));
          @endphp

          <div class="border border-gray-300 rounded-md p-2 bg-gray-50 text-xs sm:text-sm">
            <div class="flex justify-between items-center mb-1">
              <span class="font-bold text-gray-900 truncate">{{ $solicitud->codigo }}</span>
              <button wire:click="quitarSolicitud" class="px-2 py-1 text-white bg-red-500 rounded hover:bg-red-600 text-xs">Quitar</button>
            </div>

            <div class="flex justify-between items-center">
              <span class="text-cyan-600 font-semibold truncate">{{ $solicitud->cliente->nombre ?? 'Sin cliente' }}</span>
              <span class="text-gray-500 text-[10px] ml-2">{{ $solicitud->created_at->format('d/m/Y H:i') }}</span>
            </div>

            <div class="mt-2 space-y-1">
              @foreach($detallesSolicitud as $detalle)
              @php
              $item = $detalle->producto ?? $detalle->otro;
              $nombre = $item->descripcion ?? 'Sin descripción';
              $unidadPaq = $item->paquete ?? 1;
              $tipoContenido = $item->tipoContenido ?? null;
              $totalUnidadesDetalle = $detalle->cantidad * $unidadPaq;
              $precioTotal = ($item->precioReferencia ?? 0) * $totalUnidadesDetalle;
              @endphp

              <div class="flex justify-between items-center border-b border-gray-200 py-1">
                <div class="flex-1 truncate">
                  <span class="font-medium text-gray-900 truncate">{{ $nombre }}</span>
                  @if($tipoContenido)
                  <span class="block text-indigo-600 text-[10px] truncate">{{ $tipoContenido }}</span>
                  @endif
                  @if($unidadPaq > 1)
                  <span class="inline-block text-[9px] bg-gray-200 px-1 rounded ml-1">{{ $unidadPaq }} u/pq</span>
                  @endif
                </div>
                <div class="text-right text-[10px]">
                  <div>{{ $detalle->cantidad }} pq</div>
                  <div>{{ $totalUnidadesDetalle }} u</div>
                  <div class="text-green-600 font-semibold">Bs {{ number_format($precioTotal, 2) }}</div>
                </div>
              </div>
              @endforeach
            </div>

            <div class="mt-2 flex justify-between text-[11px] font-medium text-gray-700">
              <span>Total: {{ $totalPaquetes }} pq — {{ $totalUnidades }} u</span>
              <span class="text-teal-700 font-bold">Bs {{ number_format($totalPedido, 2) }}</span>
            </div>

            <p class="text-[11px] mt-1">
              <strong>Método de pago:</strong>
              <span class="text-blue-700 font-semibold">
                {{ $solicitud->metodo_pago == 0 ? 'QR' : ($solicitud->metodo_pago == 1 ? 'Efectivo' : 'Crédito') }}
              </span>
            </p>
          </div>
          @else
          <div class="border border-gray-300 rounded-md p-2 bg-white max-h-60 overflow-y-auto text-xs sm:text-sm">
            @forelse($solicitudPedidos as $solicitud)
            @php
            $detallesSolicitud = $solicitud->detalles;
            $totalPaquetes = $detallesSolicitud->sum('cantidad');
            $totalUnidades = $detallesSolicitud->sum(fn($d) => $d->cantidad * (($d->producto->paquete ?? 1) ?: ($d->otro->paquete ?? 1)));
            $totalPedido = $detallesSolicitud->sum(fn($d) => (($d->producto ?? $d->otro)->precioReferencia ?? 0) * $d->cantidad * (($d->producto->paquete ?? 1) ?: ($d->otro->paquete ?? 1)));
            @endphp

            <button wire:click="seleccionarSolicitud({{ $solicitud->id }})"
              class="w-full text-left p-2 mb-1 rounded-lg border-2 transition text-gray-800 bg-white {{ $solicitudSeleccionadaId == $solicitud->id ? 'border-cyan-600' : 'border-gray-300 hover:border-cyan-600' }}">
              <div class="flex justify-between items-center">
                <span class="font-bold text-gray-900 truncate uppercase">{{ $solicitud->codigo }}</span>
                <span class="font-semibold text-cyan-600 truncate uppercase ml-2">{{ $solicitud->cliente->nombre ?? 'Sin cliente' }}</span>
                <span class="text-[10px] text-gray-500 ml-auto">{{ $solicitud->created_at->format('d/m/Y H:i') }}</span>
              </div>


              <div class="mt-1 space-y-1">
                @foreach($detallesSolicitud as $detalle)
                @php
                $item = $detalle->producto ?? $detalle->otro;
                $nombre = $item->descripcion ?? 'Sin descripción';
                $unidadPaq = $item->paquete ?? 1;
                $tipoContenido = $item->tipoContenido ?? null;
                $totalUnidadesDetalle = $detalle->cantidad * $unidadPaq;
                @endphp

                <div class="flex justify-between items-center">
                  <div class="flex-1 truncate text-[10px]">
                    {{ $nombre }}
                    @if($tipoContenido)
                    <span class="text-indigo-600 ml-1">({{ $tipoContenido }})</span>
                    @endif
                    @if($unidadPaq > 1)
                    <span class="inline-block bg-gray-200 text-[9px] px-1 rounded ml-1">{{ $unidadPaq }} u/pq</span>
                    @endif
                  </div>
                  <div class="text-right text-[10px]">
                    {{ $detalle->cantidad }} pq / {{ $totalUnidadesDetalle }} u
                  </div>
                </div>
                @endforeach
              </div>
            </button>
            @empty
            <p class="text-center py-4 text-gray-500 text-[10px]">No hay solicitudes registradas.</p>
            @endforelse
          </div>
          @endif
        </div>



        @if(!$solicitud_pedido_id)
        <div class="mb-6">
          <label class="font-semibold text-sm mb-2 block">Cliente (Requerido)</label>
          <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 max-h-[200px] overflow-y-auto">
            <button type="button" wire:click="$set('cliente_id', null)"
              class="w-full px-3 py-3 rounded-lg border-2 text-left transition-all
            {{ is_null($cliente_id)
                ? 'bg-red-500 text-white border-red-600 shadow-lg'
                : 'bg-gray-50 text-gray-800 border-gray-300 hover:bg-red-50 hover:border-red-500 hover:text-red-600' }}">
              <p class="font-semibold text-sm">-- Ningún cliente --</p>
            </button>

            @forelse($clientes as $cliente)
            <button type="button" wire:click="$set('cliente_id', {{ $cliente->id }})"
              class="w-full px-3 py-3 rounded-lg border-2 text-left transition-all
                {{ $cliente_id == $cliente->id
                    ? 'bg-cyan-600 text-white border-cyan-700 shadow-lg'
                    : 'bg-gray-50 text-gray-800 border-gray-300 hover:bg-cyan-50 hover:border-cyan-500 hover:text-cyan-700' }}">

              <p class="font-semibold text-sm">{{ $cliente->nombre }}</p>
              <p class="text-xs text-gray-500 {{ $cliente_id == $cliente->id ? 'text-cyan-200' : '' }}">
                {{ $cliente->empresa?->nombre ?? 'Sin empresa' }}
              </p>
              <p class="text-xs text-gray-500 {{ $cliente_id == $cliente->id ? 'text-cyan-200' : '' }}">
                {{ $cliente->telefono ?? 'Sin teléfono' }}
              </p>
            </button>
            @empty
            <p class="text-center text-gray-500 py-3 text-sm">
              No hay clientes disponibles
            </p>
            @endforelse
          </div>

          @error('cliente_id')
          <span class="error-message text-red-500 text-xs">{{ $message }}</span>
          @enderror
        </div>
        @else
        <div class="mb-6">
          <label class="font-semibold text-sm">Cliente</label>
          <input type="text" disabled
            class="w-full border border-gray-300 rounded-md p-2 bg-gray-100 text-sm font-medium text-gray-800"
            value="{{ $solicitud->cliente->nombre ?? 'Sin cliente' }}">
        </div>
        @endif


        @if(auth()->user()->rol_id !== 2)
        <div class="text-center mb-6">
          <label class="font-semibold text-sm">Sucursal</label>

          <div class="flex flex-col sm:flex-row justify-center flex-wrap gap-3 mt-2">
            @foreach($sucursales as $sucursal)
            <button type="button" wire:click="$set('sucursal_id', {{ $sucursal->id }})" class="px-4 py-2 rounded-lg border text-sm font-semibold transition {{ $sucursal_id === $sucursal->id ? 'bg-blue-700 text-white border-blue-800 shadow-md' : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
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

        <div class="grid grid-cols-1 gap-2 mb-6">
          <div class="w-full border border-gray-300 rounded-md shadow-sm p-1 bg-white overflow-y-auto max-h-[350px] sm:max-h-[400px]">

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
              class="w-full p-2 sm:p-3 rounded-lg border-2 transition flex flex-col items-center text-center text-xs sm:text-sm
                        {{ $productoSeleccionado == $producto->id ? 'border-cyan-600 text-cyan-600 bg-cyan-50' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}">
              <span class="font-semibold truncate">{{ $producto->descripcion ?? 'Producto #' . $producto->id }}</span>
              @if(!empty($producto->tipoContenido))
              <span class="text-indigo-600 text-[10px] sm:text-xs -mt-1 block truncate">
                <strong>Contenido:</strong> {{ $producto->tipoContenido }}
              </span>
              @endif

              <div class="flex flex-wrap justify-center gap-1 sm:gap-2 mt-1 sm:mt-2">
                @foreach($cantidadesPorSucursal as $sucursal => $cantidad)
                <span class="text-[9px] sm:text-xs bg-teal-600 text-white px-1.5 py-0.5 rounded-full font-semibold truncate">
                  {{ $sucursal }}: {{ $cantidad }}
                </span>
                @endforeach
              </div>

              <span class="text-[10px] sm:text-sm mt-1">Bs {{ $producto->precioReferencia ?? 'sin precio' }}</span>
            </button>
            @endforeach
            @else
            <div class="text-center text-gray-500 py-4 text-xs sm:text-sm">
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
              class="w-full p-2 sm:p-3 rounded-lg border-2 transition flex flex-col items-center text-center text-xs sm:text-sm
                        {{ $otroSeleccionado == $otro->id ? 'border-green-600 text-green-600 bg-green-50' : 'border-gray-300 text-gray-800 hover:border-green-600 hover:text-green-600' }}">
              <span class="font-semibold truncate">{{ $otro->descripcion ?? 'Item #' . $otro->id }}</span>

              <div class="flex flex-wrap justify-center gap-1 sm:gap-2 mt-1 sm:mt-2">
                @foreach($cantidadesPorSucursal as $sucursal => $cantidad)
                <span class="text-[9px] sm:text-xs bg-teal-600 text-white px-1.5 py-0.5 rounded-full font-semibold truncate">
                  {{ $sucursal }}: {{ $cantidad }}
                </span>
                @endforeach
              </div>

              <span class="text-[10px] sm:text-sm mt-1">Bs {{ $otro->precioReferencia ?? 'sin precio' }}</span>
            </button>
            @endforeach
            @else
            <div class="text-center text-gray-500 py-4 text-xs sm:text-sm">
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
          <div class="grid grid-cols-1 gap-2 mt-2">
            @foreach($detalles as $index => $detalle)
            @if(!isset($detalle['eliminar']))
            <div
              class="w-full p-3 rounded-lg border transition flex flex-col items-start text-left bg-white hover:border-cyan-600 border-gray-300 shadow-sm {{ $detalle['tipo'] === 'producto' ? 'border-l-4 border-l-cyan-500' : 'border-l-4 border-l-green-500' }}">

              <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $detalle['tipo'] === 'producto' ? 'bg-cyan-100 text-cyan-800' : 'bg-green-100 text-green-800' }}">
                {{ ucfirst($detalle['tipo']) }}
              </span>

              <span class="font-semibold mt-1 text-sm">{{ $detalle['nombre'] }}</span>

              @if(!empty($detalle['tipo_contenido']))
              <span class="text-indigo-600 -mt-1 block text-xs">
                Contenido: {{ $detalle['tipo_contenido'] }}
              </span>
              @endif

              <span class="mt-1 bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                {{ fmod($detalle['cantidad'], 1) == 0 ? intval($detalle['cantidad']) : number_format($detalle['cantidad'], 2) }} Unidad(es)
              </span>

              @if(!empty($detalle['sucursal_nombre']))
              <span class="text-xs text-gray-500 mt-1">Sucursal: {{ $detalle['sucursal_nombre'] }}</span>
              @endif

              <button wire:click="eliminarDetalle({{ $index }})" class="btn-cyan mt-1 text-xs w-full">Eliminar</button>
            </div>
            @endif
            @endforeach
          </div>

          @if(count($detalles) === 0)
          <p class="text-gray-500 text-xs italic mt-2">No hay items agregados.</p>
          @endif

          <div class="mb-3 mt-3">
            <label class="font-semibold text-sm">Observaciones (Opcional)</label>
            <textarea wire:model="observaciones" class="w-full border border-gray-300 rounded-md p-2 bg-white text-sm" rows="2"
              placeholder="Escribe observaciones del pedido..."></textarea>
          </div>

          <div class="mb-4">
            <label class="font-semibold text-sm">Estado del Pedido</label>
            <div class="flex flex-col gap-2 mt-1">
              <button type="button" wire:click="$set('estado_pedido', 0)"
                class="w-full px-3 py-2 rounded-lg border text-xs font-semibold transition {{ $estado_pedido === 0 ? 'bg-blue-700 text-white border-blue-800 shadow' : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                Preparando
              </button>

              <button type="button" wire:click="$set('estado_pedido', 1)"
                class="w-full px-3 py-2 rounded-lg border text-xs font-semibold transition {{ $estado_pedido === 1 ? 'bg-yellow-500 text-white border-yellow-600 shadow' : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                En Revisión
              </button>

              <button type="button" wire:click="$set('estado_pedido', 2)"
                class="w-full px-3 py-2 rounded-lg border text-xs font-semibold transition {{ $estado_pedido === 2 ? 'bg-green-500 text-white border-green-600 shadow' : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
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

  @if($modalPagos)
  <div class="modal-overlay">
    <div class="modal-box">
      <div class="modal-content flex flex-col gap-4">
        <div class="space-y-4">

          @foreach($pagos as $index => $pago)
          <div wire:key="pago-{{ $index }}" class="border p-4 rounded flex flex-col gap-2">

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
                <input type="number" wire:model.defer="pagos.{{ $index }}.monto" class="input-minimal" min="0">
              </div>

              <div class="sm:col-span-2">
                <label class="font-semibold text-sm">Método de Pago</label>
                <div class="flex justify-center gap-3 mt-2">
                  @foreach(['QR'=>0,'Efectivo'=>1,'Crédito'=>2] as $metodoNombre => $metodoId)
                  <button type="button"
                    wire:click="$set('pagos.{{ $index }}.metodo', {{ $metodoId }})"
                    class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                      {{ $pago['metodo'] === $metodoId
                        ? 'bg-blue-700 text-white border-blue-800 shadow-md'
                        : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                    {{ $metodoNombre }}
                  </button>
                  @endforeach
                </div>
              </div>

              <div class="sm:col-span-2 mt-3">
                <label class="font-semibold text-sm block text-center">Pago Confirmado</label>
                <div class="flex justify-center mt-2">
                  <button type="button"
                    wire:click="$set('pagos.{{ $index }}.estado', {{ $pago['estado'] ? 0 : 1 }})"
                    class="px-6 py-2 rounded-lg text-white font-semibold border shadow-md transition
                    {{ $pago['estado']
                      ? 'bg-green-600 border-green-700 hover:bg-green-700'
                      : 'bg-gray-500 border-gray-600 hover:bg-gray-600' }}">
                    {{ $pago['estado'] ? 'PAGADO' : 'NO PAGADO' }}
                  </button>
                </div>
              </div>
              <div class="sm:col-span-2">
                <label class="font-semibold text-sm">Referencia (Opcional)</label>
                <input type="text" wire:model.defer="pagos.{{ $index }}.referencia" class="input-minimal">
              </div>

              <div class="sm:col-span-2">
                <label class="font-semibold text-sm">Observaciones (Opcional)</label>
                <input type="text" wire:model.defer="pagos.{{ $index }}.observaciones" class="input-minimal">
              </div>
              <div class="sm:col-span-2">
                <label class="text-u">Método QR auxiliar (Sucursal)</label>
                <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-[180px] overflow-y-auto">
                  <button type="button" wire:click="$set('pagos.{{ $index }}.sucursal_pago_id', null)"
                    class="w-full p-4 rounded-lg border-2 text-center {{ empty($pago['sucursal_pago_id']) ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800' }}">
                    Ninguno
                  </button>

                  @foreach($sucursalesPago as $sp)
                  <button type="button" wire:click="$set('pagos.{{ $index }}.sucursal_pago_id', {{ $sp->id }})"
                    class="w-full p-4 rounded-lg border-2 text-center {{ $pago['sucursal_pago_id'] == $sp->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800' }}">
                    {{ $sp->nombre }}
                    @if($sp->tipo)
                    <span class="bg-gray-600 text-white text-xs px-2 py-0.5 rounded-full mt-1">{{ $sp->tipo }}</span>
                    @endif
                  </button>
                  @endforeach
                </div>
              </div>
              <div class="sm:col-span-2">
                <label class="font-semibold text-sm">Imagen del comprobante</label>
                <input type="file" wire:model="pagos.{{ $index }}.imagen_comprobante" class="input-minimal">

                @php
                $imagenUrl = null;
                if(isset($pago['imagen_comprobante'])){
                $imagenUrl = is_string($pago['imagen_comprobante'])
                ? Storage::url($pago['imagen_comprobante'])
                : $pago['imagen_comprobante']->temporaryUrl();
                }
                @endphp

                @if($imagenUrl)
                <div class="mt-2 flex flex-col items-center space-y-2">
                  <img src="{{ $imagenUrl }}" class="w-80 h-80 object-cover rounded-lg shadow cursor-pointer"
                    wire:click="$set('imagenPreviewModal','{{ $imagenUrl }}'); $set('modalImagenAbierta',true)">
                  @if(is_string($pago['imagen_comprobante']))
                  <a href="{{ $imagenUrl }}" download class="btn-circle btn-cyan">⬇</a>
                  @endif
                </div>
                @endif
              </div>

            </div>
          </div>
          @endforeach
        </div>
        <div class="modal-footer flex gap-2 flex-wrap">
          <button type="button" wire:click="agregarPagoPedido" class="btn-cyan">
            Añadir pago
          </button>
          <button type="button" wire:click="guardarPagosPedido" class="btn-cyan">
            Guardar pagos
          </button>
          <button type="button" wire:click="$set('modalPagos', false)" class="btn-cyan">
            Cerrar
          </button>
        </div>
      </div>
    </div>
  </div>
  @endif

</div>
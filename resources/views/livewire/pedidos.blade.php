<div class="p-2 mt-20 flex justify-center bg-transparent">
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
      @if(auth()->user()->rol_id != 3)
      <button wire:click="$toggle('modalResumenExistencias')" class="btn-cyan">
        Produccion
      </button>
      @endif
    </div>

    <div class="overflow-auto max-h-[500px] border border-gray-200 rounded-md">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-teal-50 sticky top-0 z-10">
          <tr>
            <th class="px-4 py-2 text-left text-teal-700 font-semibold">Cliente</th>
            <th class="px-4 py-2 text-left text-teal-700 font-semibold">Código</th>
            <th class="px-4 py-2 text-center text-teal-700 font-semibold">Estado</th>
            <th class="px-4 py-2 text-center text-teal-700 font-semibold">Acciones</th>
          </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($pedidos as $pedido)
          @php
          $empacado = \App\Models\Adornado::where('pedido_id', $pedido->id)->exists();
          @endphp
          <tr class="hover:bg-teal-50">
            <td class="px-4 py-2">
              <div class="font-semibold text-teal-700 truncate">
                {{ $pedido->cliente?->nombre ?? $pedido->solicitudPedido?->cliente?->nombre ?? 'Sin cliente' }}
              </div>

              @if($pedido->personal?->nombres)
              <div class="mt-1">
                <span class="text-xs text-gray-500">Personal:</span>
                <span class="inline-block text-xs text-blue-700 font-semibold">
                  {{ $pedido->personal->nombres }}
                </span>
              </div>
              @endif
            </td>

            <td class="px-4 py-2">
              {{ $pedido->codigo }}<br>
              <span class="text-gray-500 text-sm">
                {{ $pedido->fecha_pedido ? \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') : 'Sin fecha' }}
              </span>
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
              <button wire:click="abrirModalDetallePedido({{ $pedido->id }})" class="btn-cyan text-xs">
                Ver
              </button>
              @if($pedido->estado_pedido === 0)
              <button wire:click="confirmarEliminarPedido({{ $pedido->id }})" class="btn-cyan text-xs">
                Eliminar
              </button>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="text-center py-4 text-gray-600">
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
        @if(auth()->user()->rol_id != 3)
        <div class="sm:w-full w-auto">
          <label class="text-sm font-semibold text-gray-700">Solicitud de Pedido</label>

          @if($solicitud_pedido_id)
          @php
          $solicitud = $solicitudPedidos->firstWhere('id', $solicitud_pedido_id);
          $detallesSolicitud = $solicitud?->detalles ?? collect();
          @endphp

          <div class="border border-gray-300 rounded-md p-2 bg-gray-50 text-xs sm:text-sm">
            <div class="flex justify-between items-center mb-1">
              <span class="font-bold text-gray-900 truncate">{{ $solicitud->codigo }}</span>
              <button wire:click="quitarSolicitud" class="px-2 py-1 text-white bg-cyan-500 rounded hover:bg-cyan-600 text-xs">Quitar</button>
            </div>

            <div class="flex justify-between items-center">
              <span class="text-cyan-600 font-semibold truncate">{{ $solicitud->cliente->nombre ?? 'Sin cliente' }}</span>
              <span class="text-gray-500 text-[10px] ml-2">{{ $solicitud->created_at->format('d/m/Y H:i') }}</span>
            </div>

            <div class="mt-2 space-y-1">
              @foreach($detallesSolicitud as $detalle)
              @php
              $item = $detalle->producto ?? $detalle->otro;
              $nombreCompleto = $item->descripcion ?? 'Sin descripción';
              $unidadPaq = $item->paquete ?? 1;
              $tipoContenido = $item->tipoContenido ?? null;
              $totalUnidadesDetalle = $detalle->cantidad * $unidadPaq;
              $tiposItems = collect([
              'Producto' => $detalle->producto,
              'Otro' => $detalle->otro,
              'Tapa' => $detalle->tapa,
              'Etiqueta' => $detalle->etiqueta
              ])->filter();
              @endphp

              <div class="flex flex-col border-b border-gray-200 py-1">
                <div class="flex justify-between items-center">
                  <div class="flex-1 truncate">
                    <span class="font-medium text-gray-900 truncate">{{ $nombreCompleto }}</span>

                    @if($tipoContenido)
                    <span class="block text-indigo-600 text-[10px] truncate">({{ $tipoContenido }})</span>
                    @endif
                  </div>

                  <div class=" text-cyan text-[10px]">
                    <div>{{ $detalle->cantidad }} Paquete(s)</div>
                    <div>{{ $totalUnidadesDetalle }} Unidad(es)</div>
                  </div>
                </div>
                <div class="mt-1 text-[9px] text-gray-700 flex flex-wrap gap-1">
                  @foreach($tiposItems as $tipo => $obj)
                  <span class="inline-block bg-gray-200 px-1 rounded">
                    {{ $tipo }}: {{ $obj->descripcion ?? class_basename($obj) }}
                  </span>
                  @endforeach
                  @if($unidadPaq > 1)
                  <span class="bg-gray-200 px-1 rounded">{{ $unidadPaq }} unidad/paquete</span>
                  @endif
                </div>
              </div>
              @endforeach
            </div>
          </div>

          @else
          <div class="border border-gray-300 rounded-md p-2 bg-white max-h-60 overflow-y-auto text-xs sm:text-sm">
            @forelse($solicitudPedidos as $solicitud)
            @php
            $detallesSolicitud = $solicitud->detalles;
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
                $nombreCompleto = $item->descripcion ?? 'Sin descripción';
                $unidadPaq = $item->paquete ?? 1;
                $totalUnidadesDetalle = $detalle->cantidad * $unidadPaq;


                @endphp

                <div class="flex flex-col">
                  <div class="flex justify-between items-center text-[10px]">
                    <div class="flex-1 truncate">{{ $nombreCompleto }}</div>
                    <div class="text-right">{{ $detalle->cantidad }} Paquetes / {{ $totalUnidadesDetalle }} Unidades</div>
                  </div>
                  <div class="mt-1 text-[9px] text-gray-700 flex flex-wrap gap-1">
                    @if($unidadPaq > 1)
                    <span class="bg-gray-200 px-1 rounded">{{ $unidadPaq }} unidad/paquete</span>
                    @endif
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
        @endif

        @if(!$solicitud_pedido_id)
        <div class="mb-6">
          <label class="font-semibold text-sm mb-2 block">Cliente (Requerido)</label>
          <input
            type="text"
            wire:model.live="searchCliente"
            placeholder="Buscar cliente..."
            class="input-minimal w-full mb-2" />
          <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 max-h-[200px] overflow-y-auto">
            <button type="button" wire:click="$set('cliente_id', null)"
              class="w-full px-3 py-3 rounded-lg border-2 text-left transition-all
            {{ is_null($cliente_id)
                ? 'bg-cyan-500 text-white border-cyan-600 shadow-lg'
                : 'bg-gray-50 text-gray-800 border-gray-300 hover:bg-cyan-50 hover:border-cyan-500 hover:text-cyan-600' }}">
              <p class="font-semibold text-sm">Sin cliente</p>
            </button>

            @forelse($clientes as $cliente)
            <button type="button"
              wire:click="$set('cliente_id', {{ $cliente->id }})"
              class="w-full px-3 py-3 rounded-lg border-2 text-left transition-all
        {{ $cliente_id == $cliente->id
            ? 'border-cyan-700'
            : 'border-gray-300 hover:border-cyan-500' }}">

              <p class="text-sm font-semibold text-cyan-700 truncate">
                {{ $cliente->nombre }}
                <span class="font-normal">
                  {{ $cliente->telefono ?? 'Sin teléfono' }}
                </span>
              </p>
            </button>

            @empty
            <p class="text-center text-gray-500 py-3 text-sm">
              No hay clientes disponibles
            </p>
            @endforelse
          </div>

          @error('cliente_id')
          <span class="error-message text-cyan-500 text-xs">{{ $message }}</span>
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


        @if(auth()->user()->rol_id === 1)
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

            CERRAR
          </button>
          <button wire:click="guardarPedido" class="btn-cyan">

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

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

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
              <span class="badge-info">{{ $pedidoDetalle->solicitudPedido->cliente->departamento_localidad ?? '-' }}</span>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
              <span class="label-info">Personal de atención:</span>
              <span class="badge-info">{{ $pedidoDetalle->personal->nombres ?? '-' }}</span>
            </div>
          </div>

          <div class="flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
              <span class="label-info">Fecha del pedido:</span>
              <span class="badge-info">{{ \Carbon\Carbon::parse($pedidoDetalle->fecha_pedido)->format('d M Y, H:i') }}</span>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
              <span class="label-info">Estado:</span>
              <span class="inline-block px-2 py-1 rounded-full text-sm font-semibold
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
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-2">
            <div class="flex flex-col gap-1">
              <span class="font-medium">{{ $producto->descripcion ?? 'Sin nombre' }}</span>
              <span class="text-sm text-gray-600">Precio unitario: {{ number_format($precioUnitario, 2) }} BS</span>
              <span class="text-sm text-gray-600">Total: {{ number_format($totalProducto, 2) }} BS</span>
              <span class="text-sm text-gray-500">Sucursal: {{ $sucursal->nombre ?? 'N/A' }}</span>
              <span class="text-sm text-gray-500">Cantidad: {{ $detalle->cantidad }}</span>
            </div>
          </div>
          @endforeach
        </div>

        <div class="mt-4 flex justify-end gap-2">
          <span class="font-semibold text-lg">Total productos:</span>
          <span class="font-semibold text-lg">{{ number_format($totalProductos, 2) }} BS</span>
        </div>

        <div>
          <span class="label-info block mb-1">Observaciones:</span>
          <div class="bg-gray-100 rounded p-2 text-sm text-gray-700">
            {{ $pedidoDetalle->observaciones ?? 'Sin observaciones' }}
          </div>
        </div>

      </div>

      <div class="modal-footer mt-4">
        <button wire:click="$set('modalDetallePedido', false)" class="btn-cyan w-full sm:w-auto" title="Cerrar">
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
      <div class="modal-footer">
        <button type="button" wire:click="eliminarPedidoConfirmado" class="btn-cyan">
          Confirmar
        </button>
        <button type="button" wire:click="$set('modalEliminarPedido', false)" class="btn-cyan">
          Cancelar
        </button>
      </div>

    </div>
  </div>
  @endif

  @if($modalResumenExistencias)
  <div class="modal-overlay">
    <div class="modal-box">

      <div class="mb-4">
        <h3 class="font-semibold text-lg">Para Producción</h3>
      </div>

      @php
      $resumenExistencias = collect();

      foreach ($solicitudPedidos as $solicitud) {
      foreach ($solicitud->detalles as $detalle) {
      $item = $detalle->producto ?? $detalle->otro;
      if (!$item) continue;

      $key = ($detalle->producto ? 'prod_' : 'otro_') . $item->id;

      $unidadPaq = $item->paquete ?? 1;
      $paquetes = $detalle->cantidad;
      $unidades = $paquetes * $unidadPaq;

      if (!$resumenExistencias->has($key)) {
      $resumenExistencias->put($key, [
      'nombre' => $item->descripcion,
      'unidadPaq' => $unidadPaq,
      'paquetes' => 0,
      'unidades' => 0,
      'tapEtiq' => [],
      ]);
      }

      $producto = $resumenExistencias->get($key);
      $producto['paquetes'] += $paquetes;
      $producto['unidades'] += $unidades;

      if ($detalle->tapa && $detalle->etiqueta) {
      $combo = $detalle->tapa->descripcion . ' + ' . $detalle->etiqueta->descripcion;
      $producto['tapEtiq'][$combo] = ($producto['tapEtiq'][$combo] ?? 0) + $unidades;
      }

      $resumenExistencias->put($key, $producto);
      }
      }
      @endphp

      @if($resumenExistencias->count())
      <table class="w-full border border-gray-200 text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="border px-2 py-1 text-left">Producto</th>
            <th class="border px-2 py-1 text-center">Paquetes</th>
            <th class="border px-2 py-1 text-center">Unidades</th>
            <th class="border px-2 py-1 text-left">Tapa + Etiqueta</th>
          </tr>
        </thead>
        <tbody>
          @foreach($resumenExistencias as $existencia)
          <tr class="border-b">
            <td class="px-2 py-1">{{ $existencia['nombre'] }}</td>
            <td class="px-2 py-1 text-center">{{ $existencia['paquetes'] }}</td>
            <td class="px-2 py-1 text-center">{{ $existencia['unidades'] }}</td>
            <td class="px-2 py-1">
              @foreach($existencia['tapEtiq'] as $combo => $cant)
              {{ $combo }} → {{ $cant }}<br>
              @endforeach
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
      <p class="text-center text-gray-500 py-4">No hay existencias en las solicitudes seleccionadas.</p>
      @endif

      <div class="mt-4 flex justify-end">
        <button wire:click="$toggle('modalResumenExistencias')" class="px-4 py-2 bg-cyan-500 text-white rounded hover:bg-cyan-600">Cerrar</button>
      </div>

    </div>
  </div>
  @endif
</div>
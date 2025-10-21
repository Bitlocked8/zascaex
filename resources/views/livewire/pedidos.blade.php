<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <h3 class="inline-block bg-teal-700 text-white px-5 py-2 rounded-full text-xl font-bold uppercase shadow-md">
      Pedidos
    </h3>

    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input
        type="text"
        wire:model.live="search"
        placeholder="Buscar por código, cliente o personal..."
        class="input-minimal w-full" />
      <button wire:click="$set('modalPedido', true)" class="btn-cyan">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
          <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
        </svg>
        añadir
      </button>
    </div>

    @forelse($pedidos as $pedido)
    <div class="card-teal">
      <div class="col-span-8 flex flex-col gap-1">
        <p><strong>Código:</strong> {{ $pedido->codigo }}</p>
        <p><strong>Cliente:</strong> {{ $pedido->cliente->nombre ?? 'N/A' }}</p>
        <p><strong>Personal:</strong> {{ $pedido->personal->nombres ?? 'N/A' }}</p>
        <p><strong>Estado:</strong>
          <span class="inline-block px-2 py-1 rounded-full text-sm font-semibold 
            {{ $pedido->estado_pedido == 0 ? 'bg-cyan-600 text-white' : ($pedido->estado_pedido == 1 ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white') }}">
            {{ $pedido->estado_pedido == 0 ? 'Pendiente' : ($pedido->estado_pedido == 1 ? 'Entregado' : 'Cancelado') }}
          </span>
        </p>
        <p><strong>Productos:</strong> {{ $pedido->detalles->count() }}</p>
      </div>

      <div class="flex flex-col gap-3 w-auto items-start">
        <button wire:click="editarPedido({{ $pedido->id }})" class="btn-cyan" title="Editar">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
            <path d="M16 5l3 3" />
          </svg>
          editar
        </button>
        <button wire:click="abrirModalPagosPedido({{ $pedido->id }})" class="btn-cyan" title="Ver pagos">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 19h-6a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v4.5" />
            <path d="M3 10h18" />
            <path d="M16 19h6" />
            <path d="M19 16l3 3l-3 3" />
            <path d="M7.005 15h.005" />
            <path d="M11 15h2" />
          </svg>
          pagos
        </button>
        <button wire:click="abrirModalDetallePedido({{ $pedido->id }})"
          class="btn-cyan" title="Ver Detalle del Pedido">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 2l.117 .007a1 1 0 0 1 .876 .876l.007 .117v4l.005 .15a2 2 0 0 0 1.838 1.844l.157 .006h4l.117 .007a1 1 0 0 1 .876 .876l.007 .117v9a3 3 0 0 1 -2.824 2.995l-.176 .005h-10a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-14a3 3 0 0 1 2.824 -2.995l.176 -.005zm3 14h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m0 -4h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2" />
            <path d="M19 7h-4l-.001 -4.001z" />
          </svg>
          detalle
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
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-4">
          <div>
            <span class="text-u">
              Personal de atención: {{ $pedido->personal->nombres ?? 'Sin asignar' }}
            </span>
          </div>
          <div>
            <span class="text-u">
              Fecha del pedido: {{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') }}
            </span>
          </div>
          <div class="text-center">
            <label class="font-semibold text-sm mb-2 block">Estado del Pedido</label>
            <div class="flex justify-center gap-3">
              <button
                type="button"
                wire:click="$set('estado_pedido', 0)"
                class="btn-cyan flex items-center gap-1 {{ $estado_pedido == 0 ? 'ring-2 ring-cyan-200' : 'opacity-40' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  fill="none" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                  <circle cx="12" cy="12" r="10" />
                  <polyline points="12 6 12 12 16 14" />
                </svg>
                Pendiente
              </button>
              <button
                type="button"
                wire:click="$set('estado_pedido', 1)"
                class="btn-cyan flex items-center gap-1 {{ $estado_pedido == 1 ? 'ring-2 ring-cyan-200' : 'opacity-40' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  fill="none" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                  <path d="M5 13l4 4L19 7" />
                </svg>
                Entregado
              </button>
              <button
                type="button"
                wire:click="$set('estado_pedido', 2)"
                class="btn-cyan flex items-center gap-1 {{ $estado_pedido == 2 ? 'ring-2 ring-cyan-200' : 'opacity-40' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  fill="none" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                  <path d="M18 6L6 18" />
                  <path d="M6 6l12 12" />
                </svg>
                Cancelado
              </button>
            </div>
          </div>


          <div class="grid grid-cols-1 gap-2 mt-2">
            <div>
              <label class="font-semibold text-sm mb-2 block">Cliente</label>
              <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 max-h-[150px] overflow-y-auto">

                @foreach(\App\Models\Cliente::all() as $cliente)
                <button type="button"
                  wire:click="$set('cliente_id', {{ $cliente->id }})"
                  class="w-full px-3 py-2 rounded-md border text-sm text-left transition
                   {{ $cliente_id == $cliente->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-teal-100' }}">
                  {{ $cliente->nombre }}
                </button>
                @endforeach

              </div>
              @error('cliente_id') <span class="error-message">{{ $message }}</span> @enderror
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-6">
          <div>
            <div class="grid grid-cols-1 gap-2 mt-2">
              <div>
                <label class="font-semibold text-sm mb-2 block">Producto</label>
                <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">

                  @foreach($productos as $producto)
                  @php
                  $cantidadesPorSucursal = [];
                  foreach ($producto->existencias as $existencia) {
                  $totalExistencia = $existencia->reposiciones->sum('cantidad');
                  if ($totalExistencia > 0) {
                  $sucursalNombre = $existencia->sucursal->nombre ?? 'Sin sucursal';
                  $cantidadesPorSucursal[$sucursalNombre] = ($cantidadesPorSucursal[$sucursalNombre] ?? 0) + $totalExistencia;
                  }
                  }
                  @endphp

                  <button type="button"
                    wire:click="$set('productoSeleccionado', {{ $producto->id }})"
                    class="w-full px-3 py-2 rounded-md border text-sm text-left flex justify-between items-center transition
                    {{ $productoSeleccionado == $producto->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                    <div class="flex flex-col">
                      <span>{{ $producto->descripcion ?? 'Producto #' . $producto->id }}</span>
                      @foreach($cantidadesPorSucursal as $sucursal => $cantidad)
                      <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                        {{ $sucursal }}: {{ $cantidad }} uds
                      </span>
                      @endforeach
                    </div>
                    <span class="flex items-center gap-2 uppercase">
                      <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                        {{ $producto->precioReferencia ?? 'sin precio' }} BS
                      </span>
                    </span>
                  </button>
                  @endforeach

                </div>
              </div>

            </div>


          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
          <div>
            <label class="font-semibold text-sm mb-1 block">Cantidad</label>
            <input type="number" wire:model="cantidadSeleccionada" class="input-minimal w-full" min="1" />
          </div>

          <div class="flex items-end">
            <button wire:click="agregarProducto" class="btn-circle btn-cyan w-full">
              Agregar
            </button>
          </div>
        </div>

        <div class="mb-6">
          <h4 class="font-semibold mb-2">Productos agregados</h4>

          @if(count($detalles))
          <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
            <div class="grid grid-cols-3 bg-cyan-600 text-white font-semibold text-sm px-4 py-2">
              <span>Nombre</span>
              <span class="text-center">Cantidad</span>
              <span class="text-right">Acción</span>
            </div>

            <div class="max-h-64 overflow-y-auto divide-y divide-gray-200">
              @foreach($detalles as $index => $detalle)
              @if(!isset($detalle['eliminar']))
              <div class="grid grid-cols-3 items-center px-4 py-2 text-sm bg-gray-50 hover:bg-gray-100 transition">
                <span class="truncate font-medium text-gray-800">{{ $detalle['nombre'] }}</span>
                <span class="text-center text-gray-700">
                  {{ fmod($detalle['cantidad'], 1) == 0 ? intval($detalle['cantidad']) : number_format($detalle['cantidad'], 2) }}
                </span>

                <div class="text-right">
                  <button wire:click="eliminarDetalle({{ $index }})"
                    class="p-1.5 rounded-full bg-red-500 hover:bg-red-600 text-white shadow transition"
                    title="Eliminar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M4 7h16" />
                      <path d="M10 11v6" />
                      <path d="M14 11v6" />
                      <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                      <path d="M9 7V4a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg>
                  </button>
                </div>
              </div>
              @endif
              @endforeach
            </div>
          </div>
          @else
          <p class="text-gray-500 text-sm italic">No hay productos agregados.</p>
          @endif
        </div>



        <div class="modal-footer">
          <button wire:click="cerrarModal" class="btn-cyan" title="Cerrar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path d="M10 10l4 4m0 -4l-4 4" />
              <circle cx="12" cy="12" r="9" />
            </svg>
            CERRAR
          </button>
          <button wire:click="guardarPedido" class="btn-cyan">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor"
                  stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                <label class="font-semibold text-sm">Monto</label>
                <input type="number" wire:model="pagos.{{ $index }}.monto" class="input-minimal" min="0">
              </div>

              <div class="sm:col-span-2">
                <label class="font-semibold text-sm">Método</label>
                <input type="text" wire:model="pagos.{{ $index }}.metodo" class="input-minimal">
              </div>

              <div class="sm:col-span-2">
                <label class="font-semibold text-sm">Referencia</label>
                <input type="text" wire:model="pagos.{{ $index }}.referencia" class="input-minimal">
              </div>

              <div class="sm:col-span-2">
                <label class="font-semibold text-sm">Observaciones</label>
                <input type="text" wire:model="pagos.{{ $index }}.observaciones" class="input-minimal">
              </div>

              <div class="sm:col-span-2">
                <label class="font-semibold text-sm">Imagen</label>
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
                  <img src="{{ $imagenUrl }}"
                    alt="Imagen"
                    class="w-80 h-80 object-cover rounded-lg shadow cursor-pointer"
                    wire:click="$set('imagenPreviewModal', '{{ $imagenUrl }}'); $set('modalImagenAbierta', true)">
                  @if(is_string($pagos[$index]['imagen_comprobante']))
                  <a href="{{ $imagenUrl }}" download class="btn-circle btn-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
          <button type="button" wire:click="agregarPagoPedido" class="btn-circle btn-cyan">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
              <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
            </svg></button>

          <button type="button" wire:click="guardarPagosPedido" class="btn-circle btn-cyan">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
              <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
              <path d="M14 4l0 4l-6 0l0 -4" />
            </svg></button>
          <button type="button" wire:click="$set('modalPagos', false)" class="btn-circle btn-cyan" title="Cerrar">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
              fill="none" viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" />
              <path d="M10 10l4 4m0 -4l-4 4" />
              <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
            </svg></button>
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
              <span class="inline-block px-2 py-1 rounded-full text-sm font-semibold 
    {{ $pedidoDetalle->estado_pedido == 0 
        ? 'bg-cyan-600 text-white' 
        : ($pedidoDetalle->estado_pedido == 1 
            ? 'bg-emerald-600 text-white' 
            : 'bg-red-600 text-white') }}">
                {{ $pedidoDetalle->estado_pedido == 0 
        ? 'Pendiente' 
        : ($pedidoDetalle->estado_pedido == 1 
            ? 'Entregado' 
            : 'Cancelado') }}
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

          // Sumamos al total general
          $totalGeneral += $totalProducto;
          @endphp
          <div class="flex flex-col md:flex-row justify-between items-start md:items-center py-2">
            <div class="flex flex-col gap-1">
              <span class="font-medium">{{ $producto->descripcion ?? 'Sin nombre' }}</span>
              <span class="font-medium">Precio unitario: {{ number_format($precioUnitario, 2) }} BS</span>
              <span class="font-medium">Total: {{ number_format($totalProducto, 2) }} BS</span>
              <span class="text-sm text-gray-500">Sucursal: {{ $sucursal->nombre ?? 'N/A' }}</span>
              @if(isset($producto->imagen))
              <img src="{{ asset('storage/'.$producto->imagen) }}" class="w-12 h-12 object-cover rounded shadow" alt="Imagen Producto">
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
              <img src="{{ asset('storage/'.$pago->imagen_comprobante) }}"
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
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M10 10l4 4m0 -4l-4 4" />
            <circle cx="12" cy="12" r="9" />
          </svg>
          CERRAR
        </button>
      </div>

    </div>
  </div>
  @endif

</div>
</div>
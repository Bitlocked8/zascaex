<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input
        type="text"
        wire:model.live="searchCodigo"
        placeholder="Buscar por código..."
        class="input-minimal w-full" />
      <button wire:click="abrirModal" class="btn-circle btn-cyan">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M12 4v16m8-8H4" />
        </svg>
      </button>
    </div>

    @forelse($pedidos as $pedido)
    <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
      <div class="flex flex-col col-span-9 space-y-1 text-left">
        <p><strong>Código:</strong> {{ $pedido->codigo ?? 'N/A' }}</p>
        <p><strong>Personal:</strong> {{ optional($pedido->personal)->nombres ?? 'Sin personal' }} {{ optional($pedido->personal)->apellidos ?? '' }}</p>
        <p><strong>Empresa:</strong> {{ $pedido->cliente->empresa ?? 'N/A' }}</p>
        <p><strong>Cliente:</strong> {{ $pedido->cliente->nombre ?? 'N/A' }}</p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') }}</p>
        <p><strong>Observaciones:</strong> {{ $pedido->observaciones ?? 'N/A' }}</p>
        <p><strong>Productos:</strong></p>

        @php $montoTotal = 0; @endphp
        <ul class="ml-4 list-disc">
          @foreach($pedido->detalles as $detalle)
          @php
          $existencia = $detalle->existencia;
          $producto = $existencia?->existenciable;
          $descripcion = $producto?->descripcion ?? 'N/A';
          $precioUnitario = $producto?->precioReferencia ?? 0;
          $cantidad = $detalle->cantidad ?? 0;
          $subtotal = $precioUnitario * $cantidad;
          $montoTotal += $subtotal;
          @endphp
          <li>
            {{ $descripcion }} - Cantidad: {{ intval($cantidad) }} - Precio unit.: {{ intval($precioUnitario) }} Bs - Subtotal: {{ intval($subtotal) }} Bs
          </li>
          @endforeach
        </ul>

        <span class="inline-block bg-cyan-700 text-white px-3 py-1 rounded-full text-sm font-semibold uppercase mt-1">
          Monto total: {{ intval($montoTotal) }} Bs
        </span>

      </div>

      <div class="flex flex-col items-end gap-4 col-span-3">
        <button wire:click="abrirModal('edit', {{ $pedido->id }})" class="btn-circle btn-cyan">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke="none" d="M0 0h24v24H0z" />
            <path d="M4 10a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
            <path d="M6 4v4" />
            <path d="M6 12v8" />
            <path d="M13.199 14.399a2 2 0 1 0 -1.199 3.601" />
            <path d="M12 4v10" />
            <path d="M12 18v2" />
            <path d="M16 7a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
            <path d="M18 4v1" />
            <path d="M18 9v2.5" />
            <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
            <path d="M19.001 15.5v1.5" />
            <path d="M19.001 21v1.5" />
            <path d="M22.032 17.25l-1.299 .75" />
            <path d="M17.27 20l-1.3 .75" />
            <path d="M15.97 17.25l1.3 .75" />
            <path d="M20.733 20l1.3 .75" />
          </svg>
        </button>

        <button wire:click="modaldetalle({{ $pedido->id }})" class="btn-circle btn-cyan" title="Ver Detalle">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9h.01" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 12h1v4h1" />
          </svg>
        </button>

        <button wire:click="confirmarEliminarPedido({{ $pedido->id }})" class="btn-circle btn-cyan"
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
    </div>
    @empty
    <div class="col-span-full text-center py-4 text-gray-600">
      No hay pedidos registrados.
    </div>
    @endforelse
  </div>



  @if($modal)
  <div class="modal-overlay">
    <div class="modal-box w-full max-w-2xl">
      <div class="modal-content flex flex-col gap-4">
        @if ($modalError)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
          <strong class="font-bold">¡Error!</strong>
          <span class="block sm:inline">{{ $mensajeError }}</span>
        </div>
        @endif
        <div class="grid grid-cols-1 gap-2 mt-2">
          <p class="font-semibold text-sm">
            Código: <span class="font-normal">{{ $codigo }}</span>
          </p>
          <p class="font-semibold text-sm">
            Personal:
<span class="font-normal">
    {{ $personal_nombres ?? 'Sin personal' }}
    {{ $personal_apellidos ?? '' }}
</span>

          </p>

          <p class="font-semibold text-sm">
            Fecha:
            <span class="font-normal">{{ \Carbon\Carbon::parse($fecha_pedido)->format('d/m/Y H:i') }}</span>
          </p>

          <div>
            <label class="font-semibold text-sm mb-1 block">Cliente</label>
            <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
              @foreach($clientes as $cliente)
              <button type="button"
                wire:click="$set('cliente_id', {{ $cliente->id }})"
                class="w-full px-3 py-2 rounded-md border text-sm text-left flex justify-between items-center transition
        {{ $cliente_id == $cliente->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">

                <span class="font-semibold">{{ $cliente->nombre }}</span>
                <span class="text-xs bg-gray-600 text-white px-2 py-1 rounded-full">
                  contacto: {{ $cliente->telefono }}
                </span>
              </button>
              @endforeach
            </div>
          </div>

          <div>
            <label class="font-semibold text-sm mb-2 block">Productos del pedido</label>

            <div class="flex flex-col gap-3">
              @foreach ($productos as $index => $producto)
              <div class="border border-gray-300 rounded-md shadow-sm p-3 bg-white">
                <div class="flex justify-between items-center mb-2">
                  <h3 class="font-semibold text-gray-700">Producto #{{ $index + 1 }}</h3>
                  <button type="button"
                    wire:click="{{ isset($producto['detalle_id']) ? 'eliminarProductoExistente(' . $producto['detalle_id'] . ')' : 'eliminarProducto(' . $index . ')' }}"
                    class="text-red-600 text-sm font-semibold hover:underline">
                    Eliminar
                  </button>

                </div>
                <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                  @foreach($existencias as $existencia)
                  @php
                  $tipo = class_basename($existencia->existenciable_type);
                  $cantidadDisponible = $existencia->reposiciones->where('estado_revision', true)->sum('cantidad');
                  @endphp
                  <button type="button"
                    wire:click="$set('productos.{{ $index }}.existencia_id', {{ $existencia->id }})"
                    class="w-full px-3 py-2 rounded-md border text-sm text-left flex justify-between items-center transition
                            {{ $producto['existencia_id'] == $existencia->id ? 'bg-cyan-600 text-white border-cyan-700' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                    <span>{{ $tipo }}: {{ $existencia->existenciable->descripcion ?? 'Existencia #' . $existencia->id }}</span>
                    <span class="flex items-center gap-2">
                      <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                        Disponible: {{ $cantidadDisponible }}
                      </span>
                      <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                        {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}
                      </span>
                    </span>
                  </button>
                  @endforeach
                </div>

                <div class="mt-2">
                  <label class="text-sm text-gray-700 font-semibold">Cantidad:</label>
                  <input type="number" min="1"
                    wire:model="productos.{{ $index }}.cantidad"
                    class="input-minimal w-full"
                    placeholder="Ingrese cantidad">
                </div>
              </div>
              @endforeach
              <button type="button"
                wire:click="agregarProducto"
                class="self-start mt-3 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-md hover:bg-green-700 transition">
                + Agregar producto
              </button>
            </div>

          </div>
          <div>
            <label class="font-semibold text-sm">Observaciones</label>
            <input type="text" wire:model="observaciones" class="input-minimal w-full" placeholder="Notas del pedido">
          </div>
          <div>
            <label class="font-semibold text-sm mb-1 block">Estado del pedido</label>
            <select wire:model="estado_pedido" class="input-minimal w-full">
              <option value="0">Pendiente</option>
              <option value="1">Entregado</option>
              <option value="2">Cancelado</option>
            </select>
          </div>

          <div class="modal-footer">
            <button type="button" wire:click="guardarPedido" class="btn-circle btn-cyan" title="Guardar">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M14 4l0 4l-6 0l0 -4" />
              </svg>
            </button>
            <button type="button" wire:click="cerrarModal" class="btn-circle btn-cyan" title="Cerrar">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" />
                <path d="M10 10l4 4m0 -4l-4 4" />
                <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

  @if($confirmingDeletePedidoId)
  <div class="modal-overlay">
    <div class="modal-box">
      <div class="modal-content text-center">
        <h2 class="text-lg font-semibold">¿Eliminar pedido?</h2>
        <p>Se eliminará todo el pedido y se revertirán los cambios en stock.</p>
      </div>
      <div class="modal-footer flex justify-center gap-4 mt-4">
        <button wire:click="eliminarPedidoConfirmado" class="btn-circle btn-cyan">✔</button>
        <button wire:click="$set('confirmingDeletePedidoId', null)" class="btn-circle btn-red">✖</button>
      </div>
    </div>
  </div>
  @endif






</div>
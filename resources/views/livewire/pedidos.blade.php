<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <h3 class="col-span-full text-center bg-cyan-700 text-white px-6 py-3 rounded-full text-3xl font-bold uppercase shadow-md">
      Pedidos
    </h3>

    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input
        type="text"
        wire:model.live="search"
        placeholder="Buscar por código, cliente o personal..."
        class="input-minimal w-full" />
      <button wire:click="$set('modalPedido', true)" class="btn-circle btn-cyan">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
          <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
        </svg>
      </button>
    </div>

    @forelse($pedidos as $pedido)
    <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
      <div class="col-span-8 flex flex-col gap-1">
        <p><strong>Código:</strong> {{ $pedido->codigo }}</p>
        <p><strong>Cliente:</strong> {{ $pedido->cliente->nombre ?? 'N/A' }}</p>
        <p><strong>Personal:</strong> {{ $pedido->personal->nombre ?? 'N/A' }}</p>
        <p><strong>Estado:</strong>
          <span class="inline-block px-2 py-1 rounded-full text-sm font-semibold 
            {{ $pedido->estado_pedido == 0 ? 'bg-yellow-400 text-white' : ($pedido->estado_pedido == 1 ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white') }}">
            {{ $pedido->estado_pedido == 0 ? 'Pendiente' : ($pedido->estado_pedido == 1 ? 'Entregado' : 'Cancelado') }}
          </span>
        </p>
        <p><strong>Productos:</strong> {{ $pedido->detalles->count() }}</p>
      </div>

      <div class="flex flex-col items-end gap-4 col-span-3">
        <button wire:click="editarPedido({{ $pedido->id }})" class="btn-circle btn-cyan">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
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
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-6">
          <div>
            <label class="font-semibold text-sm mb-1 block">Estado del Pedido</label>
            <div class="flex flex-wrap justify-center gap-2 mt-2">
              @foreach([
              0 => 'Pendiente',
              1 => 'Entregado',
              2 => 'Cancelado'
              ] as $key => $label)
              <button type="button" wire:click="$set('estado_pedido', {{ $key }})"
                class="px-4 py-2 rounded-full text-sm flex items-center justify-center
        {{ $estado_pedido == $key ? 
          ($key == 1 ? 'bg-emerald-600 text-white' : ($key == 2 ? 'bg-red-600 text-white' : 'bg-yellow-400 text-white')) : 
          'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
                {{ $label }}
              </button>
              @endforeach
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
          {{ $cliente_id == $cliente->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                  {{ $cliente->nombre }}
                </button>
                @endforeach

              </div>
              @error('cliente_id') <span class="error-message">{{ $message }}</span> @enderror
            </div>
          </div>


          <div>
            <label class="font-semibold text-sm mb-1 block">Personal</label>
            <select wire:model="personal_id" class="input-minimal w-full">
              <option value="">-- Seleccionar Personal --</option>
              @foreach(\App\Models\Personal::all() as $personal)
              <option value="{{ $personal->id }}">{{ $personal->nombres }}</option>
              @endforeach
            </select>
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
                  $cantidadDisponible = 0;
                  foreach ($producto->existencias as $existencia) {
                  foreach ($existencia->reposiciones as $reposicion) {
                  $cantidadDisponible += $reposicion->cantidad;
                  }
                  }
                  @endphp

                  <button type="button"
                    wire:click="$set('productoSeleccionado', {{ $producto->id }})"
                    class="w-full px-3 py-2 rounded-md border text-sm text-left flex justify-between items-center transition
                      {{ $productoSeleccionado == $producto->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                    <span>{{ $producto->descripcion ?? 'Producto #' . $producto->id }}</span>
                    <span class="flex items-center gap-2 uppercase">
                      <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                        {{ $producto->codigo ?? 'Sin código' }}
                      </span>
                      <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                        {{ $cantidadDisponible }} uds
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
          <button wire:click="guardarPedido" class="btn-circle btn-cyan" title="Guardar">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
              <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
              <path d="M14 4l0 4l-6 0l0 -4" />
            </svg>
          </button>
          <button wire:click="cerrarModal" class="btn-circle btn-cyan" title="Cerrar">
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
  @endif



</div>
</div>
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
      <button wire:click="$set('modalPedido', true)" class="btn-circle btn-cyan" title="Crear Pedido">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
          viewBox="0 0 24 24" fill="currentColor">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M12 5v14m-7-7h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
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

      <div class="col-span-4 flex flex-col items-end gap-2">
        <button wire:click="editarPedido({{ $pedido->id }})" class="btn-circle btn-cyan" title="Editar Pedido">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 20h9" />
            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />
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

        <h3 class="text-lg font-bold mb-4 text-center">
          {{ $pedido->exists ? 'Editar Pedido' : 'Nuevo Pedido' }}
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
          <div>
            <label class="font-semibold text-sm mb-1 block">Cliente</label>
            <select wire:model="cliente_id" class="input-minimal w-full">
              <option value="">-- Seleccionar Cliente --</option>
              @foreach(\App\Models\Cliente::all() as $cliente)
              <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
              @endforeach
            </select>
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div>
            <label class="font-semibold text-sm mb-1 block">Producto</label>
            <select wire:model="productoSeleccionado" class="input-minimal w-full">
              <option value="">-- Seleccionar Producto --</option>
              @foreach($productos as $producto)
              <option value="{{ $producto->id }}">{{ $producto->descripcion }}</option>
              @endforeach
            </select>
          </div>

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
          <div class="grid grid-cols-1 gap-2 max-h-64 overflow-y-auto">
            @foreach($detalles as $index => $detalle)
            @if(!isset($detalle['eliminar']))
            <div class="flex justify-between items-center p-2 bg-gray-100 rounded">
              <span>{{ $detalle['nombre'] }} x {{ $detalle['cantidad'] }}</span>
              <button wire:click="eliminarDetalle({{ $index }})" class="btn-circle btn-red">&times;</button>
            </div>
            @endif
            @endforeach
          </div>
          @else
          <p class="text-gray-500">No hay productos agregados.</p>
          @endif
        </div>

        <div class="modal-footer">
          <button wire:click="guardarPedido" class="btn-circle btn-cyan">
            {{ $pedido->exists ? 'Actualizar Pedido' : 'Guardar Pedido' }}
          </button>
          <button wire:click="cerrarModal" class="btn-circle btn-red">
            Cancelar
          </button>
        </div>

      </div>
    </div>
  </div>
  @endif



</div>
</div>
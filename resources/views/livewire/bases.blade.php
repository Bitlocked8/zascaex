<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input type="text" wire:model.live="search"
        placeholder="Buscar por descripción u observación..." class="input-minimal w-full" />
      <button wire:click="abrirModal('create')" class="btn-circle btn-cyan">
        <!-- icono + -->
      </button>
    </div>

    @forelse($bases as $base)
    <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
      <div class="flex flex-col col-span-9 text-left space-y-1">
        <p><strong>Capacidad:</strong> {{ $base->capacidad }} ml</p>
        <p><strong>Nombre:</strong> {{ $base->descripcion ?? 'N/A' }}</p>
        <p><strong>Observaciones:</strong> {{ $base->observaciones ?? 'N/A' }}</p>
        <p><strong>Estado:</strong>
          @if($base->estado)
          <span class="text-white bg-green-600 px-2 py-1 rounded-full">Activa</span>
          @else
          <span class="text-white bg-red-600 px-2 py-1 rounded-full">Inactiva</span>
          @endif
        </p>

        <p><strong>Sucursales:</strong>
          @if($base->existencias->isEmpty())
          N/A
          @else
          @foreach($base->existencias as $existencia)
          <span class="inline-block bg-gray-200 text-gray-800 px-2 py-1 rounded mr-1 mb-1">
            {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}
            (Cantidad: {{ $existencia->cantidad }}, Mínima: {{ $existencia->cantidadMinima ?? 0 }})
          </span>
          @endforeach
          @endif
        </p>
      </div>

      <div class="flex flex-col items-end gap-4 col-span-3">
        <button wire:click="abrirModal('edit', {{ $base->id }})" class="btn-circle btn-cyan">
          <!-- icono editar -->
        </button>
        <button wire:click="modaldetalle({{ $base->id }})" class="btn-circle btn-cyan" title="Ver Detalle">
          <!-- icono ver detalle -->
        </button>
      </div>
    </div>
    @empty
    <div class="col-span-full text-center py-4 text-gray-600">
      No hay bases registradas.
    </div>
    @endforelse
  </div>




  @if($modal)
  <div class="modal-overlay">
    <div class="modal-box">
      <div class="modal-content">
        <div class="flex flex-col gap-4">
          <div>
            <label class="font-semibold text-sm mb-2 block">Imagen</label>
            <input type="file" wire:model="imagen" class="input-minimal">
            @if($imagen)
            <div class="mt-2 flex justify-center">
              <img src="{{ is_string($imagen) ? asset('storage/'.$imagen) : $imagen->temporaryUrl() }}"
                class="w-50 h-50 object-cover rounded"
                alt="Imagen Base">
            </div>
            @endif
            @error('imagen') <span class="error-message">{{ $message }}</span> @enderror
          </div>

          <div>
            <label class="font-semibold text-sm mb-1 block">Capacidad</label>
            <input type="number" wire:model="capacidad" class="input-minimal">
            @error('capacidad') <span class="error-message">{{ $message }}</span> @enderror
          </div>
          <div>
            <label class="font-semibold text-sm mb-1 block">Nombre</label>
            <input wire:model="descripcion" class="input-minimal" placeholder="Descripción"></input>
            @error('descripcion') <span class="error-message">{{ $message }}</span> @enderror
          </div>
          <div>
            <label class="font-semibold text-sm mb-1 block">Observaciones</label>
            <textarea wire:model="observaciones" class="input-minimal" placeholder="Observaciones"></textarea>
            @error('observaciones') <span class="error-message">{{ $message }}</span> @enderror
          </div>
          <div>
            <label class="font-semibold text-sm mb-1 block">Cantidad Mínima</label>
            <input type="number" wire:model="cantidadMinima" class="input-minimal" min="0" placeholder="Cantidad mínima">
            @error('cantidadMinima') <span class="error-message">{{ $message }}</span> @enderror
          </div>
          <div class="flex flex-wrap justify-center gap-2 mt-2">
            @foreach([1 => 'Activo', 0 => 'Inactivo'] as $key => $label)
            <button type="button" wire:click="$set('estado', {{ $key }})"
              class="px-4 py-2 rounded-full text-sm flex items-center justify-center
            {{ $estado == $key ? 'bg-cyan-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
              {{ $label }}
            </button>
            @endforeach
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" wire:click="guardar" class="btn-circle btn-cyan">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
            <path d="M14 4l0 4l-6 0l0 -4" />
          </svg>
        </button>
        <button type="button" wire:click="cerrarModal" class="btn-circle btn-cyan" title="Cerrar">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" />
            <path d="M10 10l4 4m0 -4l-4 4" />
            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
          </svg>
        </button>
      </div>
    </div>
  </div>
  @endif

  @if($modalDetalle)
  <div class="modal-overlay">
    <div class="modal-box">

      <div class="modal-content flex flex-col gap-6">

        <div class="flex justify-center items-center">
          @if($baseSeleccionada->imagen)
          <img src="{{ asset('storage/'.$baseSeleccionada->imagen) }}"
            class="w-50 h-50 object-cover rounded"
            alt="Imagen Base">
          @else
          <span class="badge-info">Sin imagen</span>
          @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
              <span class="label-info">Capacidad:</span>
              <span class="badge-info">{{ $baseSeleccionada->capacidad }} ml</span>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
              <span class="label-info">Estado:</span>
              <span class="badge-info">{{ $baseSeleccionada->estado ? 'Activo' : 'Inactivo' }}</span>
            </div>
          </div>
          <div class="flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row sm:items-start gap-2">
              <span class="label-info">Descripción:</span>
              <span class="badge-info">{{ $baseSeleccionada->descripcion ?? '-' }}</span>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-start gap-2">
              <span class="label-info">Observaciones:</span>
              <span class="badge-info">{{ $baseSeleccionada->observaciones ?? '-' }}</span>
            </div>
          </div>

        </div>
      </div>

      <div class="modal-footer">
        <button wire:click="$set('modalDetalle', false)" class="btn-circle btn-cyan" title="Cerrar">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" />
            <path d="M10 10l4 4m0 -4l-4 4" />
            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
          </svg>
        </button>
      </div>

    </div>
  </div>
  @endif
</div>
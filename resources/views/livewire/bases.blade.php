<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- Título -->
    <h3 class="col-span-full text-center bg-cyan-700 text-white px-6 py-3 rounded-full text-3xl font-bold uppercase shadow-md">
      Bases
    </h3>

    <!-- Buscador + botón nuevo -->
    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input
        type="text"
        wire:model.live="search"
        placeholder="Buscar por descripción o tipo..."
        class="input-minimal w-full" />
      <button wire:click="abrirModal('create')" class="btn-circle btn-cyan" title="Agregar base">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
          viewBox="0 0 24 24" fill="currentColor">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
          <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
        </svg>
      </button>
    </div>

    <!-- Lista de bases -->
    @forelse($bases as $base)
    <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">

      <!-- Imagen -->
      <div class="col-span-3 flex justify-center items-center">
        @if($base->imagen)
        <img src="{{ asset('storage/'.$base->imagen) }}" alt="Imagen de base"
          class="w-20 h-20 object-cover rounded-lg border border-cyan-300">
        @else
        <div class="w-20 h-20 flex items-center justify-center bg-gray-200 text-gray-500 rounded-lg text-xs">
          Sin imagen
        </div>
        @endif
      </div>

      <!-- Información -->
      <div class="flex flex-col col-span-6 items-center text-center space-y-1">
        <p>
          <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold uppercase {{ $base->estado ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white' }}">
            {{ $base->estado ? 'Activo' : 'Inactivo' }}
          </span>
        </p>

        <p class="text-cyan-600 text-2xl font-bold uppercase">
          {{ $base->descripcion ?? 'Sin descripción' }}
        </p>

        <p><strong>Tipo:</strong> {{ $base->tipo ?? 'N/A' }}</p>
        <p><strong>Capacidad:</strong> {{ $base->capacidad ? $base->capacidad.' ml' : 'N/A' }}</p>

        <p><strong>Sucursal:</strong>
          @if($base->existencias->isEmpty())
          <span class="inline-block bg-gray-400 text-white px-3 py-1 rounded-full text-sm font-semibold uppercase">
            N/A
          </span>
          @else
          @foreach($base->existencias as $existencia)
          <span class="inline-block bg-white text-cyan-600 px-3 py-1 rounded-full text-sm font-semibold uppercase">
            {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}
            ({{ $existencia->cantidad }} / mín: {{ $existencia->cantidadMinima ?? 0 }})
          </span>
          @endforeach
          @endif
        </p>
      </div>

      <!-- Botones -->
      <div class="flex flex-col items-end gap-4 col-span-3">
        <button wire:click="abrirModal('edit', {{ $base->id }})" class="btn-circle btn-cyan" title="Editar">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 20h9" />
            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />
          </svg>
        </button>

        <button wire:click="modaldetalle({{ $base->id }})" class="btn-circle btn-cyan" title="Ver Detalle">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M15 12a3 3 0 1 1 -6 0a3 3 0 0 1 6 0z" />
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
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
              <img
                src="{{ is_string($imagen) ? asset('storage/'.$imagen) : $imagen->temporaryUrl() }}"
                class="w-48 h-48 object-cover rounded shadow-md border border-cyan-300"
                alt="Imagen Base">
            </div>
            @endif

          </div>

          <div>
            <label class="font-semibold text-sm mb-1 block">Nombre</label>
            <input wire:model="descripcion" class="input-minimal" placeholder="Descripción">
          </div>

          <div>
            <label class="font-semibold text-sm mb-1 block">Tipo</label>
            <input wire:model="tipo" class="input-minimal" placeholder="Tipo de base">
          </div>

          <div>
            <label class="font-semibold text-sm mb-1 block">Capacidad</label>
            <input type="number" wire:model="capacidad" class="input-minimal" min="0">
          </div>

          <div>
            <label class="font-semibold text-sm mb-1 block">Observaciones</label>
            <textarea wire:model="observaciones" class="input-minimal" placeholder="Observaciones"></textarea>
          </div>

          <div>
            <label class="font-semibold text-sm mb-1 block">Cantidad Mínima</label>
            <input type="number" wire:model="cantidadMinima" class="input-minimal" min="0" placeholder="Cantidad mínima">
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

      <div class="modal-footer flex justify-center gap-2 mt-4">
        <button type="button" wire:click="guardar" class="btn-circle btn-cyan">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
            <path d="M14 4l0 4l-6 0l0 -4" />
          </svg>
        </button>

        <button type="button" wire:click="cerrarModal" class="btn-circle btn-cyan">
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

  @if($modalDetalle && $baseSeleccionada)
  <div class="modal-overlay">
    <div class="modal-box max-w-3xl">
      <div class="modal-content flex flex-col gap-6">

        <!-- Imagen -->
        <div class="flex justify-center items-center">
          @if($baseSeleccionada->imagen)
          <img src="{{ asset('storage/'.$baseSeleccionada->imagen) }}"
              class="w-full max-w-xl h-auto object-contain rounded-xl shadow-md"
            alt="Imagen Base">
          @else
          <span class="badge-info">Sin imagen</span>
          @endif
        </div>

        <!-- Información -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
          <div class="flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
              <span class="label-info">Descripción:</span>
              <span class="badge-info">{{ $baseSeleccionada->descripcion ?? '-' }}</span>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
              <span class="label-info">Tipo:</span>
              <span class="badge-info">{{ $baseSeleccionada->tipo ?? '-' }}</span>
            </div>
          </div>

          <div class="flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
              <span class="label-info">Capacidad:</span>
              <span class="badge-info">
                {{ $baseSeleccionada->capacidad ? $baseSeleccionada->capacidad.' ml' : '-' }}
              </span>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
              <span class="label-info">Estado:</span>
              <span class="badge-info {{ $baseSeleccionada->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $baseSeleccionada->estado ? 'Activo' : 'Inactivo' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Observaciones -->
        <div>
          <span class="label-info block mb-1">Observaciones:</span>
          <div class="bg-gray-100 rounded p-2 text-sm text-gray-700">
            {{ $baseSeleccionada->observaciones ?? 'Sin observaciones' }}
          </div>
        </div>
      </div>

      <!-- Footer / Botón cerrar -->
      <div class="modal-footer mt-6 flex justify-center">
        <button wire:click="$set('modalDetalle', false)" class="btn-circle btn-cyan" title="Cerrar">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" />
            <path d="M10 10l4 4m0 -4l-4 4" />
            <circle cx="12" cy="12" r="9" />
          </svg>
        </button>
      </div>
    </div>
  </div>
  @endif



</div>
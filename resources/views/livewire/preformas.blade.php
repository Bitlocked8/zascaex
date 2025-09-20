<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- Buscar + Crear Preforma -->
    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input type="text" wire:model.live="search" placeholder="Buscar por insumo o descripción..."
        class="flex-1 border rounded px-3 py-2" />

      <button wire:click="abrirModal('create')"
        class="bg-cyan-500 hover:bg-cyan-600 rounded-xl px-4 py-2 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="9" />
          <line x1="12" y1="9" x2="12" y2="15" />
          <line x1="9" y1="12" x2="15" y2="12" />
        </svg>
      </button>
    </div>

    @forelse($preformas as $preforma)
    <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
      <!-- Columna Izquierda: Foto + Info -->
      <div class="flex flex-col items-center md:items-start text-center md:text-left col-span-8">
        @if ($preforma->imagen)
        <img src="{{ asset('storage/' . $preforma->imagen) }}"
          alt="Imagen de {{ $preforma->insumo }}"
          class="w-56 h-56 object-cover rounded-lg shadow-md mb-3"
          loading="lazy">
        @else
        <div class="w-56 h-56 bg-gray-200 flex items-center justify-center rounded-lg shadow mb-3">
          <span class="text-gray-500">Sin imagen</span>
        </div>
        @endif



        <h3 class="text-lg font-semibold uppercase text-cyan-600">
          {{ $preforma->insumo }}
        </h3>
        <p class="text-cyan-950"><strong>Capacidad:</strong> {{ $preforma->capacidad }}</p>
        <p class="text-cyan-950"><strong>Color:</strong> {{ $preforma->color }}</p>

        <div class="mt-2">
          @if($preforma->estado)
          <span class="px-3 py-1 text-sm font-semibold text-white bg-green-600 rounded-full shadow">
            Activa
          </span>
          @else
          <span class="px-3 py-1 text-sm font-semibold text-white bg-red-600 rounded-full shadow">
            Inactiva
          </span>
          @endif
        </div>
      </div>

      <div class="flex flex-col items-center md:items-end gap-4 col-span-4">
        <!-- Editar Preforma -->
        <button wire:click="abrirModal('edit', {{ $preforma->id }})"
          class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600">
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

        <!-- Ver Detalle -->
        <button wire:click="modaldetalle({{ $preforma->id }})"
          class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-cyan-600" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9h.01" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 12h1v4h1" />
          </svg>
        </button>
      </div>
    </div>
    @empty
    <div class="col-span-full text-center py-4 text-gray-600">
      No hay preformas registradas.
    </div>
    @endforelse
  </div>

  <!-- Modal Crear/Editar Preforma -->
  @if($modal)
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white text-cyan-950 rounded-lg shadow-lg w-full max-w-4xl p-6 relative overflow-y-auto max-h-[90vh]">

      <h2 class="text-xl font-semibold mb-6 text-center">
        {{ $accion === 'create' ? 'Registrar Preforma' : 'Editar Preforma' }}
      </h2>

      <div class="grid grid-cols-12 gap-4">
        <!-- Columna 1: Campos -->
        <div class="col-span-12 md:col-span-6 flex flex-col gap-4">
          <input type="file" wire:model="imagen" class="input-minimal">
          @error('imagen') <span class="error-message">{{ $message }}</span> @enderror

          <input type="text" wire:model="insumo" placeholder="Insumo" class="input-minimal">
          @error('insumo') <span class="error-message">{{ $message }}</span> @enderror

          <input type="number" wire:model="capacidad" placeholder="Capacidad" class="input-minimal">
          @error('capacidad') <span class="error-message">{{ $message }}</span> @enderror

          <input type="text" wire:model="color" placeholder="Color" class="input-minimal">
          @error('color') <span class="error-message">{{ $message }}</span> @enderror

          <input type="text" wire:model="descripcion" placeholder="Descripción" class="input-minimal">
          @error('descripcion') <span class="error-message">{{ $message }}</span> @enderror

          <input type="text" wire:model="observaciones" placeholder="Observaciones" class="input-minimal">
          @error('observaciones') <span class="error-message">{{ $message }}</span> @enderror

          <select wire:model="estado" class="input-minimal">
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
          </select>
          @error('estado') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <!-- Columna 2: Vista previa de imagen -->
        <div class="col-span-12 md:col-span-6 flex items-center justify-center">
          @if(is_object($imagen))
          <img src="{{ $imagen->temporaryUrl() }}" alt="Vista previa" class="w-56 h-56 object-cover rounded-lg shadow">
          @elseif($preformaSeleccionada && $preformaSeleccionada->imagen)
          <img src="{{ asset('storage/' . $preformaSeleccionada->imagen) }}" alt="Imagen Preforma" class="w-56 h-56 object-cover rounded-lg shadow">
          @else
          <div class="w-56 h-56 bg-gray-200 flex items-center justify-center rounded-lg shadow">
            <span class="text-gray-500">Sin imagen</span>
          </div>
          @endif
        </div>

        <!-- Botones acción -->
        <div class="col-span-12 flex justify-center md:justify-end gap-4 mt-4 md:mt-0">
          <button type="button" wire:click="guardar"
            class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
            <!-- Icono guardar -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0v.001z" />
              <path d="M9 13h6" />
              <path d="M12 10v6" />
            </svg>
          </button>
          <button type="button" wire:click="cerrarModal"
            class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
            <!-- Icono cerrar -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M10 10l4 4m0 -4l-4 4" />
              <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
  @endif

  <!-- Modal de detalle para Preforma -->
  @if ($modalDetalle)
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white text-cyan-950 rounded-lg shadow-lg w-full max-w-3xl p-6 relative overflow-y-auto max-h-[90vh]">

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <dt class="text-sm font-semibold">Insumo</dt>
          <dd class="mt-1 text-sm">{{ $preformaSeleccionada->insumo ?? 'No disponible' }}</dd>
        </div>

        <div>
          <dt class="text-sm font-semibold">Capacidad</dt>
          <dd class="mt-1 text-sm">{{ $preformaSeleccionada->capacidad ?? 'N/A' }}</dd>
        </div>

        <div>
          <dt class="text-sm font-semibold">Color</dt>
          <dd class="mt-1 text-sm">{{ $preformaSeleccionada->color ?? 'N/A' }}</dd>
        </div>

        <div class="col-span-1 md:col-span-2">
          <dt class="text-sm font-semibold">Descripción</dt>
          <dd class="mt-1 text-sm">{{ $preformaSeleccionada->descripcion ?? 'N/A' }}</dd>
        </div>

        <div>
          <dt class="text-sm font-semibold">Observaciones</dt>
          <dd class="mt-1 text-sm">{{ $preformaSeleccionada->observaciones ?? 'N/A' }}</dd>
        </div>

        <div>
          <dt class="text-sm font-semibold">Estado</dt>
          <dd class="mt-1">
            @if ($preformaSeleccionada->estado)
            <span class="px-3 py-1 text-sm font-semibold text-white bg-green-600 rounded-full shadow">
              Activa
            </span>
            @else
            <span class="px-3 py-1 text-sm font-semibold text-white bg-red-600 rounded-full shadow">
              Inactiva
            </span>
            @endif
          </dd>
        </div>
        <div class="flex justify-center col-span-1 md:col-span-2 mt-4">
          @if ($preformaSeleccionada->imagen)
          <img src="{{ asset('storage/' . $preformaSeleccionada->imagen) }}"
            alt="Imagen Preforma"
            class="w-56 h-56 object-cover rounded-lg shadow"
            loading="lazy">
          @else
          <div class="w-56 h-56 bg-gray-200 flex items-center justify-center rounded-lg shadow">
            <span class="text-gray-500">Sin imagen</span>
          </div>
          @endif

        </div>

      </div>

      <div class="col-span-12 flex justify-center md:justify-end gap-4 mt-4 md:mt-0">
        <button type="button" wire:click="cerrarModalDetalle"
          class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M10 10l4 4m0 -4l-4 4" />
            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
          </svg>
        </button>
      </div>
    </div>
  </div>
  @endif
</div>
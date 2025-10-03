<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input
        type="text"
        wire:model.live="search"
        placeholder="Buscar por descripción u observación..."
        class="input-minimal w-full" />
      <button wire:click="abrirModal('create')" class="btn-circle btn-cyan">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
          <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
        </svg>
      </button>
    </div>

    @forelse($preformas as $preforma)
    <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
      <div class="flex flex-col col-span-9 text-left space-y-1">
        <p><strong>Descripción:</strong> {{ $preforma->descripcion ?? 'N/A' }}</p>
        <p><strong>Observaciones:</strong> {{ $preforma->observaciones ?? 'N/A' }}</p>
        <p><strong>Estado:</strong>
          @if($preforma->estado)
          <span class="text-white bg-green-600 px-2 py-1 rounded-full">Activa</span>
          @else
          <span class="text-white bg-red-600 px-2 py-1 rounded-full">Inactiva</span>
          @endif
        </p>

        <p><strong>Sucursales:</strong>
          @if($preforma->existencias->isEmpty())
          N/A
          @else
          @foreach($preforma->existencias as $existencia)
          <span class="inline-block bg-gray-200 text-gray-800 px-2 py-1 rounded mr-1 mb-1">
            {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}
            (Cantidad: {{ $existencia->cantidad }}, Mínima: {{ $existencia->cantidadMinima ?? 0 }})
          </span>
          @endforeach
          @endif
        </p>

      </div>

      <div class="flex flex-col items-end gap-4 col-span-3">
        <button wire:click="abrirModal('edit', {{ $preforma->id }})" class="btn-circle btn-cyan">
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
        <button wire:click="modaldetalle({{ $preforma->id }})" class="btn-circle btn-cyan" title="Ver Detalle">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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

  @if($modal)
  <div class="modal-overlay">
    <div class="modal-box">
      <div class="modal-content flex flex-col gap-4">
        <div>
          <label class="font-semibold text-sm mb-2 block">Imagen</label>
          <input type="file" wire:model="imagen" class="input-minimal">
          @if($imagen)
          <div class="mt-2 flex justify-center">
            <img src="{{ is_string($imagen) ? asset('storage/'.$imagen) : $imagen->temporaryUrl() }}"
              class="w-50 h-50 object-cover rounded" alt="Imagen Preforma">
          </div>
          @endif
          @error('imagen') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <div>
          <label class="font-semibold text-sm mb-1 block">Descripción</label>
          <input wire:model="descripcion" class="input-minimal" placeholder="Descripción">
          @error('descripcion') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <div>
          <label class="font-semibold text-sm mb-1 block">Observaciones</label>
          <textarea wire:model="observaciones" class="input-minimal" placeholder="Observaciones"></textarea>
          @error('observaciones') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <!-- NUEVO CAMPO: Cantidad Mínima -->
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

      <div class="modal-footer">
        <button type="button" wire:click="guardar" class="btn-circle btn-cyan">
          Guardar
        </button>
        <button type="button" wire:click="cerrarModal" class="btn-circle btn-cyan" title="Cerrar">
          Cerrar
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
          @if($preformaSeleccionada->imagen)
          <img src="{{ asset('storage/'.$preformaSeleccionada->imagen) }}"
            class="w-50 h-50 object-cover rounded" alt="Imagen Preforma">
          @else
          <span class="badge-info">Sin imagen</span>
          @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
              <span class="label-info">Estado:</span>
              <span class="badge-info">{{ $preformaSeleccionada->estado ? 'Activo' : 'Inactivo' }}</span>
            </div>
          </div>
          <div class="flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row sm:items-start gap-2">
              <span class="label-info">Descripción:</span>
              <span class="badge-info">{{ $preformaSeleccionada->descripcion ?? '-' }}</span>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-start gap-2">
              <span class="label-info">Observaciones:</span>
              <span class="badge-info">{{ $preformaSeleccionada->observaciones ?? '-' }}</span>
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
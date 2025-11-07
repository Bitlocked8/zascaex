<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <h3
      class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto">
      Bases
    </h3>

    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input type="text" wire:model.live="search" placeholder="Buscar por descripción o tipo..."
        class="input-minimal w-full" />

      <button wire:click="abrirModal('create')" class="btn-cyan" title="Agregar base">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path
            d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
          <path
            d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
        </svg>
        Añadir
      </button>
    </div>

    @forelse($bases as $base)
      <div class="card-teal flex flex-col gap-4 p-4">
        <div class="flex flex-col gap-2">
          <p class="text-emerald-600 uppercase font-semibold">
            {{ $base->descripcion ?? 'Sin descripción' }}
          </p>
          <p><strong>Tipo de base:</strong> {{ $base->tipo ?? 'N/A' }}</p>
          <p><strong>Capacidad en ml.:</strong> {{ $base->capacidad ?? 'N/A' }}</p>
          <p class="mt-1 text-sm font-semibold">
            <span class="{{ $base->estado == 0 ? 'text-red-600' : 'text-green-600' }}">
              {{ $base->estado == 0 ? 'Inactivo' : 'Activo' }}
            </span>
          </p>
          @if($base->existencias->isEmpty())
            <p class="text-gray-500 text-sm font-medium">
              N/A
            </p>
          @else
            @foreach($base->existencias as $existencia)
              <p class="text-cyan-700 text-sm font-semibold">
                <span class="block">Sucursal: {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}</span>
                <span class="block">Cantidad: {{ $existencia->cantidad }}</span>
                <span class="block">Mínima: {{ $existencia->cantidadMinima ?? 0 }}</span>
              </p>
            @endforeach
          @endif

        </div>

        <div class="flex justify-center items-center mt-2">
          @if($base->imagen)
            <img src="{{ asset('storage/' . $base->imagen) }}" alt="Imagen de base"
              class="w-24 h-24 object-cover rounded-lg border border-cyan-300">
          @else
            <div class="w-24 h-24 flex items-center justify-center bg-gray-200 text-gray-500 rounded-lg text-xs">
              Sin imagen
            </div>
          @endif
        </div>

        <div class="flex flex-wrap justify-center md:justify-center gap-2 border-t border-gray-200 pt-3 pb-2">
          <button wire:click="modaldetalle({{ $base->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0"
            title="Ver Detalles">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
              <path d="M8 12l0 .01" />
              <path d="M12 12l0 .01" />
              <path d="M16 12l0 .01" />
            </svg>
            Ver mas
          </button>

          <button wire:click="abrirModal('edit', {{ $base->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0"
            title="Editar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" />
              <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
              <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
              <path d="M16 5l3 3" />
            </svg>
            Editar
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
                  <img src="{{ is_string($imagen) ? asset('storage/' . $imagen) : $imagen->temporaryUrl() }}"
                    class="w-48 h-48 object-cover rounded shadow-md border border-cyan-300" alt="Imagen Base">
                </div>
              @endif

            </div>

            <div>
              <label class="text-u">Nombre (Requerido)</label>
              <input wire:model="descripcion" class="input-minimal" placeholder="Descripción">
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Tipo (Opcional)</label>
              <input wire:model="tipo" class="input-minimal" placeholder="Tipo de base">
            </div>

            <div>
              <label class="text-u">Capacidad (Requerido)</label>
              <input type="number" wire:model="capacidad" class="input-minimal" min="0" placeholder="Capacidad en ml o l">
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Observaciones (Opcional)</label>
              <textarea wire:model="observaciones" class="input-minimal" placeholder="Observaciones"></textarea>
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Cantidad Mínima (Opcional)</label>
              <input type="number" wire:model="cantidadMinima" class="input-minimal" min="0"
                placeholder="Cantidad mínima">
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

          <button type="button" wire:click="cerrarModal" class="btn-cyan" title="Cerrar">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
              <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
            </svg>
            CERRAR
          </button>
          <button type="button" wire:click="guardar" class="btn-cyan">
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
  @endif

  @if($modalDetalle && $baseSeleccionada)
    <div class="modal-overlay">
      <div class="modal-box max-w-3xl">
        <div class="modal-content flex flex-col gap-6">
          <div class="flex justify-center items-center">
            @if($baseSeleccionada->imagen)
              <img src="{{ asset('storage/' . $baseSeleccionada->imagen) }}"
                class="w-full max-w-xl h-auto object-contain rounded-xl shadow-md" alt="Imagen Base">
            @else
              <span class="badge-info">Sin imagen</span>
            @endif
          </div>

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
                  {{ $baseSeleccionada->capacidad ? $baseSeleccionada->capacidad . ' ml' : '-' }}
                </span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Estado:</span>
                <span
                  class="badge-info {{ $baseSeleccionada->estado ? 'bg-white text-emerald-800' : 'bg-white text-red-800' }}">
                  {{ $baseSeleccionada->estado ? 'Activo' : 'Inactivo' }}
                </span>
              </div>
            </div>
          </div>
          <div>
            <span class="label-info block mb-1">Observaciones:</span>
            <div class="bg-gray-100 rounded p-2 text-sm text-gray-700">
              {{ $baseSeleccionada->observaciones ?? 'Sin observaciones' }}
            </div>
          </div>
        </div>


        <div class="modal-footer">
          <button wire:click="$set('modalDetalle', false)" class="btn-cyan" title="Cerrar">
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



</div>
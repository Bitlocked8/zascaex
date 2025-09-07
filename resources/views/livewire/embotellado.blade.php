<div class="p-text p-2 mt-10 flex justify-center">
  <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
    <div>
      <h6 class="text-xl font-bold mb-4 px-4 p-text">Gestión de Embotellado</h6>

      <!-- Botón y buscador -->
      <div class="flex justify-center items-center gap-4 w-full max-w-2xl mx-auto">
        <button title="Registrar Embotellado" wire:click='abrirModal'
          class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-plus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 5v14m7-7h-14" />
          </svg>
        </button>
        <input type="text" wire:model.live="search" placeholder="Buscar por fecha o encargado..."
          class="input-g w-auto sm:w-64" />
      </div>

      <!-- Tabla -->
      <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left border border-slate-200 dark:border-cyan-200 rounded-lg border-collapse">
          <thead class="text-x uppercase color-bg">
            <tr>
              <th scope="col" class="px-6 py-3 p-text text-left">Información</th>
              <th scope="col" class="px-6 py-3 p-text text-right">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($embotellados as $emb)
            <tr class="color-bg border border-slate-200">
              <td class="px-6 py-4 p-text text-left">
                <div class="mb-2"><b>Fecha:</b> {{ $emb->fecha_embotellado }}</div>
                <div class="mb-2"><b>Encargado:</b> {{ $emb->personal->apellidos }} {{ $emb->personal->nombres }}</div>
                <div class="mb-2"><b>Base usada:</b> {{ $emb->cantidad_base_usada }}</div>
                <div class="mb-2"><b>Tapa usada:</b> {{ $emb->cantidad_tapa_usada }}</div>
                <div class="mb-2"><b>Generados:</b> {{ $emb->cantidad_generada ?? 'Pendiente' }}</div>
                @if ($emb->observaciones)
                <div class="mb-2"><b>Observaciones:</b> {{ $emb->observaciones }}</div>
                @endif
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex justify-end space-x-2">
                  <button title="Editar" wire:click="editar({{ $emb->id }})"
                    class="text-blue-500 hover:text-blue-600 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="icon icon-tabler icon-tabler-edit">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                      <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                      <path d="M16 5l3 3" />
                    </svg>
                  </button>
                  <button title="Detalles" wire:click="modaldetalle({{ $emb->id }})"
                    class="text-indigo-500 hover:text-indigo-600 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor"
                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-info-circle">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                      <path d="M12 9h.01" />
                      <path d="M11 12h1v4h1" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="2" class="text-left py-4 text-gray-600 dark:text-gray-400">No se registraron procesos de
                embotellado.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <div class="mt-4 flex justify-center">
        {{ $embotellados->links() }}
      </div>
    </div>
  </div>

  @if($modal)
  <div class="modal-first">
    <div class="modal-center">
      <div class="modal-hiden">
        <div class="center-col">
          <h3 class="p-text">{{ $accion === 'create' ? 'Registrar Embotellado' : 'Editar Embotellado' }}</h3>

          <div class="over-col">
            <!-- Fecha de Embotellado -->
            <h3 class="p-text">Fecha de Embotellado</h3>
            <input type="date" wire:model.defer="fecha_embotellado" class="p-text input-g">
            @error('fecha_embotellado') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Encargado -->
            <h3 class="p-text">Encargado</h3>
            <select wire:model.defer="personal_id" class="p-text input-g">
              <option value="">Seleccione</option>
              @foreach($personales as $personal)
              <option value="{{ $personal->id }}">{{ $personal->apellidos }} {{ $personal->nombres }}</option>
              @endforeach
            </select>
            @error('personal_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Base usada -->
            <h3 class="p-text">Base Usada</h3>
            <select wire:model.defer="existencia_base_id" class="p-text input-g">
              <option value="">Seleccione</option>
              @foreach($existencias_base as $existencia)
              <option value="{{ $existencia->id }}">ID #{{ $existencia->id }} [{{ $existencia->cantidad }}] - {{
                $existencia->descripcion ?? 'Base' }}</option>
              @endforeach
            </select>
            @error('existencia_base_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Tapa usada -->
            <h3 class="p-text">Tapa Usada</h3>
            <select wire:model.defer="existencia_tapa_id" class="p-text input-g">
              <option value="">Seleccione</option>
              @foreach($existencias_tapa as $existencia)
              <option value="{{ $existencia->id }}">ID #{{ $existencia->id }} [{{ $existencia->cantidad }}] - {{
                $existencia->descripcion ?? 'Tapa' }}</option>
              @endforeach
            </select>
            @error('existencia_tapa_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Cantidad base usada -->
            <h3 class="p-text">Cantidad de Base Usada</h3>
            <input type="number" wire:model.defer="cantidad_base_usada" class="p-text input-g">
            @error('cantidad_base_usada') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Cantidad tapa usada -->
            <h3 class="p-text">Cantidad de Tapa Usada</h3>
            <input type="number" wire:model.defer="cantidad_tapa_usada" class="p-text input-g">
            @error('cantidad_tapa_usada') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Cantidad generada -->
            <h3 class="p-text">Cantidad Generada (Productos)</h3>
            <input type="number" wire:model.defer="cantidad_generada" class="p-text input-g">
            @error('cantidad_generada') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Observaciones -->
            <h3 class="p-text">Observaciones</h3>
            <input wire:model.defer="observaciones" class="p-text input-g"></input>
            @error('observaciones') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
          </div>

          <!-- Botones -->
          <div class="mt-6 flex justify-center w-full space-x-4">
            <button type="button" wire:click="guardar"
              class="text-indigo-500 hover:text-indigo-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M14 4l0 4l-6 0l0 -4" />
              </svg>
            </button>
            <button type="button" wire:click="cerrarModal"
              class="text-red-500 hover:text-red-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M18 6l-12 12" />
                <path d="M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
  @if ($modalDetalle)
  <div class="modal-first">
    <div class="modal-center">
      <div class="modal-hiden">
        <div class="center-col">
          <h3 class="text-base font-semibold p-text">Detalles del Embotellado</h3>

          <div class="mt-4">
            <dl class="grid grid-cols-2 gap-4">
              <!-- Fecha de Embotellado -->
              <div>
                <dt class="text-sm font-medium p-text">Fecha de Embotellado</dt>
                <dd class="mt-1 text-sm p-text">{{ $embotelladoSeleccionado->fecha_embotellado ?? '-' }}</dd>
              </div>
              <!-- Personal Encargado -->
              <div>
                <dt class="text-sm font-medium p-text">Encargado</dt>
                <dd class="mt-1 text-sm p-text">{{ $embotelladoSeleccionado->personal->nombres ?? '-' }}</dd>
              </div>
              <!-- Existencia Base -->
              <div>
                <dt class="text-sm font-medium p-text">Existencia Base</dt>
                <dd class="mt-1 text-sm p-text">
                  ID #{{ $embotelladoSeleccionado->existencia_base_id ?? '-' }} -
                  {{ optional($embotelladoSeleccionado->existenciaBase)->descripcion ?? 'Sin descripción' }}
                </dd>
              </div>
              <!-- Existencia Tapa -->
              <div>
                <dt class="text-sm font-medium p-text">Existencia Tapa</dt>
                <dd class="mt-1 text-sm p-text">
                  ID #{{ $embotelladoSeleccionado->existencia_tapa_id ?? '-' }} -
                  {{ optional($embotelladoSeleccionado->existenciaTapa)->descripcion ?? 'Sin descripción' }}
                </dd>
              </div>
              <!-- Cantidad Base Usada -->
              <div>
                <dt class="text-sm font-medium p-text">Cantidad Base Usada</dt>
                <dd class="mt-1 text-sm p-text">{{ $embotelladoSeleccionado->cantidad_base_usada ?? '-' }}</dd>
              </div>
              <!-- Cantidad Tapa Usada -->
              <div>
                <dt class="text-sm font-medium p-text">Cantidad Tapa Usada</dt>
                <dd class="mt-1 text-sm p-text">{{ $embotelladoSeleccionado->cantidad_tapa_usada ?? '-' }}</dd>
              </div>
              <!-- Cantidad Generada -->
              <div>
                <dt class="text-sm font-medium p-text">Cantidad Generada</dt>
                <dd class="mt-1 text-sm p-text">{{ $embotelladoSeleccionado->cantidad_generada ?? '-' }}</dd>
              </div>
              <!-- Observaciones -->
              <div class="col-span-2">
                <dt class="text-sm font-medium p-text">Observaciones</dt>
                <dd class="mt-1 text-sm p-text">{{ $embotelladoSeleccionado->observaciones ?? 'Ninguna' }}</dd>
              </div>
            </dl>
          </div>

          <!-- Botón cerrar -->
          <div class="mt-6 flex justify-center">
            <button type="button" wire:click="cerrarModalDetalle"
              class="text-red-500 hover:text-red-600 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full"
              aria-label="Cerrar detalle">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icon-tabler-x">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M18 6l-12 12" />
                <path d="M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif


</div>
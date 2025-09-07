<div class="p-text p-2 mt-10 flex justify-center">
  <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
    <div>
      <h6 class="text-xl font-bold mb-4 px-4 p-text">Gestión de Preformas</h6>

      <!-- Botón de registro y buscador -->
      <div class="flex justify-center items-center gap-4 w-full max-w-2xl mx-auto">
        <button title="Registrar Preforma" wire:click='abrirModal("create")'
          class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 5v14m7-7h-14" />
          </svg>
        </button>

        <input type="text" wire:model.live="search" placeholder="Buscar preforma..." class="input-g w-auto sm:w-64" />
      </div>

      <!-- Tabla -->
      <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left border border-slate-200 dark:border-cyan-200 rounded-lg border-collapse">
          <thead class="text-xs md:text-sm uppercase color-bg">
            <tr class="bg-gray-100 dark:bg-gray-800">
              <th scope="col" class="px-4 py-3 p-text text-left">DETALLES DE LA PREFORMA</th>
              <th scope="col" class="px-4 py-3 p-text text-left">SUCURSAL Y STOCK</th>
              <th scope="col" class="px-4 py-3 p-text text-right">ACCIONES</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($preformas as $preforma)
            <tr class="color-bg border border-slate-200 text-sm">
              <!-- Columna 1: Imagen + Información -->
              <td class="px-4 py-4 text-left p-text align-top">
                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                  <!-- Imagen -->
                  <img src="{{ asset('storage/' . $preforma->imagen) }}" alt="Preforma"
                    class="sm:h-24 sm:w-12 object-cover rounded mb-2 sm:mb-0">

                  <!-- Información -->
                  <div class="text-sm">
                    <div><strong>Insumo:</strong> {{ $preforma->insumo }}</div>
                    <div><strong>Color:</strong> {{ $preforma->color }}</div>
                  </div>
                </div>
              </td>

              <!-- Columna 2: Stock y Sucursal -->
              <td class="px-4 py-4 text-left align-top text-sm">
                  <strong class="block mb-1">Sucursal:</strong>
                  @forelse ($preforma->existencias as $existencia)
                  <span class="block">
                      <span class="@if ($existencia->cantidad > ($existencia->cantidadMinima * 2)) text-green-500
                                  @elseif ($existencia->cantidad >= $existencia->cantidadMinima && $existencia->cantidad <= ($existencia->cantidadMinima * 2)) text-yellow-500
                                  @else text-red-500 @endif">
                          {{ number_format($existencia->cantidad) . '/' . $existencia->cantidadMinima }}:
                      </span>
                      {{ Str::limit($existencia->sucursal->nombre ?? 'Sucursal Desconocida', 18, '...') }}
                  </span>
                  @empty
                  <span class="text-xs text-gray-500">Sin stock registrado</span>
                  @endforelse

                  <strong class="p-text block mt-2">
                      {{ number_format($preforma->existencias->sum('cantidad')) }}: Total preformas
                  </strong>
              </td>

              <!-- Columna 3: Acciones -->
              <td class="px-4 py-4 text-right align-middle">
                <div class="flex justify-end space-x-2">
                  <!-- Botón editar -->
                  <button title="Editar Preforma" wire:click="abrirModal('edit', {{ $preforma->id }})"
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

                  <!-- Botón detalles -->
                  <button title="Ver detalles" wire:click="modaldetalle({{ $preforma->id }})"
                    class="text-indigo-500 hover:text-indigo-600 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="icon icon-tabler icon-tabler-info-circle">
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
              <td colspan="3" class="text-center py-4 text-gray-600 dark:text-gray-400 text-sm">
                No hay preformas registradas.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>



      <div class="mt-4 flex justify-center">
        {{ $preformas->links() }}
      </div>
    </div>
  </div>





  <!-- Modal de Registro/Edición -->
  @if ($modal)
  <div class="modal-first">
    <div class="modal-center">
      <div class="modal-hiden">
        <div class="center-col">
          <h3 class="p-text">
            {{ $accion === 'edit' ? 'Editar Preforma' : 'Nueva Preforma' }}
          </h3>

          <div class="over-col">

            <!-- Campo de Imagen -->
            <h3 class="p-text">Imagen</h3>
            <input type="file" wire:model="imagen" accept="image/*" class="p-text input-g" />
            @error('imagen') <span class="error-message text-red-500">{{ $message }}</span> @enderror

            <!-- Campo de Insumo -->
            <h3 class="p-text">Insumo</h3>
            <input type="text" wire:model="insumo" class="p-text input-g" />
            @error('insumo') <span class="error-message text-red-500">{{ $message }}</span> @enderror

            <!-- Campo de Descripción -->
            <h3 class="p-text">Descripción</h3>
            <input type="text" wire:model="descripcion" class="p-text input-g" />
            @error('descripcion') <span class="error-message text-red-500">{{ $message }}</span> @enderror

            <!-- Campo de Capacidad -->
            <h3 class="p-text">Capacidad</h3>
            <input type="number" wire:model="capacidad" class="p-text input-g" />
            @error('capacidad') <span class="error-message text-red-500">{{ $message }}</span> @enderror

            <!-- Campo de Color -->
            <h3 class="p-text">Color</h3>
            <input type="text" wire:model="color" class="p-text input-g" />
            @error('color') <span class="error-message text-red-500">{{ $message }}</span> @enderror

            <!-- Campo de Estado -->
            <h3 class="p-text mb-2">Estado</h3>
            <div class="flex space-x-6 justify-center">
              <!-- Botón para "Activo" -->
              <label class="flex items-center space-x-2">
                <input type="radio" wire:model="estado" value="1" class="form-radio hidden peer" />
                <span class="p-text inline-block py-2 px-4 rounded-lg cursor-pointer border border-gray-300 hover:bg-indigo-100 peer-checked:bg-cyan-950 peer-checked:text-white">
                  Activo
                </span>
              </label>

              <!-- Botón para "Inactivo" -->
              <label class="flex items-center space-x-2">
                <input type="radio" wire:model="estado" value="0" class="form-radio hidden peer" />
                <span class="p-text inline-block py-2 px-4 rounded-lg cursor-pointer border border-gray-300 hover:bg-indigo-100 peer-checked:bg-cyan-950 peer-checked:text-white">
                  Inactivo
                </span>
              </label>
            </div>
            @error('estado')
            <span class="text-red-600 text-xs">{{ $message }}</span>
            @enderror


          </div>

          <!-- Botones de Acción -->
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


  <!-- Modal de Detalles -->
  @if ($modalDetalle)
  <div class="modal-first">
    <div class="modal-center">
      <div class="modal-hiden">
        <div class="center-col">
          <h3 class="text-base font-semibold p-text" id="modal-title">Detalles de la Preforma</h3>
          <div class="mt-4">
            <dl class="grid grid-cols-2 gap-4">
              <!-- Insumo -->
              <div>
                <dt class="text-sm font-semibold p-text">Insumo</dt>
                <dd class="mt-1 text-sm p-text">{{ $preformaSeleccionada->insumo ?? 'No especificado' }}</dd>
              </div>

              <!-- Descripción -->
              <div>
                <dt class="text-sm font-semibold p-text">Descripción</dt>
                <dd class="mt-1 text-sm p-text">{{ $preformaSeleccionada->descripcion ?? 'No especificada' }}</dd>
              </div>

              <!-- Capacidad -->
              <div>
                <dt class="text-sm font-semibold p-text">Capacidad</dt>
                <dd class="mt-1 text-sm p-text">{{ $preformaSeleccionada->capacidad ?? 'No especificada' }}</dd>
              </div>

              <!-- Color -->
              <div>
                <dt class="text-sm font-semibold p-text">Color</dt>
                <dd class="mt-1 text-sm p-text">{{ $preformaSeleccionada->color ?? 'No especificado' }}</dd>
              </div>

              <!-- Estado -->
              <div>
                <dt class="text-sm font-semibold p-text">Estado</dt>
                <dd class="mt-1 text-sm p-text">
                  @if (($preformaSeleccionada['estado'] ?? false) == 1)
                  <span
                    class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-600 text-white">Activo</span>
                  @else
                  <span
                    class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-red-600 text-white">Inactivo</span>
                  @endif
                </dd>
              </div>

            </dl>
          </div>

          <div class="mt-6 flex justify-center w-full">
            <button type="button" wire:click="cerrarModalDetalle"
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

</div>
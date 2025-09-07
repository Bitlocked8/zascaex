<div class="p-text p-2 mt-10 flex justify-center">
  <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
    <div>
      <h6 class="text-xl font-bold mb-4 px-4 p-text">Gestión de Elaboraciones</h6>

      <!-- Botón de registro y buscador -->
      <div class="flex justify-center items-center gap-4 w-full max-w-2xl mx-auto">
        <button title="Registrar Elaboración" wire:click='abrirModal'
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
            @forelse ($elaboraciones as $elaboracion)
            <tr class="color-bg border border-slate-200">
              <!-- Información -->
              <td class="px-6 py-4 p-text text-left">
                <div class="mb-2">                  
                  <span><b>Fecha:</b> {{ $elaboracion->fecha_elaboracion }}</span>
                </div>
                <div class="mb-2">                  
                  <span> <b>Encargado:</b> {{ $elaboracion->personal->apellidos }}{{ $elaboracion->personal->nombres }}</span>
                </div>
                <div class="mb-2">                  
                  <span> <b>Entrada:</b> {{ $elaboracion->cantidad_entrada }} [{{ $elaboracion->existenciaEntrada->existenciable->insumo}}]</span>
                </div>
                <div class="mb-2">                  
                  <span> <b>Salida:</b> {{ $elaboracion->cantidad_salida ?? 'Pendiente' }} [{{ $elaboracion->existenciaSalida->existenciable->capacidad}}]</span>
                </div>
                @if ($elaboracion->observaciones)
                <div class="mb-2">                  
                  <span> <b>Observaciones:</b> {{ $elaboracion->observaciones }}</span>
                </div>
                @endif
              </td>
              <!-- Acciones -->
              <td class="px-6 py-4 text-right">
                <div class="flex justify-end space-x-2">
                  <button title="Editar Elaboración" wire:click="editar({{ $elaboracion->id }})"
                    class="text-blue-500 hover:text-blue-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="icon icon-tabler icon-tabler-edit">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                      <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                      <path d="M16 5l3 3" />
                    </svg>
                  </button>
                  <button title="Detalles" wire:click="modaldetalle({{ $elaboracion->id }})"
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
              <td colspan="2" class="text-left py-4 text-gray-600 dark:text-gray-400">
                No se registraron procesos de elaboración.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <div class="mt-4 flex justify-center">
        {{ $elaboraciones->links() }}
      </div>
    </div>
  </div>

  <!-- Modal Registro/Edición -->
  @if($modal)
  <div class="modal-first">
    <div class="modal-center">
      <div class="modal-hiden">
        <div class="center-col">
          <h3 class="p-text">{{ $accion === 'create' ? 'Registrar Elaboración' : 'Editar Elaboración' }}</h3>

          <div class="over-col">
            <!-- Fecha de Elaboración -->
            <h3 class="p-text">Fecha de Elaboración</h3>
            <input type="date" wire:model.defer="fecha_elaboracion" class="p-text input-g">
            @error('fecha_elaboracion') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Encargado -->
            <h3 class="p-text">Encargado</h3>
            <select wire:model.defer="personal_id" class="p-text input-g">
              <option value="">Seleccione</option>
              @foreach($personales as $personal)
              <option value="{{ $personal->id }}">{{ $personal->apellidos }} {{ $personal->nombres }}</option>
              @endforeach
            </select>
            @error('personal_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Sucursal -->
            <h3 class="p-text">Sucursal</h3>
            <select wire:model.lazy="sucursal_id" wire:change='preformasSucursal()' class="p-text input-g">
              <option value="">Seleccione</option>
              @foreach($sucursales as $sucursal)
              <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
              @endforeach
            </select>
            @error('sucursal_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Existencia Entrada (Preforma) -->
            <h3 class="p-text">(Preformas) Existencia Entrada</h3>
            <select wire:model.lazy="existencia_entrada_id" wire:change='basesPreformas()' class="p-text input-g">
              <option value="">Seleccione</option>
              @foreach($existencias_preforma as $existencia)
              <option value="{{ $existencia->id }}">ID #{{ $existencia->id }} [{{ $existencia->cantidad }}] - {{ $existencia->descripcion ??
                'Preforma' }}</option>
              @endforeach
            </select>
            @error('existencia_entrada_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Existencia Salida (Base) -->
            <h3 class="p-text">(Bases) Existencia Salida</h3>
            <select wire:model.lazy="existencia_salida_id" class="p-text input-g">
              <option value="">Seleccione</option>
              @foreach($existencias_base as $existencia)
              <option value="{{ $existencia->id }}">ID #{{ $existencia->id }} [{{ $existencia->cantidad }}]- {{ $existencia->descripcion ?? 'Base' }}
              </option>
              @endforeach
            </select>
            @error('existencia_salida_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror


            <!-- Cantidad Entrada -->
            <h3 class="p-text">Cantidad Entrada</h3>
            <input type="number" wire:model.defer="cantidad_entrada" class="p-text input-g">
            @error('cantidad_entrada') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Cantidad Salida -->
            <h3 class="p-text">Cantidad Salida (Bases)</h3>
            <input type="number" wire:model.defer="cantidad_salida" class="p-text input-g">
            @error('cantidad_salida') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

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

  <!-- Modal Detalle -->
  @if ($modalDetalle)
  <div class="modal-first">
    <div class="modal-center">
      <div class="modal-hiden">
        <div class="center-col">
          <h3 class="text-base font-semibold p-text">Detalles de la Elaboración</h3>

          <div class="mt-4">
            <dl class="grid grid-cols-2 gap-4">
              <!-- Fecha de Elaboración -->
              <div>
                <dt class="text-sm font-medium p-text">Fecha de Elaboración</dt>
                <dd class="mt-1 text-sm p-text">{{ $elaboracionSeleccionada['fecha_elaboracion'] ?? '-' }}</dd>
              </div>
              <!-- Encargado -->
              <div>
                <dt class="text-sm font-medium p-text">Encargado</dt>
                <dd class="mt-1 text-sm p-text">{{ $elaboracionSeleccionada['personal']['nombres'] ?? '-' }}</dd>
              </div>
              <!-- Existencia Entrada -->
              <div>
                <dt class="text-sm font-medium p-text">Existencia Entrada (Preformas)</dt>
                <dd class="mt-1 text-sm p-text">
                  ID #{{ $elaboracionSeleccionada['existencia_entrada']['id'] ?? '-' }} -
                  {{ $elaboracionSeleccionada['existencia_entrada']['descripcion'] ?? 'Sin descripción' }}
                </dd>
              </div>
              <!-- Cantidad Entrada -->
              <div>
                <dt class="text-sm font-medium p-text">Cantidad Entrada</dt>
                <dd class="mt-1 text-sm p-text">{{ $elaboracionSeleccionada['cantidad_entrada'] ?? '-' }}</dd>
              </div>
              <!-- Cantidad Salida -->
              <div>
                <dt class="text-sm font-medium p-text">Cantidad Salida (Bases)</dt>
                <dd class="mt-1 text-sm p-text">{{ $elaboracionSeleccionada['cantidad_salida'] ?? 'Pendiente' }}</dd>
              </div>
              <!-- Observaciones -->
              <div class="col-span-2">
                <dt class="text-sm font-medium p-text">Observaciones</dt>
                <dd class="mt-1 text-sm p-text">{{ $elaboracionSeleccionada['observaciones'] ?? 'Ninguna' }}</dd>
              </div>
            </dl>
          </div>

          <!-- Botón cerrar -->
          <div class="mt-6 flex justify-center">
            <button type="button" wire:click="cerrarModalDetalle"
              class="text-red-500 hover:text-red-600 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full">
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
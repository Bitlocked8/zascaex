<div class="p-text p-2 mt-10 flex justify-center">
  <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
    <div>
      <h6 class="text-xl font-bold mb-4 px-4 p-text">Gestión de Sucursales</h6>

      <!-- Botón de registro y buscador -->
      <div class="flex justify-center items-center gap-4 w-full max-w-2xl mx-auto">
        <button title="Registrar Sucursal" wire:click='abrirModal("create")'
          class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icon-tabler-plus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 5v14m-7 -7h14" />
          </svg>
        </button>

        <input type="text" wire:model.live="search" placeholder="Buscar sucursal..." class="input-g w-auto sm:w-64" />
      </div>



      <!-- Tabla -->
      <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
        <table
          class="w-full text-sm text-left border border-slate-200 dark:border-cyan-200  rounded-lg border-collapse">
          <thead class="text-x uppercase color-bg">
            <tr>
              <th scope="col" class="px-6 py-3 p-text text-left">Información</th>
              <th scope="col" class="px-6 py-3 p-text text-right">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($sucursales as $sucursal)
            <tr class="color-bg border border-slate-200">
              <td class="px-6 py-4 p-text text-left">
                <div class="mb-2">
                  <span class="font-semibold block">Nombre de la sucursal:</span>
                  <span>{{ $sucursal->nombre }}</span>
                </div>
                <div>
                  <span class="font-semibold block">Empresa:</span>
                  <span>{{ $sucursal->empresa->nombre ?? 'Empresa no registrada' }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex justify-end space-x-2">
                  <button title="Editar" wire:click="editarSucursal({{ $sucursal->id }})"
                    class="text-blue-500 hover:text-blue-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                      <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                      <path d="M16 5l3 3" />
                    </svg>
                  </button>
                  <button title="Ver Detalle" wire:click="verDetalle({{ $sucursal->id }})"
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
                No hay sucursales registradas.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="mt-4 flex justify-center">
        {{ $sucursales->links() }}
      </div>
    </div>
  </div>

  @if ($modal)
  <div class="modal-first">
    <div class="modal-center">
      <div class="modal-hiden">
        <div class="center-col">
          <h3 class="p-text">{{ $accion === 'create' ? 'Registrar Sucursal' : 'Editar Sucursal' }}</h3>
          <div class="over-col">
            <!-- Nombre -->
            <h3 class="p-text">Nombre</h3>
            <input type="text" wire:model.defer="nombre" class="p-text input-g">
            @error('nombre') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Dirección -->
            <h3 class="p-text">Dirección</h3>
            <input type="text" wire:model.defer="direccion" class="p-text input-g">
            @error('direccion') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Teléfono -->
            <h3 class="p-text">Teléfono</h3>
            <input type="text" wire:model.defer="telefono" class="p-text input-g">
            @error('telefono') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Zona -->
            <h3 class="p-text">Zona</h3>
            <input type="text" wire:model.defer="zona" class="p-text input-g">
            @error('zona') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Empresa -->
            <h3 class="p-text">Empresa</h3>
            <select wire:model.defer="empresa_id" class="p-text text-sm sm:text-base input-g">
              <option value="" class=" text-sm sm:text-base">Seleccione una empresa</option>
              @foreach ($empresas as $empresa)
              <option value="{{ $empresa->id }}" class="text-sm sm:text-base">{{ $empresa->nombre }}</option>
              @endforeach
            </select>
            @error('empresa_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
          </div>

          <!-- Botones -->
          <div class="mt-6 flex justify-center w-full space-x-4">
            <button type="button" wire:click="guardarSucursal"
              class="text-indigo-500 hover:text-indigo-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full"><svg
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M14 4l0 4l-6 0l0 -4" />
              </svg></button>
            <button type="button" wire:click="cerrarModal"
              class="text-red-500 hover:text-red-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full"><svg
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M18 6l-12 12" />
                <path d="M6 6l12 12" />
              </svg></button>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

  @if ($detalleModal)
  <div class="modal-first">
    <div class="modal-center">
      <div class="modal-hiden">
        <div class="center-col">
          <!-- <div class="px-4 py-4 sm:px-5 sm:py-4"> -->
          <h3 class="text-base font-semibold p-text" id="modal-title">Detalles de la Sucursal</h3>
          <div class="mt-4">
            <dl class="grid grid-cols-2 gap-4">
              <!-- Nombre de la Sucursal -->
              <div>
                <dt class="text-sm font-medium p-text">Nombre</dt>
                <dd class="mt-1 text-sm p-text">{{ $sucursalSeleccionada->nombre ?? 'No especificado' }}</dd>
              </div>

              <!-- Empresa asociada -->
              <div>
                <dt class="text-sm font-medium p-text">Empresa</dt>
                <dd class="mt-1 text-sm p-text">{{ $sucursalSeleccionada->empresa->nombre ?? 'No asignada' }}</dd>
              </div>

              <!-- Dirección -->
              <div>
                <dt class="text-sm font-medium p-text">Dirección</dt>
                <dd class="mt-1 text-sm p-text">{{ $sucursalSeleccionada->direccion ?? 'No especificada' }}</dd>
              </div>

              <!-- Teléfono -->
              <div>
                <dt class="text-sm font-medium p-text">Teléfono</dt>
                <dd class="mt-1 text-sm p-text">{{ $sucursalSeleccionada->telefono ?? 'No especificado' }}</dd>
              </div>

              <!-- Zona -->
              <div>
                <dt class="text-sm font-medium p-text">Zona</dt>
                <dd class="mt-1 text-sm p-text">{{ $sucursalSeleccionada->zona ?? 'No especificada' }}</dd>
              </div>
            </dl>
            <!-- </div> -->
          </div>

          <div>
            <button type="button" wire:click="cerrarModal"
              class="text-red-500 hover:text-red-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full"><svg
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M18 6l-12 12" />
                <path d="M6 6l12 12" />
              </svg></button>
          </div>
        </div>
      </div>
    </div>
    @endif


  </div>
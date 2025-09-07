<div class="p-text p-2 mt-10 flex justify-center">
  <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
    <div>
      <h6 class="text-xl font-bold mb-4 px-4 p-text">Gestión de Proveedores</h6>

      <!-- Botón de registro y buscador -->
      <div class="flex justify-center items-center gap-4 w-full max-w-2xl mx-auto">
        <button title="Registrar Proveedor" wire:click='abrirModal("create")'
          class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icon-tabler-plus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 5v14m-7 -7h14" />
          </svg>
        </button>

        <input type="text" wire:model.live="search" placeholder="Buscar proveedor..." class="input-g w-auto sm:w-64" />
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
            @forelse ($proveedores as $proveedor)
            <tr class="color-bg border border-slate-200">
              <td class="px-6 py-4 p-text text-left">
                <div class="mb-2">
                  <span class="font-semibold block">Razón Social:</span>
                  <span>{{ $proveedor->razonSocial }}</span>
                </div>
                <div class="mb-2">
                  <span class="font-semibold block">Contacto:</span>
                  <span>{{ $proveedor->nombreContacto ?? 'No definido' }}</span>
                </div>
                <div class="mb-2">
                  <span class="font-semibold block">Correo:</span>
                  <span>{{ $proveedor->correo }}</span>
                </div>
                <div class="mb-2">
                  <span class="font-semibold block">Tipo / Servicio:</span>
                  <span>{{ ucfirst($proveedor->tipo) }} / {{ ucfirst($proveedor->servicio) }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex justify-end space-x-2">
                  <button title="Editar" wire:click="editarProveedor({{ $proveedor->id }})"
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

                  <button title="Ver Detalle" wire:click="verDetalle({{ $proveedor->id }})"
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
                No hay proveedores registrados.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-4 flex justify-center">
        {{ $proveedores->links() }}
      </div>
    </div>
  </div>


  @if ($modal)
  <div class="modal-first">
    <div class="modal-center">
      <div class="modal-hiden">
        <div class="center-col">
          <h3 class="p-text">{{ $accion === 'create' ? 'Registrar Proveedor' : 'Editar Proveedor' }}</h3>

          <div class="over-col">
            <!-- Razón Social -->
            <h3 class="p-text">Razón Social</h3>
            <input type="text" wire:model.defer="razonSocial" class="p-text input-g">
            @error('razonSocial') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Nombre del Contacto -->
            <h3 class="p-text">Nombre del Contacto</h3>
            <input type="text" wire:model.defer="nombreContacto" class="p-text input-g">
            @error('nombreContacto') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Dirección -->
            <h3 class="p-text">Dirección</h3>
            <input type="text" wire:model.defer="direccion" class="p-text input-g">
            @error('direccion') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Teléfono -->
            <h3 class="p-text">Teléfono</h3>
            <input type="text" wire:model.defer="telefono" class="p-text input-g">
            @error('telefono') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Correo -->
            <h3 class="p-text">Correo</h3>
            <input type="email" wire:model.defer="correo" class="p-text input-g">
            @error('correo') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Tipo -->
            <h3 class="p-text">Tipo</h3>
            <select wire:model.defer="tipo" class="p-text input-g">
              <option value="">Seleccione tipo</option>
              <option value="tapas">Tapas</option>
              <option value="preformas">Preformas</option>
              <option value="etiquetas">Etiquetas</option>
            </select>
            @error('tipo') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Servicio -->
            <h3 class="p-text">Servicio</h3>
            <select wire:model.defer="servicio" class="p-text input-g">
              <option value="">Seleccione servicio</option>
              <option value="soplado">Soplado</option>
              <option value="transporte">Transporte</option>
            </select>
            @error('servicio') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Descripción -->
            <h3 class="p-text">Descripción</h3>
            <input wire:model.defer="descripcion" class="p-text input-g "></input>
            @error('descripcion') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Precio -->
            <h3 class="p-text">Precio</h3>
            <input type="number" wire:model.defer="precio" class="p-text input-g" step="0.01" min="0">
            @error('precio') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Tiempo de Entrega -->
            <h3 class="p-text">Tiempo de Entrega</h3>
            <input type="text" wire:model.defer="tiempoEntrega" class="p-text input-g">
            @error('tiempoEntrega') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Estado -->
            <h3 class="p-text">Estado</h3>
            <select wire:model.defer="estado" class="p-text input-g">
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
            @error('estado') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
          </div>

          <!-- Botones -->
          <div class="mt-6 flex justify-center w-full space-x-4">
            <button type="button" wire:click="guardarProveedor"
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
  @if ($detalleModal)
  <div class="modal-first">
    <div class="modal-center">
      <div class="modal-hiden">
        <div class="center-col">
          <h3 class="text-base font-semibold p-text" id="modal-title">Detalles del Proveedor</h3>

          <div class="mt-4">
            <dl class="grid grid-cols-2 gap-4">

              <!-- Razón Social -->
              <div>
                <dt class="text-sm font-medium p-text">Razón Social</dt>
                <dd class="mt-1 text-sm p-text">{{ $proveedorSeleccionado->razonSocial ?? 'No especificada' }}</dd>
              </div>

              <!-- Nombre del Contacto -->
              <div>
                <dt class="text-sm font-medium p-text">Nombre del Contacto</dt>
                <dd class="mt-1 text-sm p-text">{{ $proveedorSeleccionado->nombreContacto ?? 'No especificado' }}</dd>
              </div>

              <!-- Dirección -->
              <div>
                <dt class="text-sm font-medium p-text">Dirección</dt>
                <dd class="mt-1 text-sm p-text">{{ $proveedorSeleccionado->direccion ?? 'No especificada' }}</dd>
              </div>

              <!-- Teléfono -->
              <div>
                <dt class="text-sm font-medium p-text">Teléfono</dt>
                <dd class="mt-1 text-sm p-text">{{ $proveedorSeleccionado->telefono ?? 'No especificado' }}</dd>
              </div>

              <!-- Correo -->
              <div>
                <dt class="text-sm font-medium p-text">Correo</dt>
                <dd class="mt-1 text-sm p-text">{{ $proveedorSeleccionado->correo ?? 'No especificado' }}</dd>
              </div>

              <!-- Tipo -->
              <div>
                <dt class="text-sm font-medium p-text">Tipo</dt>
                <dd class="mt-1 text-sm p-text">{{ ucfirst($proveedorSeleccionado->tipo ?? 'No especificado') }}</dd>
              </div>

              <!-- Servicio -->
              <div>
                <dt class="text-sm font-medium p-text">Servicio</dt>
                <dd class="mt-1 text-sm p-text">{{ ucfirst($proveedorSeleccionado->servicio ?? 'No especificado') }}
                </dd>
              </div>

              <!-- Descripción -->
              <div class="col-span-2">
                <dt class="text-sm font-medium p-text">Descripción</dt>
                <dd class="mt-1 text-sm p-text">{{ $proveedorSeleccionado->descripcion ?? 'No especificada' }}</dd>
              </div>

              <!-- Precio -->
              <div>
                <dt class="text-sm font-medium p-text">Precio</dt>
                <dd class="mt-1 text-sm p-text">Bs
                  {{ number_format($proveedorSeleccionado->precio, 2, ',', '.') ?? '0.00' }}
                </dd>
              </div>

              <!-- Tiempo de Entrega -->
              <div>
                <dt class="text-sm font-medium p-text">Tiempo de Entrega</dt>
                <dd class="mt-1 text-sm p-text">{{ $proveedorSeleccionado->tiempoEntrega ?? 'No especificado' }}</dd>
              </div>

              <!-- Estado -->
              <div class="col-span-2">
                <dt class="text-sm font-medium p-text">Estado</dt>
                <dd class="mt-1 text-sm p-text">
                  <span class="{{ $proveedorSeleccionado->estado ? 'text-green-600' : 'text-red-600' }}">
                    {{ $proveedorSeleccionado->estado ? 'Activo' : 'Inactivo' }}
                  </span>
                </dd>
              </div>

            </dl>
          </div>

          <!-- Botón cerrar -->
          <div class="mt-6 flex justify-center">
            <button type="button" wire:click="cerrarModal"
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
<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl">

    <h3 class="text-center text-2xl font-bold uppercase text-cyan-700 bg-cyan-100 px-6 py-2 rounded-full mx-auto mb-4">
      Proveedores
    </h3>

    <!-- Buscador y botón -->
    <div class="flex items-center gap-2 mb-4 flex-wrap">
      <input type="text" wire:model.live="search" placeholder="Buscar por razón social, contacto o correo..."
        class="input-minimal w-full sm:w-auto flex-1" />
      <button wire:click="abrirModal('create')" class="btn-cyan flex items-center gap-1">Añadir</button>
    </div>

    <!-- Tabla scrollable -->
    <div class="overflow-auto max-h-[500px] border border-gray-200 rounded-md">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-cyan-50 sticky top-0 z-10">
          <tr>
            <th class="px-4 py-2 text-left text-cyan-700 font-semibold">Razón Social</th>
            <th class="px-4 py-2 text-left text-cyan-700 font-semibold">Contacto</th>
            <th class="px-4 py-2 text-left text-cyan-700 font-semibold">Teléfono</th>
            <th class="px-4 py-2 text-left text-cyan-700 font-semibold">Correo</th>
            <th class="px-4 py-2 text-left text-cyan-700 font-semibold">Tipo / Servicio</th>
            <th class="px-4 py-2 text-left text-cyan-700 font-semibold">Estado</th>
            <th class="px-4 py-2 text-center text-cyan-700 font-semibold">Acciones</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($proveedores as $proveedor)
            <tr class="hover:bg-cyan-50">
              <td class="px-4 py-2">{{ $proveedor->razonSocial }}</td>
              <td class="px-4 py-2">{{ $proveedor->nombreContacto ?? 'N/A' }}</td>
              <td class="px-4 py-2">{{ $proveedor->telefono ?? 'N/A' }}</td>
              <td class="px-4 py-2">{{ $proveedor->correo ?? 'N/A' }}</td>
              <td class="px-4 py-2">
                {{ ucfirst($proveedor->tipo) ?? 'N/A' }} / {{ ucfirst($proveedor->servicio) ?? 'N/A' }}
              </td>
              <td class="px-4 py-2">
                <span class="{{ $proveedor->estado == 0 ? 'text-red-600' : 'text-green-600' }}">
                  {{ $proveedor->estado == 0 ? 'Inactivo' : 'Activo' }}
                </span>
              </td>
              <td class="px-4 py-2 flex justify-center gap-1">
                <button wire:click="verDetalle({{ $proveedor->id }})" class="btn-cyan" title="Ver detalle">Ver más</button>
                <button wire:click="editarProveedor({{ $proveedor->id }})" class="btn-cyan" title="Editar">Editar</button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-4 text-gray-600">No hay proveedores registrados.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>




  @if($modal)
    <div class="modal-overlay">
      <div class="modal-box">
        <div class="modal-content">
          <div class="flex flex-col gap-4">
            <div>
              <label class="text-u">Razón Social (requerido)</label>
              <input wire:model="razonSocial" class="input-minimal" placeholder="Ej. Proveedor S.A." />
              @error('razonSocial') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Contacto (opcional )</label>
              <input wire:model="nombreContacto" class="input-minimal" placeholder="Ej. Juan Pérez" />
              @error('nombreContacto') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Dirección (opcional )</label>
              <input wire:model="direccion" class="input-minimal" placeholder="Ej. Av. Siempre Viva 123" />
              @error('direccion') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Teléfono (opcional )</label>
              <input type="text" wire:model="telefono" class="input-minimal" placeholder="Ej. 70012345" />
              @error('telefono') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Correo (opcional )</label>
              <input type="email" wire:model="correo" class="input-minimal" placeholder="Ej. correo@proveedor.com" />
              @error('correo') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="font-semibold text-sm mb-2 block">Tipo</label>
              <div class="flex justify-center gap-3 mt-2">
                <button type="button" wire:click="$set('tipo', 'material')" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                      {{ $tipo === 'material'
      ? 'bg-cyan-600 text-white border-cyan-700 shadow-md'
      : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                  Material
                </button>
                <button type="button" wire:click="$set('tipo', 'servicio')" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                      {{ $tipo === 'servicio'
      ? 'bg-cyan-600 text-white border-cyan-700 shadow-md'
      : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                  Servicio
                </button>
              </div>
              @error('tipo') <span class="error-message">{{ $message }}</span> @enderror
            </div>



            <div>
              <label class="font-semibold text-sm mb-2 block">Servicio</label>
              <div class="flex justify-center gap-3 mt-2">
                <button type="button" wire:click="$set('servicio', 'compra')" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                          {{ $servicio === 'compra'
      ? 'bg-cyan-600 text-white border-cyan-700 shadow-md'
      : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                  Compra
                </button>
                <button type="button" wire:click="$set('servicio', 'servicio')" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                          {{ $servicio === 'servicio'
      ? 'bg-cyan-600 text-white border-cyan-700 shadow-md'
      : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                  Servicio
                </button>
                <button type="button" wire:click="$set('servicio', 'produccion')" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                          {{ $servicio === 'produccion'
      ? 'bg-cyan-600 text-white border-cyan-700 shadow-md'
      : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                  Producción
                </button>
              </div>
              @error('servicio') <span class="error-message">{{ $message }}</span> @enderror
            </div>


            <div>
              <label class="font-semibold text-sm mb-1 block">Descripción (opcional)</label>
              <input wire:model="descripcion" class="input-minimal" placeholder="Descripción del proveedor" />
              @error('descripcion') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Precio (Requerido)</label>
              <input type="number" wire:model="precio" class="input-minimal" placeholder="Ej. 100.50" />
              @error('precio') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Tiempo de Entrega (opcional)</label>
              <input wire:model="tiempoEntrega" class="input-minimal" placeholder="Ej. 3 días hábiles" />
              @error('tiempoEntrega') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-center gap-3 mt-2">
              <button type="button" wire:click="$set('estado', 1)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
        {{ $estado === 1
      ? 'bg-cyan-600 text-white border-cyan-700 shadow-md'
      : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                Activo
              </button>

              <button type="button" wire:click="$set('estado', 0)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
        {{ $estado === 0
      ? 'bg-gray-700 text-white border-gray-800 shadow-md'
      : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                Inactivo
              </button>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" wire:click="cerrarModal" class="btn-cyan" title="Cerrar">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
              <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
            </svg>
            CERRAR
          </button>
          <button type="button" wire:click="guardarProveedor" class="btn-cyan">
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

  <!-- Modal Ver Detalle Proveedor -->
  @if($detalleModal && $proveedorSeleccionado)
    <div class="modal-overlay">
      <div class="modal-box">
        <div class="modal-content flex flex-col gap-4">

          <div class="flex justify-center items-center">
            <div
              class="w-20 h-20 rounded-full bg-cyan-600 text-white flex items-center justify-center text-2xl font-bold">
              {{ strtoupper(substr($proveedorSeleccionado->razonSocial, 0, 1)) }}
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col gap-3">
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Razón Social:</span>
                <span class="badge-info">{{ $proveedorSeleccionado->razonSocial }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Contacto:</span>
                <span class="badge-info">{{ $proveedorSeleccionado->nombreContacto }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Dirección:</span>
                <span class="badge-info">{{ $proveedorSeleccionado->direccion }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Teléfono:</span>
                <span class="badge-info">{{ $proveedorSeleccionado->telefono }}</span>
              </div>
            </div>
            <div class="flex flex-col gap-3">
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Correo:</span>
                <span class="badge-info">{{ $proveedorSeleccionado->correo }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Tipo:</span>
                <span class="badge-info">{{ ucfirst($proveedorSeleccionado->tipo) }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Servicio:</span>
                <span class="badge-info">{{ ucfirst($proveedorSeleccionado->servicio) }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="label-info">Estado:</span>
                <span class="badge-info">
                  {{ $proveedorSeleccionado->estado ? 'Activo' : 'Inactivo' }}
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button wire:click="$set('detalleModal', false)" class="btn-cyan" title="Cerrar">
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
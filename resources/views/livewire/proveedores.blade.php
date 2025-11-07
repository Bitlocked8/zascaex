<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <h3
      class="col-span-full text-center text-2xl font-bold uppercase text-cyan-700 bg-cyan-100 px-6 py-2 rounded-full mx-auto">
      Proveedores
    </h3>

    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input type="text" wire:model.live="search" placeholder="Buscar por razón social, contacto o correo..."
        class="input-minimal w-full" />

      <button wire:click="abrirModal('create')" class="btn-cyan">
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

    @forelse($proveedores as $proveedor)
      <div class="card-teal flex flex-col gap-4 p-4">
        <div class="flex flex-col gap-2">
          <p class="text-emerald-600 uppercase font-semibold">
            {{ $proveedor->razonSocial }}
          </p>
          <p class="text-slate-600"> {{ $proveedor->nombreContacto ?? 'N/A' }}</p>
          <p><strong>Teléfono:</strong> {{ $proveedor->telefono ?? 'N/A' }}</p>
          <p><strong>Correo:</strong> {{ $proveedor->correo ?? 'N/A' }}</p>
          <p><strong>Tipo:</strong> {{ ucfirst($proveedor->tipo) }}</p>
          <p><strong>Servicio:</strong> {{ ucfirst($proveedor->servicio) }}</p>
          <p class="mt-1 text-sm font-semibold">
            <span class="{{ $proveedor->estado == 0 ? 'text-red-600' : 'text-green-600' }}">
              {{ $proveedor->estado == 0 ? 'Inactivo' : 'Activo' }}
            </span>
          </p>

        </div>

        <div class="flex flex-wrap justify-center md:justify-center gap-2 border-t border-gray-200 pt-3 pb-2">
          <button wire:click="verDetalle({{ $proveedor->id }})" class="btn-cyan" title="Ver detalle">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="9" />
              <path d="M8 12h.01M12 12h.01M16 12h.01" />
            </svg>
            Ver más
          </button>

          <button wire:click="editarProveedor({{ $proveedor->id }})" class="btn-cyan" title="Editar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
        No hay proveedores registrados.
      </div>
    @endforelse
  </div>



  @if($modal)
    <div class="modal-overlay">
      <div class="modal-box">
        <div class="modal-content">
          <div class="flex flex-col gap-4">
            <div>
              <label class="font-semibold text-sm mb-1 block">Razón Social</label>
              <input wire:model="razonSocial" class="input-minimal" placeholder="Ej. Proveedor S.A." />
              @error('razonSocial') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Contacto</label>
              <input wire:model="nombreContacto" class="input-minimal" placeholder="Ej. Juan Pérez" />
              @error('nombreContacto') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Dirección</label>
              <input wire:model="direccion" class="input-minimal" placeholder="Ej. Av. Siempre Viva 123" />
              @error('direccion') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Teléfono</label>
              <input type="text" wire:model="telefono" class="input-minimal" placeholder="Ej. 70012345" />
              @error('telefono') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Correo</label>
              <input type="email" wire:model="correo" class="input-minimal" placeholder="Ej. correo@proveedor.com" />
              @error('correo') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Tipo</label>
              <select wire:model="tipo" class="input-minimal">
                <option value="">-- Seleccionar Tipo --</option>
                <option value="tapas">Tapas</option>
                <option value="preformas">Preformas</option>
                <option value="etiquetas">Etiquetas</option>
              </select>
              @error('tipo') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Servicio</label>
              <select wire:model="servicio" class="input-minimal">
                <option value="">-- Seleccionar Servicio --</option>
                <option value="soplado">Soplado</option>
                <option value="transporte">Transporte</option>
              </select>
              @error('servicio') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Descripción</label>
              <input wire:model="descripcion" class="input-minimal" placeholder="Descripción del proveedor" />
              @error('descripcion') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Precio</label>
              <input type="number" wire:model="precio" class="input-minimal" placeholder="Ej. 100.50" />
              @error('precio') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="font-semibold text-sm mb-1 block">Tiempo de Entrega</label>
              <input wire:model="tiempoEntrega" class="input-minimal" placeholder="Ej. 3 días hábiles" />
              @error('tiempoEntrega') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-wrap justify-center gap-2 mt-2">
              @foreach([1 => 'Activo', 0 => 'Inactivo'] as $key => $label)
                <button type="button" wire:click="$set('estado', {{ $key }})"
                  class="px-4 py-2 rounded-full text-sm flex items-center justify-center{{ $estado == $key ? 'bg-cyan-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
                  {{ $label }}
                </button>
              @endforeach
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
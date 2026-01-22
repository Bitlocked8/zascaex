<div class="p-2 mt-20 flex justify-center bg-transparent">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <h3
      class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto">
      Sucursales
    </h3>

    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input type="text" wire:model.live="search" placeholder="Buscar por nombre o dirección..."
        class="input-minimal w-full" />

      <button wire:click="abrirModal('create')" class="btn-cyan">
        Añadir
      </button>
    </div>

    @forelse($sucursales as $sucursal)
    <div class="card-teal flex flex-col gap-4">
      <div class="flex flex-col gap-1">
        <p class="text-emerald-600 uppercase font-semibold">
          {{ $sucursal->nombre }}
        </p>
        <p class="text-slate-600">{{ $sucursal->direccion }}</p>
        <p><strong>Teléfono:</strong> {{ $sucursal->telefono ?? 'N/A' }}</p>
        <p><strong>Zona:</strong> {{ $sucursal->zona ?? 'N/A' }}</p>
        <p><strong>Empresa:</strong> {{ $sucursal->empresa->nombre ?? 'N/A' }}</p>
      </div>

      <div class="flex flex-wrap justify-center md:justify-center gap-2 border-t border-gray-200 pt-3 pb-2">
        <button wire:click="editarSucursal({{ $sucursal->id }})" class="btn-cyan" title="Editar">

          Editar
        </button>
        <button wire:click="verDetalle({{ $sucursal->id }})" class="btn-cyan" title="Ver detalle">
          Ver mas
        </button>
        <button wire:click="abrirModalPagosSucursal({{ $sucursal->id }})" class="btn-cyan" title="Ver/Agregar Pagos">
          Pagos
        </button>
      </div>
    </div>
    @empty
    <div class="col-span-full text-center py-4 text-gray-600">
      No hay sucursales registradas.
    </div>
    @endforelse

  </div>


  @if($modal)
  <div class="modal-overlay">
    <div class="modal-box">
      <div class="modal-content">
        <div class="flex flex-col gap-4">
          <div>
            <label class="font-semibold text-sm mb-1 block">Nombre</label>
            <input wire:model="nombre" class="input-minimal" placeholder="Ej. Sucursal Central" />
            @error('nombre') <span class="error-message">{{ $message }}</span> @enderror
          </div>
          <div>
            <label class="font-semibold text-sm mb-1 block">Dirección</label>
            <input wire:model="direccion" class="input-minimal" placeholder="Ej. Av. Principal #123" />
            @error('direccion') <span class="error-message">{{ $message }}</span> @enderror
          </div>
          <div>
            <label class="font-semibold text-sm mb-1 block">Teléfono</label>
            <input type="text" wire:model="telefono" class="input-minimal" placeholder="Ej. 44556677" />
            @error('telefono') <span class="error-message">{{ $message }}</span> @enderror
          </div>
          <div>
            <label class="font-semibold text-sm mb-1 block">Zona</label>
            <input type="text" wire:model="zona" class="input-minimal" placeholder="Ej. Norte" />
            @error('zona') <span class="error-message">{{ $message }}</span> @enderror
          </div>
          <div>
            <label class="font-semibold text-sm mb-2 block">Empresa</label>

            <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white
                 grid grid-cols-1 gap-2 max-h-[170px] overflow-y-auto">
              @forelse($empresas as $empresa)
              <button type="button" wire:click="$set('empresa_id', {{ $empresa->id }})" class="w-full px-3 py-2 rounded-md border text-left transition
                                {{ $empresa_id == $empresa->id
                  ? 'bg-cyan-600 text-white border-cyan-700 shadow'
                  : 'bg-gray-100 text-gray-800 hover:bg-cyan-100'
                                }}">

                <p class="font-semibold text-sm">{{ $empresa->nombre }}</p>
              </button>
              @empty
              <p class="text-center text-gray-500 py-3 text-sm">No hay empresas disponibles</p>
              @endforelse
            </div>
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
        <button type="button" wire:click="guardarSucursal" class="btn-cyan">
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

  @if($modalPagosSucursal)
  <div class="modal-overlay">
    <div class="modal-box max-w-3xl">
      <div class="modal-content flex flex-col gap-4">
        <div class="space-y-4">
          @foreach($pagosSucursal as $index => $pago)
          <div class="border p-4 rounded flex flex-col gap-2">
            <div class="flex justify-between items-center">
              <strong>Nombre: {{ $pago['nombre'] ?? 'Nuevo Pago' }}</strong>
              <button type="button" wire:click="eliminarPagoSucursal({{ $index }})" class="btn-circle btn-cyan"
                title="Eliminar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M4 7l16 0" />
                  <path d="M10 11l0 6" />
                  <path d="M14 11l0 6" />
                  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                </svg>
              </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">

              <div>
                <label class="font-semibold text-sm">Nombre</label>
                <input type="text" wire:model="pagosSucursal.{{ $index }}.nombre" class="input-minimal">
              </div>

              <div>
                <label class="font-semibold text-sm">Tipo</label>
                <input type="text" wire:model="pagosSucursal.{{ $index }}.tipo" class="input-minimal"
                  placeholder="QR / Transferencia">
              </div>

              <div>
                <label class="font-semibold text-sm">Número de Cuenta</label>
                <input type="text" wire:model="pagosSucursal.{{ $index }}.numero_cuenta" class="input-minimal">
              </div>

              <div>
                <label class="font-semibold text-sm">Titular</label>
                <input type="text" wire:model="pagosSucursal.{{ $index }}.titular" class="input-minimal">
              </div>

              <div class="sm:col-span-2">
                <label class="font-semibold text-sm">Imagen QR</label>
                <input type="file" wire:model="pagosSucursal.{{ $index }}.imagen_qr" class="input-minimal">

                @php
                $imagenUrl = null;

                if (isset($pagosSucursal[$index]['imagen_qr'])) {

                $archivo = $pagosSucursal[$index]['imagen_qr'];

                if (is_string($archivo)) {
                $imagenUrl = Storage::url($archivo);
                } elseif ($archivo instanceof \Livewire\TemporaryUploadedFile) {
                if ($archivo->isPreviewable()) {
                $imagenUrl = $archivo->temporaryUrl();
                } else {
                $path = $archivo->store('pagos_sucursal', 'public');
                $imagenUrl = Storage::url($path);
                }
                }
                }
                @endphp


                @if($imagenUrl)
                <div class="mt-2 flex flex-col items-center space-y-2">
                  <img src="{{ $imagenUrl }}" alt="QR"
                    class="w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg object-cover rounded-lg shadow cursor-pointer"
                    wire:click="$set('imagenPreviewModal', '{{ $imagenUrl }}'); $set('modalImagenAbierta', true)">
                  @if(is_string($pagosSucursal[$index]['imagen_qr']))
                  <a href="{{ $imagenUrl }}" download class="btn-circle btn-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                      <path d="M7 11l5 5l5 -5" />
                      <path d="M12 4l0 12" />
                    </svg>
                  </a>
                  @endif
                </div>
                @endif
              </div>

              <div>
                <label class="font-semibold text-sm">Estado</label>
                <select wire:model="pagosSucursal.{{ $index }}.estado" class="input-minimal">
                  <option value="1">Activo</option>
                  <option value="0">Inactivo</option>
                </select>
              </div>

            </div>
          </div>
          @endforeach
        </div>

        <div class="modal-footer">
          <button type="button" wire:click="agregarPagoSucursal" class="btn-cyan">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path
                d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
              <path
                d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
            </svg>
            añadir pago
          </button>


          <button type="button" wire:click="guardarPagosSucursal" class="btn-cyan">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
              <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
              <path d="M14 4l0 4l-6 0l0 -4" />
            </svg>
            guardar pago
          </button>

          <button type="button" wire:click="$set('modalPagosSucursal', false)" class="btn-cyan" title="Cerrar">
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
  </div>
  @endif


  @if($detalleModal && $sucursalSeleccionada)
  <div class="modal-overlay">
    <div class="modal-box">
      <div class="modal-content flex flex-col gap-4">

        <div class="flex justify-center items-center">
          <div
            class="w-20 h-20 rounded-full bg-cyan-600 text-white flex items-center justify-center text-2xl font-bold">
            {{ strtoupper(substr($sucursalSeleccionada->nombre, 0, 1)) }}
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
              <span class="label-info">Nombre:</span>
              <span class="badge-info">{{ $sucursalSeleccionada->nombre }}</span>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
              <span class="label-info">Dirección:</span>
              <span class="badge-info">{{ $sucursalSeleccionada->direccion ?? '-' }}</span>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
              <span class="label-info">Teléfono:</span>
              <span class="badge-info">{{ $sucursalSeleccionada->telefono ?? '-' }}</span>
            </div>
          </div>

          <div class="flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
              <span class="label-info">Zona:</span>
              <span class="badge-info">{{ $sucursalSeleccionada->zona ?? '-' }}</span>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
              <span class="label-info">Empresa:</span>
              <span class="badge-info">{{ $sucursalSeleccionada->empresa->nombre ?? '-' }}</span>
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
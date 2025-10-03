<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input
        type="text"
        wire:model.live="search"
        placeholder="Buscar por nombre o dirección..."
        class="input-minimal w-full" />
      <button wire:click="abrirModal('create')" class="btn-circle btn-cyan">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
          viewBox="0 0 24 24" fill="currentColor">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path
            d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
          <path
            d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
        </svg>
      </button>
    </div>

    @forelse($sucursales as $sucursal)
    <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
      <div class="flex flex-col col-span-9 text-left space-y-1">
        <p><strong>Nombre:</strong> {{ $sucursal->nombre }}</p>
        <p><strong>Dirección:</strong> {{ $sucursal->direccion }}</p>
        <p><strong>Teléfono:</strong> {{ $sucursal->telefono ?? 'N/A' }}</p>
        <p><strong>Zona:</strong> {{ $sucursal->zona ?? 'N/A' }}</p>
        <p><strong>Empresa:</strong> {{ $sucursal->empresa->nombre ?? 'N/A' }}</p>
      </div>

      <div class="flex flex-col items-end gap-4 col-span-3">
        <button wire:click="editarSucursal({{ $sucursal->id }})" class="btn-circle btn-cyan">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
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

        <button wire:click="verDetalle({{ $sucursal->id }})" class="btn-circle btn-cyan" title="Ver Detalle">
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
            <label class="font-semibold text-sm mb-1 block">Empresa</label>
            <select wire:model="empresa_id" class="input-minimal">
              <option value="">-- Seleccionar Empresa --</option>
              @foreach($empresas as $empresa)
              <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
              @endforeach
            </select>
            @error('empresa_id') <span class="error-message">{{ $message }}</span> @enderror
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" wire:click="guardarSucursal" class="btn-circle btn-cyan" title="Guardar">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
            <path d="M14 4l0 4l-6 0l0 -4" />
          </svg>
        </button>
        <button type="button" wire:click="cerrarModal" class="btn-circle btn-cyan" title="Cerrar">
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

  @if($detalleModal && $sucursalSeleccionada)
  <div class="modal-overlay">
    <div class="modal-box">
      <div class="modal-content flex flex-col gap-4">

        <div class="flex justify-center items-center">
          <div class="w-20 h-20 rounded-full bg-cyan-600 text-white flex items-center justify-center text-2xl font-bold">
            {{ strtoupper(substr($sucursalSeleccionada->nombre,0,1)) }}
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
        <button wire:click="$set('detalleModal', false)" class="btn-circle btn-cyan" title="Cerrar">
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
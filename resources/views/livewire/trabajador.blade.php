<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input
        type="text"
        wire:model.live="search"
        placeholder="Buscar por sucursal o personal..."
        class="input-minimal w-full" />
      <button wire:click="abrirModal('create')" class="btn-circle btn-cyan">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
          <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
        </svg>
      </button>
      <button wire:click="abrirModalLabores" class="btn-circle btn-cyan">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M3 9a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9z" />
          <path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" />
        </svg>
      </button>
    </div>

    @forelse($trabajos as $trabajo)
    <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
      <div class="flex flex-col col-span-9 text-left space-y-1">
        <p><strong>Fecha Inicio:</strong> {{ $trabajo->fechaInicio }}</p>
        <p><strong>Fecha Final:</strong> {{ $trabajo->fechaFinal ?? 'N/A' }}</p>
        <p><strong>Sucursal:</strong> {{ $trabajo->sucursal->nombre ?? 'N/A' }}</p>
        <p><strong>Personal:</strong> {{ $trabajo->personal->nombres ?? 'N/A' }}</p>
        <p><strong>Estado:</strong>
          @if($trabajo->estado)
          <span class="text-white bg-green-600 px-2 py-1 rounded-full">Activo</span>
          @else
          <span class="text-white bg-red-600 px-2 py-1 rounded-full">Inactivo</span>
          @endif
        </p>
      </div>

      <!-- Botones de acción -->
      <div class="flex flex-col items-end gap-4 col-span-3">
        <button wire:click="abrirModal('edit', {{ $trabajo->id }})" class="btn-circle btn-cyan">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke="none" d="M0 0h24v24H0z" />
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
        <button wire:click="modaldetalle({{ $trabajo->id }})" class="btn-circle btn-cyan" title="Ver Detalle">
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
      No hay trabajos registrados.
    </div>
    @endforelse
  </div>

  @if($modal)
  <div class="modal-overlay">
    <div class="modal-box">
      <div class="modal-content flex flex-col gap-4">

        <!-- Fechas -->
        <div class="grid grid-cols-2 gap-2">
          <div>
            <p class="font-semibold text-sm">
              Fecha de Inicio: <span class="font-normal">{{ $fechaInicio ?? 'N/A' }}</span>
            </p>
          </div>
          <div>
            <label class="font-semibold text-sm">Fecha Final</label>
            <input type="date" wire:model="fechaFinal" class="input-minimal">
            @error('fechaFinal') <span class="error-message">{{ $message }}</span> @enderror
          </div>
        </div>


        <!-- Estado -->
        <div>
          <label class="font-semibold text-sm">Estado</label>
          <div class="flex flex-wrap gap-2 mt-2">
            @foreach(['1' => 'Activo', '0' => 'Inactivo'] as $st => $label)
            <button type="button"
              wire:click="$set('estado','{{ $st }}')"
              class="px-4 py-2 rounded-full text-sm
                        {{ $estado == $st ? 'bg-cyan-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
              {{ $label }}
            </button>
            @endforeach
          </div>
          @error('estado') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <!-- Sucursal -->
        <div>
          <label class="font-semibold text-sm mb-2 block">Sucursal</label>
          <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 max-h-[170px] overflow-y-auto">
            @foreach($sucursales as $s)
            <button type="button" wire:click="$set('sucursal_id', {{ $s->id }})"
              class="w-full px-3 py-2 rounded-md border text-sm text-left transition
                        {{ $sucursal_id == $s->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
              {{ $s->nombre }}
            </button>
            @endforeach
          </div>
          @error('sucursal_id') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <!-- Personal -->
        <div>
          <label class="font-semibold text-sm mb-2 block">Personal Responsable</label>
          <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 max-h-[170px] overflow-y-auto">
            @foreach($personales as $p)
            <button type="button" wire:click="$set('personal_id', {{ $p->id }})"
              class="w-full px-3 py-2 rounded-md border text-sm text-left transition
                        {{ $personal_id == $p->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
              {{ $p->nombres }}
            </button>
            @endforeach
          </div>
          @error('personal_id') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <!-- Labor -->
        <div>
          <label class="font-semibold text-sm mb-2 block">Labor</label>
          <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 max-h-[170px] overflow-y-auto">
            @foreach(\App\Models\Labor::where('estado',1)->get() as $l)
            <button type="button" wire:click="$set('labor_id', {{ $l->id }})"
              class="w-full px-3 py-2 rounded-md border text-sm text-left transition
                       {{ $labor_id == $l->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
              {{ $l->nombre }}
            </button>
            @endforeach
          </div>
          @error('labor_id') <span class="error-message">{{ $message }}</span> @enderror
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" wire:click="guardar" class="btn-circle btn-cyan">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
            <path d="M14 4l0 4l-6 0l0 -4" />
          </svg>
        </button>
        <button type="button" wire:click="cerrarModal" class="btn-circle btn-cyan">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" />
            <path d="M10 10l4 4m0 -4l-4 4" />
            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
          </svg>
        </button>
      </div>
    </div>
  </div>
  @endif

  @if($modalLabores)
  <div class="modal-overlay">
    <div class="modal-box">
      <div class="modal-content flex flex-col gap-4">
        <div class="space-y-4 max-h-[400px] overflow-y-auto">
          @foreach($labores as $index => $labor)
          <div class="border p-4 rounded flex flex-col gap-2">
            <div class="flex justify-between items-center">
              <strong>Labor #{{ $index + 1 }}</strong>
              <button type="button" wire:click="eliminarLabor({{ $index }})" class="btn-circle btn-cyan"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M4 7l16 0" />
                  <path d="M10 11l0 6" />
                  <path d="M14 11l0 6" />
                  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                </svg></button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
              <div>
                <label class="font-semibold text-sm">Nombre</label>
                <input type="text" wire:model="labores.{{ $index }}.nombre" class="input-minimal">
              </div>
              <div>
                <label class="font-semibold text-sm">Descripción</label>
                <input type="text" wire:model="labores.{{ $index }}.descripcion" class="input-minimal">
              </div>
              <div class="sm:col-span-2">
                <label class="font-semibold text-sm">Estado</label>
                <select wire:model="labores.{{ $index }}.estado" class="input-minimal w-full">
                  <option value="1">Activo</option>
                  <option value="0">Inactivo</option>
                </select>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <div class="modal-footer">
          <button type="button" wire:click="agregarLabor" class="btn-circle btn-cyan">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
              <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
            </svg></button>

          <button type="button" wire:click="guardarLabores" class="btn-circle btn-cyan">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
              <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
              <path d="M14 4l0 4l-6 0l0 -4" />
            </svg></button>

          <button type="button" wire:click="$set('modalLabores', false)" class="btn-circle btn-cyan" title="Cerrar">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
              fill="none" viewBox="0 0 24 24" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" />
              <path d="M10 10l4 4m0 -4l-4 4" />
              <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
            </svg></button>
        </div>
      </div>
    </div>
  </div>
  @endif

  @if($modalDetalle)
  <div class="modal-overlay">
    <div class="modal-box">
      <div class="modal-content flex flex-col gap-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

          <div class="flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row sm:items-start gap-2">
              <span class="label-info">Sucursal:</span>
              <span class="badge-info">{{ $trabajoSeleccionado->sucursal->nombre ?? '-' }}</span>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-start gap-2">
              <span class="label-info">Personal:</span>
              <span class="badge-info">{{ $trabajoSeleccionado->personal->nombres ?? '-' }}</span>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-start gap-2">
              <span class="label-info">Labor:</span>
              <span class="badge-info">{{ $trabajoSeleccionado->labor->nombre ?? '-' }}</span>
            </div>
          </div>

          <div class="flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row sm:items-start gap-2">
              <span class="label-info">Estado:</span>
              <span class="badge-info">
                {{ $trabajoSeleccionado->estado ? 'Activo' : 'Inactivo' }}
              </span>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-start gap-2">
              <span class="label-info">Fecha Inicio:</span>
              <span class="badge-info">{{ \Carbon\Carbon::parse($trabajoSeleccionado->fechaInicio)->format('Y-m-d') ?? '-' }}</span>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-start gap-2">
              <span class="label-info">Fecha Final:</span>
              <span class="badge-info">{{ $trabajoSeleccionado->fechaFinal ? \Carbon\Carbon::parse($trabajoSeleccionado->fechaFinal)->format('Y-m-d') : '-' }}</span>
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button wire:click="$set('modalDetalle', false)" class="btn-circle btn-cyan" title="Cerrar">
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
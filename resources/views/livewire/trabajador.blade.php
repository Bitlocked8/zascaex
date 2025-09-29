<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- Barra de búsqueda y botón de crear -->
    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input
        type="text"
        wire:model.live="search"
        placeholder="Buscar por sucursal o personal..."
        class="input-minimal w-full" />
      <button wire:click="abrirModal('create')" class="btn-circle btn-cyan">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M12 5v14m-7-7h14" />
        </svg>
      </button>
    </div>

    <!-- Lista de trabajos -->
    @forelse($trabajos as $trabajo)
    <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
      <div class="flex flex-col col-span-9 text-left space-y-1">
        <p><strong>Fecha Inicio:</strong> {{ $trabajo->fechaInicio }}</p>
        <p><strong>Fecha Final:</strong> {{ $trabajo->fechaFinal ?? 'N/A' }}</p>
        <p><strong>Sucursal:</strong> {{ $trabajo->sucursal->nombre ?? 'N/A' }}</p>
        <p><strong>Personal:</strong> {{ $trabajo->personal->nombre ?? 'N/A' }}</p>
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
        <button wire:click="abrirModal('edit', {{ $trabajo->id }})" class="btn-circle btn-cyan" title="Editar">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z"/>
          </svg>
        </button>
        <button wire:click="modaldetalle({{ $trabajo->id }})" class="btn-circle btn-cyan" title="Ver Detalle">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a3 3 0 100-6 3 3 0 000 6z"/>
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
</div>

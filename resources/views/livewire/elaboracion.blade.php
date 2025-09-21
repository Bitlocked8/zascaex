<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- Buscar + Crear Elaboración -->
    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input type="text" wire:model.live="search" placeholder="Buscar por observaciones..."
        class="flex-1 border rounded px-3 py-2" />

      <button wire:click="abrirModal('create')"
        class="bg-cyan-500 hover:bg-cyan-600 rounded-xl px-4 py-2 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
          stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="9" />
          <line x1="12" y1="9" x2="12" y2="15" />
          <line x1="9" y1="12" x2="15" y2="12" />
        </svg>
      </button>
    </div>

    @forelse($elaboraciones as $elaboracion)
    <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
      <!-- Columna Izquierda: Info Elaboración -->
      <div class="flex flex-col col-span-8 text-left">
        <p><strong>Entrada:</strong> {{ $elaboracion->existenciaEntrada->existenciable->nombre ?? 'N/A' }} ({{ $elaboracion->cantidad_entrada }})</p>
        <p><strong>Salida:</strong> {{ $elaboracion->existenciaSalida->existenciable->nombre ?? 'N/A' }} ({{ $elaboracion->cantidad_salida }})</p>
        <p><strong>Personal:</strong> {{ $elaboracion->personal->nombre ?? 'N/A' }}</p>
        <p><strong>Fecha:</strong> {{ $elaboracion->fecha_elaboracion }}</p>
        <p><strong>Merma:</strong> {{ $elaboracion->merma }}</p>
        <p><strong>Observaciones:</strong> {{ $elaboracion->observaciones ?? '-' }}</p>
      </div>

      <div class="flex flex-col items-end gap-4 col-span-4">
        <!-- Editar Elaboración -->
        <button wire:click="abrirModal('edit', {{ $elaboracion->id }})"
          class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center text-cyan-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6 6L7 21l-6-6 6-6z" />
          </svg>
        </button>

        <!-- Ver Detalle -->
        <button wire:click="modaldetalle({{ $elaboracion->id }})"
          class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center text-cyan-600">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </button>
      </div>
    </div>
    @empty
    <div class="col-span-full text-center py-4 text-gray-600">
      No hay elaboraciones registradas.
    </div>
    @endforelse
  </div>


  @if($modal)
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white text-cyan-950 rounded-lg shadow-lg w-full max-w-4xl p-6 relative overflow-y-auto max-h-[90vh]">
      <h2 class="text-xl font-semibold mb-6 text-center">
        {{ $accion === 'create' ? 'Registrar Elaboración' : 'Editar Elaboración' }}
      </h2>

      <div class="grid grid-cols-12 gap-4">

        <div class="col-span-12 md:col-span-6 flex flex-col gap-4">

          <!-- Entrada: preformas -->
          <select wire:model="existencia_entrada_id" class="input-minimal">
            <option value="">Seleccionar Preforma</option>
            @foreach($preformas as $ex)
            <option value="{{ $ex->id }}">
              {{ $ex->existenciable->nombre ?? 'Artículo' }} (Stock: {{ $ex->cantidad }})
            </option>
            @endforeach
          </select>

          <!-- Salida: bases -->
          <select wire:model="existencia_salida_id" class="input-minimal">
            <option value="">Seleccionar Base</option>
            @foreach($bases as $ex)
            <option value="{{ $ex->id }}">
              {{ $ex->existenciable->nombre ?? 'Artículo' }} (Stock: {{ $ex->cantidad }})
            </option>
            @endforeach
          </select>



          <!-- Personal -->
          <label class="font-semibold text-sm">Personal Responsable</label>
          <select wire:model="personal_id" class="input-minimal">
            <option value="">Seleccionar Personal</option>
            @foreach($personals as $p)
            <option value="{{ $p->id }}">{{ $p->nombre }}</option>
            @endforeach
          </select>
          @error('personal_id') <span class="error-message">{{ $message }}</span> @enderror

          <!-- Cantidades -->
          <label class="font-semibold text-sm">Cantidad Entrada</label>
          <input type="number" wire:model="cantidad_entrada" placeholder="Cantidad Entrada" class="input-minimal">
          @error('cantidad_entrada') <span class="error-message">{{ $message }}</span> @enderror

          <label class="font-semibold text-sm">Cantidad Salida</label>
          <input type="number" wire:model="cantidad_salida" placeholder="Cantidad Salida" class="input-minimal">
          @error('cantidad_salida') <span class="error-message">{{ $message }}</span> @enderror

          <!-- Fecha -->
          <label class="font-semibold text-sm">Fecha de Elaboración</label>
          <input type="date" wire:model="fecha_elaboracion" class="input-minimal">
          @error('fecha_elaboracion') <span class="error-message">{{ $message }}</span> @enderror

          <!-- Merma -->
          <label class="font-semibold text-sm">Merma</label>
          <input type="number" wire:model="merma" placeholder="Merma" class="input-minimal">
          @error('merma') <span class="error-message">{{ $message }}</span> @enderror

          <!-- Observaciones -->
          <label class="font-semibold text-sm">Observaciones</label>
          <textarea wire:model="observaciones" placeholder="Observaciones" class="input-minimal"></textarea>
          @error('observaciones') <span class="error-message">{{ $message }}</span> @enderror

        </div>

        <div class="col-span-12 flex justify-center md:justify-end gap-4 mt-4 md:mt-0">
          <button type="button" wire:click="guardar" class="bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl px-4 py-2">
            Guardar
          </button>
          <button type="button" wire:click="cerrarModal" class="bg-gray-300 hover:bg-gray-400 rounded-xl px-4 py-2">
            Cancelar
          </button>
        </div>

      </div>
    </div>
  </div>
  @endif




</div>
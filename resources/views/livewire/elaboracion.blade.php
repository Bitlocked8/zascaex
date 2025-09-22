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
        <p><strong>Entrada:</strong> {{ $elaboracion->existenciaEntrada->existenciable->descripcion ?? 'N/A' }} ({{ $elaboracion->cantidad_entrada }})</p>
        <p><strong>Salida:</strong> {{ $elaboracion->existenciaSalida->existenciable->descripcion ?? 'N/A' }} ({{ $elaboracion->cantidad_salida }})</p>
        <p><strong>Personal:</strong> {{ $elaboracion->personal->nombres ?? 'N/A' }}</p>
        <p><strong>Fecha:</strong> {{ $elaboracion->fecha_elaboracion }}</p>
        <p><strong>Merma:</strong> {{ $elaboracion->merma }}</p>
        <p><strong>Observaciones:</strong> {{ $elaboracion->observaciones ?? '-' }}</p>
      </div>

      <div class="flex flex-col items-end gap-4 col-span-4">
        <!-- Editar Elaboración -->
        <button wire:click="abrirModal('edit', {{ $elaboracion->id }})"
          class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600">
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

        <!-- Ver Detalle -->
        <button wire:click="modaldetalle({{ $elaboracion->id }})"
          class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-cyan-600" fill="none"
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

      <!-- Grid 2 columnas en desktop, 1 en móvil -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Columna izquierda -->
        <div class="flex flex-col gap-4">
          <input type="text" wire:model="codigo" class="input-minimal bg-gray-100" readonly>
          <input type="date" wire:model="fecha_elaboracion" class="input-minimal" readonly>
          @error('fecha_elaboracion') <span class="error-message">{{ $message }}</span> @enderror

          <!-- Botones sucursal -->
          <div class="flex flex-wrap gap-2 mb-2">
            @foreach(\App\Models\Sucursal::all() as $sucursal)
            <button
              wire:click="filtrarPorSucursal({{ $sucursal->id }})"
              class="px-3 py-1 rounded text-sm
            @if($sucursalSeleccionada == $sucursal->id) bg-cyan-600 text-white @else bg-white @endif">
              {{ $sucursal->nombre }}
            </button>
            @endforeach
          </div>

          <!-- Preforma + Cantidad Entrada -->
          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="font-semibold text-sm">Preforma (Entrada)</label>
              <select wire:model="existencia_entrada_id" class="input-minimal">
                <option value="">Seleccionar Preforma</option>
                @foreach($preformas as $ex)
                <option value="{{ $ex->id }}">
                  {{ $ex->existenciable->descripcion ?? 'Artículo' }} (Stock: {{ $ex->cantidad }})
                </option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="font-semibold text-sm">Cantidad Entrada</label>
              <input type="number" wire:model="cantidad_entrada" placeholder="Cantidad Entrada" class="input-minimal">
              @error('cantidad_entrada') <span class="error-message">{{ $message }}</span> @enderror
            </div>
          </div>

          <!-- Base + Cantidad Salida -->
          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="font-semibold text-sm">Base (Salida)</label>
              <select wire:model="existencia_salida_id" class="input-minimal">
                <option value="">Seleccionar Base</option>
                @foreach($bases as $ex)
                <option value="{{ $ex->id }}">
                  {{ $ex->existenciable->descripcion ?? 'Artículo' }} (Stock: {{ $ex->cantidad }})
                </option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="font-semibold text-sm">Cantidad Salida</label>
              <input type="number" wire:model="cantidad_salida" placeholder="Cantidad Salida" class="input-minimal">
              @error('cantidad_salida') <span class="error-message">{{ $message }}</span> @enderror
            </div>
          </div>


          <!-- Personal -->
          <label class="font-semibold text-sm">Personal Responsable</label>
          <select wire:model="personal_id" class="input-minimal">
            <option value="">Seleccionar Personal</option>
            @foreach($personals as $p)
            <option value="{{ $p->id }}">{{ $p->nombres }}</option>
            @endforeach
          </select>
          @error('personal_id') <span class="error-message">{{ $message }}</span> @enderror
        </div>

        <!-- Columna derecha -->
        <div class="flex flex-col gap-4">
          <!-- Código de lote -->


          <!-- Estado como botones -->
          <label class="font-semibold text-sm">Estado</label>
          <div class="flex gap-2">
            @foreach(['pendiente','procesado','completado'] as $st)
            <button
              type="button"
              wire:click="$set('estado','{{ $st }}')"
              class="px-3 py-1 rounded text-sm
              @if($estado == $st) bg-cyan-600 text-white @else bg-white border @endif">
              {{ ucfirst($st) }}
            </button>
            @endforeach
          </div>



          <!-- Merma -->
          <label class="font-semibold text-sm">Merma</label>
          <input type="number" wire:model="merma" placeholder="Merma" class="input-minimal">
          @error('merma') <span class="error-message">{{ $message }}</span> @enderror

          <!-- Observaciones -->
          <label class="font-semibold text-sm">Observaciones</label>
          <textarea wire:model="observaciones" placeholder="Observaciones" class="input-minimal"></textarea>
          @error('observaciones') <span class="error-message">{{ $message }}</span> @enderror
        </div>
      </div>

      <!-- Botones abajo -->
      <div class="flex justify-center md:justify-end gap-4 mt-6">
        <button type="button" wire:click="guardar" class="bg-cyan-500 hover:bg-cyan-600 text-white rounded-xl px-4 py-2">
          Guardar
        </button>
        <button type="button" wire:click="cerrarModal" class="bg-gray-300 hover:bg-gray-400 rounded-xl px-4 py-2">
          Cancelar
        </button>
      </div>
    </div>
  </div>
  @endif





</div>
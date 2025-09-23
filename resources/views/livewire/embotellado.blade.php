<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- Barra de búsqueda y botón de crear -->
    <div class="flex items-center gap-2 mb-4 col-span-full">
      <input type="text" wire:model.live="search" placeholder="Buscar por código o observación..." class="input-minimal" />

      <button wire:click="abrirModal('create')" class="btn-circle btn-cyan">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
          <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
        </svg>
      </button>
    </div>

    @forelse($embotellados as $embotellado)
    <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">

      <!-- Información -->
      <div class="flex flex-col col-span-8 text-left space-y-1">
        <p><strong>Código:</strong> {{ $embotellado->codigo }}</p>
        <p><strong>Fecha:</strong> {{ $embotellado->fecha_embotellado }}</p>
        <p><strong>Personal:</strong> {{ $embotellado->personal->nombres ?? 'N/A' }}</p>
        <p><strong>Base usada:</strong> {{ $embotellado->existenciaBase->existenciable->descripcion ?? 'N/A' }} ({{ $embotellado->cantidad_base_usada }})</p>
        <p><strong>Tapa usada:</strong> {{ $embotellado->existenciaTapa->existenciable->descripcion ?? 'N/A' }} ({{ $embotellado->cantidad_tapa_usada }})</p>
        <p><strong>Producto generado:</strong> {{ $embotellado->existenciaProducto->existenciable->descripcion ?? 'N/A' }} ({{ $embotellado->cantidad_generada ?? 0 }})</p>
        <p><strong>Estado:</strong> {{ ucfirst($embotellado->estado) }}</p>
      </div>

      <!-- Botones de acción -->
      <div class="flex flex-col items-end gap-4 col-span-4">
        <button wire:click="abrirModal('edit', {{ $embotellado->id }})" class="btn-circle btn-cyan">
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

        <button wire:click="modaldetalle({{ $embotellado->id }})" class="btn-circle btn-cyan">
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
      No hay embotellados registrados.
    </div>
    @endforelse
  </div>
  @if($modal)
  <div class="modal-overlay">
    <div class="modal-box">
      <div class="modal-content">
        <div class="flex flex-col gap-4">

          <!-- Código y Fecha -->
          <div class="flex flex-col gap-2">
            <p class="font-semibold text-sm">Código: <span class="font-normal">{{ $codigo }}</span></p>
            <p class="font-semibold text-sm">Fecha de Embotellado: <span class="font-normal">{{ $fecha_embotellado }}</span></p>
          </div>

          <!-- Selección de Sucursal -->
          <div class="flex flex-wrap justify-center gap-2 mb-2">
            @if($accion === 'edit' && $sucursalSeleccionada)
            @php $sucursal = \App\Models\Sucursal::find($sucursalSeleccionada); @endphp
            <button class="badge-info flex items-center gap-2" disabled>
              <span>&#10003;</span> {{ $sucursal->nombre }}
            </button>
            @else
            @foreach(\App\Models\Sucursal::all() as $sucursal)
            <button
              wire:click="filtrarPorSucursal({{ $sucursal->id }})"
              class="badge-info {{ $sucursalSeleccionada == $sucursal->id ? 'bg-cyan-600 text-white' : 'bg-gray-200 text-gray-700' }}">
              @if($sucursalSeleccionada == $sucursal->id)<span>&#10003;</span>@endif
              {{ $sucursal->nombre }}
            </button>
            @endforeach
            @endif
          </div>

          <!-- Base y Tapa -->
          <div class="grid grid-cols-2 gap-2">
            <!-- Base -->
            <div>
              <label class="font-semibold text-sm mb-2 block">Base (Entrada)</label>
              <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[170px]">
                @if($accion === 'edit' && $existencia_base_id)
                @php $base = $bases->firstWhere('id', $existencia_base_id); @endphp
                @if($base)
                <button class="w-full px-3 py-2 rounded-md border text-sm text-left bg-cyan-600 text-white" disabled>
                  {{ $base->existenciable->descripcion ?? 'Artículo' }} (Stock: {{ $base->cantidad }})
                </button>
                @else
                <span class="text-gray-500 text-sm">Artículo no disponible en esta sucursal</span>
                @endif
                @else
                @foreach($bases as $b)
                <button type="button" wire:click="$set('existencia_base_id', {{ $b->id }})"
                  class="w-full px-3 py-2 rounded-md border text-sm text-left transition
            {{ $existencia_base_id == $b->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                  {{ $b->existenciable->descripcion ?? 'Artículo' }} (Stock: {{ $b->cantidad }})
                </button>
                @endforeach
                @endif

              </div>
              @error('existencia_base_id') <span class="error-message">{{ $message }}</span> @enderror
            </div>

            <!-- Tapa -->
            <div>
              <label class="font-semibold text-sm mb-2 block">Tapa (Entrada)</label>
              <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[170px]">
                @if($accion === 'edit' && $existencia_tapa_id)
                @php $tapa = $tapas->firstWhere('id', $existencia_tapa_id); @endphp
                @if($tapa)
                <button class="w-full px-3 py-2 rounded-md border text-sm text-left bg-cyan-600 text-white" disabled>
                  {{ $tapa->existenciable->descripcion ?? 'Artículo' }} (Stock: {{ $tapa->cantidad }})
                </button>
                @else
                <span class="text-gray-500 text-sm">Artículo no disponible en esta sucursal</span>
                @endif
                @else
                @foreach($tapas as $t)
                <button type="button" wire:click="$set('existencia_tapa_id', {{ $t->id }})"
                  class="w-full px-3 py-2 rounded-md border text-sm text-left transition
            {{ $existencia_tapa_id == $t->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                  {{ $t->existenciable->descripcion ?? 'Artículo' }} (Stock: {{ $t->cantidad }})
                </button>
                @endforeach
                @endif
              </div>
              @error('existencia_tapa_id') <span class="error-message">{{ $message }}</span> @enderror
            </div>
          </div>
          <div>
            <label class="font-semibold text-sm mb-2 block">Producto Generado (Salida)</label>
            <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[120px]">
              @if($accion === 'edit' && $existencia_producto_id)
              @php $prod = $productos->firstWhere('id', $existencia_producto_id); @endphp
              @if($prod)
              <button class="w-full px-3 py-2 rounded-md border text-sm text-left bg-cyan-600 text-white" disabled>
                {{ $prod->existenciable->descripcion ?? 'Artículo' }} (Stock: {{ $prod->cantidad }})
              </button>
              @else
              <span class="text-gray-500 text-sm">Artículo no disponible en esta sucursal</span>
              @endif
              @else
              @foreach($productos as $p)
              <button type="button" wire:click="$set('existencia_producto_id', {{ $p->id }})"
                class="w-full px-3 py-2 rounded-md border text-sm text-left transition
            {{ $existencia_producto_id == $p->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                {{ $p->existenciable->descripcion ?? 'Artículo' }} (Stock: {{ $p->cantidad }})
              </button>
              @endforeach
              @endif
            </div>
            @error('existencia_producto_id') <span class="error-message">{{ $message }}</span> @enderror
          </div>

          <!-- Cantidades -->
          <div class="grid grid-cols-3 gap-2">
            <div>
              <label class="font-semibold text-sm">Cantidad Base Usada</label>
              <input type="number" wire:model="cantidad_base_usada" placeholder="Cantidad Base" class="input-minimal">
              @error('cantidad_base_usada') <span class="error-message">{{ $message }}</span> @enderror
            </div>
            <div>
              <label class="font-semibold text-sm">Cantidad Tapa Usada</label>
              <input type="number" wire:model="cantidad_tapa_usada" placeholder="Cantidad Tapa" class="input-minimal">
              @error('cantidad_tapa_usada') <span class="error-message">{{ $message }}</span> @enderror
            </div>
            <div>
              <label class="font-semibold text-sm">Cantidad Generada</label>
              <input type="number" wire:model="cantidad_generada" placeholder="Cantidad Generada" class="input-minimal">
              @error('cantidad_generada') <span class="error-message">{{ $message }}</span> @enderror
            </div>
          </div>




          <!-- Mermas -->
          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="font-semibold text-sm">Merma Base</label>
              <input type="number" wire:model="mermaBase" placeholder="Merma Base" class="input-minimal">
              @error('mermaBase') <span class="error-message">{{ $message }}</span> @enderror
            </div>
            <div>
              <label class="font-semibold text-sm">Merma Tapa</label>
              <input type="number" wire:model="mermaTapa" placeholder="Merma Tapa" class="input-minimal">
              @error('mermaTapa') <span class="error-message">{{ $message }}</span> @enderror
            </div>
          </div>

          <!-- Estado -->
          <div class="flex flex-col gap-4">
            <label class="font-semibold text-sm">Estado</label>
            <div class="flex flex-wrap justify-center gap-2">
              @foreach(['pendiente' => 'Pendiente', 'terminado' => 'Terminado'] as $st => $label)
              <button type="button" wire:click="$set('estado','{{ $st }}')"
                class="px-4 py-2 rounded-full text-sm flex items-center justify-center
                     {{ $estado === $st ? 'bg-cyan-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
                {{ $label }}
              </button>
              @endforeach
            </div>
          </div>

          <!-- Personal y Observaciones -->
          <div>
            <label class="font-semibold text-sm mb-2 block">Personal Responsable</label>
            <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[170px]">
              @foreach($personals as $p)
              <button type="button" wire:click="$set('personal_id', {{ $p->id }})"
                class="w-full px-3 py-2 rounded-md border text-sm text-left transition
              {{ $personal_id == $p->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                {{ $p->nombres }}
              </button>
              @endforeach
            </div>
            @error('personal_id') <span class="error-message">{{ $message }}</span> @enderror

            <label class="font-semibold text-sm mt-2">Observaciones</label>
            <textarea wire:model="observaciones" placeholder="Observaciones" class="input-minimal"></textarea>
            @error('observaciones') <span class="error-message">{{ $message }}</span> @enderror
          </div>

        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" wire:click="guardar" class="btn-circle btn-cyan">
          Guardar
        </button>
        <button type="button" wire:click="cerrarModal" class="btn-circle btn-cyan">
          Cerrar
        </button>
      </div>
    </div>
  </div>
  @endif


</div>
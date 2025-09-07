<div class="p-text p-2 mt-10 flex justify-center">
  <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
    <div>
      <h6 class="text-xl font-bold mb-4 px-4 p-text">Gestión de Stock</h6>

      <!-- Botón de registro y buscador -->
      <div class="flex justify-center items-center gap-4 w-full max-w-2xl mx-auto">
        <button title="Registrar Stock" wire:click='abrirModal("create")'
          class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-plus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 5v14m7-7h-14" />
          </svg>
        </button>

        <!-- <button title="Registrar Existencia" wire:click="abrirModalExistencia"
          class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-category-plus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 4h6v6h-6zm10 0h6v6h-6zm-10 10h6v6h-6zm10 3h6m-3 -3v6" />
          </svg>
        </button> -->

        <button type="button" wire:click="abrirModalVerExistencias"
          class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-clipboard-list">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
            <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
            <path d="M9 12l.01 0" />
            <path d="M13 12l2 0" />
            <path d="M9 16l.01 0" />
            <path d="M13 16l2 0" />
          </svg>
        </button>



        <input type="text" wire:model.live="search" placeholder="Buscar por producto o sucursal..."
          class="input-g w-auto sm:w-64" />
      </div>

      <!-- Tabla -->
      <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left border border-slate-200 dark:border-cyan-200 rounded-lg border-collapse">
          <thead class="text-xs md:text-sm uppercase color-bg">
            <tr class="bg-gray-100 dark:bg-gray-800">
              <th class="px-4 py-3 p-text text-left">PRODUCTO Y DETALLES</th>
              <th class="px-4 py-3 p-text text-left">SUCURSAL Y CANTIDAD</th>
              <th class="px-4 py-3 p-text text-right">ACCIONES</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($stocks as $stock)
            <tr class="color-bg border border-slate-200 text-sm">
              <!-- Producto -->
              <td class="px-4 py-4 text-left p-text align-top">
                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                  <img src="{{ asset('storage/' . $stock->producto->imagen) }}" alt="Producto"
                    class="h-20 w-20 sm:h-24 sm:w-24 object-cover rounded mb-2 sm:mb-0">
                  <div class="text-sm">
                    <div><strong>Nombre del Producto:</strong> {{ $stock->producto->nombre ?? 'Sin nombre' }}</div>
                    <div>
                      <strong>Etiqueta:</strong>
                      @if ($stock->etiqueta_id)
                      {{ $stock->etiqueta->capacidad ?? '' }} -
                      {{ $stock->etiqueta->cliente->empresa ?? $stock->etiqueta->cliente->nombre ?? 'Sin datos de
                      cliente' }}
                      @else
                      Sin etiqueta
                      @endif
                    </div>
                    <div><strong>Fecha Elaboracion:</strong> {{ $stock->fechaElaboracion ?? 'No definido' }}</div>
                    <div><strong>Fecha Vencimiento:</strong> {{ $stock->fechaVencimiento ?: 'No definido' }}</div>
                  </div>
                </div>
              </td>

              <!-- Sucursal y cantidad -->
              {{-- <td class="px-4 py-4 text-left align-top text-sm">
                <div><strong>Sucursal:</strong> {{ $stock->sucursal->nombre ?? 'Sucursal desconocida' }}</div>
                <div><strong>Etiqueta:</strong> {{ $stock->etiqueta->capacidad ?? 'Sin etiqueta' }}-{{
                  $stock->etiqueta->cliente->nombre ?? 'Sin etiqueta' }}</div>
                <div><strong>Precio de referencia:</strong> {{ $stock->producto->precioReferencia ?? 'No definido' }}
                </div>
                <div><strong>Precio de referencia 2:</strong> {{ $stock->producto->precioReferencia2 ?? 'No definido' }}
                </div>
                <div><strong>Precio de referencia 3:</strong> {{ $stock->producto->precioReferencia3 ?? 'No definido' }}
                </div>
              </td> --}}
              <!-- Columna: Sucursal y Stock del producto -->
              <td class="px-4 py-4 text-left align-top text-sm">
                <strong class="block mb-1">Sucursal:</strong>
                @forelse ($stock->existencias as $existencia)
                <span class="block">
                  <span class="@if ($existencia->cantidad > ($existencia->cantidadMinima * 2)) text-green-500
                     @elseif ($existencia->cantidad >= $existencia->cantidadMinima && $existencia->cantidad <= ($existencia->cantidadMinima * 2)) text-yellow-500
                     @else text-red-500 @endif">
                    {{ number_format($existencia->cantidad).'/'.$existencia->cantidadMinima }}:
                  </span>
                  {{ Str::limit($existencia->sucursal->nombre ?? 'Sucursal Desconocida', 18, '...') }}
                </span>
                @empty
                <span class="text-xs text-gray-500">Sin stock registrado</span>
                @endforelse

                <strong class="block mt-2">
                  {{ number_format($stock->existencias->sum('cantidad')) }}: Total unidades
                </strong>
              </td>



              <!-- Acciones -->
              <td class="px-4 py-4 text-right align-center">
                <div class="flex justify-end space-x-2">
                  <!-- Editar -->
                  <button title="Editar Stock" wire:click="abrirModal('edit', {{ $stock->id }})"
                    class="text-blue-500 hover:text-blue-600 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor"
                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="icon icon-tabler icon-tabler-edit">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                      <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                      <path d="M16 5l3 3" />
                    </svg>
                  </button>

                  <!-- Detalles -->
                  <button title="Ver detalles" wire:click="modaldetalle({{ $stock->id }})"
                    class="text-indigo-500 hover:text-indigo-600 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor"
                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="icon icon-tabler icon-tabler-info-circle">
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
              <td colspan="3" class="text-center py-4 text-gray-600 dark:text-gray-400 text-sm">
                No hay registros de stock.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <div class="mt-4 flex justify-center">
        {{ $stocks->links() }}
      </div>
    </div>
  </div>
  @if ($modal)
  <div class="modal-first">
    <div class="modal-center">
      <div class="modal-hiden">
        <div class="center-col">
          <h3 class="p-text">
            {{ $accion === 'edit' ? 'Editar Stock' : 'Nuevo Stock' }}
          </h3>

          <div class="over-col">

            <h3 class="p-text">Fecha de Elaboración</h3>
            <input type="date" wire:model="fechaElaboracion" class="p-text input-g" />
            @error('fechaElaboracion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            <h3 class="p-text">Fecha de Vencimiento</h3>
            <input type="date" wire:model="fechaVencimiento" class="p-text input-g" />
            @error('fechaVencimiento') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            <h3 class="p-text">Observaciones</h3>
            <input type="text" wire:model="observaciones" class="p-text input-g" />
            @error('observaciones') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            <h3 class="p-text">Etiqueta</h3>
            <select wire:model="etiqueta_id" class="p-text input-g">
              <option value="">Sin etiqueta</option>
              @foreach ($etiquetas as $etiqueta)
              <option value="{{ $etiqueta->id }}">{{ $etiqueta->capacidad }}-{{ $etiqueta->cliente->nombre }}</option>
              @endforeach
            </select>
            @error('etiqueta_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            <h3 class="p-text">Producto</h3>
            <select wire:model="producto_id" class="p-text input-g">
              <option value="">Sin producto</option>
              @foreach ($productos as $producto)
              <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
              @endforeach
            </select>
            @error('producto_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            {{-- <h3 class="p-text">Sucursal</h3>
            <select wire:model="sucursal_id" class="p-text input-g">
              <option value="">Seleccione una sucursal</option>
              @foreach ($sucursales as $sucursal)
              <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
              @endforeach
            </select>
            @error('sucursal_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror --}}

          </div>

          <div class="mt-6 flex justify-center w-full space-x-4">
            <button type="button" wire:click="guardar"
              class="text-indigo-500 hover:text-indigo-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
              <!-- Ícono de guardar -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler" width="24" height="24"
                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M14 4l0 4l-6 0l0 -4" />
              </svg>
            </button>

            <button type="button" wire:click="cerrarModal"
              class="text-red-500 hover:text-red-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full">
              <!-- Ícono de cerrar -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler" width="24" height="24"
                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                stroke-linejoin="round">
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
  @if ($modalDetalle)
  <div class="modal-first">
    <div class="modal-center">
      <div class="modal-hiden">
        <div class="center-col">
          <h3 class="p-text mb-4">Detalles del Stock</h3>

          <div class="mt-4 grid grid-cols-2 gap-4">
            <div class="flex flex-col gap-4">
              <p class="text-semibold">
                <strong class="p-text">Fecha de Elaboración:</strong>
                {{ $stockSeleccionado['fechaElaboracion'] }}
              </p>
              <p class="text-semibold">
                <strong class="p-text">Fecha de Vencimiento:</strong>
                {{ $stockSeleccionado['fechaVencimiento'] }}
              </p>
              <p class="text-semibold">
                <strong class="p-text">Observaciones:</strong>
                {{ $stockSeleccionado['observaciones'] ?? 'Sin observaciones' }}
              </p>
              <p class="text-semibold">
                <strong class="p-text">Producto:</strong>
                {{ $stockSeleccionado['producto']['nombre'] ?? 'Sin producto' }}
              </p>
              <p class="text-semibold">
                <strong class="p-text">Sucursal:</strong>
                {{ $stockSeleccionado['sucursal']['nombre'] ?? 'Sin sucursal' }}
              </p>
              <p class="text-semibold">
                <strong class="p-text">Etiqueta (Capacidad):</strong>
                {{ $stockSeleccionado['etiqueta']['capacidad'] ?? 'Sin etiqueta' }}
              </p>
              <p class="text-semibold">
                <strong class="p-text">Cliente:</strong>
                {{ $stockSeleccionado['etiqueta']['cliente']['nombre'] ?? 'Sin cliente' }}
              </p>
            </div>
          </div>

          <div class="mt-6 flex justify-center w-full">
            <button type="button" wire:click="cerrarModalDetalle"
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
  @if ($modalExistencia)
  <div class="modal-first">
    <div class="modal-center">
      <div class="modal-hiden">
        <div class="center-col">
          <h3 class="p-text">{{ $existencia_id ? 'Editar Existencia' : 'Nueva Existencia' }}</h3>

          <div class="over-col">
            <!-- Cantidad -->
            <h3 class="p-text">Cantidad</h3>
            <input type="number" wire:model="cantidad" min="1" class="p-text input-g" />
            @error('cantidad') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            <!-- Cantidad Mínima -->
            <h3 class="p-text">Cantidad Mínima</h3>
            <input type="number" wire:model="cantidadMinima" min="0" class="p-text input-g" />
            @error('cantidadMinima') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            <!-- Sucursal -->
            <h3 class="p-text">Sucursal</h3>
            <select wire:model="existencia_sucursal_id" class="p-text input-g">
              <option value="">Seleccione sucursal</option>
              @foreach ($sucursales as $sucursal)
              <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
              @endforeach
            </select>
            @error('existencia_sucursal_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            <!-- Existencia -->
            <h3 class="p-text">Existencia</h3>
            <select wire:model="existenciable_id" class="p-text input-g">
              <option value="">Seleccione existencia</option>
              @foreach ($existencias as $existencia)
              <option value="{{ $existencia->id }}">
                {{ $existencia->id }} -
                {{ class_basename($existencia->existenciable_type) }}:
                {{ $existencia->existenciable->descripcion ?? 'Sin descripción' }}
              </option>
              @endforeach
            </select>
            @error('existenciable_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
          </div>

          <!-- Botones -->
          <div class="mt-6 flex justify-center w-full space-x-4">
            <button type="button" wire:click="guardarExistencia"
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
            <button type="button" wire:click="cerrarModalExistencia"
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
  @if ($modalVerExistencias)
  <div class="modal-first">
    <div class="modal-center">
      <div class="modal-hiden">
        <div class="center-col">
          <h3 class="p-text mb-4">Lista de Existencias</h3>

          <div class="overflow-y-auto max-h-96 w-full">
            <table class="min-w-full border border-gray-300">
              <thead class="bg-gray-100">
                <tr>
                  <th class="text-left p-2 border-b">Sucursal</th>
                  <th class="text-left p-2 border-b">Producto</th>
                  <th class="text-left p-2 border-b">Cantidad</th>
                  <!-- <th class="text-left p-2 border-b">Cantidad Mínima</th> -->
                </tr>
              </thead>
              <tbody>
                @foreach ($todasExistencias as $existencia)
                <tr>
                  <td class="p-2 border-b">{{ $existencia->sucursal->nombre ?? 'N/A' }}</td>
                  <td class="p-2 border-b">
                    {{ class_basename($existencia->existenciable_type) ?? 'Sin nombre' }} - {{
                    $existencia->existenciable->descripcion ?? 'Sin nombre' }}
                  </td>

                  <td class="p-2 border-b">{{ $existencia->cantidad }}</td>
                  <!-- <td class="p-2 border-b">{{ $existencia->cantidadMinima ?? 'No definida' }}</td> -->
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="mt-4 flex justify-center">
            <button type="button" wire:click="cerrarModalVerExistencias"
              class="text-red-500 hover:text-red-600 transition-transform duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full p-2">
              <!-- Icono cerrar -->
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
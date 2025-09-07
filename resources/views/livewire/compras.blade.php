<div class="p-text p-2 mt-10 flex justify-center">
  <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
    <div>
      <h6 class="text-xl font-bold mb-4 px-4 p-text">Gestión de Compras</h6>

      <!-- Botón de registro y buscador -->
      <div class="flex justify-center items-center gap-4 w-full max-w-2xl mx-auto">
        <button title="Registrar Compra" wire:click='abrirModal("create")'
          class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icon-tabler-plus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 5v14m-7 -7h14" />
          </svg>
        </button>

        <input type="text" wire:model.live="search" placeholder="Buscar compra..." class="input-g w-auto sm:w-64" />
      </div>

      <!-- Tabla -->
      <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left border border-slate-200 dark:border-cyan-200 rounded-lg border-collapse">
          <thead class="text-x uppercase color-bg">
            <tr>
              <th scope="col" class="px-6 py-3 p-text text-left">Información</th>
              <th scope="col" class="px-6 py-3 p-text text-right">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($compras as $compra)
            <tr class="color-bg border border-slate-200">
              <td class="px-6 py-4 p-text text-left">
                <div class="mb-2">
                  <span class="font-semibold block">Fecha de compra:</span>
                  <span>{{ $compra->fecha }}</span>
                </div>
                <div class="mb-2">
                  <span class="font-semibold block">Proveedor:</span>
                  <span>{{ $compra->proveedor->razonSocial }}</span>
                </div>
                <div class="mb-2">
                  <span class="font-semibold block">Personal:</span>
                  <span>{{ $compra->personal->nombres }}-{{ $compra->personal->apellidos }}</span>
                </div>
                <div>
                  <span class="font-semibold block">Observaciones:</span>
                  <span>{{ $compra->observaciones ?? 'Ninguna' }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex justify-end space-x-2">
                  <button title="Editar" wire:click="editarCompra({{ $compra->id }})"
                    class="text-blue-500 hover:text-blue-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24"
                      height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                      <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                      <path d="M16 5l3 3" />
                    </svg>
                  </button>
                  <button title="Ver Detalle" wire:click="verDetalle({{ $compra->id }})"
                    class="text-indigo-500 hover:text-indigo-600 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor"
                      stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-info-circle">
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
              <td colspan="2" class="text-left py-4 text-gray-600 dark:text-gray-400">
                No hay compras registradas.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-4 flex justify-center">
        {{ $compras->links() }}
      </div>
    </div>
  </div>
  <!-- Modal de Registro/Edición -->
  @if ($modal)
  <div class="modal-first">
    <div class="modal-center">
      <div class="modal-hiden">
        <div class="center-col">
          <h3 class="p-text">{{ $accion === 'create' ? 'Registrar Compra' : 'Editar Compra' }}</h3>
          <div class="over-col">
            <!-- Fecha -->
            <h3 class="p-text">Fecha</h3>
            <input type="date" wire:model.defer="fecha" class="p-text input-g">
            @error('fecha') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Observaciones -->
            <h3 class="p-text">Observaciones</h3>
            <input wire:model.defer="observaciones" class="p-text input-g" rows="3"></input>
            @error('observaciones') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <h3 class="p-text">Personal</h3>
            <select wire:model.defer="personal_id" class="p-text text-sm sm:text-base input-g">
              <option value="">Seleccione un personal</option>
              @foreach ($personals as $personal)
              <option value="{{ $personal->id }}">{{ $personal->nombres }}-{{ $personal->apellidos }}</option>
              @endforeach
            </select>
            @error('personal_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Proveedor -->
            <h3 class="p-text">Proveedor</h3>
            <select wire:model.defer="proveedor_id" wire:change="cargarExistencias" class="p-text text-sm sm:text-base input-g">
              <option value="">Seleccione un proveedor</option>
              @foreach ($proveedors as $proveedor)
              <option value="{{ $proveedor->id }}">{{ $proveedor->razonSocial }} ({{ $proveedor->tipo ?? 'Sin tipo' }})</option>
              @endforeach
            </select>
            @error('proveedor_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Personal -->
            <h3 class="p-text">Personal</h3>
            <select wire:model.defer="personal_id" class="p-text text-sm sm:text-base input-g">
              <option value="">Seleccione un personal</option>
              @foreach ($personals as $personal)
              <option value="{{ $personal->id }}">{{ $personal->nombres }}</option>
              @endforeach
            </select>
            @error('personal_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            <!-- Selección de ítems -->
            <h3 class="p-text">Ítems de Compra</h3>
            <div class="mb-4">
              <select wire:model="existencia_id" class="p-text input-g">
                <option value="">Seleccione un ítem</option>
                @foreach ($existenciasDisponibles as $existencia)
                <option value="{{ $existencia->id }}">
                  {{ $existencia->existenciable->insumo ?? $existencia->existenciable->imagen ?? 'Ítem' }} (Sucursal: {{ $existencia->sucursal->nombre }})
                  {{-- {{ $existencia->existenciable->insumo ?? $existencia->existenciable->imagen ?? 'Ítem' }} (Sucursal: {{ $existencia->sucursal->nombre }}) --}}
                </option>
                @endforeach
              </select>
              @error('existencia_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror


            <!-- Cantidad / Precio / Botón -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
              <div>
                <label class="p-text block mb-1">Cantidad</label>
                <input type="number" wire:model="item_cantidad" placeholder="Cantidad" class="p-text input-g w-full" min="1">
                @error('item_cantidad') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
              </div>

              <div>
                <label class="p-text block mb-1">Precio unitario (Bs)</label>
                <input type="number" wire:model="item_precio" placeholder="Precio" class="p-text input-g w-full" step="0.01" min="0">
                @error('item_precio') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
              </div>

              <div class="flex items-end justify-start">
                <button wire:click="agregarItem" title="Agregar ítem"
                  class="text-indigo-500 hover:text-indigo-600 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
                  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icon-tabler-plus">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 5v14m-7 -7h14" />
                  </svg>
                </button>
              </div>
            </div>


            <!-- Lista de ítems -->
            @if (!empty($items))
            <div class="mt-4">
              <h4 class="p-text">Ítems Agregados</h4>
              <table class="w-full text-sm">
                <thead>
                  <tr>
                    <th class="p-text">Ítem</th>
                    <th class="p-text">Cantidad</th>
                    <th class="p-text">Precio</th>
                    <th class="p-text">Acción</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($items as $index => $item)
                  <tr>
                    <td class="p-text">{{ $item['existencia']->existenciable->insumo ?? $item['existencia']->existenciable->imagen ?? 'Ítem' }}</td>
                    <td class="p-text">{{ $item['cantidad'] }}</td>
                    <td class="p-text">{{ number_format($item['precio'], 2) }}</td>
                    <td>
                      <button wire:click="eliminarItem({{ $index }})" class="text-red-500 hover:text-red-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash-x">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <path d="M4 7h16" />
                          <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                          <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                          <path d="M10 12l4 4m0 -4l-4 4" />
                        </svg></button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @endif
          </div>

          <!-- Botones -->
          <div class="mt-6 flex justify-center w-full space-x-4">
            <button type="button" wire:click="guardarCompra"
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
            <button type="button" wire:click="cerrarModal"
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

  <!-- Modal de Detalles -->
  @if ($detalleModal)
  <div class="modal-first">
    <div class="modal-center">
      <div class="modal-hiden">
        <div class="center-col">
          <h3 class="p-text">Detalles de la Compra</h3>
          <div class="over-col">
            <p class="p-text"><strong class="p-text">Fecha:</strong> {{ $compraSeleccionada->fecha }}</p>
            <p class="p-text"><strong class="p-text">Observaciones:</strong> {{ $compraSeleccionada->observaciones ?? 'Sin observaciones' }}</p>
            <p class="p-text"><strong class="p-text">Proveedor:</strong> {{ $compraSeleccionada->proveedor->razonSocial }}</p>
            <p class="p-text"><strong class="p-text">Personal:</strong> {{ $compraSeleccionada->personal->nombres }}</p>
            <h4 class="p-text mt-2">Ítems de Compra</h4>
            @if ($compraSeleccionada->itemCompras->isNotEmpty())
            <table class="w-full text-sm">
              <thead>
                <tr>
                  <th class="p-text">Ítem</th>
                  <th class="p-text">Cantidad</th>
                  <th class="p-text">Precio</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($compraSeleccionada->itemCompras as $item)
                <tr>
                  <td class="p-text">{{ $item->existencia->existenciable->insumo ?? $item->existencia->existenciable->imagen ?? 'Ítem' }}</td>
                  <td class="p-text">{{ $item->cantidad }}</td>
                  <td class="p-text">{{ number_format($item->precio, 2) }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @else
            <p class="p-text">Sin ítems registrados.</p>
            @endif
          </div>
          <div class="mt-6 flex justify-center w-full">
            <button type="button" wire:click="cerrarModal" class="text-red-500 hover:text-red-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full">
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

</div>
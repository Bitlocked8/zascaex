<div class="p-text p-2 mt-10 flex justify-center">
    <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
        <div>
            <h6 class="text-xl font-bold mb-4 px-4 p-text">Gestión de Distribuciones</h6>

            <!-- Botón de registro y buscador -->
            <div class="flex justify-center items-center gap-4 w-full max-w-2xl mx-auto">
                <button title="Registrar Distribución" wire:click='abrirModal("create")'
                    class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5v14m7-7h-14" />
                    </svg>
                </button>

                <input type="text" wire:model.live="search" placeholder="Buscar distribución..."
                    class="input-g w-auto sm:w-64" />
            </div>

            <!-- Tabla -->
            <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
                <table
                    class="w-full text-sm text-left border border-slate-200 dark:border-cyan-200 rounded-lg border-collapse">
                    <thead class="text-x uppercase color-bg">
                        <tr>
                            <th scope="col" class="px-6 py-3 p-text text-left">Información</th>
                            <th scope="col" class="px-6 py-3 p-text text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($distribucions as $distribucion)
                        <tr class="color-bg border border-slate-200">
                            <td class="px-6 py-4 p-text text-left">
                                <div class="mb-2">
                                    <span class="font-semibold block">Fecha:</span>
                                    <span>{{ $distribucion->fecha }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold block">Asignado a:</span>
                                    <span>{{ $distribucion->asignacion->personal->apellidos }}
                                        {{ $distribucion->asignacion->personal->nombres }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold block">Estado:</span>
                                    @if ($distribucion->estado == 0)
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-red-600 text-white">Cancelado</span>
                                    @elseif ($distribucion->estado == 1)
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-600 text-white">En
                                        Distribución</span>
                                    @elseif ($distribucion->estado == 2)
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-600 text-white">Concluido</span>
                                    @endif
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold block">Items:</span>
                                    <span>{{ $distribucion->itemdistribucions->count() }} items</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-2">
                                    <button title="Editar" wire:click="abrirModal('edit', {{ $distribucion->id }})"
                                        class="text-blue-500 hover:text-blue-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                            <path
                                                d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                            <path d="M16 5l3 3" />
                                        </svg>
                                    </button>
                                    <button title="Ver Detalle" wire:click="verDetalle({{ $distribucion->id }})"
                                        class="text-indigo-500 hover:text-indigo-600 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-info-circle">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                            <path d="M12 9h.01" />
                                            <path d="M11 12h1v4h1" />
                                        </svg>
                                    </button>
                                    @if($distribucion->estado == 1)
                                    <button title="Retornar Stock" wire:click="retornarStock({{ $distribucion->id }})"
                                        class="text-rose-500 hover:text-rose-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-rose-500 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-back">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M5 12h14" />
                                            <path d="M5 12l4 4" />
                                            <path d="M5 12l4 -4" />
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-left py-4 text-gray-600 dark:text-gray-400">
                                No hay registros de distribuciones.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-center">
                {{ $distribucions->links() }}
            </div>
        </div>
    </div>

    <!-- Modal de registro y edición -->
    @if ($modal)
    <div class="modal-first">
        <div class="modal-center">
            <div class="modal-hiden">
                <div class="center-col">
                    <h3 class="p-text">{{ $accion === 'create' ? 'Registrar Distribución' : 'Editar Distribución' }}
                    </h3>
                    <div class="over-col">
                        <!-- Fecha -->
                        <h3 class="p-text">Fecha</h3>
                        <input type="date" wire:model="fecha" class="p-text input-g">
                        @error('fecha') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Estado -->
                        <h3 class="p-text">Estado</h3>
                        <select wire:model="estado" class="p-text input-g">
                            <option value="1">En distribución</option>
                            <option value="2">Concluido</option>
                        </select>
                        @error('estado') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Observaciones -->
                        <h3 class="p-text">Observaciones</h3>
                        <input wire:model="observaciones" class="p-text input-g"></input>
                        @error('observaciones') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Asignación -->
                        <h3 class="p-text">Asignación</h3>
                        <select wire:model="asignacion_id" class="p-text input-g">
                            <option value="">Seleccione una asignación</option>
                            @foreach ($asignaciones as $asignacion)
                            <option value="{{ $asignacion->id }}">Asignación #{{ $asignacion->id }} -
                                {{ $asignacion->personal->apellidos }} {{ $asignacion->personal->nombres }}
                            </option>
                            @endforeach
                        </select>
                        @error('asignacion_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Sucursal -->
                        <h3 class="p-text">Sucursal de origen</h3>
                        <select wire:model="selectedSucursal" class="p-text input-g">
                            <option value="">Seleccione una sucursal</option>
                            @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                            @endforeach
                        </select>
                        @error('selectedSucursal') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Selector de modo (Venta o Stock) -->
                        <div class="mt-4 flex justify-center space-x-4">
                            <button type="button" wire:click="cambiarModoSeleccion('venta')"
                                class="px-4 py-2 rounded-md {{ $modoSeleccion === 'venta' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                                Agregar Venta
                            </button>
                            <button type="button" wire:click="cambiarModoSeleccion('stock')"
                                class="px-4 py-2 rounded-md {{ $modoSeleccion === 'stock' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                                Agregar Stock
                            </button>
                        </div>

                        <!-- Sección de Venta (condicional) -->
                        @if($modoSeleccion === 'venta')
                        <div class="flex items-center justify-between mt-4">
                            <h3 class="p-text">Venta</h3>
                            @if($venta_id)
                            <button type="button" wire:click="previewVenta"
                                class="text-blue-600 hover:text-blue-800 text-sm">
                                Ver detalle de venta
                            </button>
                            @endif
                        </div>
                        <select wire:model="venta_id" wire:change="cargarStocksVenta" class="p-text input-g">
                            <option value="">Seleccione una venta (estado Contado)</option>
                            @foreach ($ventasContado as $venta)
                            <option value="{{ $venta->id }}">Venta #{{ $venta->id }} - Cliente:
                                {{ $venta->cliente->nombre }}
                            </option>
                            @endforeach
                        </select>
                        @error('venta_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Listado de ítems de venta con verificación de stock -->
                        @if (!empty($itemsVentaDisponibles))
                        <div class="mt-4">
                            <h3 class="p-text">Ítems de la Venta</h3>
                            <div class="overflow-y-auto max-h-60 mt-2">
                                <table class="w-full text-sm text-left border border-slate-200 rounded-lg">
                                    <thead class="text-xs uppercase bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2">Producto</th>
                                            <th class="px-4 py-2">Solicitado</th>
                                            <th class="px-4 py-2">Disponible</th>
                                            <th class="px-4 py-2">Estado</th>
                                            <th class="px-4 py-2">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($itemsVentaDisponibles as $index => $item)
                                        <tr class="border-b">
                                            <td class="px-4 py-2">{{ $item['producto'] }}</td>
                                            <td class="px-4 py-2">{{ $item['cantidad_pedida'] }}</td>
                                            <td class="px-4 py-2">{{ $item['cantidad_disponible'] }}</td>
                                            <td class="px-4 py-2">
                                                @if($item['disponible'])
                                                <span class="text-green-500">✓ Disponible</span>
                                                @else
                                                <span class="text-red-500">✗ Falta: {{ $item['faltante'] }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2">
                                                @if($item['disponible'] && !isset($item['agregado']))
                                                <button type="button" wire:click="agregarItemVenta({{ $index }})"
                                                    class="text-indigo-600 hover:text-indigo-800">
                                                    Agregar
                                                </button>
                                                @elseif(isset($item['agregado']) && $item['agregado'])
                                                <span class="text-gray-500">Agregado</span>
                                                @else
                                                <span class="text-gray-500">No disponible</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        @endif

                        <!-- Sección de Stock Suelto (condicional) -->
                        @if($modoSeleccion === 'stock')
                        <h3 class="p-text mt-4">Stocks Disponibles</h3>
                        <div class="overflow-y-auto max-h-60 mt-2">
                            <table class="w-full text-sm text-left border border-slate-200 rounded-lg">
                                <thead class="text-xs uppercase bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2">Producto</th>
                                        <th class="px-4 py-2">Disponible</th>
                                        <th class="px-4 py-2">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stocksDisponibles as $stock)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $stock->producto->nombre }}</td>
                                        <td class="px-4 py-2">{{ $stock->existencia ? $stock->existencia->cantidad : 0 }}</td>
                                        <td class="px-4 py-2">
                                            <div class="flex items-center space-x-2">
                                                <input type="number" min="1"
                                                    max="{{ $stock->existencia ? $stock->existencia->cantidad : 0 }}"
                                                    class="w-20 p-1 border rounded"
                                                    id="cantidad-{{ $stock->id }}"
                                                    value="1">
                                                <button type="button"
                                                    onclick="Livewire.dispatch('agregarStockSuelto', { stockId: {{ $stock->id }}, cantidad: document.getElementById('cantidad-{{ $stock->id }}').value })"
                                                    class="text-indigo-600 hover:text-indigo-800">
                                                    Agregar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 text-center">No hay stock disponible en esta sucursal.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <!-- Listado de Ítems de Distribución -->
                        <div class="mt-6 flex justify-between items-center">
                            <h3 class="p-text">Ítems de la Distribución</h3>
                            <!-- Nuevo botón para agregar item manual -->
                            <button type="button" wire:click="abrirModalItemManual"
                                class="bg-indigo-600 text-white px-3 py-1 rounded-md text-sm hover:bg-indigo-700">
                                + Agregar Item Manual
                            </button>
                        </div>
                        <div class="overflow-y-auto max-h-60 mt-2">
                            <table class="w-full text-sm text-left border border-slate-200 rounded-lg">
                                <thead class="text-xs uppercase bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2">Producto</th>
                                        <th class="px-4 py-2">Nuevos</th>
                                        <th class="px-4 py-2">Usados</th>
                                        <th class="px-4 py-2">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($itemsDistribucion as $index => $item)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $item['producto'] }}</td>
                                        <td class="px-4 py-2">
                                            <input type="number" min="0" wire:model="itemsDistribucion.{{ $index }}.cantidadNuevo"
                                                class="w-20 p-1 border rounded">
                                        </td>
                                        <td class="px-4 py-2">
                                            <input type="number" min="0" wire:model="itemsDistribucion.{{ $index }}.cantidadUsados"
                                                class="w-20 p-1 border rounded">
                                        </td>
                                        <td class="px-4 py-2">
                                            <button type="button" wire:click="eliminarItemDistribucion({{ $index }})"
                                                class="text-red-600 hover:text-red-800">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-2 text-center">No se han agregado ítems a la distribución.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-6 flex justify-center w-full space-x-4">
                        <button type="button" wire:click="guardarDistribucion"
                            class="text-indigo-500 hover:text-indigo-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M14 4l0 4l-6 0l0 -4" />
                            </svg>
                        </button>
                        <button type="button" wire:click="cerrarModal"
                            class="text-red-500 hover:text-red-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
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

    <!-- Modal de detalle -->
    @if ($detalleModal)
    <div class="modal-first">
        <div class="modal-center">
            <div class="modal-hiden">
                <div class="center-col">
                    <h3 class="text-base font-semibold p-text" id="modal-title">Detalles de la Distribución</h3>
                    <div class="mt-4">
                        <dl class="grid grid-cols-2 gap-4">

                            <!-- Fecha -->
                            <div>
                                <dt class="text-sm font-medium p-text">Fecha</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $distribucionSeleccionada['fecha'] ?? 'No especificado' }}
                                </dd>
                            </div>

                            <!-- Estado -->
                            <div>
                                <dt class="text-sm font-medium p-text">Estado</dt>
                                <dd class="mt-1 text-sm p-text">
                                    @if (($distribucionSeleccionada['estado'] ?? false) == 0)
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-red-600 text-white">Cancelado</span>
                                    @elseif (($distribucionSeleccionada['estado'] ?? false) == 1)
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-600 text-white">En
                                        distribución</span>
                                    @elseif (($distribucionSeleccionada['estado'] ?? false) == 2)
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-600 text-white">Concluido</span>
                                    @endif
                                </dd>
                            </div>

                            <!-- Observaciones -->
                            <div class="col-span-2">
                                <dt class="text-sm font-medium p-text">Observaciones</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $distribucionSeleccionada['observaciones'] ?? 'Sin observaciones' }}
                                </dd>
                            </div>

                            <!-- Personal asignado -->
                            <div class="col-span-2">
                                <dt class="text-sm font-medium p-text">Asignado a</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $distribucionSeleccionada['asignacion']['personal']['apellidos'] ?? '' }}
                                    {{ $distribucionSeleccionada['asignacion']['personal']['nombres'] ?? 'No asignado' }}
                                </dd>
                            </div>

                            <!-- Lista de Items de Distribución -->
                            @if (!empty($distribucionSeleccionada['itemdistribucions']))
                            <div class="col-span-2 mt-4">
                                <dt class="text-sm font-medium p-text">Items de Distribución</dt>
                                <dd class="mt-1">
                                    <table class="w-full text-sm text-left border border-slate-200 rounded-lg">
                                        <thead class="text-xs uppercase bg-gray-100">
                                            <tr>
                                                <th class="px-4 py-2">Producto</th>
                                                <th class="px-4 py-2">Nuevos</th>
                                                <th class="px-4 py-2">Usados</th>
                                                <th class="px-4 py-2">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($distribucionSeleccionada['itemdistribucions'] as $item)
                                            <tr class="border-b">
                                                <td class="px-4 py-2">{{ $item['stock']['producto']['nombre'] }}</td>
                                                <td class="px-4 py-2">{{ $item['cantidadNuevo'] }}</td>
                                                <td class="px-4 py-2">{{ $item['cantidadUsados'] }}</td>
                                                <td class="px-4 py-2">{{ $item['cantidadNuevo'] + $item['cantidadUsados'] }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </dd>
                            </div>
                            @endif

                        </dl>
                    </div>

                    <!-- Botón de cierre -->
                    <div class="mt-4 flex justify-center w-full">
                        <button type="button" wire:click="cerrarModal"
                            class="text-red-500 hover:text-red-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
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

    <!-- Modal de previsualización de venta -->
    @if($previewVentaModal && $ventaSeleccionada)
    <div class="modal-first">
        <div class="modal-center">
            <div class="modal-hiden">
                <div class="center-col">
                    <h3 class="text-base font-semibold p-text">Detalle de Venta #{{ $ventaSeleccionada->id }}</h3>
                    <div class="mt-4 overflow-y-auto max-h-96">
                        <dl class="grid grid-cols-2 gap-4">
                            <!-- Información del cliente -->
                            <div class="col-span-2 bg-gray-50 p-3 rounded-lg">
                                <dt class="text-sm font-medium p-text">Cliente</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $ventaSeleccionada->cliente->nombre ?? 'No especificado' }}
                                </dd>
                            </div>

                            <!-- Fechas -->
                            <div>
                                <dt class="text-sm font-medium p-text">Fecha de Pedido</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $ventaSeleccionada->fechaPedido ?? 'No especificado' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium p-text">Fecha de Entrega</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $ventaSeleccionada->fechaEntrega ?? 'No especificado' }}
                                </dd>
                            </div>

                            <!-- Estado -->
                            <div>
                                <dt class="text-sm font-medium p-text">Estado del Pedido</dt>
                                <dd class="mt-1 text-sm p-text">
                                    @if ($ventaSeleccionada->estadoPedido == 0)
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-red-600 text-white">
                                        Cancelado
                                    </span>
                                    @elseif ($ventaSeleccionada->estadoPedido == 1)
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-600 text-white">
                                        Pedido
                                    </span>
                                    @elseif ($ventaSeleccionada->estadoPedido == 2)
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-600 text-white">
                                        Entregado
                                    </span>
                                    @endif
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium p-text">Estado del Pago</dt>
                                <dd class="mt-1 text-sm p-text">
                                    @if ($ventaSeleccionada->estadoPago == 0)
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-600 text-white">
                                        Parcial
                                    </span>
                                    @elseif ($ventaSeleccionada->estadoPago == 1)
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-600 text-white">
                                        Completo
                                    </span>
                                    @endif
                                </dd>
                            </div>

                            <!-- Lista de Items -->
                            <div class="col-span-2 mt-4">
                                <dt class="text-sm font-medium p-text">Items de la Venta</dt>
                                <dd class="mt-1">
                                    <table class="w-full text-sm text-left border border-slate-200 rounded-lg">
                                        <thead class="text-xs uppercase bg-gray-100">
                                            <tr>
                                                <th class="px-4 py-2">Producto</th>
                                                <th class="px-4 py-2">Cantidad</th>
                                                <th class="px-4 py-2">Precio Unit.</th>
                                                <th class="px-4 py-2">Total</th>
                                                <th class="px-4 py-2">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $total = 0; @endphp
                                            @foreach ($ventaSeleccionada->itemVentas as $item)
                                            @php
                                            $subtotal = $item->cantidad * $item->precio;
                                            $total += $subtotal;
                                            $nombreProducto = '?';
                                            if ($item->existencia && $item->existencia->existenciable_type === 'App\\Models\\Stock') {
                                            $stock = $item->existencia->existenciable;
                                            if ($stock && $stock->producto) {
                                            $nombreProducto = $stock->producto->nombre;
                                            }
                                            }
                                            @endphp
                                            <tr class="border-b">
                                                <td class="px-4 py-2">{{ $nombreProducto }}</td>
                                                <td class="px-4 py-2">{{ $item->cantidad }}</td>
                                                <td class="px-4 py-2">{{ number_format($item->precio, 2) }}</td>
                                                <td class="px-4 py-2">{{ number_format($subtotal, 2) }}</td>
                                                <td class="px-4 py-2">
                                                    @if ($item->estado == 0)
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-red-600 text-white">
                                                        Cancelado
                                                    </span>
                                                    @elseif ($item->estado == 1)
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-600 text-white">
                                                        Pedido
                                                    </span>
                                                    @elseif ($item->estado == 2)
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-600 text-white">
                                                        Vendido
                                                    </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                            <tr class="bg-gray-50 font-semibold">
                                                <td colspan="3" class="px-4 py-2 text-right">Total:</td>
                                                <td class="px-4 py-2">{{ number_format($total, 2) }}</td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Botón de cierre -->
                    <div class="mt-4 flex justify-center w-full">
                        <button type="button" wire:click="$set('previewVentaModal', false)"
                            class="text-red-500 hover:text-red-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
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

    <!-- Modal para agregar item manual -->
    @if($itemManualModal)
    <div class="modal-first">
        <div class="modal-center">
            <div class="modal-hiden">
                <div class="center-col">
                    <h3 class="text-base font-semibold p-text">Agregar Item Manual</h3>
                    <div class="mt-4">
                        <!-- Selección de producto -->
                        <h3 class="p-text">Producto</h3>
                        <select wire:model="productoSeleccionado" class="p-text input-g">
                            <option value="">Seleccione un producto</option>
                            @foreach ($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                            @endforeach
                        </select>
                        @error('productoSeleccionado') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Selección de stock -->
                        @if($productoSeleccionado && $stocksProducto && count($stocksProducto) > 0)
                        <h3 class="p-text mt-3">Stock</h3>
                        <select wire:model="stockManualId" class="p-text input-g">
                            <option value="">Seleccione un stock</option>
                            @foreach ($stocksProducto as $stock)
                            <option value="{{ $stock->id }}">
                                Lote: {{ date('d/m/Y', strtotime($stock->fechaElaboracion)) }} -
                                Disponible: {{ $stock->existencia ? $stock->existencia->cantidad : 0 }}
                            </option>
                            @endforeach
                        </select>
                        @error('stockManualId') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Cantidades -->
                        <div class="grid grid-cols-2 gap-4 mt-3">
                            <div>
                                <h3 class="p-text">Cantidad Nuevos</h3>
                                <input type="number" min="0" wire:model="cantidadManualNuevo" class="p-text input-g">
                                @error('cantidadManualNuevo') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <h3 class="p-text">Cantidad Usados</h3>
                                <input type="number" min="0" wire:model="cantidadManualUsados" class="p-text input-g">
                                @error('cantidadManualUsados') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        @elseif($productoSeleccionado)
                        <div class="mt-3 text-red-600 text-sm">
                            No hay stocks disponibles para este producto en la sucursal seleccionada.
                        </div>
                        @endif
                    </div>

                    <!-- Botones -->
                    <div class="mt-6 flex justify-center w-full space-x-4">
                        <button type="button" wire:click="agregarStockManual"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            @if(!$stockManualId || ($cantidadManualNuevo <=0 && $cantidadManualUsados <=0)) disabled @endif>
                            Agregar a Distribución
                        </button>
                        <button type="button" wire:click="$set('itemManualModal', false)"
                            class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
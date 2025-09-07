<div class="p-text p-2 mt-10 flex justify-center">
    <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
        <div>
            <h6 class="text-xl font-bold mb-4 px-4 p-text">Gestión de Productos</h6>

            <!-- Botón de registro y buscador -->
            <div class="flex justify-center items-center gap-4 w-full max-w-2xl mx-auto">
                <button title="Registrar Producto" wire:click='abrirModal("create")'
                    class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5v14m7-7h-14" />
                    </svg>
                </button>

                <input type="text" wire:model.live="search" placeholder="Buscar producto..."
                    class="input-g w-auto sm:w-64" />
            </div>

            <!-- Tabla -->
            <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
                <table
                    class="w-full text-sm text-left border border-slate-200 dark:border-cyan-200 rounded-lg border-collapse">
                    <thead class="text-xs md:text-sm uppercase color-bg">
                        <tr class="bg-gray-100 dark:bg-gray-800">
                            <th scope="col" class="px-4 py-3 p-text text-left">DETALLES DEL PRODUCTO</th>
                            <th scope="col" class="px-4 py-3 p-text text-left">SUCURSAL Y STOCK</th>
                            <th scope="col" class="px-4 py-3 p-text text-right">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productos as $producto)
                        <tr class="color-bg border border-slate-200">
                            <!-- Columna 1: Imagen + Info producto -->
                            <td class="px-4 py-4 p-text text-left align-top">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="producto"
                                        class="h-20 w-20 sm:h-24 sm:w-24 object-cover rounded mb-2 sm:mb-0">
                                    <div class="text-sm space-y-1">

                                        <div><strong>Nombre:</strong> {{ $producto->nombre }}</div>
                                        <div><strong>Tipo Producto:</strong> {{ $producto->tipoProducto?'Con retorno':'Sin retorno' }}</div>
                                        <div><strong>Capacidad:</strong> {{ $producto->capacidad }} ml</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Columna 2: Sucursal + stock -->
                            <td class="px-4 py-4 text-left align-top text-sm">
                                <strong class="block mb-1">Sucursal:</strong>
                                @forelse ($producto->existencias as $existencia)
                                <span class="block">
                                    <span class="@if ($existencia->cantidad > ($existencia->cantidadMinima * 2)) text-green-500
                                                @elseif ($existencia->cantidad >= $existencia->cantidadMinima && $existencia->cantidad <= ($existencia->cantidadMinima * 2)) text-yellow-500
                                                @else text-red-500 @endif">
                                        {{ number_format($existencia->cantidad) . '/' . $existencia->cantidadMinima }}:
                                    </span>
                                    {{ Str::limit($existencia->sucursal->nombre ?? 'Sucursal Desconocida', 18, '...') }}
                                </span>
                                @empty
                                <span class="text-xs text-gray-500">Sin stock registrado</span>
                                @endforelse

                                <strong class="p-text block mt-2">
                                    {{ number_format($producto->existencias->sum('cantidad')) }}: Total productos
                                </strong>
                            </td>

                            

                            <!-- Columna 3: Acciones -->
                            <td class="px-4 py-4 text-right align-middle">
                                <div class="flex justify-end space-x-2">
                                    <!-- Editar -->
                                    <button title="Editar Producto" wire:click="abrirModal('edit', {{ $producto->id }})"
                                        class="text-blue-500 hover:text-blue-600 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icon-tabler-edit">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                            <path
                                                d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                            <path d="M16 5l3 3" />
                                        </svg>
                                    </button>

                                    <!-- Detalles -->
                                    <button title="Ver detalles" wire:click="modaldetalle({{ $producto->id }})"
                                        class="text-indigo-500 hover:text-indigo-600 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
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
                                No hay productos registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            <div class="mt-4 flex justify-center">
                {{ $productos->links() }}
            </div>
        </div>
    </div>


    @if ($modal)
    <div class="modal-first">
        <div class="modal-center">
            <div class="modal-hiden">
                <div class="center-col">
                    <h3 class="p-text">{{ $accion === 'create' ? 'Registrar Producto' : 'Editar Producto' }}</h3>

                    <div class="over-col">
                        <!-- Imagen -->

                        <label class="block text-sm">Imagen</label>
                        <input type="file" wire:model="imagen" accept="image/*" class="w-full border p-2 rounded" />
                        @error('imagen') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror


                        <!-- Nombre -->
                        <h3 class="p-text">Nombre</h3>
                        <input type="text" wire:model="nombre" class="p-text input-g" />
                        @error('nombre') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Capacidad -->
                        <h3 class="p-text">Capacidad</h3>
                        <input type="text" wire:model="capacidad" class="p-text input-g" />
                        @error('capacidad') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Unidad -->
                        <h3 class="p-text">Unidad</h3>
                        <select wire:model="unidad" class="p-text input-g">
                            <option value="">Seleccione una unidad</option>
                            <option value="L">L</option>
                            <option value="ml">ml</option>
                            <option value="g">g</option>
                            <option value="Kg">Kg</option>
                            <option value="unidad">unidad</option>
                        </select>
                        @error('unidad') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <h3 class="p-text">Tipo de Contenido</h3>
                        <select id="tipoContenido" wire:model="tipoContenido" class="p-text input-g">

                            <option value="1">Líquido</option>
                            <option value="2">Líquido con gas</option>
                            <option value="3">Solido</option>
                        </select>
                        @error('tipoContenido')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror

                        <!-- Tipo de Producto -->
                        <h3 class="p-text">Tipo de Producto</h3>

                        <div class="flex space-x-6 justify-center">
                            <!-- Botón de radio para "Con retorno" -->
                            <label class="flex items-center space-x-2">
                                <input type="radio" wire:model="tipoProducto" value="1"
                                    class="form-radio hidden peer" />
                                <span
                                    class="p-text inline-block py-2 px-4 rounded-lg cursor-pointer border border-gray-300 hover:bg-indigo-100 peer-checked:bg-cyan-950 peer-checked:text-white">
                                    Con retorno
                                </span>
                            </label>

                            <!-- Botón de radio para "Sin retorno" -->
                            <label class="flex items-center space-x-2">
                                <input type="radio" wire:model="tipoProducto" value="0"
                                    class="form-radio hidden peer" />
                                <span
                                    class="p-text inline-block py-2 px-4 rounded-lg cursor-pointer border border-gray-300 hover:bg-indigo-100 peer-checked:bg-cyan-950 peer-checked:text-white">
                                    Sin retorno
                                </span>
                            </label>
                        </div>

                        @error('tipoProducto')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror


                        <!-- Precio de Referencia 1 -->
                        <h3 class="p-text">Precio de Referencia 1</h3>
                        <input type="number" wire:model="precioReferencia" class="p-text input-g" />
                        @error('precioReferencia') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Precio de Referencia 2 -->
                        <h3 class="p-text">Precio de Referencia 2</h3>
                        <input type="number" wire:model="precioReferencia2" class="p-text input-g" />
                        @error('precioReferencia2') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Precio de Referencia 3 -->
                        <h3 class="p-text">Precio de Referencia 3</h3>
                        <input type="number" wire:model="precioReferencia3" class="p-text input-g" />
                        @error('precioReferencia3') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Observaciones -->
                        <h3 class="p-text">Observaciones</h3>
                        <input wire:model="observaciones" class="p-text input-g"></input>
                        @error('observaciones') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Estado -->
                        <h3 class="p-text">Estado</h3>

                        <div class="flex space-x-6 justify-center">
                            <!-- Botón para "Activo" -->
                            <label class="flex items-center space-x-2">
                                <input type="radio" wire:model="estado" value="1" class="form-radio hidden peer" />
                                <span
                                    class="p-text inline-block py-2 px-4 rounded-lg cursor-pointer border border-gray-300 hover:bg-indigo-100 peer-checked:bg-cyan-950 peer-checked:text-white">
                                    Activo
                                </span>
                            </label>

                            <!-- Botón para "Inactivo" -->
                            <label class="flex items-center space-x-2">
                                <input type="radio" wire:model="estado" value="0" class="form-radio hidden peer" />
                                <span
                                    class="p-text inline-block py-2 px-4 rounded-lg cursor-pointer border border-gray-300 hover:bg-indigo-100 peer-checked:bg-cyan-950 peer-checked:text-white">
                                    Inactivo
                                </span>
                            </label>
                        </div>
                        @error('estado') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <h3 class="p-text">Base</h3>
                        <select wire:model="base_id" class="p-text input-g">
                            @foreach($bases as $base)
                            <option value="{{ $base->id }}">
                                {{ $base->capacidad }}{{ $base->unidad }} - {{ $base->preforma->insumo ?? 'Sin preforma'
                                }}
                            </option>
                            @endforeach
                        </select>

                        @error('base_id')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror


                    </div>

                    <!-- Botones -->
                    <div class="mt-6 flex justify-center w-full space-x-4">
                        <button type="button" wire:click="guardar"
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


    @if ($modalDetalle)
    <div class="modal-first">
        <div class="modal-center">
            <div class="modal-hiden">
                <div class="center-col">
                    <h3 class="text-base font-semibold p-text" id="modal-title">Detalles del Producto</h3>
                    <div class="mt-4">
                        <dl class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-semibold p-text">Base Asociada</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $productoSeleccionado?->base?->capacidad }}{{
                                    $productoSeleccionado?->base?->unidad }}
                                    -
                                    {{ $productoSeleccionado?->base?->preforma?->insumo ?? 'Sin preforma' }}
                                </dd>
                            </div>
                            <!-- Nombre -->
                            <div>
                                <dt class="text-sm font-semibold p-text">Nombre</dt>
                                <dd class="mt-1 text-sm p-text">{{ $productoSeleccionado->nombre ?? 'No especificado' }}
                                </dd>
                            </div>

                            <!-- Tipo de Contenido -->
                            <div>
                                <dt class="text-sm font-semibold p-text">Tipo de Contenido</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $productoSeleccionado->tipoContenido === 0 ? 'Líquido' :
                                    ($productoSeleccionado->tipoContenido === 1 ? 'Sólido' :
                                    ($productoSeleccionado->tipoContenido === 2 ? 'Polvo' :
                                    ($productoSeleccionado->tipoContenido === 3 ? 'Pastillas' :
                                    ($productoSeleccionado->tipoContenido === 4 ? 'Gel' :
                                    ($productoSeleccionado->tipoContenido === 5 ? 'Aerosol' : 'No especificado'))))) }}
                                </dd>
                            </div>

                            <!-- Tipo de Producto -->
                            <div>
                                <dt class="text-sm font-semibold p-text">Tipo de Retorno</dt>
                                <dd class="mt-1 text-sm p-text">
                                    @if (($productoSeleccionado['tipoProducto'] ?? false) == 1)
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-600 text-white">
                                        Con retorno
                                    </span>
                                    @else
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-red-600 text-white">
                                        Sin retorno
                                    </span>
                                    @endif
                                </dd>
                            </div>



                            <!-- Capacidad -->
                            <div>
                                <dt class="text-sm font-semibold p-text">Capacidad</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $productoSeleccionado->capacidad ?? 'No especificada' }}
                                </dd>
                            </div>

                            <!-- Unidad -->
                            <div>
                                <dt class="text-sm font-semibold p-text">Unidad</dt>
                                <dd class="mt-1 text-sm p-text">{{ $productoSeleccionado->unidad ?? 'No especificada' }}
                                </dd>
                            </div>

                            <!-- Precio Referencia 1 -->
                            <div>
                                <dt class="text-sm font-semibold p-text">Precio de Referencia</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $productoSeleccionado->precioReferencia ?? 'No especificado' }}
                                </dd>
                            </div>

                            <!-- Precio Referencia 2 -->
                            <div>
                                <dt class="text-sm font-semibold p-text">Precio de Referencia 2</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $productoSeleccionado->precioReferencia2 ?? 'No especificado' }}
                                </dd>
                            </div>

                            <!-- Precio Referencia 3 -->
                            <div>
                                <dt class="text-sm font-semibold p-text">Precio de Referencia 3</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $productoSeleccionado->precioReferencia3 ?? 'No especificado' }}
                                </dd>
                            </div>

                            <!-- Estado -->
                            <div>
                                <dt class="text-sm font-semibold p-text">Estado</dt>
                                <dd class="mt-1 text-sm p-text">
                                    @if (($productoSeleccionado['estado'] ?? false) == 1)
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-600 text-white">Activo</span>
                                    @else
                                    <span
                                        class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-red-600 text-white">Inactivo</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="mt-6 flex justify-center w-full">
                        <button type="button" wire:click="cerrarModalDetalle"
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



</div>
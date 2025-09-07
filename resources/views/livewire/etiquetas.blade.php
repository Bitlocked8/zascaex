<div class="p-text p-2 mt-10 flex justify-center">
    <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
        <div>
            <h6 class="text-xl font-bold mb-4 px-4 p-text">Gestión de Etiquetas</h6>

            <!-- Botón de registro y buscador -->
            <div class="flex justify-center items-center gap-4 w-full max-w-2xl mx-auto">
                <button title="Registrar etiqueta" wire:click='abrirModal("create")'
                    class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5v14m7-7h-14" />
                    </svg>
                </button>

                <input type="text" wire:model.live="search" placeholder="Buscar etiqueta..."
                    class="input-g w-auto sm:w-64" />
            </div>

            <!-- Tabla -->
            <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left border border-slate-200 dark:border-cyan-200 rounded-lg border-collapse">
                    <thead class="text-xs md:text-sm uppercase color-bg">
                        <tr class="bg-gray-100 dark:bg-gray-800">
                            <th scope="col" class="px-4 py-3 p-text text-left">IMAGEN Y DETALLES</th>
                            <th scope="col" class="px-4 py-3 p-text text-left">SUCURSAL Y STOCK</th>
                            <th scope="col" class="px-4 py-3 p-text text-right">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($etiquetas as $etiqueta)
                        <tr class="color-bg border border-slate-200 text-sm">
                            <!-- Columna 1: Imagen + Detalles -->
                            <td class="px-4 py-4 text-left p-text align-top">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                                    <img src="{{ asset('storage/' . $etiqueta->imagen) }}" alt="Etiqueta"
                                        class="h-20 w-20 sm:h-24 sm:w-24 object-cover rounded mb-2 sm:mb-0">
                                    <div class="text-sm">

                                        <div><strong>Capacidad:</strong> {{ $etiqueta->capacidad }}</div>
                                        <div><strong>Descripción:</strong> {{ $etiqueta->descripcion ?? 'Sin descripción' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Columna 2: Sucursal + Stock -->
                            <td class="px-4 py-4 text-left align-top text-sm">
                                <strong class="block mb-1">Sucursal:</strong>
                                @forelse ($etiqueta->existencias as $existencia)
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

                                <strong class="block mt-2">
                                    {{ number_format($etiqueta->existencias->sum('cantidad')) }}: Total etiquetas
                                </strong>
                            </td>

                            <!-- Columna 3: Acciones -->
                            <td class="px-4 py-4 text-right align-center">
                                <div class="flex justify-end space-x-2">
                                    <!-- Editar -->
                                    <button title="Editar Etiqueta" wire:click="abrirModal('edit', {{ $etiqueta->id }})"
                                        class="text-blue-500 hover:text-blue-600 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icon-tabler-edit">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                            <path d="M16 5l3 3" />
                                        </svg>
                                    </button>

                                    <!-- Detalles -->
                                    <button title="Ver detalles" wire:click="modaldetalle({{ $etiqueta->id }})"
                                        class="text-indigo-500 hover:text-indigo-600 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
                                No hay etiquetas registradas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>



            <!-- Paginación -->
            <div class="mt-4 flex justify-center">
                {{ $etiquetas->links() }}
            </div>
        </div>
    </div>

    <!-- Modal de Registro/Edición -->
    @if ($modal)
    <div class="modal-first">
        <div class="modal-center">
            <div class="modal-hiden">
                <div class="center-col">
                    <h3 class="p-text">
                        {{ $accion === 'edit' ? 'Editar Etiqueta' : 'Nueva Etiqueta' }}
                    </h3>

                    <div class="over-col">

                        <h3 class="p-text">Imagen</h3>
                        <input type="file" wire:model="imagen" accept="image/*" class="p-text input-g" />
                        @error('imagen') <span class="error-message text-red-500">{{ $message }}</span> @enderror

                        <h3 class="p-text">Capacidad</h3>
                        <input type="text" wire:model="capacidad" class="p-text input-g" />
                        @error('capacidad') <span class="error-message text-red-500">{{ $message }}</span> @enderror

                        <h3 class="p-text">Descripción</h3>
                        <input type="text" wire:model="descripcion" class="p-text input-g" />
                        @error('descripcion') <span class="error-message text-red-500">{{ $message }}</span> @enderror

                        <h3 class="p-text mb-2">Estado</h3>
                        <div class="flex space-x-6 justify-center">
                            <!-- Botón para "Activo" -->
                            <label class="flex items-center space-x-2">
                                <input type="radio" wire:model="estado" value="1" class="form-radio hidden peer" />
                                <span class="p-text inline-block py-2 px-4 rounded-lg cursor-pointer border border-gray-300 hover:bg-indigo-100 peer-checked:bg-cyan-950 peer-checked:text-white">
                                    Activo
                                </span>
                            </label>

                            <!-- Botón para "Inactivo" -->
                            <label class="flex items-center space-x-2">
                                <input type="radio" wire:model="estado" value="0" class="form-radio hidden peer" />
                                <span class="p-text inline-block py-2 px-4 rounded-lg cursor-pointer border border-gray-300 hover:bg-indigo-100 peer-checked:bg-cyan-950 peer-checked:text-white">
                                    Inactivo
                                </span>
                            </label>
                        </div>
                        @error('estado')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                        @enderror


                        <h3 class="p-text">Cliente</h3>
                        <select wire:model="cliente_id" class="p-text input-g">
                            <option value="">Seleccione un cliente</option>
                            @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                        @error('cliente_id') <span class="error-message text-red-500">{{ $message }}</span> @enderror

                    </div>

                    <div class="mt-6 flex justify-center w-full space-x-4">
                        <button type="button" wire:click="guardar"
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



    <!-- Modal de Detalle -->
    @if ($modalDetalle)
    <div class="modal-first">
        <div class="modal-center">
            <div class="modal-hiden">
                <div class="center-col">
                    <h3 class="p-text mb-4">Detalles de la Etiqueta</h3>

                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <!-- Columna de Información -->
                        <div class="flex flex-col gap-4">
                            <p class="text-semibold">
                                <strong class="p-text">Capacidad:</strong>
                                {{ $etiquetaSeleccionada['capacidad'] }}
                            </p>
                            <p class="text-semibold">
                                <strong class="p-text">Descripción:</strong>
                                {{ $etiquetaSeleccionada['descripcion'] }}
                            </p>
                            <p class="text-semibold">
                                <strong class="p-text">Estado:</strong>
                                <span class="text-{{ $etiquetaSeleccionada['estado'] ? 'green' : 'red' }}-500">
                                    {{ $etiquetaSeleccionada['estado'] ? 'Activo' : 'Inactivo' }}
                                </span>
                            </p>
                            <p class="text-semibold">
                                <strong class="p-text">Cliente:</strong>
                                {{ $etiquetaSeleccionada['cliente']['nombre'] ?? 'Sin cliente' }}
                            </p>
                            <!-- Si quieres mostrar más información, puedes agregar más datos aquí -->
                        </div>
                    </div>

                    <!-- Botón de Cerrar Modal -->
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
<div class="p-text p-2 mt-10 flex justify-center">
    <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
        <div>
            <h6 class="text-xl font-bold mb-4 px-4 p-text">Gestión de Coches</h6>

            <!-- Botón de registro y buscador -->
            <div class="flex justify-center items-center gap-4 w-full max-w-2xl mx-auto">
                <button title="Registrar Coche" wire:click='abrirModal("create")'
                    class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-car">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5" />
                    </svg>
                </button>

                <input type="text" wire:model.live="search" placeholder="Buscar coche..."
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
                        @forelse ($coches as $coche)
                            <tr class="color-bg border border-slate-200">
                                <td class="px-6 py-4 p-text text-left">
                                    <div class="mb-2">
                                        <span class="font-semibold block">Placa:</span>
                                        <span>{{ $coche->placa }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold block">Modelo:</span>
                                        <span>{{ $coche->modelo }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <button title="Editar" wire:click="editarCoche({{ $coche->id }})"
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
                                        <button title="Ver Detalle" wire:click="verDetalle({{ $coche->id }})"
                                            class="text-yellow-500 hover:text-yellow-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-eye-plus">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                <path d="M12 18c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                <path d="M16 19h6" />
                                                <path d="M19 16v6" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-left py-4 text-gray-600 dark:text-gray-400">
                                    No hay coches registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-center">
                {{ $coches->links() }}
            </div>
        </div>
    </div>
    <!-- Modal de registro y edición -->
    @if ($modal)
        <div class="modal-first">
            <div class="modal-center">
                <div class="modal-hiden">
                    <div class="center-col">
                        <h3 class="p-text">{{ $accion === 'create' ? 'Registrar Coche' : 'Editar Coche' }}</h3>
                        <div class="over-col">
                            <!-- Móvil -->
                            <h3 class="p-text">Móvil</h3>
                            <input type="number" wire:model.defer="movil" class="p-text input-g">
                            @error('movil') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                            <!-- Marca -->
                            <h3 class="p-text">Marca</h3>
                            <input type="text" wire:model.defer="marca" class="p-text input-g">
                            @error('marca') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                            <!-- Modelo -->
                            <h3 class="p-text">Modelo</h3>
                            <input type="text" wire:model.defer="modelo" class="p-text input-g">
                            @error('modelo') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                            <!-- Año -->
                            <h3 class="p-text">Año</h3>
                            <input type="number" wire:model.defer="anio" class="p-text input-g">
                            @error('anio') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                            <!-- Color -->
                            <h3 class="p-text">Color</h3>
                            <input type="text" wire:model.defer="color" class="p-text input-g">
                            @error('color') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                            <!-- Placa -->
                            <h3 class="p-text">Placa</h3>
                            <input type="text" wire:model.defer="placa" class="p-text input-g">
                            @error('placa') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                            <!-- Estado -->
                            <h3 class="p-text">Estado</h3>
                            <select wire:model.defer="estado" class="p-text input-g">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            @error('estado') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Botones -->
                        <div class="mt-6 flex justify-center w-full space-x-4">
                            <button type="button" wire:click="guardarCoche"
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


    @if ($detalleModal)
        <div class="modal-first">
            <div class="modal-center">
                <div class="modal-hiden">
                    <div class="center-col">
                        <h3 class="text-base font-semibold p-text" id="modal-title">Detalles del Coche</h3>
                        <div class="mt-4">
                            <dl class="grid grid-cols-2 gap-4">
                                <!-- Móvil -->
                                <div>
                                    <dt class="text-sm font-medium p-text">Móvil</dt>
                                    <dd class="mt-1 text-sm p-text">{{ $cocheSeleccionado->movil ?? 'No especificado' }}
                                    </dd>
                                </div>

                                <!-- Marca -->
                                <div>
                                    <dt class="text-sm font-medium p-text">Marca</dt>
                                    <dd class="mt-1 text-sm p-text">{{ $cocheSeleccionado->marca ?? 'No especificada' }}
                                    </dd>
                                </div>

                                <!-- Modelo -->
                                <div>
                                    <dt class="text-sm font-medium p-text">Modelo</dt>
                                    <dd class="mt-1 text-sm p-text">{{ $cocheSeleccionado->modelo ?? 'No especificado' }}
                                    </dd>
                                </div>

                                <!-- Año -->
                                <div>
                                    <dt class="text-sm font-medium p-text">Año</dt>
                                    <dd class="mt-1 text-sm p-text">{{ $cocheSeleccionado->anio ?? 'No especificado' }}</dd>
                                </div>

                                <!-- Color -->
                                <div>
                                    <dt class="text-sm font-medium p-text">Color</dt>
                                    <dd class="mt-1 text-sm p-text">{{ $cocheSeleccionado->color ?? 'No especificado' }}
                                    </dd>
                                </div>

                                <!-- Placa -->
                                <div>
                                    <dt class="text-sm font-medium p-text">Placa</dt>
                                    <dd class="mt-1 text-sm p-text">{{ $cocheSeleccionado->placa ?? 'No especificada' }}
                                    </dd>
                                </div>

                                <!-- Estado -->
                                <div>
                                    <dt class="text-sm font-medium p-text">Estado</dt>
                                    <dd class="mt-1 text-sm p-text">
                                        {{ $cocheSeleccionado->estado === 1 ? 'Activo' : 'Inactivo' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Botón cerrar -->
                        <div>
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

</div>
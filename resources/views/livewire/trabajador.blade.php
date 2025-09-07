<div class="p-text p-2 mt-10 flex justify-center">
    <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
        <div>
            <h6 class="text-xl font-bold mb-4 px-4 p-text">Gestión de Trabajos</h6>

            <!-- Botón de registro y buscador -->
            <div class="flex justify-center items-center gap-4 w-full max-w-2xl mx-auto">
                <button title="Registrar Trabajo" wire:click='abrirModal("create")'
                    class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5v14m7-7h-14" />
                    </svg>
                </button>

                <input type="text" wire:model.live="search" placeholder="Buscar por Personal o Tipo..."
                    class="input-g w-auto sm:w-64" />
            </div>

            <!-- Tabla -->
            <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left border border-slate-200 dark:border-cyan-200 rounded-lg border-collapse">
                    <thead class="text-xs md:text-sm uppercase color-bg">
                        <tr class="bg-gray-100 dark:bg-gray-800">
                            <th scope="col" class="px-4 py-3 p-text text-left">PERSONAL Y DETALLES</th>
                            <th scope="col" class="px-4 py-3 p-text text-left">SUCURSAL Y ESTADO</th>
                            <th scope="col" class="px-4 py-3 p-text text-right">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trabajos as $trabajo)
                        <tr class="color-bg border border-slate-200 text-sm">
                            <!-- Primera columna: Personal, fecha de inicio y descripción -->
                            <td class="px-4 py-4 text-left p-text align-top">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                                    <div class="text-sm">
                                        <div><strong>Personal:</strong> {{ $trabajo->personal->nombres }}</div>
                                        <div><strong>Fecha de Inicio:</strong> {{ $trabajo->fechaInicio }}</div>
                                        <div><strong>Fecha Final:</strong> {{ $trabajo->fechaFinal }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Segunda columna: Sucursal y Estado -->
                            <td class="px-4 py-4 text-left align-top text-sm">
                                <strong class="block mb-1">Sucursal:</strong>
                                <span class="block">
                                    {{ Str::limit($trabajo->sucursal->nombre ?? 'Sucursal Desconocida', 18, '...') }}
                                </span>
                                <strong class="block mt-2">
                                    Estado: {{ $trabajo->estado ? 'Activo' : 'Inactivo' }}
                                </strong>
                            </td>

                            <!-- Tercera columna: Acciones -->
                            <td class="px-4 py-4 text-right align-center">
                                <div class="flex justify-end space-x-2">
                                    <!-- Editar -->
                                    <button title="Editar Trabajo" wire:click="abrirModal('edit', {{ $trabajo->id }})"
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
                                    <button title="Ver detalles" wire:click="modaldetalle({{ $trabajo->id }})"
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
                            <td colspan="3" class="text-center py-4 text-gray-600 dark:text-gray-400 text-sm">
                                No hay trabajos registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-4 flex justify-center">
                {{ $trabajos->links() }}
            </div>
        </div>
    </div>
    @if ($modal)
    <div class="modal-first">
        <div class="modal-center">
            <div class="modal-hiden">
                <div class="center-col">
                    <h3 class="p-text">
                        {{ $accion === 'edit' ? 'Editar Trabajo' : 'Nuevo Trabajo' }}
                    </h3>

                    <div class="over-col">
                        <!-- Fecha de Inicio -->
                        <h3 class="p-text">Fecha de Inicio</h3>
                        <input type="date" wire:model="fechaInicio" class="p-text input-g" />
                        @error('fechaInicio') <span class="error-message text-red-500">{{ $message }}</span> @enderror

                        <!-- Fecha de Finalización -->
                        <h3 class="p-text">Fecha de Finalización</h3>
                        <input type="date" wire:model="fechaFinal" class="p-text input-g" />
                        @error('fechaFinal') <span class="error-message text-red-500">{{ $message }}</span> @enderror

                        <!-- Estado -->
                        <h3 class="p-text">Estado</h3>
                        <select wire:model="estado" class="p-text input-g">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                        @error('estado') <span class="error-message text-red-500">{{ $message }}</span> @enderror

                        <!-- Sucursal -->
                        <h3 class="p-text">Sucursal</h3>
                        <select wire:model="sucursal_id" class="p-text input-g">
                            <option value="">Selecciona una Sucursal</option>
                            @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                            @endforeach
                        </select>
                        @error('sucursal_id') <span class="error-message text-red-500">{{ $message }}</span> @enderror

                        <!-- Trabajador -->
                        <h3 class="p-text">Trabajador</h3>
                        <select wire:model="personal_id" class="p-text input-g">
                            <option value="">Selecciona un Trabajador</option>
                            @foreach ($personales as $personal)
                            <option value="{{ $personal->id }}">{{ $personal->nombres }} {{ $personal->apellidos }}</option>
                            @endforeach
                        </select>
                        @error('personal_id') <span class="error-message text-red-500">{{ $message }}</span> @enderror

                    </div>

                    <!-- Botones de Acción -->
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

    @if ($modalDetalle)
    <div class="modal-first">
        <div class="modal-center">
            <div class="modal-hiden">
                <div class="center-col">
                    <h3 class="p-text mb-4">Detalles del Trabajo</h3>

                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <!-- Columna de Información -->
                        <div class="flex flex-col gap-4">
                            <p class="text-semibold">
                                <strong class="p-text">Fecha de Inicio:</strong>
                                {{ \Carbon\Carbon::parse($trabajoSeleccionado['fechaInicio'])->format('d/m/Y') }}
                            </p>

                            <p class="text-semibold">
                                <strong class="p-text">Fecha de Finalización:</strong>
                                {{ $trabajoSeleccionado['fechaFinal'] ? \Carbon\Carbon::parse($trabajoSeleccionado['fechaFinal'])->format('d/m/Y') : 'Sin fecha de finalización' }}
                            </p>

                            <p class="text-semibold">
                                <strong class="p-text">Estado:</strong>
                                <span class="text-{{ $trabajoSeleccionado['estado'] ? 'green' : 'red' }}-500">
                                    {{ $trabajoSeleccionado['estado'] ? 'Activo' : 'Inactivo' }}
                                </span>
                            </p>

                            <p class="text-semibold">
                                <strong class="p-text">Sucursal:</strong>
                                {{ $trabajoSeleccionado['sucursal']['nombre'] }}
                            </p>

                            <p class="text-semibold">
                                <strong class="p-text">Personal Asignado:</strong>
                                {{ $trabajoSeleccionado['personal']['nombres'] }} {{ $trabajoSeleccionado['personal']['apellidos'] }}
                            </p>

                            <!-- Imagen del Trabajo (si existe) -->
                            @if ($trabajoSeleccionado['imagen'])
                            <p class="text-semibold">
                                <strong class="p-text">Imagen:</strong>
                                <img src="{{ Storage::url($trabajoSeleccionado['imagen']) }}" alt="Imagen del trabajo" class="w-32 h-32 object-cover mt-2">
                            </p>
                            @endif
                        </div>
                    </div>

                    <!-- Botón de Cerrar Modal -->
                    <div class="mt-6 flex justify-center w-full">
                        <button wire:click="cerrarModalDetalle" class="text-red-500 hover:text-red-600 mx-1">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
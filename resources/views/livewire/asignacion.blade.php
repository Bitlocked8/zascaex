<div class="p-text p-2 mt-10 flex justify-center">
    <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
        <div>
            <h6 class="text-xl font-bold mb-4 px-4 p-text">Gestión de Asignaciones</h6>

            <!-- Botón de registro y buscador -->
            <div class="flex justify-center items-center gap-4 w-full max-w-2xl mx-auto">
                <button title="Registrar Asignación" wire:click='abrirModal("create")'
                    class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-pin">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M15 4.5l-4 4l-4 1.5l-1.5 1.5l7 7l1.5 -1.5l1.5 -4l4 -4" />
                        <path d="M9 15l-4.5 4.5" />
                        <path d="M14.5 4l5.5 5.5" />
                    </svg>
                </button>

                <input type="text" wire:model.live="search" placeholder="Buscar asignación..."
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
                        @forelse ($asignaciones as $asignacion)
                            <tr class="color-bg border border-slate-200">
                                <td class="px-6 py-4 p-text text-left">
                                    <div class="mb-2">
                                        <span class="font-semibold block">Inicio:</span>
                                        <span>{{ $asignacion->fechaInicio }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="font-semibold block">Fin:</span>
                                        <span>{{ $asignacion->fechaFinal }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="font-semibold block">Coche:</span>
                                        <span>{{ $asignacion->coche->placa ?? 'Sin vehículo' }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="font-semibold block">Personal:</span>
                                        <span>{{ $asignacion->personal->nombres ?? 'Sin personal' }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="font-semibold block">Estado:</span>
                                        <span class="{{ $asignacion->estado ? 'bg-green-900 text-white' : 'bg-red-900 text-white' }} 
                                             px-3 py-1 rounded-full text-sm font-medium cursor-default inline-block">
                                            {{ $asignacion->estado ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <button title="Editar" wire:click="editarAsignacion({{ $asignacion->id }})"
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
                                        <button title="Ver Detalle" wire:click="verDetalle({{ $asignacion->id }})"
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
                                    No hay asignaciones registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-4 flex justify-center">
                {{ $asignaciones->links() }}
            </div>
        </div>
    </div>
    @if ($modal)
        <div class="modal-first">
            <div class="modal-center">
                <div class="modal-hiden">
                    <div class="center-col">
                        <h3 class="p-text">{{ $accion === 'create' ? 'Registrar Asignación' : 'Editar Asignación' }}</h3>
                        <div class="over-col">
                            <!-- Fecha Inicio -->
                            <h3 class="p-text">Fecha de Inicio</h3>
                            <input type="date" wire:model.defer="fechaInicio" class="p-text input-g">
                            @error('fechaInicio') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                            <!-- Fecha Final -->
                            <h3 class="p-text">Fecha Final</h3>
                            <input type="date" wire:model.defer="fechaFinal" class="p-text input-g">
                            @error('fechaFinal') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                            <!-- Estado -->
                            <h3 class="p-text">Estado</h3>
                            <select wire:model.defer="estado" class="p-text input-g">
                                <option value="">Seleccione estado</option>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            @error('estado') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                            <!-- Coche -->
                            <h3 class="p-text">Coche</h3>
                            <select wire:model.defer="coche_id" class="p-text input-g">
                                <option value="">Seleccione un coche</option>
                                @foreach ($coches as $coche)
                                    <option value="{{ $coche->id }}">{{ $coche->placa }} - {{ $coche->marca }}
                                        {{ $coche->modelo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('coche_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                            <!-- Personal -->
                            <h3 class="p-text">Personal</h3>
                            <select wire:model.defer="personal_id" class="p-text input-g">
                                <option value="">Seleccione personal</option>
                                @foreach ($personals as $personal)
                                    <option value="{{ $personal->id }}">{{ $personal->nombres}}</option>
                                @endforeach
                            </select>
                            @error('personal_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Botones -->
                        <div class="mt-6 flex justify-center w-full space-x-4">
                            <button type="button" wire:click="guardarAsignacion"
                                class="text-indigo-500 hover:text-indigo-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
                                <!-- Icono Guardar -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                    class="icon icon-tabler icon-tabler-device-floppy">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M14 4l0 4l-6 0l0 -4" />
                                </svg>
                            </button>
                            <button type="button" wire:click="cerrarModal"
                                class="text-red-500 hover:text-red-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full">
                                <!-- Icono Cancelar -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                    class="icon icon-tabler icon-tabler-x">
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
                        <h3 class="text-base font-semibold p-text" id="modal-title">Detalles de la Asignación</h3>
                        <div class="mt-4">
                            <dl class="grid grid-cols-2 gap-4">
                                <!-- Fecha de Inicio -->
                                <div>
                                    <dt class="text-sm font-medium p-text">Fecha de Inicio</dt>
                                    <dd class="mt-1 text-sm p-text">
                                        {{ $asignacionSeleccionada->fechaInicio ?? 'No especificada' }}
                                    </dd>
                                </div>

                                <!-- Fecha Final -->
                                <div>
                                    <dt class="text-sm font-medium p-text">Fecha Final</dt>
                                    <dd class="mt-1 text-sm p-text">
                                        {{ $asignacionSeleccionada->fechaFinal ?? 'No especificada' }}
                                    </dd>
                                </div>

                                <!-- Estado -->
                                <div>
                                    <dt class="text-sm font-medium p-text">Estado</dt>
                                    <dd class="mt-1 text-sm p-text">
                                        @if (($asignacionSeleccionada['estado'] ?? false) == 1)
                                            <span
                                                class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-600 text-white">Activo</span>
                                        @else
                                            <span
                                                class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-red-600 text-white">Inactivo</span>
                                        @endif
                                    </dd>
                                </div>

                                <!-- Coche -->
                                <div>
                                    <dt class="text-sm font-medium p-text">Coche</dt>
                                    <dd class="mt-1 text-sm p-text">
                                        {{ $asignacionSeleccionada->coche->placa ?? 'No especificado' }} -
                                        {{ $asignacionSeleccionada->coche->marca ?? 'No especificado' }}
                                        {{ $asignacionSeleccionada->coche->modelo ?? 'No especificado' }}
                                    </dd>
                                </div>

                                <!-- Personal -->
                                <div>
                                    <dt class="text-sm font-medium p-text">Personal</dt>
                                    <dd class="mt-1 text-sm p-text">
                                        {{ $asignacionSeleccionada->personal->nombre ?? 'No asignado' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Botón de Cerrar Modal -->
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
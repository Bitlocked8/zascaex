<div class="p-text p-2 mt-10 flex justify-center">
    <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
        <div>
            <h6 class="text-xl font-bold mb-4 px-4 p-text">Gestión de Roles</h6>

            <!-- Botón de registro y buscador -->
            <!-- <div class="flex justify-center items-center gap-4 w-full max-w-2xl mx-auto">
                <button title="Registrar Rol" wire:click="abrirModal"
                    class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icon-tabler-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5v14m-7 -7h14" />
                    </svg>
                </button>

                <input type="text" wire:model.live="search" placeholder="Buscar rol..."
                    class="input-g w-auto sm:w-64" />
            </div> -->

            <!-- Tabla -->
            <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left border border-slate-200 dark:border-cyan-200 rounded-lg border-collapse">
                    <thead class="text-x uppercase color-bg">
                        <tr>
                            <th scope="col" class="px-6 py-3 p-text text-left">Información</th>
                            <!-- <th scope="col" class="px-6 py-3 p-text text-right">Acciones</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $rol)
                            <tr class="color-bg border border-slate-200">
                                <td class="px-6 py-4 p-text text-left">
                                    <div class="mb-2">
                                        <span class="font-semibold block">Nombre:</span>
                                        <span>{{ $rol->nombre }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="font-semibold block">Descripción:</span>
                                        <span>{{ $rol->descripcion ?? 'Sin descripción' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <!-- <button title="Editar" wire:click="editar({{ $rol->id }})"
                                            class="text-blue-500 hover:text-blue-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-edit" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                <path d="M16 5l3 3" />
                                            </svg>
                                        </button> -->
                                        <!-- <button title="Ver Detalle" wire:click="verDetalle({{ $rol->id }})"
                                            class="text-yellow-500 hover:text-yellow-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icon-tabler-eye">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M12 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                <path d="M22 12c-2.5 -5 -7 -8 -10 -8s-7.5 3 -10 8c2.5 5 7 8 10 8s7.5 -3 10 -8" />
                                            </svg>
                                        </button> -->
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-left py-4 text-gray-600 dark:text-gray-400">
                                    No hay roles registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-center">
                {{ $roles->links() }}
            </div>
        </div>
    </div>
    @if ($modal)
    <div class="modal-first">
        <div class="modal-center">
            <div class="modal-hiden">
                <div class="center-col">
                    <h3 class="p-text">{{ $accion === 'create' ? 'Registrar Rol' : 'Editar Rol' }}</h3>
                    <div class="over-col">

                        <!-- Nombre del Rol -->
                        <h3 class="p-text">Nombre</h3>
                        <input type="text" wire:model.defer="nombre" class="p-text input-g">
                        @error('nombre') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Descripción del Rol -->
                        <h3 class="p-text">Descripción</h3>
                        <textarea wire:model.defer="descripcion" class="p-text input-g"></textarea>
                        @error('descripcion') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                    </div>

                    <!-- Botones -->
                    <div class="mt-6 flex justify-center w-full space-x-4">
                        <button type="button" wire:click="guardarRol"
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

</div>

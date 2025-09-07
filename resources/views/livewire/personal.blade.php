<div class="p-text p-2 mt-10 flex justify-center">
    <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
        <div>
            <h6 class="text-xl font-bold mb-4 px-4 p-text">Gestión de Personal</h6>

            <!-- Botón de registro y buscador -->
            <div class="flex justify-center items-center gap-4 w-full max-w-2xl mx-auto">
                <button title="Registrar Personal" wire:click="abrirModal"
                    class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icon-tabler-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5v14m-7 -7h14" />
                    </svg>
                </button>

                <input type="text" wire:model.live="search" placeholder="Buscar personal..."
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
                        @forelse ($personales as $personal)
                        <tr class="color-bg border border-slate-200">
                            <td class="px-6 py-4 p-text text-left">
                                <div class="mb-2">
                                    <span class="font-semibold block">Nombre:</span>
                                    <span>{{ $personal->nombres }} {{ $personal->apellidos }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold block">Celular:</span>
                                    <span>{{ $personal->celular }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold block">Email:</span>
                                    <span>{{ $personal->user->email ?? 'Sin usuario' }}</span>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold block">Estado:</span>
                                    <span class="{{ $personal->estado ? 'bg-green-900 text-white' : 'bg-red-900 text-white' }}
                                            px-3 py-1 rounded-full text-sm font-medium cursor-default inline-block">
                                        {{ $personal->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-2">
                                    <button title="Editar" wire:click="editar({{ $personal->id }})"
                                        class="text-blue-500 hover:text-blue-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-edit" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                            <path
                                                d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                            <path d="M16 5l3 3" />
                                        </svg>
                                    </button>
                                    <button title="Ver Detalle" wire:click="verDetalle({{ $personal->id }})"
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
                                No hay personales registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-center">
                {{ $personales->links() }}
            </div>
        </div>
    </div>

    @if ($modal)
    <div class="modal-first">
        <div class="modal-center">
            <div class="modal-hiden">
                <div class="center-col">
                    <h3 class="p-text">{{ $accion === 'create' ? 'Registrar Personal' : 'Editar Personal' }}</h3>
                    <div class="over-col">
                        <!-- Nombres -->
                        <h3 class="p-text">Nombres</h3>
                        <input type="text" wire:model.defer="nombres" class="p-text input-g">
                        @error('nombres') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Apellidos -->
                        <h3 class="p-text">Apellidos</h3>
                        <input type="text" wire:model.defer="apellidos" class="p-text input-g">
                        @error('apellidos') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Dirección -->
                        <h3 class="p-text">Dirección</h3>
                        <input type="text" wire:model.defer="direccion" class="p-text input-g">
                        @error('direccion') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Celular -->
                        <h3 class="p-text">Celular</h3>
                        <input type="text" wire:model.defer="celular" class="p-text input-g">
                        @error('celular') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

           
                        <!-- Email -->
                        <h3 class="p-text">Email</h3>
                        <input type="email" wire:model.defer="email" class="p-text input-g" @if($accion==='edit' ) disabled @endif>
                        @error('email') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Password -->
                        <h3 class="p-text">Contraseña</h3>
                        <input type="password" wire:model.defer="password" class="p-text input-g" @if($accion==='edit' ) disabled @endif>
                        @error('password') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Rol -->
                        <h3 class="p-text">Rol</h3>
                        <select wire:model.defer="rol_id" class="p-text input-g text-sm sm:text-base">
                            <option value="">Seleccione un rol</option>
                            @foreach ($roles as $rol)
                            <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                            @endforeach
                        </select>
                        @error('rol_id') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Estado -->
                        <h3 class="p-text">Estado</h3>
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
                    </div>

                    <!-- Botones -->
                    <div class="mt-6 flex justify-center w-full space-x-4">
                        <button type="button" wire:click="guardarPersonal"
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
                    <h3 class="text-base font-semibold p-text" id="modal-title">Detalles del Personal</h3>
                    <div class="mt-4">
                        <dl class="grid grid-cols-2 gap-4">
                            <!-- Nombres -->
                            <div>
                                <dt class="text-sm font-medium p-text">Nombres</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $personalSeleccionado->nombres ?? 'No especificado' }}
                                </dd>
                            </div>

                            <!-- Apellidos -->
                            <div>
                                <dt class="text-sm font-medium p-text">Apellidos</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $personalSeleccionado->apellidos ?? 'No especificado' }}
                                </dd>
                            </div>

                            <!-- Dirección -->
                            <div>
                                <dt class="text-sm font-medium p-text">Dirección</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $personalSeleccionado->direccion ?? 'No especificada' }}
                                </dd>
                            </div>

                            <!-- Celular -->
                            <div>
                                <dt class="text-sm font-medium p-text">Celular</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $personalSeleccionado->celular ?? 'No especificado' }}
                                </dd>
                            </div>

                            <!-- Email -->
                            <div>
                                <dt class="text-sm font-medium p-text">Email</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $personalSeleccionado->user->email ?? 'No especificado' }}
                                </dd>
                            </div>

                            <!-- Rol -->
                            <div>
                                <dt class="text-sm font-medium p-text">Rol</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $personalSeleccionado->user->rol->nombre ?? 'No especificado' }}
                                </dd>
                            </div>

                            <!-- Estado -->
                            <div>
                                <dt class="text-sm font-medium p-text">Estado</dt>
                                <dd class="mt-1 text-sm p-text">
                                    @if (($personalSeleccionado['estado'] ?? false) == 1)
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
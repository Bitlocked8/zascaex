<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3 class="inline-block bg-teal-700 text-white px-5 py-2 rounded-full text-xl font-bold uppercase shadow-md">
            Personal
        </h3>
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por nombre, apellido o celular..."
                class="input-minimal w-full" />
            <button wire:click="abrirModal('create')" class="btn-cyan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                    <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                </svg>
                añadir
            </button>
        </div>
        @forelse($personales as $personal)
        <div class="card-teal flex flex-col gap-4 p-4 shadow rounded-lg">

            <div class="flex flex-col gap-2">
                <p class="text-u">
                    {{ $personal->nombres }} {{ $personal->apellidos }}
                </p>
                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold uppercase
          {{ $personal->estado ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white' }}">
                    {{ $personal->estado ? 'Activo' : 'Inactivo' }}
                </span>


                <p><strong>Celular:</strong> {{ $personal->celular ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $personal->user->email ?? 'N/A' }}</p>
                <p><strong>Rol:</strong> {{ $personal->user->rol->nombre ?? 'N/A' }}</p>
            </div>
            <div class="flex gap-2 overflow-x-auto no-scrollbar pt-2 justify-start md:justify-end">
                <button wire:click="abrirModal('edit', {{ $personal->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Editar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                        <path d="M16 5l3 3" />
                    </svg>
                    Editar
                </button>

                <button wire:click="verDetalle({{ $personal->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Ver Detalle del Pedido">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 2l.117 .007a1 1 0 0 1 .876 .876l.007 .117v4l.005 .15a2 2 0 0 0 1.838 1.844l.157 .006h4l.117 .007a1 1 0 0 1 .876 .876l.007 .117v9a3 3 0 0 1 -2.824 2.995l-.176 .005h-10a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-14a3 3 0 0 1 2.824 -2.995l.176 -.005zm3 14h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m0 -4h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2" />
                        <path d="M19 7h-4l-.001 -4.001z" />
                    </svg>
                    Detalles
                </button>
            </div>

        </div>
        @empty
        <div class="col-span-full text-center py-4 text-gray-600">
            No hay personal registrado.
        </div>
        @endforelse

    </div>


    @if($modal)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content">
                <div class="flex flex-col gap-4">
                    <div>
                        <label class="text-u">Nombres (Requerido)</label>
                        <input wire:model="nombres" class="input-minimal" placeholder="Ej. Juan" />
                        @error('nombres') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Apellidos (Opcional)</label>
                        <input wire:model="apellidos" class="input-minimal" placeholder="Ej. Pérez" />
                        @error('apellidos') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Dirección (Opcional)</label>
                        <input wire:model="direccion" class="input-minimal" placeholder="Ej. Av. Siempre Viva 123" />
                        @error('direccion') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Celular (Opcional)</label>
                        <input type="text" wire:model="celular" class="input-minimal" placeholder="Ej. 70012345" />
                        @error('celular') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-u">Correo (Requerido)</label>
                        <input type="email" wire:model="email" class="input-minimal" placeholder="Ej. correo@ejemplo.com" />
                        @error('email') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-u">Contraseña (Requerido)</label>
                        <input type="password" wire:model="password" class="input-minimal" placeholder="********" />
                        @error('password') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-1 gap-2 mt-2">
                        <div>
                            <label class="font-semibold text-sm mb-2 block">Rol (Requerido)</label>
                            <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">

                                @forelse($roles as $rol)
                                <button
                                    type="button"
                                    wire:click="$set('rol_id', {{ $rol->id }})"
                                    class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                       {{ $rol_id == $rol->id
                                           ? 'border-cyan-600 text-cyan-600 bg-cyan-50'
                                         : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 hover:bg-cyan-50' }}">

                                    <span class="text-u font-medium">
                                        {{ $rol->nombre }}
                                    </span>

                                    <span class="inline-block bg-cyan-700 text-white px-3 py-1 rounded-full text-sm font-semibold uppercase">
                                        {{ $rol->descripcion }}
                                    </span>

                                </button>
                                @empty
                                <p class="text-gray-500 text-sm text-center py-2 col-span-full">
                                    No hay roles disponibles
                                </p>
                                @endforelse

                            </div>
                        </div>
                    </div>



                    <div class="flex flex-wrap justify-center gap-2 mt-2">
                        @foreach([1 => 'Activo', 0 => 'Inactivo'] as $key => $label)
                        <button type="button" wire:click="$set('estado', {{ $key }})"
                            class="px-4 py-2 rounded-full text-sm flex items-center justify-center
                               {{ $estado == $key ? 'bg-cyan-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="cerrarModal" class="btn-cyan" title="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M10 10l4 4m0 -4l-4 4" />
                        <circle cx="12" cy="12" r="9" />
                    </svg>
                    CERRAR
                </button>
                <button type="button" wire:click="guardarPersonal" class="btn-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M14 4l0 4l-6 0l0 -4" />
                    </svg>
                    Guardar
                    </svg>
                </button>

            </div>
        </div>
    </div>
    @endif

    @if($detalleModal && $personalSeleccionado)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">

                <div class="flex justify-center items-center">
                    <div class="w-20 h-20 rounded-full bg-cyan-600 text-white flex items-center justify-center text-2xl font-bold">
                        {{ strtoupper(substr($personalSeleccionado->nombres,0,1)) }}
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Nombres:</span>
                            <span class="badge-info">{{ $personalSeleccionado->nombres }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Apellidos:</span>
                            <span class="badge-info">{{ $personalSeleccionado->apellidos }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Dirección:</span>
                            <span class="badge-info">{{ $personalSeleccionado->direccion ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Celular:</span>
                            <span class="badge-info">{{ $personalSeleccionado->celular }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Correo:</span>
                            <span class="badge-info">{{ $personalSeleccionado->user->email ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Rol:</span>
                            <span class="badge-info">{{ $personalSeleccionado->user->rol->nombre ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Estado:</span>
                            <span class="badge-info">
                                {{ $personalSeleccionado->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="$set('detalleModal', false)" class="btn-cyan" title="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M10 10l4 4m0 -4l-4 4" />
                        <circle cx="12" cy="12" r="9" />
                    </svg>
                    CERRAR
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
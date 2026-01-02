<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl">
        <h3
            class="text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto shadow-sm mb-4">
            Personal
        </h3>

        <div class="flex items-center gap-2 mb-4">
            <input type="text" wire:model.live="search" placeholder="Buscar por nombre, apellido o celular..."
                class="input-minimal w-full" />
            <button wire:click="abrirModal('create')" class="btn-cyan flex items-center gap-1">
                Añadir
            </button>
        </div>

        <div class="overflow-auto max-h-[500px] border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-teal-50 sticky top-0">
                    <tr>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Nombre</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Rol</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Estado</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Celular</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Email</th>
                        <th class="px-4 py-2 text-center text-teal-700 font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($personales as $personal)
                        <tr class="hover:bg-teal-50">
                            <td class="px-4 py-2">{{ $personal->nombres }} {{ $personal->apellidos }}</td>
                            <td class="px-4 py-2">{{ $personal->user->rol->nombre ?? 'Sin rol' }}</td>
                            <td class="px-4 py-2">
                                <span class="{{ $personal->estado == 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $personal->estado == 0 ? 'Inactivo' : 'Activo' }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $personal->celular ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $personal->user->email ?? 'N/A' }}</td>
                            <td class="px-4 py-2 flex justify-center gap-1">
                                <button wire:click="abrirModal('edit', {{ $personal->id }})" class="btn-cyan" title="Editar">
                                    Editar
                                </button>
                                <button wire:click="verDetalle({{ $personal->id }})" class="btn-cyan" title="Ver detalle">
                                    Ver
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-600">No hay personal registrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

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
                            <input type="email" wire:model="email" class="input-minimal"
                                placeholder="Ej. correo@ejemplo.com" />
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
                                <div
                                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">

                                    @forelse($roles as $rol)
                                        <button type="button" wire:click="$set('rol_id', {{ $rol->id }})"
                                            class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center {{ $rol_id == $rol->id ? 'border-cyan-600 text-cyan-600 bg-cyan-50' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 hover:bg-cyan-50' }}">

                                            <span class="text-u font-medium">
                                                {{ $rol->nombre }}
                                            </span>

                                            <span
                                                class="inline-block bg-cyan-700 text-white px-3 py-1 rounded-full text-sm font-semibold uppercase">
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
                                    class="px-4 py-2 rounded-full text-sm flex items-center justify-center {{ $estado == $key ? 'bg-cyan-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="cerrarModal" class="btn-cyan" title="Cerrar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                            <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
                        </svg>
                        CERRAR
                    </button>
                    <button type="button" wire:click="guardarPersonal" class="btn-cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                        <div
                            class="w-20 h-20 rounded-full bg-cyan-600 text-white flex items-center justify-center text-2xl font-bold">
                            {{ strtoupper(substr($personalSeleccionado->nombres, 0, 1)) }}
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                            <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
                        </svg>
                        CERRAR
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
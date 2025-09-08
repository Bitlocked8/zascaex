<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Buscar + Crear Cliente -->
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <!-- Input de búsqueda -->
            <input type="text" wire:model.live="search"
                placeholder="Buscar por cliente..."
                class="flex-1 border rounded px-3 py-2" />

            <!-- Botón Crear Cliente -->
            <button wire:click="abrirModal('create')"
                class="bg-cyan-500 hover:bg-cyan-600 rounded-xl px-4 py-2 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9" />
                    <line x1="12" y1="9" x2="12" y2="15" />
                    <line x1="9" y1="12" x2="15" y2="12" />
                </svg>
            </button>
        </div>

        @forelse ($clientes as $cliente)
        <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
            <!-- Columna Izquierda: Foto + Info -->
            <div class="flex flex-col items-center md:items-start text-center md:text-left col-span-8">
                @if ($cliente->foto)
                <img src="{{ asset('storage/' . $cliente->foto) }}"
                    alt="Foto de {{ $cliente->nombre }}"
                    class="w-56 h-56 object-cover rounded-lg shadow-md mb-3 ">
                @else
                <div class="w-56 h-56 bg-gray-200 flex items-center justify-center rounded-lg shadow mb-3">
                    <span class="text-gray-500">Sin foto</span>
                </div>
                @endif

                <h3 class="text-lg font-semibold uppercase text-cyan-600">
                    {{ $cliente->nombre }}
                </h3>
                <p class="text-cyan-950"><strong>Empresa:</strong> {{ $cliente->empresa ?? 'N/A' }}</p>
                <div class="mt-2">
                    @if ($cliente->verificado)
                    <span class="px-3 py-1 text-sm font-semibold text-white bg-cyan-600 rounded-full shadow">
                        Verificado
                    </span>
                    @else
                    <span class="px-3 py-1 text-sm font-semibold text-cyan-950 bg-cyan-200 rounded-full shadow">
                        Sin Verificar
                    </span>
                    @endif
                </div>

            </div>

            <!-- Columna Derecha: Botones -->
            <div class="flex flex-col items-center md:items-end gap-4 col-span-4">
                <button wire:click="editarCliente({{ $cliente->id }})"
                    class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 10a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M6 4v4" />
                        <path d="M6 12v8" />
                        <path d="M13.199 14.399a2 2 0 1 0 -1.199 3.601" />
                        <path d="M12 4v10" />
                        <path d="M12 18v2" />
                        <path d="M16 7a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M18 4v1" />
                        <path d="M18 9v2.5" />
                        <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M19.001 15.5v1.5" />
                        <path d="M19.001 21v1.5" />
                        <path d="M22.032 17.25l-1.299 .75" />
                        <path d="M17.27 20l-1.3 .75" />
                        <path d="M15.97 17.25l1.3 .75" />
                        <path d="M20.733 20l1.3 .75" />
                    </svg>
                </button>

                <!-- Ver Detalle -->
                <button wire:click="verDetalle({{ $cliente->id }})"
                    class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-cyan-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9h.01" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 12h1v4h1" />
                    </svg>
                </button>

                <!-- Toggle Verificado -->
                <button wire:click="toggleVerificado({{ $cliente->id }})"
                    class="{{ $cliente->verificado ? 'bg-white' : 'bg-cyan-600 ' }} rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                    @if ($cliente->verificado)
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="text-cyan-600">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12.01 2.011a3.2 3.2 0 0 1 2.113 .797l.154 .145l.698 .698a1.2 1.2 0 0 0 .71 .341l.135 .008h1a3.2 3.2 0 0 1 3.195 3.018l.005 .182v1c0 .27 .092 .533 .258 .743l.09 .1l.697 .698a3.2 3.2 0 0 1 .147 4.382l-.145 .154l-.698 .698a1.2 1.2 0 0 0 -.341 .71l-.008 .135v1a3.2 3.2 0 0 1 -3.018 3.195l-.182 .005h-1a1.2 1.2 0 0 0 -.743 .258l-.1 .09l-.698 .697a3.2 3.2 0 0 1 -4.382 .147l-.154 -.145l-.698 -.698a1.2 1.2 0 0 0 -.71 -.341l-.135 -.008h-1a3.2 3.2 0 0 1 -3.195 -3.018l-.005 -.182v-1a1.2 1.2 0 0 0 -.258 -.743l-.09 -.1l-.697 -.698a3.2 3.2 0 0 1 -.147 -4.382l.145 -.154l.698 -.698a1.2 1.2 0 0 0 .341 -.71l.008 -.135v-1l.005 -.182a3.2 3.2 0 0 1 3.013 -3.013l.182 -.005h1a1.2 1.2 0 0 0 .743 -.258l.1 -.09l.698 -.697a3.2 3.2 0 0 1 2.269 -.944zm3.697 7.282a1 1 0 0 0 -1.414 0l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.32 1.497l2 2l.094 .083a1 1 0 0 0 1.32 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                    </svg>
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 7.2a2.2 2.2 0 0 1 2.2 -2.2h1a2.2 2.2 0 0 0 1.55 -.64l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7c.412 .41 .97 .64 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1c0 .58 .23 1.138 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.64 1.55v1a2.2 2.2 0 0 1 -2.2 2.2h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1" />
                        <path d="M9 12l2 2l4 -4" />
                    </svg>
                    @endif
                </button>
            </div>
        </div>


        @empty
        <div class="col-span-full text-center py-4 text-gray-600">
            No hay clientes registrados.
        </div>
        @endforelse



    </div>



    <!-- Modal de registro y edición -->
    @if ($modal)
    <div class="modal-first">
        <div class="modal-center">
            <div class="modal-hiden">
                <div class="center-col">
                    <h3 class="p-text">{{ $accion === 'create' ? 'Registrar Cliente' : 'Editar Cliente' }}</h3>
                    <div class="over-col">

                        <!-- Nombre -->
                        <h3 class="p-text">Nombre</h3>
                        <input type="text" wire:model="nombre" class="p-text input-g">
                        @error('nombre') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Empresa -->
                        <h3 class="p-text">Empresa</h3>
                        <input type="text" wire:model="empresa" class="p-text input-g">
                        @error('empresa') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Razón Social -->
                        <h3 class="p-text">Razón Social</h3>
                        <input type="text" wire:model="razonSocial" class="p-text input-g">
                        @error('razonSocial') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- NIT/CI -->
                        <h3 class="p-text">NIT/CI</h3>
                        <input type="text" wire:model="nitCi" class="p-text input-g">
                        @error('nitCi') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Teléfono -->
                        <h3 class="p-text">Teléfono</h3>
                        <input type="text" wire:model="telefono" class="p-text input-g">
                        @error('telefono') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Correo -->
                        <h3 class="p-text">Correo</h3>
                        <input type="email" wire:model="correo" class="p-text input-g">
                        @error('correo') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <h3 class="p-text">Correo de acceso</h3>
                        <input type="email" wire:model="email" class="p-text input-g" readonly>
                        @error('email') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <h3 class="p-text">Contraseña</h3>
                        <input type="password" wire:model="password" placeholder="Nueva contraseña (opcional)" class="p-text input-g">
                        @error('password') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Latitud -->
                        <h3 class="p-text">Coordenadas (Latitud, Longitud)</h3>
                        <input type="text" wire:model="coordenadas" wire:change="separarCoordenadas"
                            class="p-text input-g" placeholder="-17.78, -63.17">
                        @error('coordenadas') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Foto -->
                        <h3 class="p-text">Foto</h3>
                        <input type="file" wire:model="foto" class="p-text input-g">
                        @error('foto') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                        <!-- Vista previa de la imagen -->
                        @if ($foto)
                        <div class="mt-2">
                            @if (is_object($foto))
                            <!-- Si es archivo subido -->
                            <img src="{{ $foto->temporaryUrl() }}" alt="Vista previa" class="w-24 h-24 object-cover rounded">
                            @else
                            <!-- Si es ruta guardada en BD -->
                            <img src="{{ asset('storage/' . $foto) }}" alt="Foto cliente" class="w-24 h-24 object-cover rounded">
                            @endif
                        </div>
                        @endif


                        <!-- Estado -->
                        <h3 class="p-text">Estado</h3>
                        <select wire:model="estado" class="p-text input-g">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                        @error('estado') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

                    </div>

                    <!-- Botones -->
                    <div class="mt-6 flex justify-center w-full space-x-4">
                        <button type="button" wire:click="guardarCliente"
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



    <!-- Modal de detalle -->
    @if ($detalleModal)
    <div class="modal-first">
        <div class="modal-center">
            <div class="modal-hiden">
                <div class="center-col">
                    <h3 class="p-text">Detalles del Cliente</h3>
                    <div class="over-col">
                        <dl class="grid grid-cols-2 gap-4">
                            <!-- Nombre -->
                            <div>
                                <dt class="text-sm font-semibold p-text">Nombre</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $clienteSeleccionado->nombre ?? 'No disponible' }}
                                </dd>
                            </div>

                            <!-- Empresa -->
                            <div>
                                <dt class="text-sm font-semibold p-text">Empresa</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $clienteSeleccionado->empresa ?? 'N/A' }}
                                </dd>
                            </div>

                            <!-- NIT/CI -->
                            <div>
                                <dt class="text-sm font-semibold p-text">NIT/CI</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $clienteSeleccionado->nitCi ?? 'N/A' }}
                                </dd>
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <dt class="text-sm font-semibold p-text">Teléfono</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $clienteSeleccionado->telefono ?? 'N/A' }}
                                </dd>
                            </div>

                            <!-- Correo -->
                            <div>
                                <dt class="text-sm font-semibold p-text">Correo</dt>
                                <dd class="mt-1 text-sm p-text">
                                    {{ $clienteSeleccionado->correo ?? 'N/A' }}
                                </dd>
                            </div>

                            <!-- Estado -->
                            <div>
                                <dt class="text-sm font-semibold p-text">Estado</dt>
                                <dd class="mt-1 text-sm p-text">
                                    <span class="text-{{ $clienteSeleccionado->estado ? 'green' : 'red' }}-500">
                                        {{ $clienteSeleccionado->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Botón de Cerrar Modal -->
                    <div class="mt-6 flex justify-center w-full">
                        <button type="button" wire:click="cerrarModal"
                            class="text-red-500 hover:text-red-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M18 6l-12 12" />
                                <path d="M6 6l12 12" />
                            </svg></button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endif


</div>
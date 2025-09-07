<div class="p-text p-2 mt-10 flex justify-center">
    <div class="w-full max-w-screen-xl grid grid-cols-1 gap-6">
        <div>
            <h6 class="text-xl font-bold mb-4 px-4 text-center p-text">Gestión de Clientes</h6>

            <!-- Botón de registro y buscador -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full">
                <a title="Registrar Cliente" href="{{ route('cliente.registrar') }}"
                    class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                        <path d="M15 19l2 2l4 -4" />
                    </svg>
                </a>

                <input type="text" wire:model.live="search" placeholder="Buscar por cliente..." class="input-g" />
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
                        @forelse ($clientes as $cliente)
                        <tr class="color-bg border border-slate-200">
                            <td class="px-6 py-4 p-text text-left">
                                <div class="mb-2">
                                    <!-- Imagen del cliente -->
                                    @if ($cliente->foto)
                                    <img src="{{ asset('storage/' . $cliente->foto) }}" alt="Foto de {{ $cliente->nombre }}"
                                        class="w-16 h-16 object-cover rounded mb-2">
                                    @else
                                    <span class="text-gray-500">Sin foto</span>
                                    @endif
                                </div>
                                <div class="mb-2">
                                    <span><b>Cliente: </b>{{ $cliente->nombre }}</span>
                                </div>
                                <div>
                                    <span><b>Empresa: </b>{{ $cliente->empresa ?? 'N/A' }}</span>
                                    <span></span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-2">
                                    <button title="Editar Cliente" wire:click="editarCliente({{ $cliente->id }})"
                                        class="text-blue-500 hover:text-blue-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="icon icon-tabler icon-tabler-edit">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                            <path
                                                d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                            <path d="M16 5l3 3" />
                                        </svg>
                                    </button>

                                    <button title="Ver Detalles" wire:click="verDetalle({{ $cliente->id }})"
                                        class="text-yellow-500 hover:text-yellow-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="icon icon-tabler icon-tabler-info-circle">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                            <path d="M12 9h.01" />
                                            <path d="M11 12h1v4h1" />
                                        </svg>
                                    </button>
                                    <a href="{{ route('clientes.map', $cliente->id) }}"
                                        title="Ver Mapa"
                                        class="text-green-500 hover:text-green-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="icon icon-tabler icon-tabler-map-pin">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 11m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                            <path d="M17.657 16.657l-4.414 4.414a2 2 0 0 1 -2.827 0l-4.414 -4.414a8 8 0 1 1 11.314 0z" />
                                        </svg>
                                    </a>

                                    <button title="{{ $cliente->verificado ? 'Cancelar Verificación' : 'Confirmar Cliente' }}"
                                        wire:click="toggleVerificado({{ $cliente->id }})"
                                        class="text-purple-500 hover:text-purple-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 rounded-full">
                                        @if ($cliente->verificado)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="icon icon-tabler icon-tabler-x">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M18 6l-12 12" />
                                            <path d="M6 6l12 12" />
                                        </svg>
                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="icon icon-tabler icon-tabler-check">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M5 12l5 5l10 -10" />
                                        </svg>
                                        @endif
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center py-4 text-gray-600 dark:text-gray-400">
                                No hay clientes registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-center">
                {{ $clientes->links() }}
            </div>
        </div>
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
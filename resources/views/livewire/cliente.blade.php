<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Buscar + Crear Cliente -->
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <!-- Input de búsqueda -->
            <input type="text" wire:model.live="search"
                placeholder="Buscar por cliente..."
                class="flex-1 border rounded px-3 py-2" />

            <a href="{{ route('cliente.registrar') }}"

                class="bg-cyan-500 hover:bg-cyan-600 rounded-xl px-4 py-2 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9" />
                    <line x1="12" y1="9" x2="12" y2="15" />
                    <line x1="9" y1="12" x2="15" y2="12" />
                </svg>
            </a>

        </div>

        @forelse ($clientes as $cliente)
        <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
            <!-- Columna Izquierda: Foto + Info -->
            <div class="flex flex-col items-center md:items-start text-center md:text-left col-span-8">
                @if ($cliente->foto)
                <img src="{{ asset('storage/' . $cliente->foto) }}"
                    alt="Foto de {{ $cliente->nombre }}"
                    class="w-56 h-56 object-cover rounded-lg shadow-md mb-3"
                    onerror="this.onerror=null; this.replaceWith(document.getElementById('sinFotoTemplate').content.cloneNode(true));">
                @else
                <div class="w-56 h-56 bg-gray-200 flex items-center justify-center rounded-lg shadow mb-3">
                    <span class="text-gray-500">Sin foto</span>
                </div>
                @endif

                <!-- Template oculto para cuando la imagen no carga -->
                <template id="sinFotoTemplate">
                    <div class="w-56 h-56 bg-gray-200 flex items-center justify-center rounded-lg shadow mb-3">
                        <span class="text-gray-500">Sin foto</span>
                    </div>
                </template>


                <h3 class="text-lg font-semibold uppercase text-cyan-600">
                    {{ $cliente->nombre }}
                </h3>
                <p class="text-cyan-950"><strong>Empresa:</strong> {{ $cliente->empresa ?? 'N/A' }}</p>

                <!-- Categoría -->
                <div class="mt-2">
                    @if ($cliente->categoria == 1)
                    <span class="px-3 py-1 text-sm font-semibold text-white bg-cyan-600 rounded-full shadow">
                        Cliente Nuevo
                    </span>
                    @elseif ($cliente->categoria == 2)
                    <span class="px-3 py-1 text-sm font-semibold text-white bg-indigo-600 rounded-full shadow">
                        Cliente Regular
                    </span>
                    @elseif ($cliente->categoria == 3)
                    <span class="px-3 py-1 text-sm font-semibold text-white bg-purple-600 rounded-full shadow">
                        Cliente VIP
                    </span>
                    @else
                    <span class="px-3 py-1 text-sm font-semibold text-gray-700 bg-gray-200 rounded-full shadow">
                        N/A
                    </span>
                    @endif
                </div>

                <!-- Estado -->
                <div class="mt-2">
                    @if ($cliente->estado == 1)
                    <span class="px-3 py-1 text-sm font-semibold text-white bg-green-600 rounded-full shadow">
                        Activo
                    </span>
                    @else
                    <span class="px-3 py-1 text-sm font-semibold text-white bg-red-600 rounded-full shadow">
                        Inactivo
                    </span>
                    @endif
                </div>

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

                <a href="{{ route('clientes.map', $cliente->id) }}"
                    class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 7l6 -3l6 3l6 -3v13l-6 3l-6 -3l-6 3v-13" />
                        <path d="M9 4v13" />
                        <path d="M15 7v13" />
                    </svg>
                </a>

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
    @if ($modal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white text-cyan-950 rounded-lg shadow-lg w-full max-w-4xl p-6 relative overflow-y-auto max-h-[90vh]">

            <h2 class="text-xl font-semibold mb-6 text-center">
                {{ $accion === 'create' ? 'Registrar Cliente' : 'Editar Cliente' }}
            </h2>

            <div class="grid grid-cols-12 gap-4">

                <!-- Columna 1: 5/12 -->
                <div class="col-span-12 md:col-span-5 flex flex-col gap-4">
                    @if ($foto)
                    <div class="mt-2">
                        @if (is_object($foto))
                        <img src="{{ $foto->temporaryUrl() }}" alt="Vista previa" class="w-24 h-24 object-cover rounded">
                        @else
                        <img src="{{ asset('storage/' . $foto) }}" alt="Foto cliente" class="w-24 h-24 object-cover rounded">
                        @endif
                    </div>
                    @endif

                    <input type="file" wire:model="foto" class="input-minimal">
                    @error('foto') <span class="error-message">{{ $message }}</span> @enderror

                    <select wire:model="categoria" class="input-minimal">
                        <option value="1">Cliente Nuevo</option>
                        <option value="2">Cliente Regular</option>
                        <option value="3">Cliente VIP</option>
                    </select>
                    @error('categoria') <span class="error-message">{{ $message }}</span> @enderror

                    <select wire:model="estado" class="input-minimal">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                    @error('estado') <span class="error-message">{{ $message }}</span> @enderror

                    <input type="text" wire:model="nombre" placeholder="Nombre" class="input-minimal">
                    @error('nombre') <span class="error-message">{{ $message }}</span> @enderror

                    <input type="text" wire:model="empresa" placeholder="Empresa" class="input-minimal">
                    @error('empresa') <span class="error-message">{{ $message }}</span> @enderror

                    <input type="text" wire:model="razonSocial" placeholder="Razón Social" class="input-minimal">
                    @error('razonSocial') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Columna 2: 4/12 -->
                <div class="col-span-12 md:col-span-4 flex flex-col gap-4">
                    <input type="text" wire:model="nitCi" placeholder="NIT/CI" class="input-minimal">
                    @error('nitCi') <span class="error-message">{{ $message }}</span> @enderror

                    <input type="text" wire:model="telefono" placeholder="Teléfono" class="input-minimal">
                    @error('telefono') <span class="error-message">{{ $message }}</span> @enderror

                    <input type="email" wire:model="correo" placeholder="Correo Empresa" class="input-minimal">
                    @error('correo') <span class="error-message">{{ $message }}</span> @enderror

                    <input type="email" wire:model="email" placeholder="Correo de acceso" class="input-minimal" readonly>
                    @error('email') <span class="error-message">{{ $message }}</span> @enderror

                    <input type="password" wire:model="password" placeholder="Nueva contraseña (opcional)" class="input-minimal">
                    @error('password') <span class="error-message">{{ $message }}</span> @enderror

                    <input type="text" wire:model="coordenadas" wire:change="separarCoordenadas" placeholder="Coordenadas (Latitud, Longitud)" class="input-minimal">
                    @error('coordenadas') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Columna 3: 3/12 -->
                <div class="col-span-12 md:col-span-3 flex flex-col justify-center items-center h-full">
                    <div class="flex flex-col items-center md:items-end gap-4 col-span-4">
                        <button type="button" wire:click="guardarCliente" class="bg-cyan-500 hover:bg-cyan-600 rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                            </svg>
                        </button>
                        <button type="button" wire:click="cerrarModal" class="bg-gray-300 hover:bg-gray-400 rounded-xl w-12 h-12 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
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
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white text-cyan-950 rounded-lg shadow-lg w-full max-w-3xl p-6 relative overflow-y-auto max-h-[90vh]">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nombre -->
                <div>
                    <dt class="text-sm font-semibold">Nombre</dt>
                    <dd class="mt-1 text-sm">{{ $clienteSeleccionado->nombre ?? 'No disponible' }}</dd>
                </div>

                <!-- Empresa -->
                <div>
                    <dt class="text-sm font-semibold">Empresa</dt>
                    <dd class="mt-1 text-sm">{{ $clienteSeleccionado->empresa ?? 'N/A' }}</dd>
                </div>

                <!-- NIT/CI -->
                <div>
                    <dt class="text-sm font-semibold">NIT/CI</dt>
                    <dd class="mt-1 text-sm">{{ $clienteSeleccionado->nitCi ?? 'N/A' }}</dd>
                </div>

                <!-- Teléfono -->
                <div>
                    <dt class="text-sm font-semibold">Teléfono</dt>
                    <dd class="mt-1 text-sm">{{ $clienteSeleccionado->telefono ?? 'N/A' }}</dd>
                </div>

                <!-- Correo -->
                <div>
                    <dt class="text-sm font-semibold">Correo</dt>
                    <dd class="mt-1 text-sm">{{ $clienteSeleccionado->correo ?? 'N/A' }}</dd>
                </div>

                <!-- Estado -->
                <div>
                    <dt class="text-sm font-semibold">Estado</dt>
                    <dd class="mt-1">
                        @if ($clienteSeleccionado->estado)
                        <span class="px-3 py-1 text-sm font-semibold text-white bg-cyan-600 rounded-full shadow">
                            Activo
                        </span>
                        @else
                        <span class="px-3 py-1 text-sm font-semibold text-cyan-950 bg-cyan-200 rounded-full shadow">
                            Inactivo
                        </span>
                        @endif
                    </dd>
                </div>

                <!-- Categoría -->
                <div>
                    <dt class="text-sm font-semibold">Categoría</dt>
                    <dd class="mt-1">
                        @if ($clienteSeleccionado->categoria == 1)
                        <span class="px-3 py-1 text-sm font-semibold text-white bg-cyan-600 rounded-full shadow">
                            Cliente Nuevo
                        </span>
                        @elseif ($clienteSeleccionado->categoria == 2)
                        <span class="px-3 py-1 text-sm font-semibold text-white bg-indigo-600 rounded-full shadow">
                            Cliente Regular
                        </span>
                        @elseif ($clienteSeleccionado->categoria == 3)
                        <span class="px-3 py-1 text-sm font-semibold text-white bg-purple-600 rounded-full shadow">
                            Cliente VIP
                        </span>
                        @else
                        <span class="px-3 py-1 text-sm font-semibold text-gray-700 bg-gray-200 rounded-full shadow">
                            N/A
                        </span>
                        @endif
                    </dd>
                </div>
            </div>

            <!-- Botón de Cerrar Modal -->
            <div class="mt-6 flex justify-center w-full">
                <button type="button" wire:click="cerrarModal"
                    class="text-red-500 hover:text-red-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M18 6l-12 12" />
                        <path d="M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif



</div>
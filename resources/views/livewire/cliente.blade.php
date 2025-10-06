<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="search"
                placeholder="Buscar por cliente..." class="input-minimal w-full" />
            <a href="{{ route('cliente.registrar') }}" class="btn-circle btn-cyan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                    <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                </svg>
            </a>
        </div>
        @forelse($clientes as $cliente)
        <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
            <div class="flex flex-col col-span-9 text-left space-y-1">
                <p><strong>Código:</strong> <span class="font-mono text-cyan-600">{{ $cliente->codigo ?? 'N/A' }}</span></p>
                <p><strong>Nombre:</strong> {{ $cliente->nombre }}</p>
                <p><strong>Empresa:</strong> {{ $cliente->empresa ?? 'N/A' }}</p>
                <p><strong>Categoría:</strong>
                    @if($cliente->categoria == 1)
                    <span class="text-white bg-cyan-600 px-2 py-1 rounded-full">Cliente Nuevo</span>
                    @elseif($cliente->categoria == 2)
                    <span class="text-white bg-indigo-600 px-2 py-1 rounded-full">Cliente Regular</span>
                    @elseif($cliente->categoria == 3)
                    <span class="text-white bg-purple-600 px-2 py-1 rounded-full">Cliente VIP</span>
                    @else
                    <span class="text-gray-700 bg-gray-200 px-2 py-1 rounded-full">N/A</span>
                    @endif
                </p>
                <p><strong>Estado:</strong>
                    @if($cliente->estado == 1)
                    <span class="text-white bg-green-600 px-2 py-1 rounded-full">Activo</span>
                    @else
                    <span class="text-white bg-red-600 px-2 py-1 rounded-full">Inactivo</span>
                    @endif
                </p>
                <p><strong>Verificado:</strong>
                    @if($cliente->verificado)
                    <span class="text-white bg-cyan-600 px-2 py-1 rounded-full">Sí</span>
                    @else
                    <span class="text-cyan-950 bg-cyan-200 px-2 py-1 rounded-full">No</span>
                    @endif
                </p>
            </div>
            <div class="flex flex-col items-end gap-4 col-span-3">
                <button wire:click="editarCliente({{ $cliente->id }})" class="btn-circle btn-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                <button wire:click="verDetalle({{ $cliente->id }})" class="btn-circle btn-cyan" title="Ver Detalle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9h.01" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 12h1v4h1" />
                    </svg>
                </button>
                <a href="{{ route('clientes.map', $cliente->id) }}" class="btn-circle btn-cyan" title="Ver Mapa">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5" />
                        <path d="M9 4v13" />
                        <path d="M15 7v5.5" />
                        <path d="M21.121 20.121a3 3 0 1 0 -4.242 0c.418 .419 1.125 1.045 2.121 1.879c1.051 -.89 1.759 -1.516 2.121 -1.879z" />
                        <path d="M19 18v.01" />
                    </svg>
                </a>

                <a href="{{ route('clientes.editar', $cliente->id) }}"
                    class="btn-circle btn-cyan"
                    title="Editar Coordenadas">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v8" />
                        <path d="M9 4v13" />
                        <path d="M15 7v6.5" />
                        <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M19.001 15.5v1.5" />
                        <path d="M19.001 21v1.5" />
                        <path d="M22.032 17.25l-1.299 .75" />
                        <path d="M17.27 20l-1.3 .75" />
                        <path d="M15.97 17.25l1.3 .75" />
                        <path d="M20.733 20l1.3 .75" />
                    </svg>
                </a>
                <button wire:click="toggleVerificado({{ $cliente->id }})"
                    class="{{ $cliente->verificado ? 'bg-cyan-600 text-white' : ' bg-white text-cyan-600' }} btn-circle" title="Toggle Verificado">
                    @if($cliente->verificado)
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-checks">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 12l5 5l10 -10" />
                        <path d="M2 12l5 5m5 -5l5 -5" />
                    </svg>
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-checks">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 12l5 5l10 -10" />
                        <path d="M2 12l5 5m5 -5l5 -5" />
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



    @if($modal)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">
                <div>
                    <label class="font-semibold text-sm mb-2 block">Imagen</label>
                    <input type="file" wire:model="foto" class="input-minimal">
                    @if($foto)
                    <div class="mt-2 flex justify-center">
                        <img src="{{ is_string($foto) ? asset('storage/'.$foto) : $foto->temporaryUrl() }}"
                            class="w-50 h-50 object-cover rounded" alt="Imagen Cliente">
                    </div>
                    @endif
                    @error('foto') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="font-semibold text-sm mb-1 block">Nombre</label>
                    <input type="text" wire:model="nombre" class="input-minimal" placeholder="Nombre">
                    @error('nombre') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="font-semibold text-sm mb-1 block">Empresa</label>
                    <input type="text" wire:model="empresa" class="input-minimal" placeholder="Empresa">
                    @error('empresa') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="font-semibold text-sm mb-1 block">Razón Social</label>
                    <input type="text" wire:model="razonSocial" class="input-minimal" placeholder="Razón Social">
                    @error('razonSocial') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="font-semibold text-sm mb-1 block">NIT/CI</label>
                    <input type="text" wire:model="nitCi" class="input-minimal" placeholder="NIT/CI">
                    @error('nitCi') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="font-semibold text-sm mb-1 block">Teléfono</label>
                    <input type="text" wire:model="telefono" class="input-minimal" placeholder="Teléfono">
                    @error('telefono') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="font-semibold text-sm mb-1 block">Correo Empresa</label>
                    <input type="email" wire:model="correo" class="input-minimal" placeholder="Correo Empresa">
                    @error('correo') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="font-semibold text-sm mb-1 block">Correo de acceso</label>
                    <input type="email" wire:model="email" class="input-minimal" placeholder="Correo de acceso">

                </div>

                <div>
                    <label class="font-semibold text-sm mb-1 block">Nueva contraseña (opcional)</label>
                    <input type="password" wire:model="password" class="input-minimal" placeholder="Contraseña">
                    @error('password') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="font-semibold text-sm mb-1 block">Latitud</label>
                    <p class="px-3 py-2 bg-gray-100 rounded text-gray-700">
                        {{ $latitud ?? 'No disponible' }}
                    </p>
                </div>

                <div class="mb-4">
                    <label class="font-semibold text-sm mb-1 block">Longitud</label>
                    <p class="px-3 py-2 bg-gray-100 rounded text-gray-700">
                        {{ $longitud ?? 'No disponible' }}
                    </p>
                </div>
                <div class="flex flex-wrap justify-center gap-2 mt-2">
                    @foreach([1 => 'Cliente Nuevo', 2 => 'Cliente Regular', 3 => 'Cliente Antiguo'] as $key => $label)
                    <button type="button" wire:click="$set('categoria', {{ $key }})"
                        class="px-4 py-2 rounded-full text-sm flex items-center justify-center
          {{ $categoria == $key ? 'bg-cyan-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
                        {{ $label }}
                    </button>
                    @endforeach
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

            <!-- Footer: Guardar / Cerrar -->
            <div class="modal-footer flex justify-center gap-4 mt-4">
                <button type="button" wire:click="guardarCliente" class="btn-circle btn-cyan" title="Guardar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M14 4l0 4l-6 0l0 -4" />
                    </svg>
                </button>
                <button type="button" wire:click="cerrarModal" class="btn-circle btn-cyan" title="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <path d="M10 10l4 4m0 -4l-4 4" />
                        <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                    </svg>
                </button>
            </div>
            @if($showAlert)
            <div class="px-4 py-2 mb-2 rounded text-white
        {{ $alertType == 'success' ? 'bg-green-500' : ($alertType == 'error' ? 'bg-red-500' : 'bg-yellow-500') }}">
                {{ $alertMessage }}
            </div>
            @endif

            <script>
                window.addEventListener('hide-alert', () => {
                    setTimeout(() => {
                        Livewire.emit('ocultarAlerta');
                    }, 3000);
                });
            </script>

        </div>
    </div>
    @endif


    @if($detalleModal)
    <div class="modal-overlay">
        <div class="modal-box">

            <div class="modal-content flex flex-col gap-6">

                <!-- Foto del Cliente -->
                <div class="flex justify-center items-center">
                    @if($clienteSeleccionado->foto)
                    <img src="{{ asset('storage/'.$clienteSeleccionado->foto) }}"
                        class="w-50 h-50 object-cover rounded"
                        alt="Foto de {{ $clienteSeleccionado->nombre }}">
                    @else
                    <span class="badge-info">Sin foto</span>
                    @endif
                </div>

                <!-- Información del Cliente -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Nombre:</span>
                            <span class="badge-info">{{ $clienteSeleccionado->nombre ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Código:</span>
                            <span class="badge-info">{{ $clienteSeleccionado->codigo ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Empresa:</span>
                            <span class="badge-info">{{ $clienteSeleccionado->empresa ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">NIT/CI:</span>
                            <span class="badge-info">{{ $clienteSeleccionado->nitCi ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Teléfono:</span>
                            <span class="badge-info">{{ $clienteSeleccionado->telefono ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Correo:</span>
                            <span class="badge-info">{{ $clienteSeleccionado->correo ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Estado:</span>
                            <span class="badge-info">{{ $clienteSeleccionado->estado ? 'Activo' : 'Inactivo' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Categoría:</span>
                            <span class="badge-info">
                                @if ($clienteSeleccionado->categoria == 1)
                                Cliente Nuevo
                                @elseif ($clienteSeleccionado->categoria == 2)
                                Cliente Regular
                                @elseif ($clienteSeleccionado->categoria == 3)
                                Cliente VIP
                                @else
                                N/A
                                @endif
                            </span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Verificado:</span>
                            <span class="badge-info">
                                {{ $clienteSeleccionado->verificado ? 'Sí' : 'No' }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="$set('detalleModal', false)" class="btn-circle btn-cyan" title="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <path d="M10 10l4 4m0 -4l-4 4" />
                        <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                    </svg>
                </button>
            </div>

        </div>
    </div>
    @endif




</div>
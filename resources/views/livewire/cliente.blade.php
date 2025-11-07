<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <h3
            class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto shadow-sm">
            Clientes
        </h3>
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="search" placeholder="Buscar por nombre o empresa..."
                class="input-minimal w-full" />

            <a href="{{ route('cliente.registrar') }}" class="btn-cyan flex items-center gap-1" title="Agregar cliente">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                    <path
                        d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                </svg>
                Añadir
            </a>
        </div>
        @forelse($clientes as $cliente)
            <div class="card-teal flex flex-col gap-4 p-4">
                <div class="flex flex-col gap-2">
                    <p class="text-emerald-600 uppercase font-semibold">
                        {{ $cliente->nombre }}
                    </p>
                    <p class="text-slate-600"> {{ $cliente->codigo ?? 'N/A' }}</p>
                    <p><strong>Empresa:</strong> {{ $cliente->empresa ?? 'Sin empresa' }}</p>
                    <p><strong>Dirección:</strong> {{ $cliente->direccion ?? 'Sin direccion' }}</p>
                    <p class="mt-1 text-sm font-semibold">

                        <span
                            class="{{ $cliente->categoria == 1 ? 'text-cyan-600' : ($cliente->categoria == 2 ? 'text-indigo-600' : ($cliente->categoria == 3 ? 'text-purple-600' : 'text-gray-500')) }}">
                            {{ $cliente->categoria == 1 ? 'Nuevo Cliente' : ($cliente->categoria == 2 ? 'Cliente Regular' : ($cliente->categoria == 3 ? 'Cliente Antiguo' : 'N/A')) }}
                        </span>
                    </p>
                    <p class="mt-1 text-sm font-semibold">
                        <span class="{{ $cliente->estado == 1 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $cliente->estado == 1 ? 'Cliente Activo' : 'Cliente Inactivo' }}
                        </span>
                    </p>
                    <p class="mt-1 text-sm font-semibold">
                        <span class="{{ $cliente->verificado ? 'text-cyan-600' : 'text-cyan-400' }}">
                            {{ $cliente->verificado ? 'Cliente verficado' : 'No verficado' }}
                        </span>
                    </p>
                </div>
                <div class="flex flex-wrap justify-center md:justify-center gap-2 border-t border-gray-200 pt-3 pb-2">
                    <button wire:click="verDetalle({{ $cliente->id }})"
                        class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Ver detalles">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M8 12l0 .01" />
                            <path d="M12 12l0 .01" />
                            <path d="M16 12l0 .01" />
                        </svg>
                        Ver más
                    </button>

                    <button wire:click="editarCliente({{ $cliente->id }})"
                        class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                            <path d="M16 5l3 3" />
                        </svg>
                        Editar
                    </button>

                    <a href="{{ route('clientes.map', $cliente->id) }}"
                        class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Ver mapa">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5" />
                            <path d="M9 4v13" />
                            <path d="M15 7v5.5" />
                        </svg>
                        Dirección del cliente
                    </a>

                    <a href="{{ route('clientes.editar', $cliente->id) }}"
                        class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Editar coordenadas">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v8" />
                            <path d="M9 4v13" />
                            <path d="M15 7v6.5" />
                        </svg>
                        Editar direccion
                    </a>

                    <button wire:click="toggleVerificado({{ $cliente->id }})"
                        class="btn-cyan flex items-center gap-1 flex-shrink-0 {{ $cliente->verificado ? 'bg-cyan-600 text-white' : 'bg-white text-cyan-600 border border-cyan-600' }}"
                        title="Cambiar verificación">
                        @if($cliente->verificado)
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 12l5 5l10 -10" />
                            </svg>
                            Verificado
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M18 6l-12 12" />
                                <path d="M6 6l12 12" />
                            </svg>
                            No verificado
                        @endif
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-4 text-gray-600">
                No hay clientes registrados.
            </div>
        @endforelse
        @if($clientes->count() >= $cantidad)
            <div class="col-span-full text-center mt-4">
                <button wire:click="$set('cantidad', {{ $cantidad + 50 }})" class="btn-cyan px-4 py-2 rounded">
                    Cargar más
                </button>
            </div>
        @endif
    </div>

    @if($modal)
        <div class="modal-overlay">
            <div class="modal-box">
                <div class="modal-content flex flex-col gap-4">
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Imagen (Opcional)</label>
                        <input type="file" wire:model="foto" class="input-minimal">
                        @if($foto)
                            <div class="mt-2 flex justify-center">
                                <img src="{{ is_string($foto) ? asset('storage/' . $foto) : $foto->temporaryUrl() }}"
                                    class="w-50 h-50 object-cover rounded" alt="Imagen Cliente">
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Nombre (requerido)</label>
                        <input type="text" wire:model="nombre" class="input-minimal" placeholder="Nombre">
                        @error('nombre') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Empresa (Opcional)</label>
                        <input type="text" wire:model="empresa" class="input-minimal" placeholder="Empresa">
                        @error('empresa') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Razón Social (Opcional)</label>
                        <input type="text" wire:model="razonSocial" class="input-minimal" placeholder="Razón Social">
                        @error('razonSocial') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">NIT/CI (Opcional)</label>
                        <input type="text" wire:model="nitCi" class="input-minimal" placeholder="NIT/CI">
                        @error('nitCi') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Teléfono (Opcional)</label>
                        <input type="text" wire:model="telefono" class="input-minimal" placeholder="Teléfono">
                        @error('telefono') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Celular (Opcional)</label>
                        <input type="text" wire:model="celular" class="input-minimal" placeholder="Celular">
                        @error('celular') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Dirección (Opcional)</label>
                        <input type="text" wire:model="direccion" class="input-minimal" placeholder="Dirección">
                        @error('direccion') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Ubicación (Opcional)</label>
                        <input type="text" wire:model="ubicacion" class="input-minimal" placeholder="Ubicación">
                        @error('ubicacion') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Departamento / Localidad (Opcional)</label>
                        <input type="text" wire:model="departamento_localidad" class="input-minimal"
                            placeholder="Departamento / Localidad">
                        @error('departamento_localidad') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Establecimiento (Opcional)</label>
                        <input type="text" wire:model="establecimiento" class="input-minimal" placeholder="Establecimiento">
                        @error('establecimiento') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Disponible (Opcional)</label>
                        <input type="text" wire:model="disponible" class="input-minimal"
                            placeholder="Cliente esta con tiempo">
                        @error('disponible') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Movil (Opcional)</label>
                        <input type="text" wire:model="movil" class="input-minimal" placeholder="Movil del envio">
                        @error('movil') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Días (Opcional)</label>
                        <input type="text" wire:model="dias" class="input-minimal"
                            placeholder="Días disponibles de atecnion">
                        @error('dias') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">BOT (Opcional)</label>
                        <input type="text" wire:model="bot" class="input-minimal" placeholder="BOT">
                        @error('bot') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Correo de acceso (opcional)</label>
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
                    <button type="button" wire:click="guardarCliente" class="btn-cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M14 4l0 4l-6 0l0 -4" />
                        </svg>
                        Guardar
                    </button>
                </div>
                @if($showAlert)
                    <div
                        class="px-4 py-2 mb-2 rounded text-white
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

    @if($detalleModal && $clienteSeleccionado)
        <div class="modal-overlay">
            <div class="modal-box max-w-3xl">
                <div class="modal-content flex flex-col gap-6">
                    <div class="flex justify-center items-center">
                        @if($clienteSeleccionado->foto)
                            <img src="{{ asset('storage/' . $clienteSeleccionado->foto) }}"
                                class="w-full max-w-xs h-auto object-cover rounded-xl shadow-md"
                                alt="Foto de {{ $clienteSeleccionado->nombre }}">
                        @else
                            <span class="badge-info">Sin foto</span>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
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
                                <span class="label-info">Razón Social:</span>
                                <span class="badge-info">{{ $clienteSeleccionado->razonSocial ?? '-' }}</span>
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
                                <span class="label-info">Celular:</span>
                                <span class="badge-info">{{ $clienteSeleccionado->celular ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Dirección:</span>
                                <span class="badge-info">{{ $clienteSeleccionado->direccion ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Ubicación:</span>
                                <span class="badge-info">{{ $clienteSeleccionado->ubicacion ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Departamento / Localidad:</span>
                                <span class="badge-info">{{ $clienteSeleccionado->departamento_localidad ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Establecimiento:</span>
                                <span class="badge-info">{{ $clienteSeleccionado->establecimiento ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Disponible:</span>
                                <span class="badge-info">{{ $clienteSeleccionado->disponible ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Movil:</span>
                                <span class="badge-info">{{ $clienteSeleccionado->movil ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Días:</span>
                                <span class="badge-info">{{ $clienteSeleccionado->dias ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">BOT:</span>
                                <span class="badge-info">{{ $clienteSeleccionado->bot ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Latitud:</span>
                                <span class="badge-info">{{ $clienteSeleccionado->latitud ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Longitud:</span>
                                <span class="badge-info">{{ $clienteSeleccionado->longitud ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Estado:</span>
                                <span
                                    class="badge-info {{ $clienteSeleccionado->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $clienteSeleccionado->estado ? 'Activo' : 'Inactivo' }}
                                </span>
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
                                <span class="badge-info">{{ $clienteSeleccionado->verificado ? 'Sí' : 'No' }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer mt-6 flex justify-center">
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
<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <h3 class="inline-block bg-teal-700 text-white px-5 py-2 rounded-full text-xl font-bold uppercase shadow-md">
            Etiquetas
        </h3>
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por capacidad o descripción..."
                class="input-minimal w-full" />
            <button wire:click="abrirModal('create')" class="btn-cyan" title="Agregar etiqueta">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                    <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                </svg>
                Añadir
            </button>
        </div>
        @forelse($etiquetas as $etiqueta)
        <div class="card-teal flex flex-col gap-4 p-4 shadow rounded-lg">
            <div class="flex flex-col gap-2">
                <p class="text-u">
                    {{ $etiqueta->descripcion ?? 'Sin descripción' }}
                </p>
                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold uppercase
                         {{ $etiqueta->estado ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white' }}">
                    {{ $etiqueta->estado ? 'Activo' : 'Inactivo' }}
                </span>
                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold uppercase
                         {{ $etiqueta->tipo === 1 ? 'bg-blue-600 text-white' : ($etiqueta->tipo === 2 ? 'bg-yellow-500 text-white' : 'bg-gray-400 text-white') }}">
                    {{ $etiqueta->tipo === 1 ? 'Transparente' : ($etiqueta->tipo === 2 ? 'Blanco' : 'Otro') }}
                </span>
                <p><strong>Capacidad:</strong> {{ $etiqueta->capacidad ?? 'N/A' }}</p>
                <p><strong>Unidad:</strong> {{ $etiqueta->unidad ?? '' }}</p>
                @if($etiqueta->existencias->isEmpty())
                <span class="inline-block bg-gray-400 text-white px-3 py-1 rounded-full text-sm font-semibold uppercase">
                    N/A
                </span>
                @else
                @foreach($etiqueta->existencias as $existencia)
                <span class="inline-block bg-cyan-600 text-white px-3 py-1 rounded-full text-sm font-semibold uppercase">
                    Sucursal : {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}
                    ({{ $existencia->cantidad }} / mín: {{ $existencia->cantidadMinima ?? 0 }})
                </span>

                @endforeach
                @endif
               
            </div>
            <div class="flex justify-center items-center mt-2">
                @if($etiqueta->imagen)
                <img src="{{ asset('storage/'.$etiqueta->imagen) }}" alt="Imagen de etiqueta"
                    class="w-24 h-24 object-cover rounded-lg border border-cyan-300">
                @else
                <div class="w-24 h-24 flex items-center justify-center bg-gray-200 text-gray-500 rounded-lg text-xs">
                    Sin imagen
                </div>
                @endif
            </div>
            <div class="flex gap-2 overflow-x-auto no-scrollbar pt-2 justify-start md:justify-end">
                <button wire:click="abrirModal('edit', {{ $etiqueta->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Editar">
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
                <button wire:click="modaldetalle({{ $etiqueta->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Ver Detalle del Pedido">
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
            No hay etiquetas registradas.
        </div>
        @endforelse

    </div>


    @if($modal)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content">
                <div class="flex flex-col gap-4">
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Imagen (Opcional)</label>
                        <input type="file" wire:model="imagen" class="input-minimal">
                        @if($imagen)
                        <div class="mt-2 flex justify-center">
                            <img
                                src="{{ is_string($imagen) ? asset('storage/'.$imagen) : $imagen->temporaryUrl() }}"
                                class="w-48 h-48 object-cover rounded shadow-md border border-cyan-300"
                                alt="Imagen Etiqueta">
                        </div>
                        @endif
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Descripción (Opcional)</label>
                        <input wire:model="descripcion" class="input-minimal" placeholder="Nombre o descripción de la etiqueta">
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Capacidad (Requerido)</label>
                        <input type="number" wire:model="capacidad" class="input-minimal" min="0" placeholder="Capacidad de la etiqueta">
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Unidad (Opcional)</label>
                        <input type="text" wire:model="unidad" class="input-minimal" placeholder="Unidad (ml, L, etc.)">
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Tipo (Requerido)</label>
                        <div class="flex gap-2">
                            @foreach([1 => 'Transparente', 2 => 'Brilloso'] as $key => $label)
                            <button type="button"
                                wire:click="$set('tipo', {{ $key }})"
                                class="px-4 py-2 rounded-full text-sm transition
                {{ (int)$tipo === $key ? 'bg-cyan-600 text-white shadow-md' : 'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
                                {{ $label }}
                            </button>
                            @endforeach
                        </div>
                        @error('tipo')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Estado</label>
                        <div class="flex gap-2">
                            @foreach([1 => 'Activo', 0 => 'Inactivo'] as $key => $label)
                            <button type="button"
                                wire:click="$set('estado', {{ $key }})"
                                class="px-4 py-2 rounded-full text-sm transition
                {{ (int)$estado === $key ? 'bg-cyan-600 text-white shadow-md' : 'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
                                {{ $label }}
                            </button>
                            @endforeach
                        </div>
                    </div>


                    <div>
                        <label class="font-semibold text-sm mb-1 block">Cliente</label>
                        <select wire:model="cliente_id" class="input-minimal">
                            <option value="">Seleccionar cliente</option>
                            @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-1 block">Cantidad Mínima</label>
                        <input type="number" wire:model="cantidadMinima" class="input-minimal" min="0" placeholder="Cantidad mínima permitida">
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
                <button type="button" wire:click="guardar" class="btn-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M14 4l0 4l-6 0l0 -4" />
                    </svg>
                    Guardar
                </button>

            </div>

        </div>
    </div>
    @endif


    @if($modalDetalle && $etiquetaSeleccionada)
    <div class="modal-overlay">
        <div class="modal-box max-w-3xl">
            <div class="modal-content flex flex-col gap-6">
                <div class="flex justify-center items-center">
                    @if($etiquetaSeleccionada->imagen)
                    <img src="{{ asset('storage/'.$etiquetaSeleccionada->imagen) }}"
                        class="w-full max-w-xl h-auto object-contain rounded-xl shadow-md"
                        alt="Imagen Etiqueta">
                    @else
                    <span class="badge-info">Sin imagen</span>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Descripción:</span>
                            <span class="badge-info">{{ $etiquetaSeleccionada->descripcion ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Capacidad:</span>
                            <span class="badge-info">
                                {{ $etiquetaSeleccionada->capacidad ?? '-' }} {{ $etiquetaSeleccionada->unidad ?? '' }}
                            </span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Cliente:</span>
                            <span class="badge-info">{{ $etiquetaSeleccionada->cliente->nombre ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Tipo:</span>
                            <span class="badge-info">
                                @if($etiquetaSeleccionada->tipo === 1)
                                Transparente
                                @elseif($etiquetaSeleccionada->tipo === 2)
                                Brilloso
                                @else
                                Otro
                                @endif
                            </span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Cantidad mínima:</span>
                            <span class="badge-info">
                                {{ $etiquetaSeleccionada->cantidadMinima ?? '-' }}
                            </span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Estado:</span>
                            <span
                                class="badge-info {{ $etiquetaSeleccionada->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $etiquetaSeleccionada->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    <span class="label-info block mb-1">Observaciones:</span>
                    <div class="bg-gray-100 rounded p-2 text-sm text-gray-700">
                        {{ $etiquetaSeleccionada->observaciones ?? 'Sin observaciones' }}
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button wire:click="$set('modalDetalle', false)" class="btn-cyan" title="Cerrar">
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
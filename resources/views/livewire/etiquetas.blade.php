<div class="p-2 mt-20 flex justify-center bg-transparent">
    <div class="w-full max-w-screen-xl">

        <h3 class="text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto mb-4">
            Etiquetas
        </h3>

        <div class="flex items-center gap-2 mb-4 flex-wrap">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por capacidad o descripción..."
                class="input-minimal w-full sm:w-auto flex-1" />

            <button wire:click="abrirModal('create')" class="btn-cyan flex items-center gap-1">
                Añadir
            </button>
        </div>

        <div class="overflow-auto max-h-[500px] border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-teal-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Descripción</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Capacidad</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Unidad</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Tipo</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Estado</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Existencias</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Cliente</th>
                        <th class="px-4 py-2 text-center text-teal-700 font-semibold">Acciones</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($etiquetas as $etiqueta)
                    <tr class="hover:bg-teal-50">
                        <td class="px-4 py-2">{{ $etiqueta->descripcion ?? 'Sin descripción' }}</td>
                        <td class="px-4 py-2">{{ $etiqueta->capacidad ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $etiqueta->unidad ?? 'N/A' }}</td>

                        <td class="px-4 py-2">
                            <span class="{{ $etiqueta->tipo === 1 ? 'text-blue-600' : ($etiqueta->tipo === 2 ? 'text-yellow-600' : 'text-gray-600') }}">
                                {{ $etiqueta->tipo === 1 ? 'Transparente' : ($etiqueta->tipo === 2 ? 'Blanco' : 'Otro') }}
                            </span>
                        </td>

                        <td class="px-4 py-2">
                            <span class="{{ $etiqueta->estado == 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $etiqueta->estado == 0 ? 'Inactivo' : 'Activo' }}
                            </span>
                        </td>

                        <td class="px-4 py-2">
                            @if($etiqueta->existencias->isEmpty())
                            N/A
                            @else
                            @foreach($etiqueta->existencias as $existencia)
                            <div class="text-sm text-cyan-700">
                                {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}:
                                {{ $existencia->cantidad }}
                                (Min: {{ $existencia->cantidadMinima ?? 0 }})
                            </div>
                            @endforeach
                            @endif
                        </td>

                        <td class="px-4 py-2">
                            @if($etiqueta->cliente)
                            <div class="text-sm text-gray-800 font-semibold">
                                {{ $etiqueta->cliente->nombre ?? 'Sin nombre' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $etiqueta->cliente->telefono ?? '' }}
                            </div>
                            @else
                            <span class="text-gray-400 text-sm">No asignado</span>
                            @endif
                        </td>

                        <td class="px-4 py-2 flex justify-center gap-1">
                            <button
                                wire:click="modaldetalle({{ $etiqueta->id }})"
                                class="btn-cyan"
                                title="Ver Detalles">
                                Ver más
                            </button>

                            <button
                                wire:click="abrirModal('edit', {{ $etiqueta->id }})"
                                class="btn-cyan"
                                title="Editar">
                                Editar
                            </button>
                            @if($etiqueta->imagen)
                            <a
                                href="{{ asset('storage/' . $etiqueta->imagen) }}"
                                download="etiqueta_{{ $etiqueta->id }}.jpg"
                                class="btn-cyan"
                                title="Descargar imagen">
                                Descargar
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-gray-600">
                            No hay etiquetas registradas.
                        </td>
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
                        <label class="font-semibold text-sm mb-2 block">Imagen (Opcional)</label>
                        <input type="file" wire:model="imagen" class="input-minimal">
                        @if($imagen)
                        <div class="mt-2 flex justify-center">
                            <img src="{{ is_string($imagen) ? asset('storage/' . $imagen) : $imagen->temporaryUrl() }}"
                                class="w-48 h-48 object-cover rounded shadow-md border border-cyan-300"
                                alt="Imagen Etiqueta">
                        </div>
                        @endif
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Descripción (Opcional)</label>
                        <input wire:model="descripcion" class="input-minimal"
                            placeholder="Nombre o descripción de la etiqueta">
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Capacidad (Requerido)</label>
                        <input type="number" wire:model="capacidad" class="input-minimal" min="0"
                            placeholder="Capacidad de la etiqueta">
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Unidad (Opcional)</label>
                        <input type="text" wire:model="unidad" class="input-minimal" placeholder="Unidad (ml, L, etc.)">
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Tipo (Requerido)</label>
                        <div class="flex gap-2">
                            @foreach([1 => 'Transparente', 2 => 'Brilloso'] as $key => $label)
                            <button type="button" wire:click="$set('tipo', {{ $key }})"
                                class="px-4 py-2 rounded-full text-sm transition
                                            {{ (int) $tipo === $key ? 'bg-cyan-600 text-white shadow-md' : 'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
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
                            <button type="button" wire:click="$set('estado', {{ $key }})"
                                class="px-4 py-2 rounded-full text-sm transition
                                            {{ (int) $estado === $key ? 'bg-cyan-600 text-white shadow-md' : 'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
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
                        <input type="number" wire:model="cantidadMinima" class="input-minimal" min="0"
                            placeholder="Cantidad mínima permitida">
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
                <button type="button" wire:click="guardar" class="btn-cyan">
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

        </div>
    </div>
    @endif


    @if($modalDetalle && $etiquetaSeleccionada)
    <div class="modal-overlay">
        <div class="modal-box max-w-3xl">
            <div class="modal-content flex flex-col gap-6">
                <div class="flex justify-center items-center">
                    @if($etiquetaSeleccionada->imagen)
                    <img src="{{ asset('storage/' . $etiquetaSeleccionada->imagen) }}"
                        class="w-full max-w-xl h-auto object-contain rounded-xl shadow-md" alt="Imagen Etiqueta">
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
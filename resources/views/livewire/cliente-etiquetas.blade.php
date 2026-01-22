<div class="p-2 mt-20 flex justify-center bg-transparent">
    <div class="w-full max-w-screen-xl">

        <h3 class="text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto mb-4">
            Etiquetas
        </h3>

        <div class="flex items-center gap-2 mb-4 flex-wrap">
            <button wire:click="abrirModal()" class="btn-cyan flex items-center gap-1">
                Añadir etiqueta
            </button>
        </div>

        <div class="overflow-auto max-h-[500px] border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-teal-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Descripción</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Capacidad</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Unidad</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Estado</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Imagen</th>
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
                            <span class="{{ $etiqueta->estado ? 'text-green-600' : 'text-red-600' }}">
                                {{ $etiqueta->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>



                        <td class="px-4 py-2">
                            @if($etiqueta->imagen)
                            <img src="{{ asset('storage/' . $etiqueta->imagen) }}"
                                class="w-16 h-16 object-cover rounded-lg border border-cyan-300">
                            @else
                            <div class="w-16 h-16 flex items-center justify-center bg-gray-200 text-gray-500 rounded-lg text-xs">
                                Sin imagen
                            </div>
                            @endif
                        </td>

                        <td class="px-4 py-2 flex justify-center gap-1">
                            <button wire:click="abrirModal({{ $etiqueta->id }})" class="btn-cyan" title="Editar">
                                Editar
                            </button>

                            <button wire:click="eliminar({{ $etiqueta->id }})" class="btn-cyan" title="Eliminar">
                                Eliminar
                            </button>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-600">No hay etiquetas registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>



    @if($modal)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">

                <div>
                    <label class="font-semibold text-sm">Imagen</label>
                    <input type="file" wire:model="imagen" class="input-minimal">
                    @if($imagen)
                    <img src="{{ is_string($imagen) ? asset('storage/' . $imagen) : $imagen->temporaryUrl() }}"
                        class="mt-2 w-40 h-40 object-cover rounded border">
                    @endif
                </div>

                <div>
                    <label class="font-semibold text-sm">Descripción</label>
                    <input wire:model="descripcion" class="input-minimal">
                </div>

                <div>
                    <label class="font-semibold text-sm">Capacidad /tamaño</label>
                    <input type="text" wire:model="capacidad" class="input-minimal">
                </div>

                <div>
                    <label class="font-semibold text-sm">Unidad</label>
                    <input wire:model="unidad" class="input-minimal">
                </div>

                <div>
                    <label class="font-semibold text-sm">Estado</label>
                    <div class="flex gap-2 mt-1">
                        @foreach([1 => 'Activo', 0 => 'Inactivo'] as $key => $label)
                        <button type="button" wire:click="$set('estado', {{ $key }})"
                            class="px-4 py-1 rounded-full text-sm
                                                                            {{ (int) $estado === $key ? 'bg-cyan-600 text-white' : 'bg-gray-200' }}">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button wire:click="cerrarModal" class="btn-cyan" title="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                        <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
                    </svg>
                    CERRAR
                </button>
                <button wire:click="guardar" class="btn-cyan">
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
</div>
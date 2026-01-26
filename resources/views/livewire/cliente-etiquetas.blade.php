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
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Imagen</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Estado</th>
                        <th class="px-4 py-2 text-center text-teal-700 font-semibold">Acciones</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($etiquetas as $etiqueta)
                    <tr class="hover:bg-teal-50">
                        <td class="px-4 py-2">{{ $etiqueta->descripcion ?? 'Sin descripción' }}</td>
                        <td class="px-4 py-2">
                            @if($etiqueta->imagen)
                            <a href="{{ asset('storage/'.$etiqueta->imagen) }}" target="_blank"
                                class="text-sm text-cyan-700 underline">
                                Ver imagen
                            </a>
                            @else
                            <span class="text-gray-500">Sin imagen</span>
                            @endif
                        </td>

                        <td class="px-4 py-2">
                            <span class="{{ $etiqueta->estado ? 'text-green-600' : 'text-red-600' }}">
                                {{ $etiqueta->estado ? 'Activo' : 'Inactivo' }}
                            </span>
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
                    <label class="font-semibold text-sm">Descripción o nombre</label>
                    <input wire:model="descripcion" class="input-minimal">
                </div>

            </div>

            <div class="modal-footer">
                <button wire:click="cerrarModal" class="btn-cyan" title="Cerrar">
                    CERRAR
                </button>
                <button wire:click="guardar" class="btn-cyan">
                    Guardar
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
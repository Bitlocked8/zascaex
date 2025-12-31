<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3
            class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto">
            Etiquetas
        </h3>

        <div class="flex justify-end col-span-full mb-4">
            <button wire:click="abrirModal" class="btn-cyan flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M19 4a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-14a3 3 0 0 1 -3 -3v-10a3 3 0 0 1 3 -3zm-4 4h-1a1 1 0 0 0 -1 1v6a1 1 0 0 0 1 1h1a3 3 0 0 0 3 -3v-2a3 3 0 0 0 -3 -3m-6.5 0a2.5 2.5 0 0 0 -2.5 2.5v4.5a1 1 0 0 0 2 0v-1h1v1a1 1 0 0 0 .883 .993l.117 .007a1 1 0 0 0 1 -1v-4.5a2.5 2.5 0 0 0 -2.5 -2.5m6.5 2a1 1 0 0 1 1 1v2a1 1 0 0 1 -.883 .993l-.117 .007zm-6.5 0a.5 .5 0 0 1 .5 .5v1.5h-1v-1.5a.5 .5 0 0 1 .41 -.492z" />
                </svg>
                Añadir etiqueta
            </button>
        </div>

        @forelse($etiquetas as $etiqueta)
            <div class="card-teal flex flex-col gap-4 p-4">

                <div class="flex flex-col gap-1">
                    <p class="text-emerald-700 font-semibold uppercase">
                        {{ $etiqueta->descripcion ?? 'Sin descripción' }}
                    </p>

                    <p><strong>Capacidad:</strong> {{ $etiqueta->capacidad }}</p>
                    <p><strong>Unidad:</strong> {{ $etiqueta->unidad ?? 'N/A' }}</p>

                    <span class="text-sm font-semibold {{ $etiqueta->estado ? 'text-green-600' : 'text-red-600' }}">
                        {{ $etiqueta->estado ? 'Activo' : 'Inactivo' }}
                    </span>

                    @foreach($etiqueta->existencias as $existencia)
                        <div class="mt-2 text-sm text-cyan-700 font-medium">
                            <p>Sucursal: {{ $existencia->sucursal->nombre ?? 'Sucursal 1' }}</p>
                            <p>Cantidad: {{ $existencia->cantidad }}</p>
                            <p>Mínima: {{ $existencia->cantidadMinima }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-col items-center gap-2">
                    @if($etiqueta->imagen)
                        <img src="{{ asset('storage/' . $etiqueta->imagen) }}"
                            class="w-24 h-24 object-cover rounded-lg border border-cyan-300">

                        <a href="{{ asset('storage/' . $etiqueta->imagen) }}" download class="text-xs bg-cyan-500 text-white px-3 py-1 rounded-full
                          hover:bg-cyan-600 transition flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3v12" />
                                <path d="M7 10l5 5l5 -5" />
                                <path d="M5 21h14" />
                            </svg>
                            Descargar
                        </a>
                    @else
                        <div class="w-24 h-24 flex items-center justify-center bg-gray-200 text-gray-500 rounded-lg text-xs">
                            Sin imagen
                        </div>
                    @endif
                </div>


                <div class="flex justify-center gap-2 border-t pt-3">
                    <button wire:click="abrirModal({{ $etiqueta->id }})" class="btn-cyan" title="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                            <path d="M16 5l3 3" />
                        </svg>
                        Editar
                    </button>

                    <button wire:click="eliminar({{ $etiqueta->id }})" class="btn-cyan" title="Eliminar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 7l16 0" />
                            <path d="M10 11l0 6" />
                            <path d="M14 11l0 6" />
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                        </svg>
                        Eliminar
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 py-6">
                No hay etiquetas registradas.
            </div>
        @endforelse
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
                        <label class="font-semibold text-sm">Capacidad *</label>
                        <input type="number" wire:model="capacidad" class="input-minimal">
                        @error('capacidad') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
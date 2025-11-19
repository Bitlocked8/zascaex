<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3
            class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto">
            Productos
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="search" placeholder="Buscar por descripción o tipo..."
                class="input-minimal w-full" />

            <button wire:click="abrirModal('create')" class="btn-cyan" title="Agregar producto">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                    <path
                        d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                </svg>
                Añadir
            </button>
        </div>

        @forelse($productos as $producto)
            <div class="card-teal flex flex-col gap-4 p-4">
                <div class="flex flex-col gap-2">
                    <p class="text-emerald-600 uppercase font-semibold">
                        {{ $producto->descripcion ?? 'Sin descripción' }}
                    </p>
                    <p><strong>Tipo:</strong> {{ $producto->tipo ?? 'N/A' }}</p>
                    <p><strong>Unidad:</strong> {{ $producto->unidad ?? 'N/A' }}</p>
                    <p><strong>Capacidad:</strong> {{ $producto->capacidad ?? 'N/A' }}</p>
                    <p><strong>Precio Ref.:</strong> {{ $producto->precioReferencia ?? 'N/A' }} Bs</p>

                    <p class="mt-1 text-sm font-semibold">
                        <span class="{{ $producto->estado == 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $producto->estado == 0 ? 'Inactivo' : 'Activo' }}
                        </span>
                    </p>

                    @if($producto->existencias->isEmpty())
                        <p class="text-gray-500 text-sm font-medium">
                            N/A
                        </p>
                    @else
                        @foreach($producto->existencias as $existencia)
                            <p class="text-cyan-700 text-sm font-semibold">
                                <span class="block">Sucursal: {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}</span>
                                <span class="block">Cantidad: {{ $existencia->cantidad }}</span>
                                <span class="block">Mínima: {{ $existencia->cantidadMinima ?? 0 }}</span>
                            </p>
                        @endforeach
                    @endif
                </div>

                <div class="flex justify-center items-center mt-2">
                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen"
                            class="w-24 h-24 object-cover rounded-lg border border-cyan-300">
                    @else
                        <div class="w-24 h-24 flex items-center justify-center bg-gray-200 text-gray-500 rounded-lg text-xs">
                            Sin imagen
                        </div>
                    @endif
                </div>

                <div class="flex flex-wrap justify-center md:justify-center gap-2 border-t border-gray-200 pt-3 pb-2">
                    <button wire:click="modaldetalle({{ $producto->id }})"
                        class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Ver Detalles">
                        Ver más
                    </button>

                    <button wire:click="abrirModal('edit', {{ $producto->id }})"
                        class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Editar">
                        Editar
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-4 text-gray-600">
                No hay registros de productos.
            </div>
        @endforelse

    </div>


    @if($modal)
        <div class="modal-overlay">
            <div class="modal-box max-w-3xl">
                <div class="modal-content flex flex-col gap-4">

                    {{-- IMAGEN --}}
                    <div>
                        <label>Imagen</label>
                        <input type="file" wire:model="imagen" class="input-minimal">

                        @if($imagen || $imagenExistente)
                            <div class="mt-2 flex justify-center">
                                <img src="{{ $imagen ? $imagen->temporaryUrl() : asset('storage/' . $imagenExistente) }}"
                                    class="w-full max-w-xl h-auto object-contain rounded-xl shadow-md border border-cyan-300">
                            </div>
                        @endif
                    </div>

                    {{-- DESCRIPCIÓN --}}
                    <div>
                        <label class="text-u">Nombre (Requerido)</label>
                        <input wire:model="descripcion" class="input-minimal" placeholder="Descripción">
                    </div>

                    {{-- UNIDAD --}}
                    <div>
                        <label>Unidad (Opcional)</label>
                        <input wire:model="unidad" class="input-minimal" placeholder="Litros, ml, etc">
                    </div>

                    {{-- TIPO CONTENIDO --}}
                    <div>
                        <label class="text-u">Tipo de Contenido (Requerido)</label>
                        <input wire:model="tipoContenido" class="input-minimal" placeholder="Contenido">
                    </div>

                    {{-- TIPO PRODUCTO --}}
                    <div>
                        <label class="text-u">Tipo de Producto (Requerido)</label>
                        <input wire:model="tipoProducto" class="input-minimal" placeholder="Botella, botellón, etc">
                    </div>

                    {{-- MATERIAL / TIPO --}}
                    <div>
                        <label>Tipo / Material (Opcional)</label>
                        <input wire:model="tipo" class="input-minimal" placeholder="Material">
                    </div>

                    {{-- CAPACIDAD --}}
                    <div>
                        <label class="text-u">Capacidad (Requerido)</label>
                        <input type="number" wire:model="capacidad" class="input-minimal" min="0" step="0.01"
                            placeholder="Litros">
                    </div>

                    {{-- PAQUETE --}}
                    <div>
                        <label>Paquete (Opcional)</label>
                        <input wire:model="paquete" class="input-minimal" placeholder="Paquete(s)">
                    </div>

                    {{-- PRECIO REF --}}
                    <div>
                        <label class="text-u">Precio de Referencia (Bs) (Requerido)</label>
                        <input type="number" wire:model="precioReferencia" class="input-minimal" step="0.01" min="0">
                    </div>

                    {{-- OBS --}}
                    <div>
                        <label>Observaciones (Opcional)</label>
                        <textarea wire:model="observaciones" class="input-minimal" rows="2"></textarea>
                    </div>

                    {{-- CANTIDAD MÍNIMA --}}
                    <div>
                        <label>Cantidad Mínima (Opcional)</label>
                        <input type="number" wire:model="cantidadMinima" class="input-minimal" min="0">
                    </div>

                    {{-- ESTADO --}}
                    <div>
                        <label>Estado</label>

                        <div class="flex flex-wrap justify-center gap-2 mt-2">
                            @foreach([1 => 'Activo', 0 => 'Inactivo'] as $key => $label)
                                <button type="button" wire:click="$set('estado', {{ $key }})"
                                    class="px-4 py-2 rounded-full text-sm flex items-center justify-center transition {{ $estado == $key ? 'bg-cyan-600 text-white shadow-md' : 'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- FOOTER --}}
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

    @if($modalDetalle && $productoSeleccionado)
        <div class="modal-overlay">
            <div class="modal-box max-w-3xl">
                <div class="modal-content flex flex-col gap-6">
                    <div class="flex justify-center items-center">
                        @if($productoSeleccionado->imagen)
                            <img src="{{ asset('storage/' . $productoSeleccionado->imagen) }}"
                                class="w-full max-w-xl h-auto object-contain rounded-xl shadow-md">
                        @else
                            <span class="badge-info">Sin imagen</span>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex flex-col gap-3">
                            <div class="flex gap-2">
                                <span>Descripción:</span><span>{{ $productoSeleccionado->descripcion ?? '-' }}</span>
                            </div>
                            <div class="flex gap-2">
                                <span>Unidad:</span><span>{{ $productoSeleccionado->unidad ?? '-' }}</span></div>
                            <div class="flex gap-2">
                                <span>Capacidad:</span><span>{{ $productoSeleccionado->capacidad ?? '-' }}</span>
                            </div>
                            <div class="flex gap-2"><span>Tipo de
                                    Contenido:</span><span>{{ $productoSeleccionado->tipoContenido ?? '-' }}</span></div>
                            <div class="flex gap-2"><span>Tipo de
                                    Producto:</span><span>{{ $productoSeleccionado->tipoProducto ?? '-' }}</span></div>
                        </div>
                        <div class="flex flex-col gap-3">
                            <div class="flex gap-2">
                                <span>Paquete:</span><span>{{ $productoSeleccionado->paquete ?? '-' }}</span>
                            </div>
                            <div class="flex gap-2"><span>Precio
                                    Ref.:</span><span>{{ $productoSeleccionado->precioReferencia ?? '-' }}</span></div>
                            <div class="flex gap-2"><span>Cantidad
                                    mínima:</span><span>{{ $productoSeleccionado->cantidadMinima ?? '-' }}</span></div>
                            <div class="flex gap-2"><span>Tipo /
                                    Material:</span><span>{{ $productoSeleccionado->tipo ?? '-' }}</span></div>
                        </div>
                    </div>
                    <div class="flex gap-2 text-sm"><span>Estado:</span>
                        <span
                            class="{{ $productoSeleccionado->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $productoSeleccionado->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    <div><span>Observaciones:</span>
                        <div class="bg-gray-100 rounded p-2 text-sm text-gray-700">
                            {{ $productoSeleccionado->observaciones ?? 'Sin observaciones' }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('modalDetalle', false)" class="btn-cyan" title="Cerrar">CERRAR</button>
                </div>
            </div>
        </div>
    @endif
</div>
<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <h3 class="inline-block bg-teal-700 text-white px-5 py-2 rounded-full text-xl font-bold uppercase shadow-md">
            Productos
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por nombre o descripción..."
                class="input-minimal w-full" />
            <button wire:click="abrirModal('create')" class="btn-cyan" title="Agregar producto">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                    <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                </svg>
                Añadir
            </button>
        </div>

        @forelse($productos as $producto)
        <div class="card-teal flex flex-col gap-4 p-4 shadow rounded-lg">
            <div class="flex flex-col gap-2">
                <p class="text-u">
                    {{ $producto->descripcion ?? 'Sin descripción' }}
                </p>
                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold uppercase
                    {{ $producto->estado ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white' }}">
                    {{ $producto->estado ? 'Activo' : 'Inactivo' }}
                </span>
                <p><strong>Unidad:</strong> {{ $producto->unidad ?? 'N/A' }}</p>
                <p><strong>Capacidad:</strong> {{ $producto->capacidad ?? 'N/A' }}</p>
                <p><strong>Precio Ref.:</strong> {{ $producto->precioReferencia ?? 'N/A' }} Bs</p>

                @if($producto->existencias->isEmpty())
                <span class="inline-block bg-gray-400 text-white px-3 py-1 rounded-full text-sm font-semibold uppercase">
                    Sin existencias
                </span>
                @else
                @foreach($producto->existencias as $existencia)
                <span class="inline-block bg-cyan-600 text-white px-3 py-1 rounded-full text-sm font-semibold uppercase">
                    {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}
                    ({{ $existencia->cantidad }} / mín: {{ $existencia->cantidadMinima ?? 0 }})
                </span>
                @endforeach
                @endif
            </div>

            <div class="flex justify-center items-center mt-2">
                @if($producto->imagen)
                <img src="{{ asset('storage/'.$producto->imagen) }}" alt="Imagen de producto"
                    class="w-24 h-24 object-cover rounded-lg border border-cyan-300">
                @else
                <div class="w-24 h-24 flex items-center justify-center bg-gray-200 text-gray-500 rounded-lg text-xs">
                    Sin imagen
                </div>
                @endif
            </div>

            <div class="flex gap-2 overflow-x-auto no-scrollbar pt-2 justify-start md:justify-end">
                <button wire:click="abrirModal('edit', {{ $producto->id }})" class="btn-cyan" title="Editar">
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

                <button wire:click="modaldetalle({{ $producto->id }})" class="btn-cyan" title="Ver Detalle">
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
            No hay productos registrados.
        </div>
        @endforelse
    </div>




    @if($modal)
    <div class="modal-overlay">
        <div class="modal-box max-w-3xl">
            <div class="modal-content flex flex-col gap-4">

                <!-- Imagen -->
                <div>
                    <label class="font-semibold text-sm mb-2 block">Imagen</label>
                    <input type="file" wire:model="imagen" class="input-minimal">
                    @if($imagen || $imagenExistente)
                    <div class="mt-2 flex justify-center">
                        <img src="{{ $imagen ? $imagen->temporaryUrl() : asset('storage/'.$imagenExistente) }}"
                            class="w-full max-w-xl h-auto object-contain rounded-xl shadow-md border border-cyan-300"
                            alt="Imagen Producto">
                    </div>
                    @endif
                    @error('imagen') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Datos básicos -->
                <div>
                    <label class="font-semibold text-sm mb-1 block">Descripción</label>
                    <input wire:model="descripcion" class="input-minimal" placeholder="Descripción del producto">
                </div>
                <div>
                    <label class="font-semibold text-sm mb-1 block">Unidad</label>
                    <input wire:model="unidad" class="input-minimal" placeholder="Ej. mls, Lts, Kgs...">
                </div>
                <div>
                    <label class="font-semibold text-sm mb-1 block">Tipo de Contenido</label>
                    <input wire:model="tipoContenido" class="input-minimal" placeholder="Ej. Agua, Gaseosa, Hielo...">
                </div>
                <div>
                    <label class="font-semibold text-sm mb-1 block">Tipo de Producto</label>
                    <input wire:model="tipoProducto" class="input-minimal" placeholder="Ej. Botella, Botellón, Dispenser...">
                </div>
                <div>
                    <label class="font-semibold text-sm mb-1 block">Tipo / Material</label>
                    <input wire:model="tipo" class="input-minimal" placeholder="Ej. Plástico, Vidrio...">
                </div>
                <div>
                    <label class="font-semibold text-sm mb-1 block">Capacidad</label>
                    <input type="number" wire:model="capacidad" class="input-minimal" min="0" step="0.01" placeholder="Ej. 500">
                </div>
                <div>
                    <label class="font-semibold text-sm mb-1 block">Paquete</label>
                    <input wire:model="paquete" class="input-minimal" placeholder="Ej. 10 unidades, Caja, etc.">
                </div>
                <div>
                    <label class="font-semibold text-sm mb-1 block">Precio de Referencia (Bs)</label>
                    <input type="number" wire:model="precioReferencia" class="input-minimal" step="0.01" min="0" placeholder="Ej. 10.50">
                </div>
                <div>
                    <label class="font-semibold text-sm mb-1 block">Observaciones</label>
                    <textarea wire:model="observaciones" class="input-minimal" rows="2" placeholder="Comentarios o detalles adicionales"></textarea>
                </div>
                <div>
                    <label class="font-semibold text-sm mb-1 block">Cantidad Mínima</label>
                    <input type="number" wire:model="cantidadMinima" class="input-minimal" min="0" placeholder="Cantidad mínima">
                </div>

                <!-- Estado -->
                <div>
                    <label class="font-semibold text-sm mb-1 block">Estado</label>
                    <div class="flex flex-wrap justify-center gap-2 mt-2">
                        @foreach([1 => 'Activo', 0 => 'Inactivo'] as $key => $label)
                        <button type="button"
                            wire:click="$set('estado', {{ $key }})"
                            class="px-4 py-2 rounded-full text-sm flex items-center justify-center transition
            {{ $estado == $key ? 'bg-cyan-600 text-white shadow-md' : 'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Botones inferiores -->
            <div class="modal-footer mt-6 flex justify-center gap-2">
                <button type="button" wire:click="guardar" class="btn-circle btn-cyan" title="Guardar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M14 4v4h-6v-4" />
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
        </div>
    </div>
    @endif


    @if($modalDetalle && $productoSeleccionado)
    <div class="modal-overlay">
        <div class="modal-box max-w-3xl">
            <div class="modal-content flex flex-col gap-6">
                <div class="flex justify-center items-center">
                    @if($productoSeleccionado->imagen)
                    <img src="{{ asset('storage/'.$productoSeleccionado->imagen) }}"
                        class="w-full max-w-xl h-auto object-contain rounded-xl shadow-md"
                        alt="Imagen Producto">
                    @else
                    <span class="badge-info">Sin imagen</span>
                    @endif
                </div>

                <!-- Datos principales -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Descripción:</span>
                            <span class="badge-info">{{ $productoSeleccionado->descripcion ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Unidad:</span>
                            <span class="badge-info">{{ $productoSeleccionado->unidad ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Capacidad:</span>
                            <span class="badge-info">{{ $productoSeleccionado->capacidad ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Tipo de Contenido:</span>
                            <span class="badge-info">{{ $productoSeleccionado->tipoContenido ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Tipo de Producto:</span>
                            <span class="badge-info">{{ $productoSeleccionado->tipoProducto ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Paquete:</span>
                            <span class="badge-info">{{ $productoSeleccionado->paquete ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Precio Ref. (Bs):</span>
                            <span class="badge-info">{{ $productoSeleccionado->precioReferencia ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Cantidad mínima:</span>
                            <span class="badge-info">{{ $productoSeleccionado->cantidadMinima ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Tipo / Material:</span>
                            <span class="badge-info">{{ $productoSeleccionado->tipo ?? '-' }}</span>
                        </div>
                    </div>

                </div>

                <!-- Estado -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 text-sm">
                    <span class="label-info">Estado:</span>
                    <span class="badge-info {{ $productoSeleccionado->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $productoSeleccionado->estado ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>

                <!-- Observaciones -->
                <div>
                    <span class="label-info block mb-1">Observaciones:</span>
                    <div class="bg-gray-100 rounded p-2 text-sm text-gray-700">
                        {{ $productoSeleccionado->observaciones ?? 'Sin observaciones' }}
                    </div>
                </div>

            </div>

            <!-- Botón cerrar -->
            <div class="modal-footer mt-6 flex justify-center">
                <button wire:click="$set('modalDetalle', false)" class="btn-circle btn-cyan" title="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <path d="M10 10l4 4m0 -4l-4 4" />
                        <circle cx="12" cy="12" r="9" />
                    </svg>
                </button>
            </div>

        </div>
    </div>
    @endif


</div>
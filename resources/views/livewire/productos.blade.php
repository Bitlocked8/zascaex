<div class="p-2 mt-20 flex justify-center bg-transparent">
    <div class="w-full max-w-screen-xl">

        <h3 class="text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto mb-4">
            Productos
        </h3>

        <div class="flex items-center gap-2 mb-4 flex-wrap">
            <input type="text" wire:model.live="search" placeholder="Buscar por descripción o tipo..."
                class="input-minimal w-full sm:w-auto flex-1" />
            <button wire:click="abrirModal('create')" class="btn-cyan flex items-center gap-1">Añadir</button>

        </div>
        <div class="overflow-auto max-h-[500px] border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-teal-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Descripción</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Tipo</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Capacidad</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Precio Producto</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Estado</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Existencias</th>
                        <th class="px-4 py-2 text-center text-teal-700 font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($productos as $producto)
                    <tr class="hover:bg-teal-50">
                        <td class="px-4 py-2">{{ $producto->descripcion ?? 'Sin descripción' }}</td>
                        <td class="px-4 py-2">{{ $producto->tipo ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            {{ $producto->capacidad == intval($producto->capacidad)
        ? intval($producto->capacidad)
        : number_format($producto->capacidad, 2) }}
                            {{ $producto->unidad ?? '' }}
                        </td>

                        <td class="px-4 py-2">
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500">
                                    Precio normal:
                                    {{ $producto->precioReferencia == floor($producto->precioReferencia) 
                ? intval($producto->precioReferencia) 
                : number_format($producto->precioReferencia, 2, '.', '') }} Bs
                                </span>
                                @if(!is_null($producto->precioAlternativo))
                                <span class="text-xs text-gray-500">
                                    Precio facturado:
                                    {{ $producto->precioAlternativo == floor($producto->precioAlternativo) 
                    ? intval($producto->precioAlternativo) 
                    : number_format($producto->precioAlternativo, 2, '.', '') }} Bs
                                </span>
                                @endif
                            </div>
                        </td>


                        <td class="px-4 py-2">
                            <span class="{{ $producto->estado == 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $producto->estado == 0 ? 'Inactivo' : 'Activo' }}
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            @if($producto->existencias->isEmpty())
                            N/A
                            @else
                            @foreach($producto->existencias as $existencia)
                            <div class="text-sm text-cyan-700">
                                {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}: {{ $existencia->cantidad }}
                            </div>
                            @endforeach
                            @endif
                        </td>
                        <td class="px-4 py-2 flex justify-center gap-1">
                            <button wire:click="modaldetalle({{ $producto->id }})" class="btn-cyan" title="Ver Detalles">Ver más</button>
                            <button wire:click="abrirModal('edit', {{ $producto->id }})" class="btn-cyan" title="Editar">Editar</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-gray-600">No hay registros de productos.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($modal)
    <div class="modal-overlay">
        <div class="modal-box max-w-3xl">
            <div class="modal-content flex flex-col gap-4">
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
                <div>
                    <label class="text-u">Nombre (Requerido)</label>
                    <input wire:model="descripcion" class="input-minimal" placeholder="Descripción">
                </div>

                <div>
                    <label>Unidad</label>
                    <input wire:model="unidad" class="input-minimal" placeholder="Litros, ml, etc">
                </div>

                <div>
                    <label>Tipo de Contenido</label>
                    <input wire:model="tipoContenido" class="input-minimal" placeholder="Contenido">
                </div>
                <div>
                    <label class="text-u">Tipo de Producto (Requerido)</label>

                    @php
                    $tiposProducto = [
                    0 => 'Agua',
                    1 => 'Agua saborizada',
                    2 => 'Botellones',
                    3 => 'Hielos',
                    4 => 'Otros',
                    ];
                    @endphp

                    <div class="flex flex-wrap justify-center gap-2 mt-2">
                        @foreach($tiposProducto as $key => $label)
                        <button
                            type="button"
                            wire:click="$set('tipoProducto', {{ $key }})"
                            class="px-4 py-2 rounded-full text-sm transition
                {{ $tipoProducto == $key
                    ? 'bg-cyan-600 text-white shadow-md scale-105'
                    : 'bg-cyan-100 text-cyan-700 hover:bg-cyan-200' }}">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>



                <div>
                    <label>Tipo / Material</label>
                    <input wire:model="tipo" class="input-minimal" placeholder="Material">
                </div>

                <div>
                    <label >Capacidad</label>
                    <input type="number" wire:model="capacidad" class="input-minimal" min="0" step="1"
                        placeholder="Litros">
                </div>

                <div>
                    <label>Paquete</label>
                    <input wire:model="paquete" class="input-minimal" placeholder="Paquete(s)">
                </div>

                <div>
                    <label class="text-u">Precio de Referencia (Bs)</label>
                    <input type="number" wire:model="precioReferencia" class="input-minimal" step="0.5" min="0">
                </div>

                <div>
                    <label>Precio Alternativo (Bs)</label>
                    <input type="number"
                        wire:model="precioAlternativo"
                        class="input-minimal"
                        step="0.01"
                        min="0"
                        placeholder="Precio facturado / variable">
                </div>


                <div>
                    <label>Observaciones</label>
                    <textarea wire:model="observaciones" class="input-minimal" rows="2"></textarea>
                </div>

                <div>
                    <label>Cantidad Mínima</label>
                    <input type="number" wire:model="cantidadMinima" class="input-minimal" min="0">
                </div>
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
                            <span>Unidad:</span><span>{{ $productoSeleccionado->unidad ?? '-' }}</span>
                        </div>
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
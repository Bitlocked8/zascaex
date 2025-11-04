<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <h3 class="inline-block bg-teal-700 text-white px-5 py-2 rounded-full text-xl font-bold uppercase shadow-md">
            Otros
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="search" placeholder="Buscar por nombre o descripción..." class="input-minimal w-full" />
            <button wire:click="abrirModal('create')" class="btn-cyan">
                Añadir
            </button>
        </div>

        @forelse($otros as $otro)
        <div class="card-teal flex flex-col gap-4 p-4 shadow rounded-lg">
            <div class="flex flex-col gap-2">
                <p class="text-u">{{ $otro->descripcion ?? 'Sin descripción' }}</p>
                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold uppercase
                    {{ $otro->estado ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white' }}">
                    {{ $otro->estado ? 'Activo' : 'Inactivo' }}
                </span>
                <p><strong>Unidad:</strong> {{ $otro->unidad ?? 'N/A' }}</p>
                <p><strong>Capacidad:</strong> {{ $otro->capacidad ?? 'N/A' }}</p>
                <p><strong>Precio Ref.:</strong> {{ $otro->precioReferencia ?? 'N/A' }} Bs</p>

                @if($otro->existencias->isEmpty())
                <span class="inline-block bg-gray-400 text-white px-3 py-1 rounded-full text-sm font-semibold uppercase">
                    Sin existencias
                </span>
                @else
                @foreach($otro->existencias as $existencia)
                <span class="inline-block bg-cyan-600 text-white px-3 py-1 rounded-full text-sm font-semibold uppercase">
                    {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}
                    ({{ $existencia->cantidad }} / mín: {{ $existencia->cantidadMinima ?? 0 }})
                </span>
                @endforeach
                @endif
            </div>

            <div class="flex justify-center items-center mt-2">
                @if($otro->imagen)
                <img src="{{ asset('storage/'.$otro->imagen) }}" alt="Imagen" class="w-24 h-24 object-cover rounded-lg border border-cyan-300">
                @else
                <div class="w-24 h-24 flex items-center justify-center bg-gray-200 text-gray-500 rounded-lg text-xs">
                    Sin imagen
                </div>
                @endif
            </div>

            <div class="flex gap-2 overflow-x-auto no-scrollbar pt-2 justify-start md:justify-end">
                <button wire:click="abrirModal('edit', {{ $otro->id }})" class="btn-cyan">Editar</button>
                <button wire:click="modaldetalle({{ $otro->id }})" class="btn-cyan">Detalles</button>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-4 text-gray-600">
            No hay registros de Otros.
        </div>
        @endforelse
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
                        <img src="{{ $imagen ? $imagen->temporaryUrl() : asset('storage/'.$imagenExistente) }}"
                            class="w-full max-w-xl h-auto object-contain rounded-xl shadow-md border border-cyan-300">
                    </div>
                    @endif
                </div>

                <div>
                    <label class="text-u">Nombre (Requerido)</label>
                    <input wire:model="descripcion" class="input-minimal" placeholder="Descripción">
                </div>
                <div>
                    <label>Unidad (Opcional)</label>
                    <input wire:model="unidad" class="input-minimal" placeholder="Litros, ml, etc">
                </div>
                <div>
                    <label class="text-u">Tipo de Contenido (Requerido)</label>
                    <input wire:model="tipoContenido" class="input-minimal" placeholder="Contenido">
                </div>
                <div>
                    <label class="text-u">Tipo de Producto (Requerido)</label>
                    <input wire:model="tipoProducto" class="input-minimal" placeholder="Botella, botellón, etc">
                </div>
                <div>
                    <label>Tipo / Material (Opcional)</label>
                    <input wire:model="tipo" class="input-minimal" placeholder="Material">
                </div>
                <div>
                    <label class="text-u">Capacidad (Requerido)</label>
                    <input type="number" wire:model="capacidad" class="input-minimal" min="0" step="0.01" placeholder="Litros">
                </div>
                <div>
                    <label>Paquete (Opcional)</label>
                    <input wire:model="paquete" class="input-minimal" placeholder="Paquete(s)">
                </div>
                <div>
                    <label class="text-u">Precio de Referencia (Bs) (Requerido)</label>
                    <input type="number" wire:model="precioReferencia" class="input-minimal" step="0.01" min="0">
                </div>
                <div>
                    <label>Observaciones (Opcional)</label>
                    <textarea wire:model="observaciones" class="input-minimal" rows="2"></textarea>
                </div>
                <div>
                    <label>Cantidad Mínima (Opcional)</label>
                    <input type="number" wire:model="cantidadMinima" class="input-minimal" min="0">
                </div>
                <div>
                    <label>Estado</label>
                    <div class="flex flex-wrap justify-center gap-2 mt-2">
                        @foreach([1 => 'Activo', 0 => 'Inactivo'] as $key => $label)
                        <button type="button" wire:click="$set('estado', {{ $key }})"
                            class="px-4 py-2 rounded-full text-sm flex items-center justify-center transition
                            {{ $estado == $key ? 'bg-cyan-600 text-white shadow-md' : 'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer flex gap-2 justify-end mt-4">
                <button type="button" wire:click="cerrarModal" class="btn-cyan">Cerrar</button>
                <button type="button" wire:click="guardar" class="btn-cyan">Guardar</button>
            </div>
        </div>
    </div>
    @endif

    @if($modalDetalle && $otroSeleccionado)
    <div class="modal-overlay">
        <div class="modal-box max-w-3xl">
            <div class="modal-content flex flex-col gap-6">
                <div class="flex justify-center items-center">
                    @if($otroSeleccionado->imagen)
                    <img src="{{ asset('storage/'.$otroSeleccionado->imagen) }}" class="w-full max-w-xl h-auto object-contain rounded-xl shadow-md">
                    @else
                    <span class="badge-info">Sin imagen</span>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="flex flex-col gap-3">
                        <div class="flex gap-2"><span>Descripción:</span><span>{{ $otroSeleccionado->descripcion ?? '-' }}</span></div>
                        <div class="flex gap-2"><span>Unidad:</span><span>{{ $otroSeleccionado->unidad ?? '-' }}</span></div>
                        <div class="flex gap-2"><span>Capacidad:</span><span>{{ $otroSeleccionado->capacidad ?? '-' }}</span></div>
                        <div class="flex gap-2"><span>Tipo de Contenido:</span><span>{{ $otroSeleccionado->tipoContenido ?? '-' }}</span></div>
                        <div class="flex gap-2"><span>Tipo de Producto:</span><span>{{ $otroSeleccionado->tipoProducto ?? '-' }}</span></div>
                    </div>
                    <div class="flex flex-col gap-3">
                        <div class="flex gap-2"><span>Paquete:</span><span>{{ $otroSeleccionado->paquete ?? '-' }}</span></div>
                        <div class="flex gap-2"><span>Precio Ref.:</span><span>{{ $otroSeleccionado->precioReferencia ?? '-' }}</span></div>
                        <div class="flex gap-2"><span>Cantidad mínima:</span><span>{{ $otroSeleccionado->cantidadMinima ?? '-' }}</span></div>
                        <div class="flex gap-2"><span>Tipo / Material:</span><span>{{ $otroSeleccionado->tipo ?? '-' }}</span></div>
                    </div>
                </div>
                <div class="flex gap-2 text-sm"><span>Estado:</span>
                    <span class="{{ $otroSeleccionado->estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $otroSeleccionado->estado ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                <div><span>Observaciones:</span>
                    <div class="bg-gray-100 rounded p-2 text-sm text-gray-700">{{ $otroSeleccionado->observaciones ?? 'Sin observaciones' }}</div>
                </div>
            </div>
            <div class="modal-footer flex justify-end mt-4">
                <button wire:click="$set('modalDetalle', false)" class="btn-cyan">Cerrar</button>
            </div>
        </div>
    </div>
    @endif
</div>

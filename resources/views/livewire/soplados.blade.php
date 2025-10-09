<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Barra de búsqueda y botón de crear -->
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por código..."
                class="input-minimal w-full" />

            <button wire:click="abrirModal('create')" class="btn-circle btn-cyan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 5v14m-7-7h14" />
                </svg>
            </button>
        </div>

        <!-- Lista de soplados -->
        @forelse($soplados as $soplado)
        <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
            <div class="flex flex-col col-span-9 space-y-1 text-left">
                <p><strong>Código Soplado:</strong> {{ $soplado->codigo }}</p>
                <p><strong>Código Asignación:</strong> {{ $soplado->asignado->codigo ?? 'N/A' }}</p>
                <p><strong>Item:</strong> {{ $soplado->asignado->existencia->existenciable->descripcion ?? 'N/A' }}</p>
                <p><strong>Cantidad:</strong> {{ $soplado->cantidad }}</p>
                <p><strong>Merma:</strong> {{ $soplado->merma }}</p>
                <p><strong>Estado:</strong>
                    @if($soplado->estado == 0) En proceso
                    @elseif($soplado->estado == 1) Revisado
                    @else Confirmado
                    @endif
                </p>
                <p><strong>Observaciones:</strong> {{ $soplado->observaciones ?? 'N/A' }}</p>
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($soplado->fecha)->format('d/m/Y H:i') }}</p>
            </div>

            <div class="flex flex-col items-end gap-4 col-span-3">
                <!-- Editar -->
                <button wire:click="abrirModal('edit', {{ $soplado->id }})" class="btn-circle btn-cyan" title="Editar Soplado">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 20h16M12 4v16" />
                    </svg>
                </button>

                <!-- Detalle -->
                <button wire:click="modalDetalle({{ $soplado->id }})" class="btn-circle btn-cyan" title="Ver Detalle">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5" />
                    </svg>
                </button>
                <button wire:click="eliminar({{ $soplado->id }})"
                    onclick="confirm('¿Seguro que quieres eliminar este soplado?') || event.stopImmediatePropagation()">
                    Eliminar
                </button>

            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-4 text-gray-600">
            No hay soplados registrados.
        </div>
        @endforelse
    </div>
    @if($modal)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">

                @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">¡Atención!</strong>
                    <span class="block sm:inline">Debes corregir los siguientes errores:</span>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="grid grid-cols-1 gap-2 mt-2">
                    <p class="font-semibold text-sm">
                        Código: <span class="font-normal">{{ $codigo }}</span>
                    </p>
                    <p class="font-semibold text-sm">
                        Fecha:
                        <span class="font-normal">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</span>
                    </p>

                    <!-- Selección de Asignación -->
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Asignación (Preforma)</label>
                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                            @foreach($asignaciones as $asignado)
                            @php
                            $tipo = class_basename($asignado->existencia->existenciable_type);
                            $cantidadDisponible = $asignado->cantidad;
                            @endphp
                            <button type="button"
                                wire:click="$set('asignado_id', {{ $asignado->id }})"
                                class="w-full px-3 py-2 rounded-md border text-sm text-left flex justify-between items-center transition
                            {{ $asignado_id == $asignado->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                <span>{{ $tipo }}: {{ $asignado->existencia->existenciable->descripcion }}</span>
                                <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                    Disponible: {{ $cantidadDisponible }}
                                </span>
                            </button>
                            @endforeach
                        </div>
                        @error('asignado_id') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Selección de Existencia Destino -->
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Destino (Existencia)</label>
                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                            @foreach($existenciasDestino as $existencia)
                            @php
                            $tipoDestino = class_basename($existencia->existenciable_type);
                            @endphp
                            <button type="button"
                                wire:click="$set('existencia_destino_id', {{ $existencia->id }})"
                                class="w-full px-3 py-2 rounded-md border text-sm text-left flex justify-between items-center transition
                            {{ $existencia_destino_id == $existencia->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                <span>{{ $tipoDestino }}: {{ $existencia->existenciable->descripcion }}</span>
                                <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                    Disponible: {{ $existencia->cantidad }}
                                </span>
                            </button>
                            @endforeach
                        </div>
                        @error('existencia_destino_id') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm">Cantidad a producir</label>
                        <input type="number" wire:model="cantidad" class="input-minimal" min="1">
                        @error('cantidad') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm">Merma</label>
                        <input type="number" wire:model="merma" class="input-minimal" min="0">
                        @error('merma') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm">Observaciones</label>
                        <input type="text" wire:model="observaciones" class="input-minimal">
                    </div>

                    <div>
                        <label class="font-semibold text-sm">Estado</label>
                        <select wire:model="estado" class="input-minimal w-full">
                            <option value="0">En proceso</option>
                            <option value="1">Revisado</option>
                            <option value="2">Confirmado</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer flex gap-2 mt-4">
                    <button type="button" wire:click="guardar" class="btn-circle btn-cyan" title="Guardar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M14 4l0 4l-6 0l0 -4" />
                        </svg>
                    </button>

                    <button type="button" wire:click="cerrarModal" class="btn-circle btn-cyan" title="Cerrar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <path d="M10 10l4 4m0 -4l-4 4" />
                            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                        </svg>
                    </button>

                </div>

            </div>
        </div>
    </div>
    @endif



</div>
<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3 class="inline-block bg-teal-700 text-white px-5 py-2 rounded-full text-xl font-bold uppercase shadow-md">
            Llenados
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="search" placeholder="Buscar por código..." class="input-minimal w-full" />
            <button wire:click="abrirModal('create')" class="btn-cyan flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor">
                    <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                </svg>
                Añadir
            </button>
        </div>

        @forelse($llenados as $llenado)
        <div class="card-teal flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <p class="text-u">{{ $llenado->codigo }}</p>
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($llenado->fecha)->format('d/m/Y H:i') }}</p>

                <p><strong>Base:</strong> {{ $llenado->asignadoBase->existencia->existenciable->descripcion ?? 'N/A' }}</p>
                <p><strong>Tapa:</strong> {{ $llenado->asignadoTapa->existencia->existenciable->descripcion ?? 'N/A' }}</p>
                <p><strong>Destino:</strong> {{ $llenado->existenciaDestino->existenciable->descripcion ?? 'N/A' }}</p>

                <p><strong>Cantidad:</strong> {{ $llenado->cantidad }}</p>
                <p><strong>Merma Base:</strong> {{ $llenado->merma_base ?? 0 }}</p>
                <p><strong>Merma Tapa:</strong> {{ $llenado->merma_tapa ?? 0 }}</p>
                <p><strong>Personal:</strong> {{ $llenado->personal->nombre ?? 'N/A' }}</p>
                <p><strong>Observaciones:</strong> {{ $llenado->observaciones ?? 'N/A' }}</p>

                <p><strong>Estado:</strong>
                    <span class="inline-block px-2 py-1 rounded-full text-sm font-semibold
            {{ $llenado->estado == 0 ? 'bg-yellow-600 text-white' : ($llenado->estado == 1 ? 'bg-blue-600 text-white' : 'bg-green-600 text-white') }}">
                        {{ $llenado->estado == 0 ? 'Pendiente' : ($llenado->estado == 1 ? 'En Proceso' : 'Finalizado') }}
                    </span>
                </p>
            </div>

            <div class="flex gap-2 overflow-x-auto no-scrollbar border-t border-gray-200 pt-3 pb-1 justify-start md:justify-between">
                <button wire:click="abrirModal('edit', {{ $llenado->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Editar">
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

                <button wire:click="verDetalleLlenado({{ $llenado->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Ver Detalle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 2l.117 .007a1 1 0 0 1 .876 .876l.007 .117v4l.005 .15a2 2 0 0 0 1.838 1.844l.157 .006h4l.117 .007a1 1 0 0 1 .876 .876l.007 .117v9a3 3 0 0 1 -2.824 2.995l-.176 .005h-10a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-14a3 3 0 0 1 2.824 -2.995l.176 -.005zm3 14h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2m0 -4h-6a1 1 0 0 0 0 2h6a1 1 0 0 0 0 -2" />
                        <path d="M19 7h-4l-.001 -4.001z" />
                    </svg>
                    Detalles
                </button>

                @if($llenado->estado != 2)
                <button wire:click="confirmarEliminarLlenado({{ $llenado->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Eliminar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 7h16" />
                        <path d="M10 11v6" />
                        <path d="M14 11v6" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7V4a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg>
                    Eliminar
                </button>
                @endif
            </div>
        </div>

        @empty
        <div class="col-span-full text-center py-4 text-gray-600">
            No hay llenados registrados.
        </div>
        @endforelse
    </div>




    @if($modal)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">

                <div class="grid grid-cols-1 gap-2 mt-2">
                    <p class="text-u">Código: <span class="font-normal">{{ $codigo }}</span></p>
                    <p class="text-u">Fecha:
                        <span class="font-normal">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</span>
                    </p>
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Asignación (Base)</label>

                        @if($accion === 'edit')
                        @php
                        // Primero buscar en la colección (si está disponible por filtros),
                        // si no, buscar directamente en la BD para mostrar lo usado.
                        $asBase = $asignacionesBase->firstWhere('id', $asignado_base_id)
                        ?? \App\Models\Asignado::with('existencia.existenciable', 'existencia.sucursal')->find($asignado_base_id);

                        $tipoBase = $asBase ? class_basename($asBase->existencia->existenciable_type) : 'Desconocido';
                        @endphp

                        <p class="flex flex-col items-center gap-2 p-4 rounded-lg border-2 bg-white text-gray-800 text-center">
                            <span class="font-medium">
                                {{ $tipoBase }}: {{ $asBase->existencia->existenciable->descripcion ?? ('Asignado #' . $asignado_base_id) }}
                            </span>
                            <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                Disponible: {{ $asBase->cantidad ?? 0 }}
                            </span>
                            <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                {{ $asBase->existencia->sucursal->nombre ?? 'Sin sucursal' }}
                            </span>
                        </p>
                        @else

                        <div class="flex-1">
                            <label for="busquedaAsignacionBase" class="block text-sm font-medium text-gray-700">
                                Buscar base
                            </label>
                            <input
                                id="busquedaAsignacionBase"
                                type="search"
                                wire:model.live="busquedaAsignacionBase"
                                class="input-minimal"
                                placeholder="Buscar base..." />
                        </div>

                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                            @forelse($asignacionesBase as $asignado)
                            @php
                            $tipo = class_basename($asignado->existencia->existenciable_type);
                            $disabled = isset($asignado->existencia->existenciable->estado) && !$asignado->existencia->existenciable->estado;
                            @endphp

                            <button type="button"
                                wire:click="$set('asignado_base_id', {{ $asignado->id }})"
                                class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                        {{ $asignado_base_id == $asignado->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}
                        bg-white">

                                <span class="text-u">
                                    {{ $tipo }}: {{ $asignado->existencia->existenciable->descripcion ?? 'Asignado #' . $asignado->id }}
                                </span>

                                <div class="flex flex-wrap justify-center gap-3 mt-2">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-xs font-medium text-gray-600">
                                            {{ $asignado->existencia->sucursal->nombre ?? 'Sin sucursal' }}
                                        </span>
                                        <span class="bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                                            {{ $asignado->cantidad }} Disponibles
                                        </span>
                                    </div>
                                </div>
                            </button>
                            @empty
                            <p class="text-gray-500 text-sm text-center py-2 col-span-full">
                                No hay asignaciones disponibles
                            </p>
                            @endforelse
                        </div>
                        @endif
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-2 block">Asignación (Tapa)</label>

                        @if($accion === 'edit')
                        @php
                        // Buscar primero en la colección (filtrada), o directamente en la BD si no se encuentra
                        $asTapa = $asignacionesTapa->firstWhere('id', $asignado_tapa_id)
                        ?? \App\Models\Asignado::with('existencia.existenciable', 'existencia.sucursal')->find($asignado_tapa_id);

                        $tipoTapa = $asTapa ? class_basename($asTapa->existencia->existenciable_type) : 'Desconocido';
                        @endphp

                        <p class="flex flex-col items-center gap-2 p-4 rounded-lg border-2 bg-white text-gray-800 text-center">
                            <span class="font-medium">
                                {{ $tipoTapa }}: {{ $asTapa->existencia->existenciable->descripcion ?? ('Asignado #' . $asignado_tapa_id) }}
                            </span>
                            <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                Disponible: {{ $asTapa->cantidad ?? 0 }}
                            </span>
                            <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                {{ $asTapa->existencia->sucursal->nombre ?? 'Sin sucursal' }}
                            </span>
                        </p>
                        @else
                        <div class="flex-1">
                            <label for="busquedaAsignacionTapa" class="block text-sm font-medium text-gray-700">
                                Buscar tapa
                            </label>
                            <input
                                id="busquedaAsignacionTapa"
                                type="search"
                                wire:model.live="busquedaAsignacionTapa"
                                class="input-minimal"
                                placeholder="Buscar tapa..." />
                        </div>

                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                            @forelse($asignacionesTapa as $asignado)
                            @php
                            $tipo = class_basename($asignado->existencia->existenciable_type);
                            $disabled = isset($asignado->existencia->existenciable->estado) && !$asignado->existencia->existenciable->estado;
                            @endphp

                            <button type="button"
                                wire:click="$set('asignado_tapa_id', {{ $asignado->id }})"
                                class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                        {{ $asignado_tapa_id == $asignado->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}
                        bg-white">

                                <span class="text-u">
                                    {{ $tipo }}: {{ $asignado->existencia->existenciable->descripcion ?? 'Asignado #' . $asignado->id }}
                                </span>

                                <div class="flex flex-wrap justify-center gap-3 mt-2">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-xs font-medium text-gray-600">
                                            {{ $asignado->existencia->sucursal->nombre ?? 'Sin sucursal' }}
                                        </span>
                                        <span class="bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                                            {{ $asignado->cantidad }} Disponibles
                                        </span>
                                    </div>
                                </div>
                            </button>
                            @empty
                            <p class="text-gray-500 text-sm text-center py-2 col-span-full">
                                No hay tapas disponibles
                            </p>
                            @endforelse
                        </div>
                        @endif
                    </div>



                    <div>
                        <label class="font-semibold text-sm mb-2 block">Destino (Producto lleno)</label>
                        @if($accion === 'edit')
                        @php
                        $destinoSel = $existenciasDestino->firstWhere('id', $existencia_destino_id);
                        @endphp

                        <p class="flex flex-col items-center gap-2 p-4 rounded-lg border-2 bg-white text-gray-800 text-center">
                            <span class="font-medium">
                                {{ $destinoSel->existenciable->descripcion ?? 'Destino #' . $existencia_destino_id }}
                            </span>
                            <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                {{ $destinoSel->sucursal->nombre ?? 'Sin sucursal' }}
                            </span>
                        </p>
                        @else
                        <div class="flex-1">
                            <label for="busquedaDestino" class="block text-sm font-medium text-gray-700">
                                Buscar destino
                            </label>
                            <input
                                id="busquedaDestino"
                                type="search"
                                wire:model.live="busquedaDestino"
                                class="input-minimal"
                                placeholder="Buscar producto lleno..." />
                        </div>
                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                            @foreach($existenciasDestino as $existencia)
                            @php
                            $disabled = isset($existencia->existenciable->estado) && !$existencia->existenciable->estado;
                            @endphp

                            <button type="button"
                                wire:click="$set('existencia_destino_id', {{ $existencia->id }})"
                                class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                  {{ $existencia_destino_id == $existencia->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}
                                     bg-white">

                                <span class="text-u">
                                    {{ $existencia->existenciable->descripcion ?? 'Destino #' . $existencia->id }}
                                </span>

                                <div class="flex flex-wrap justify-center gap-3 mt-2">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-xs font-medium text-gray-600">
                                            {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}
                                        </span>
                                        @if(isset($existencia->cantidad))
                                        <span class="bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                                            {{ $existencia->cantidad }} en stock
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-2 block">Personal encargado </label>
                        <select wire:model="personal_id" class="input-minimal w-full">
                            <option value="">Seleccionar personal</option>
                            @foreach($personales as $p)
                            <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 gap-2">
                        <div>
                            <label class="font-semibold text-sm">Cantidad producida (Opcional)</label>
                            <input type="number" wire:model="cantidad" class="input-minimal" min="1" placeholder="Ej. 250">
                        </div>


                    </div>

                    <div class="grid grid-cols-1 gap-2">
                        <label class="font-semibold text-sm">Mermas calculadas (Automatico)</label>
                        <div class="flex flex-col text-sm bg-gray-100 border border-gray-300 rounded-lg p-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Base:</span>
                                <span class="font-semibold text-cyan-700">
                                    {{ number_format($merma_base ?? 0, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-gray-600">Tapa:</span>
                                <span class="font-semibold text-cyan-700">
                                    {{ number_format($merma_tapa ?? 0, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="font-semibold text-sm">Observaciones</label>
                        <input type="text" wire:model="observaciones" class="input-minimal">
                    </div>

                    {{-- Estado --}}
                    <div>
                        <label class="font-semibold text-sm">Estado</label>
                        <select wire:model="estado" class="input-minimal w-full">
                            <option value="0">En proceso</option>
                            <option value="1">Revisado</option>
                            <option value="2">Confirmado</option>
                        </select>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="modal-footer">
                    <button wire:click="guardar" class="btn-circle btn-cyan" title="Guardar">💾</button>
                    <button wire:click="cerrarModal" class="btn-circle btn-cyan" title="Cerrar">❌</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
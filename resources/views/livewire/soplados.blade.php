<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por código..."
                class="input-minimal w-full" />

            <button wire:click="abrirModal('create')" class="btn-circle btn-cyan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                    <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                </svg>
            </button>
        </div>
        @forelse($soplados as $soplado)
        <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
            <div class="flex flex-col col-span-9 space-y-1 text-left">
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($soplado->fecha)->format('d/m/Y H:i') }}</p>
                <p><strong>Código Soplado:</strong> {{ $soplado->codigo }}</p>
                <p><strong>Código Asignación:</strong> {{ $soplado->asignado->codigo ?? 'N/A' }}</p>
                <p><strong>Item:</strong> {{ $soplado->asignado->existencia->existenciable->descripcion ?? 'N/A' }}</p>
                <p><strong>Cantidad:</strong> {{ $soplado->cantidad }}</p>
                <p><strong>Merma:</strong> {{ $soplado->merma }}</p>
                <p><strong>Observaciones:</strong> {{ $soplado->observaciones ?? 'N/A' }}</p>
                @php
                $montoUsado = 0;
                $montoMerma = 0;

                if($soplado->asignado) {
                foreach ($soplado->asignado->reposiciones as $reposicion) {
                $precioUnitario = $reposicion->cantidad_inicial > 0
                ? $reposicion->comprobantes->sum('monto') / $reposicion->cantidad_inicial
                : 0;

                // Cantidad usada en soplado
                $montoUsado += $precioUnitario * $soplado->cantidad;

                // Merma
                $montoMerma += $precioUnitario * ($soplado->merma ?? 0);
                }
                }
                @endphp

                <span class="inline-block bg-cyan-700 text-white px-3 py-1 rounded-full text-sm font-semibold uppercase">
                    Monto usado: {{ number_format($montoUsado, 2, ',', '.') }} Bs
                </span>

                <span class="inline-block bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold uppercase">
                    Monto merma: {{ number_format($montoMerma, 2, ',', '.') }} Bs
                </span>


                <p>
                    <strong>Estado:</strong>
                    <span class="
                         {{ $soplado->estado == 0 ? 'bg-yellow-600 text-white' : '' }}
                         {{ $soplado->estado == 1 ? 'bg-blue-600 text-white' : '' }}
                         {{ $soplado->estado == 2 ? 'bg-green-600 text-white' : '' }}
                            font-semibold px-2 py-1 rounded-full">
                        {{ $soplado->estado == 0 ? 'Pendiente' : ($soplado->estado == 1 ? 'En Proceso' : 'Finalizado') }}
                    </span>
                </p>

            </div>
            <div class="flex flex-col items-end gap-4 col-span-3">
                <button wire:click="abrirModal('edit', {{ $soplado->id }})" class="btn-circle btn-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 10a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M6 4v4" />
                        <path d="M6 12v8" />
                        <path d="M13.199 14.399a2 2 0 1 0 -1.199 3.601" />
                        <path d="M12 4v10" />
                        <path d="M12 18v2" />
                        <path d="M16 7a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M18 4v1" />
                        <path d="M18 9v2.5" />
                        <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M19.001 15.5v1.5" />
                        <path d="M19.001 21v1.5" />
                        <path d="M22.032 17.25l-1.299 .75" />
                        <path d="M17.27 20l-1.3 .75" />
                        <path d="M15.97 17.25l1.3 .75" />
                        <path d="M20.733 20l1.3 .75" />
                    </svg>
                </button>
                <button wire:click="verDetalleSoplado({{ $soplado->id }})"
                    class="btn-circle btn-cyan"
                    title="Ver Detalle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <circle cx="12" cy="12" r="2" />
                        <path d="M22 12c0 5.523 -4.477 10 -10 10s-10 -4.477 -10 -10s4.477 -10 10 -10s10 4.477 10 10z" />
                    </svg>
                </button>



                <button wire:click="confirmarEliminarSoplado({{ $soplado->id }})" class="btn-circle btn-cyan" title="Eliminar soplado">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 7h16" />
                        <path d="M10 11v6" />
                        <path d="M14 11v6" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg>
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

                {{-- ALERTA GENERAL DE ERRORES --}}
                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">¡Atención!</strong>
                    <span class="block sm:inline">Debes corregir los siguientes errores:</span>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
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
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Asignación (Preforma)</label>

                        @if($accion === 'edit')
                        @php
                        $as = $asignaciones->firstWhere('id', $asignado_id);
                        $tipo = $as ? class_basename($as->existencia->existenciable_type) : 'Desconocido';
                        @endphp
                        <p class="flex items-center gap-2">
                            <span>{{ $tipo }}: {{ $as->existencia->existenciable->descripcion ?? 'Asignado #' . $asignado_id }}</span>
                            <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                Disponible: {{ $as->cantidad ?? 0 }}
                            </span>
                            <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                {{ $as->existencia->sucursal->nombre ?? 'Sin sucursal' }}
                            </span>
                        </p>
                        @else
                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                            @foreach($asignaciones as $asignado)
                            @php
                            $tipo = class_basename($asignado->existencia->existenciable_type);
                            $disabled = isset($asignado->existencia->existenciable->estado) && !$asignado->existencia->existenciable->estado;
                            @endphp
                            <button
                                type="button"
                                wire:click="$set('asignado_id', {{ $asignado->id }})"
                                class="w-full px-3 py-2 rounded-md border text-sm text-left flex justify-between items-center transition
                           {{ $asignado_id == $asignado->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}
                           {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if($disabled) disabled @endif>
                                <span>{{ $tipo }}: {{ $asignado->existencia->existenciable->descripcion ?? 'Asignado #' . $asignado->id }}</span>
                                <span class="flex items-center gap-2">
                                    <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        Disponible: {{ $asignado->cantidad }}
                                    </span>
                                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        {{ $asignado->existencia->sucursal->nombre ?? 'Sin sucursal' }}
                                    </span>
                                </span>
                            </button>
                            @endforeach
                        </div>
                        @endif

                        @error('asignado_id')
                        <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>


                    <div>
                        <label class="font-semibold text-sm mb-2 block">Destino (Existencia)</label>

                        @if($accion === 'edit')
                        @php
                        $ex = $existenciasDestino->firstWhere('id', $existencia_destino_id);
                        $tipo = $ex ? class_basename($ex->existenciable_type) : 'Desconocido';
                        @endphp
                        <p class="flex items-center gap-2">
                            <span>{{ $tipo }}: {{ $ex->existenciable->descripcion ?? 'Existencia #' . $existencia_destino_id }}</span>
                            <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                Disponible: {{ $ex->cantidad ?? 0 }}
                            </span>
                            <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                {{ $ex->sucursal->nombre ?? 'Sin sucursal' }}
                            </span>
                        </p>
                        @else
                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                            @foreach($existenciasDestino as $existencia)
                            @php
                            $tipo = class_basename($existencia->existenciable_type);
                            $disabled = isset($existencia->existenciable->estado) && !$existencia->existenciable->estado;
                            @endphp
                            <button
                                type="button"
                                wire:click="$set('existencia_destino_id', {{ $existencia->id }})"
                                class="w-full px-3 py-2 rounded-md border text-sm text-left flex justify-between items-center transition
                           {{ $existencia_destino_id == $existencia->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}
                           {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if($disabled) disabled @endif>
                                <span>{{ $tipo }}: {{ $existencia->existenciable->descripcion ?? 'Existencia #' . $existencia->id }}</span>
                                <span class="flex items-center gap-2">
                                    <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        Disponible: {{ $existencia->cantidad }}
                                    </span>
                                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}
                                    </span>
                                </span>
                            </button>
                            @endforeach
                        </div>
                        @endif

                        @error('existencia_destino_id')
                        <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>


                    {{-- Cantidad a producir --}}
                    <div>
                        <label class="font-semibold text-sm">Cantidad a producir</label>
                        <input type="number" wire:model="cantidad" class="input-minimal" min="1">
                        @error('cantidad')
                        <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Merma --}}
                    <div>
                        <label class="font-semibold text-sm">Merma</label>
                        <input type="number" wire:model="merma" class="input-minimal" min="0">
                        @error('merma')
                        <span class="text-red-500">{{ $message }}</span>
                        @enderror
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

                <div class="modal-footer">
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

    @if($modalDetalle && $sopladoSeleccionado)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">

                <!-- Icono con inicial -->
                <div class="flex justify-center items-center">
                    <div class="w-20 h-20 rounded-full bg-cyan-600 text-white flex items-center justify-center text-2xl font-bold">
                        {{ strtoupper(substr($sopladoSeleccionado->codigo ?? '-', 0, 1)) }}
                    </div>
                </div>

                <!-- Información del Soplado -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Código:</span>
                            <span class="badge-info">{{ $sopladoSeleccionado->codigo ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Fecha:</span>
                            <span class="badge-info">{{ \Carbon\Carbon::parse($sopladoSeleccionado->fecha)->format('d/m/Y H:i') ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Estado:</span>
                            <span class="px-2 py-1 rounded-full font-semibold text-sm
        {{ $sopladoSeleccionado->estado == 0 ? 'bg-yellow-600 text-white' : '' }}
        {{ $sopladoSeleccionado->estado == 1 ? 'bg-blue-600 text-white' : '' }}
        {{ $sopladoSeleccionado->estado == 2 ? 'bg-green-600 text-white' : '' }}">
                                {{ $sopladoSeleccionado->estado == 0 ? 'Pendiente' : ($sopladoSeleccionado->estado == 1 ? 'En proceso' : 'Finalizado') }}
                            </span>
                        </div>

                    </div>

                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Responsable:</span>
                            <span class="badge-info">{{ $sopladoSeleccionado->personal?->nombres ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Reposición:</span>
                            <span class="badge-info">{{ $sopladoSeleccionado->reposicion?->codigo ?? 'Sin reposición' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Cantidades y Asignación/Destino -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="flex flex-col gap-2">
                        <span class="label-info">Cantidad:</span>
                        <span class="badge-info">{{ $sopladoSeleccionado->cantidad ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="label-info">Merma:</span>
                        <span class="badge-info">{{ $sopladoSeleccionado->merma ?? '-' }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="flex flex-col gap-2">
                        <span class="label-info">Asignado desde:</span>
                        <span class="badge-info">
                            {{ $sopladoSeleccionado->asignado?->existencia?->existenciable?->descripcion ?? '-' }}
                        </span>
                    </div>

                    <div class="flex flex-col gap-2">
                        <span class="label-info">Destino:</span>
                        <span class="badge-info">
                            {{ $sopladoSeleccionado->existencia?->existenciable?->descripcion ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col gap-2 mt-4">
                    <span class="label-info">Observaciones:</span>
                    <span class="badge-info">{{ $sopladoSeleccionado->observaciones ?? '-' }}</span>
                </div>

            </div>

            <!-- Footer -->
            <div class="modal-footer mt-4">
                <button wire:click="cerrarModalDetalle" class="btn-circle btn-cyan" title="Cerrar">
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




    @if($confirmingDeleteSopladoId)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content">
                <div class="flex flex-col gap-4 text-center">
                    <h2 class="text-lg font-semibold">¿Estás seguro?</h2>
                    <p class="text-gray-600">
                        El registro de soplado seleccionado se eliminará y se revertirán las cantidades utilizadas.
                    </p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" wire:click="eliminarSopladoConfirmado" class="btn-circle btn-cyan" title="Confirmar eliminación">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="currentColor">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M18.333 2c1.96 0 3.56 1.537 3.662 3.472l.005 .195v12.666c0 1.96 -1.537 3.56 -3.472 3.662l-.195 .005h-12.666a3.667 3.667 0 0 1 -3.662 -3.472l-.005 -.195v-12.666c0 -1.96 1.537 -3.56 3.472 -3.662l.195 -.005h12.666zm-2.626 7.293a1 1 0 0 0 -1.414 0l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.32 1.497l2 2l.094 .083a1 1 0 0 0 1.32 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                    </svg>
                </button>

                <button type="button" wire:click="$set('confirmingDeleteSopladoId', null)" class="btn-circle btn-cyan" title="Cancelar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 10l4 4m0-4l-4 4" />
                        <circle cx="12" cy="12" r="9" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif




</div>
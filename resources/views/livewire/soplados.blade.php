<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3 class="inline-block bg-teal-700 text-white px-5 py-2 rounded-full text-xl font-bold uppercase shadow-md">
            Soplados
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por código o asignación..."
                class="input-minimal w-full" />

            <button wire:click="abrirModal('create')" class="btn-cyan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                    <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                </svg>
                Añadir
            </button>
        </div>

        @forelse($soplados as $soplado)
        @php
        $montoUsado = 0;
        $montoMerma = 0;

        if ($soplado->asignado) {
        foreach ($soplado->asignado->reposiciones as $reposicion) {
        $precioUnitario = $reposicion->cantidad_inicial > 0
        ? $reposicion->comprobantes->sum('monto') / $reposicion->cantidad_inicial
        : 0;
        $montoUsado += $precioUnitario * $soplado->cantidad;
        $montoMerma += $precioUnitario * ($soplado->merma ?? 0);
        }
        }
        @endphp

        <div class="card-teal flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <p class="text-u">{{ $soplado->codigo }}</p>
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($soplado->fecha)->format('d/m/Y H:i') }}</p>
                <p><strong>Código Asignación:</strong> {{ $soplado->asignado->codigo ?? 'N/A' }}</p>
                <p><strong>Item:</strong> {{ $soplado->asignado->existencia->existenciable->descripcion ?? 'N/A' }}</p>
                <p><strong>Cantidad:</strong> {{ $soplado->cantidad }}</p>
                <p><strong>Merma:</strong> {{ $soplado->merma }}</p>
                <p><strong>Observaciones:</strong> {{ $soplado->observaciones ?? 'N/A' }}</p>

                <div class="mt-2 border-t border-gray-200 pt-2 text-sm">
                    <div class="flex justify-between font-semibold text-cyan-700">
                        <span>Monto usado:</span>
                        <span>{{ number_format($montoUsado, 2, ',', '.') }} Bs</span>
                    </div>
                    <div class="flex justify-between font-semibold text-red-600">
                        <span>Monto merma:</span>
                        <span>{{ number_format($montoMerma, 2, ',', '.') }} Bs</span>
                    </div>
                </div>

                <p class="mt-1">
                    <strong>Estado:</strong>
                    <span class="
            {{ $soplado->estado == 0 ? 'bg-yellow-600 text-white' : '' }}
            {{ $soplado->estado == 1 ? 'bg-blue-600 text-white' : '' }}
            {{ $soplado->estado == 2 ? 'bg-green-600 text-white' : '' }}
            font-semibold px-2 py-1 rounded-full text-sm">
                        {{ $soplado->estado == 0 ? 'Pendiente' : ($soplado->estado == 1 ? 'En Proceso' : 'Finalizado') }}
                    </span>
                </p>
            </div>

            <div class="flex gap-2 overflow-x-auto no-scrollbar border-t border-gray-200 pt-3 pb-1 justify-start md:justify-between">
                <button wire:click="abrirModal('edit', {{ $soplado->id }})"
                    class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Editar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                        <path d="M16 5l3 3" />
                    </svg>
                    Editar
                </button>

                <button wire:click="verDetalleSoplado({{ $soplado->id }})"
                    class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Ver detalle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="12" cy="12" r="2" />
                        <path d="M22 12c0 5.523 -4.477 10 -10 10s-10 -4.477 -10 -10s4.477 -10 10 -10s10 4.477 10 10z" />
                    </svg>
                    Ver
                </button>

                @if($soplado->estado != 2)
                <button wire:click="confirmarEliminarSoplado({{ $soplado->id }})"
                    class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Eliminar soplado">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <path d="M4 7h16" />
                        <path d="M10 11v6" />
                        <path d="M14 11v6" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg>
                    Eliminar
                </button>
                @endif
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
                    <span class="text-u">{{ $codigo }}</span>

                    <span class="text-u"> fecha soplado: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</span>

                    <div>
                        <label class="font-semibold text-sm mb-2 block">Asignación (Preforma)</label>

                        @if($accion === 'edit')
                        @php
                        $as = $asignaciones->firstWhere('id', $asignado_id);
                        $tipo = $as ? class_basename($as->existencia->existenciable_type) : 'Desconocido';
                        @endphp
                        <p class="flex flex-col md:flex-row md:items-center gap-2 p-4 rounded-lg border-2 bg-white text-gray-800">
                            <span class="font-medium text-u">
                                {{ $tipo }}: {{ $as->existencia->existenciable->descripcion ?? 'Asignado #' . $asignado_id }}
                            </span>
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
                            <button type="button"
                                wire:click="$set('asignado_id', {{ $asignado->id }})"
                                class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                 {{ $asignado_id == $asignado->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}
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

                            @endforeach
                        </div>
                        @endif

                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Destino (Existencia)</label>

                        @if($accion === 'edit')
                        @php
                        $ex = $existenciasDestino->firstWhere('id', $existencia_destino_id);
                        $tipo = $ex ? class_basename($ex->existenciable_type) : 'Desconocido';
                        @endphp
                        <p class="flex flex-col md:flex-row md:items-center gap-2 p-4 rounded-lg border-2 bg-white text-gray-800">
                            <span class="font-medium text-u">
                                {{ $tipo }}: {{ $ex->existenciable->descripcion ?? 'Existencia #' . $existencia_destino_id }}
                            </span>
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
                                class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                     {{ $existencia_destino_id == $existencia->id ? 'border-cyan-600 text-cyan-600 bg-cyan-50' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 hover:bg-cyan-50' }}
                                      {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if($disabled) disabled @endif>
                                <span class="text-u font-medium">
                                    {{ $tipo }}: {{ $existencia->existenciable->descripcion ?? 'Existencia #' . $existencia->id }}
                                </span>
                                <div class="flex flex-wrap justify-center gap-3 mt-2">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                                            Disponible: {{ $existencia->cantidad }}
                                        </span>
                                    </div>
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="bg-gray-700 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                                            {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}
                                        </span>
                                    </div>
                                </div>
                            </button>

                            @endforeach
                        </div>
                        @endif
                        @error('existencia_destino_id')
                        <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="font-semibold text-sm">Cantidad a producir</label>
                        <input type="number" wire:model="cantidad" class="input-minimal" placeholder="ingrese la cantidad que se obtuvo">
                    </div>
                    <div>
                        <label class="font-semibold text-sm">Merma</label>
                        <input type="number" wire:model="merma" class="input-minimal" placeholder="se genera automaticamente">

                    </div>
                    <div>
                        <label class="font-semibold text-sm">Observaciones</label>
                        <input type="text" wire:model="observaciones" class="input-minimal" placeholder="Observaciones">
                    </div>
                    <div class="text-center">
                        <label class="font-semibold text-sm mb-2 block">Estado</label>

                        <div class="flex justify-center flex-wrap gap-3">
                            <button
                                type="button"
                                wire:click="$set('estado', 0)"
                                class="btn-cyan flex items-center gap-1 {{ $estado == 0 ? 'ring-2 ring-cyan-200' : 'opacity-40' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <circle cx="10" cy="10" r="9" />
                                    <polyline points="10 5 10 10 13 12" />
                                </svg>
                                En proceso
                            </button>

                            <button
                                type="button"
                                wire:click="$set('estado', 1)"
                                class="btn-cyan flex items-center gap-1 {{ $estado == 1 ? 'ring-2 ring-cyan-200' : 'opacity-40' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <path d="M4 12l4 4L18 6" />
                                </svg>
                                Revisado
                            </button>

                            <button
                                type="button"
                                wire:click="$set('estado', 2)"
                                class="btn-cyan flex items-center gap-1 {{ $estado == 2 ? 'ring-2 ring-cyan-200' : 'opacity-40' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                    <path d="M16 4L4 16" />
                                    <path d="M4 4l12 12" />
                                </svg>
                                Confirmado
                            </button>
                        </div>
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
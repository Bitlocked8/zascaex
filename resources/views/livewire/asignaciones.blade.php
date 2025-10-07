<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input
                type="text"
                wire:model.live="searchCodigo"
                placeholder="Buscar por código..."
                class="input-minimal w-full" />

            <button wire:click="abrirModal" class="btn-circle btn-cyan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                    <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                </svg>
            </button>
        </div>

        @forelse($asignaciones as $asignado)
        <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
            <div class="flex flex-col col-span-9 space-y-1 text-left">
                <p><strong>Código:</strong> {{ $asignado->codigo ?? 'N/A' }}</p>
                <p><strong>Item:</strong> {{ $asignado->existencia->existenciable->descripcion ?? 'N/A' }}</p>
                <p><strong>Cantidad original:</strong> {{ $asignado->cantidad_original ?? $asignado->cantidad }}</p>
                <p><strong>Cantidad:</strong> {{ $asignado->cantidad }}</p>
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($asignado->fecha)->format('d/m/Y H:i') }}</p>

                <p><strong>Observaciones:</strong> {{ $asignado->observaciones ?? 'N/A' }}</p>
                @if(isset($asignado->cantidad_original) && $asignado->cantidad_original != $asignado->cantidad)
                <p class="text-sm text-orange-600">
                    ⚠ Se eliminó un lote, la cantidad actual es menor que la original, se recomienda eliminar y crear uno nuevo.
                </p>
                @endif
            </div>

            <div class="flex flex-col items-end gap-4 col-span-3">
                <button wire:click="abrirModal('edit', {{ $asignado->id }})"
                    class="btn-circle btn-cyan">
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
                <button wire:click="modaldetalle({{ $asignado->id }})" class="btn-circle btn-cyan"
                    title="Ver Detalle">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9h.01" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 12h1v4h1" />
                    </svg>
                </button>
                <button wire:click="confirmarEliminarAsignacion({{ $asignado->id }})" class="btn-circle btn-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 7l16 0" />
                        <path d="M10 11l0 6" />
                        <path d="M14 11l0 6" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg>
                </button>

            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-4 text-gray-600">
            No hay asignaciones registradas.
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
                        <span class="font-normal">
                            {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}
                        </span>
                    </p>
                    <p class="font-semibold text-sm">
                        Personal: <span class="font-normal">
                            {{ optional(\App\Models\Personal::find($personal_id))->nombres ?? 'Sin nombre' }}
                        </span>
                    </p>
                    <p class="font-semibold text-sm">
                        Sucursal: <span class="font-normal">
                            {{ optional($existencias->firstWhere('id', $existencia_id))->sucursal->nombre ?? 'Sin sucursal' }}
                        </span>
                    </p>

                    <div>
                        <label class="font-semibold text-sm mb-2 block">Producto</label>

                        @if($accion === 'edit')
                        @php
                        $ex = $existencias->firstWhere('id', $existencia_id);
                        $tipo = $ex ? class_basename($ex->existenciable_type) : 'Desconocido';
                        $cantidadDisponible = $ex
                        ? $ex->reposiciones->where('estado_revision', true)->sum('cantidad')
                        : 0;
                        @endphp
                        <p class="flex items-center gap-2">
                            <span>{{ $tipo }}: {{ $ex->existenciable->descripcion ?? 'Existencia #' . $existencia_id }}</span>
                            <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                Disponible: {{ $cantidadDisponible }}
                            </span>
                            <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                {{ $ex->sucursal->nombre ?? 'Sin sucursal' }}
                            </span>
                        </p>
                        @else
                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                            @foreach($existencias as $existencia)
                            @php
                            $tipo = class_basename($existencia->existenciable_type);
                            $cantidadDisponible = $existencia->reposiciones->where('estado_revision', true)->sum('cantidad');
                            @endphp
                            <button type="button"
                                wire:click="$set('existencia_id', {{ $existencia->id }})"
                                class="w-full px-3 py-2 rounded-md border text-sm text-left flex justify-between items-center transition
                        {{ $existencia_id == $existencia->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                <span>{{ $tipo }}: {{ $existencia->existenciable->descripcion ?? 'Existencia #' . $existencia->id }}</span>
                                <span class="flex items-center gap-2">
                                    <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        Disponible: {{ $cantidadDisponible }}
                                    </span>
                                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}
                                    </span>
                                </span>
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>


                    <div>
                        <label class="font-semibold text-sm">Cantidad</label>
                        @if($accion === 'edit')
                        <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">{{ $cantidad }}</span>
                        @else
                        <input type="number" wire:model="cantidad" class="input-minimal" min="1">
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                    <div>
                        <label class="font-semibold text-sm">Motivo</label>
                        <input type="text" wire:model="motivo" class="input-minimal">
                    </div>

                    <div>
                        <label class="font-semibold text-sm">Observaciones</label>
                        <input type="text" wire:model="observaciones" class="input-minimal">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" wire:click="guardarAsignacion" class="btn-circle btn-cyan" title="Guardar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M14 4l0 4l-6 0l0 -4" />
                        </svg>
                    </button>
                    <button type="button" wire:click="cerrarModal" class="btn-circle btn-cyan" title="Cerrar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

    @if($modalError)
    <div wire:click.self="$set('modalError', false)"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white text-red-700 rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <h2 class="text-lg font-semibold mb-4">Error</h2>
            <p>{{ $mensajeError }}</p>

        </div>
    </div>
    @endif
    @if($confirmingDeleteAsignacionId)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content">
                <div class="flex flex-col gap-4 text-center">
                    <h2 class="text-lg font-semibold">¿Estás seguro?</h2>
                    <p class="text-gray-600">
                        La asignación seleccionada se eliminará y se restaurarán los lotes.
                    </p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" wire:click="eliminarAsignacionConfirmado" class="btn-circle btn-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M18.333 2c1.96 0 3.56 1.537 3.662 3.472l.005 .195v12.666c0 1.96 -1.537 3.56 -3.472 3.662l-.195 .005h-12.666a3.667 3.667 0 0 1 -3.662 -3.472l-.005 -.195v-12.666c0 -1.96 1.537 -3.56 3.472 -3.662l.195 -.005h12.666zm-2.626 7.293a1 1 0 0 0 -1.414 0l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.32 1.497l2 2l.094 .083a1 1 0 0 0 1.32 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                    </svg>
                </button>

                <button type="button" wire:click="$set('confirmingDeleteAsignacionId', null)" class="btn-circle btn-cyan">
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
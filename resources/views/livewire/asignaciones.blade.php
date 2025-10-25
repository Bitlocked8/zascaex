<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3 class="inline-block bg-teal-700 text-white px-5 py-2 rounded-full text-xl font-bold uppercase shadow-md">
            Asignaciones
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input
                type="text"
                wire:model.live="searchCodigo"
                placeholder="Buscar por código..."
                class="input-minimal w-full" />
            <button wire:click="abrirModal" class="btn-cyan flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                    <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                </svg>
                Añadir
            </button>
        </div>

        @forelse($asignaciones as $asignado)
        @php
        $montoAsignado = 0;
        foreach ($asignado->reposiciones as $reposicion) {
        $cantidadUsada = $reposicion->pivot->cantidad;
        foreach ($reposicion->comprobantes as $comprobante) {
        $precioUnitario = $reposicion->cantidad_inicial > 0
        ? $comprobante->monto / $reposicion->cantidad_inicial
        : 0;
        $montoAsignado += $precioUnitario * $cantidadUsada;
        }
        }
        @endphp

        <div class="card-teal flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <p class="text-u">{{ $asignado->codigo ?? 'N/A' }}</p>
                <p><strong>Item:</strong> {{ $asignado->existencia->existenciable->descripcion ?? 'N/A' }}</p>
                <p><strong>Cantidad original:</strong> {{ $asignado->cantidad_original ?? $asignado->cantidad }}</p>
                <p><strong>Cantidad:</strong> {{ $asignado->cantidad }}</p>
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($asignado->fecha)->format('d/m/Y H:i') }}</p>
                <p><strong>Observaciones:</strong> {{ $asignado->observaciones ?? 'N/A' }}</p>

                <span class="inline-block bg-cyan-700 text-white px-3 py-1 rounded-full text-sm font-semibold uppercase">
                    Monto: {{ number_format($montoAsignado, 2) }} Bs
                </span>

                @if(isset($asignado->cantidad_original) && $asignado->cantidad_original != $asignado->cantidad)
                <span class="inline-block bg-emerald-600 text-white px-3 py-1 rounded-full text-sm font-semibold uppercase">
                    Fue usada en soplado
                </span>
                @endif
            </div>

            <div class="flex flex-wrap justify-center md:justify-center gap-2 border-t border-gray-200 pt-3 pb-2">
                <button wire:click="abrirModal('edit', {{ $asignado->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Editar">
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

                <button wire:click="modaldetalle({{ $asignado->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Ver detalle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9h.01" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 12h1v4h1" />
                    </svg>
                    Detalles
                </button>

                @if($asignado->cantidad > 0)
                <button wire:click="confirmarEliminarAsignacion({{ $asignado->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Eliminar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 7l16 0" />
                        <path d="M10 11l0 6" />
                        <path d="M14 11l0 6" />
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
                        <span class="text-u">{{ $codigo }}</span>
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

                    <div class="grid grid-cols-1 gap-2 mt-2">
                        <div>
                            <label class="text-u">Producto (Requerido)</label>

                            @if($accion === 'edit')
                            @php
                            $ex = $existencias->firstWhere('id', $existencia_id);
                            $tipo = $ex ? class_basename($ex->existenciable_type) : 'Desconocido';
                            $cantidadDisponible = $ex
                            ? $ex->reposiciones->where('estado_revision', true)->sum('cantidad')
                            : 0;
                            @endphp

                            <p class="flex flex-col items-center gap-2 p-4 rounded-lg border-2 bg-white text-gray-800 text-center">
                                <span class="text-u">
                                    {{ $tipo }}: {{ $ex->existenciable->descripcion ?? ('Existencia #' . $existencia_id) }}
                                </span>

                                <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                    Disponible: {{ $cantidadDisponible }}
                                </span>

                                <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                    {{ $ex->sucursal->nombre ?? 'Sin sucursal' }}
                                </span>
                            </p>
                            @else
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Sucursal</label>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button"
                                        wire:click="filtrarSucursalModal(null)"
                                        class="px-3 py-1 rounded-full text-sm font-medium border 
                {{ $filtroSucursalModal === null ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-blue-100' }}">
                                        Todas
                                    </button>
                                    @foreach($sucursales as $sucursal)
                                    <button type="button"
                                        wire:click="filtrarSucursalModal({{ $sucursal->id }})"
                                        class="px-3 py-1 rounded-full text-sm font-medium border 
                {{ $filtroSucursalModal == $sucursal->id ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-blue-100' }}">
                                        {{ $sucursal->nombre }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar existencia</label>
                                <input type="text" wire:model.live="searchExistencia"
                                    class="input-minimal w-full" placeholder="Escribe la descripción..." />
                            </div>

                            <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                                @forelse($existencias as $existencia)
                                @php
                                $tipo = class_basename($existencia->existenciable_type);
                                $cantidadDisponible = $existencia->reposiciones->where('estado_revision', true)->sum('cantidad');
                                @endphp

                                <button
                                    type="button"
                                    wire:click="$set('existencia_id', {{ $existencia->id }})"
                                    class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                            {{ $existencia_id == $existencia->id
                                ? 'border-cyan-600 text-cyan-600'
                                : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}
                            bg-white">

                                    <span class="text-u">
                                        {{ $tipo }}: {{ $existencia->existenciable->descripcion ?? 'Existencia #' . $existencia->id }}
                                    </span>

                                    <div class="flex flex-wrap justify-center gap-3 mt-2">
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="text-xs font-medium text-gray-600">
                                                {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}
                                            </span>
                                            <span class="bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                                                {{ $cantidadDisponible }} Disponibles
                                            </span>
                                        </div>
                                    </div>
                                </button>
                                @empty
                                <p class="text-gray-500 text-sm text-center py-2 col-span-full">
                                    No hay productos disponibles
                                </p>
                                @endforelse
                            </div>
                            @endif
                        </div>
                    </div>



                    <div>
                        <label class=" text-u">Cantidad (Requerido)</label>
                        @if($accion === 'edit')
                        <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">{{ $cantidad }}</span>
                        @else
                        <input type="number" wire:model="cantidad" class="input-minimal" min="1" placeholder="Cantidad">
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                    <div>
                        <label class="font-semibold text-sm">Motivo (Opcional)</label>
                        <input type="text" wire:model="motivo" class="input-minimal" placeholder="Motivo de la asignacion del material">
                    </div>

                    <div>
                        <label class="font-semibold text-sm">Observaciones (Opcional)</label>
                        <input type="text" wire:model="observaciones" class="input-minimal" placeholder="Observacion de la asignacion">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" wire:click="cerrarModal" class="btn-cyan" title="Cerrar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M10 10l4 4m0 -4l-4 4" />
                            <circle cx="12" cy="12" r="9" />
                        </svg>
                        CERRAR
                    </button>
                    <button type="button" wire:click="guardarAsignacion" class="btn-cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
    @if($modalDetalle && $asignacionSeleccionada)
    <div class="modal-overlay" wire:ignore.self>
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">

                <!-- Icono con inicial -->
                <div class="flex justify-center items-center">
                    <div class="w-20 h-20 rounded-full bg-cyan-600 text-white flex items-center justify-center text-2xl font-bold">
                        {{ strtoupper(substr($asignacionSeleccionada->codigo ?? '-', 0, 1)) }}
                    </div>
                </div>

                <!-- Información principal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Código:</span>
                            <span class="badge-info">{{ $asignacionSeleccionada->codigo ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Fecha:</span>
                            <span class="badge-info">
                                {{ \Carbon\Carbon::parse($asignacionSeleccionada->fecha)->format('d/m/Y H:i') ?? '-' }}
                            </span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Motivo:</span>
                            <span class="badge-info">{{ $asignacionSeleccionada->motivo ?? 'Sin motivo' }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Responsable:</span>
                            <span class="badge-info">{{ $asignacionSeleccionada->personal?->nombres ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Sucursal:</span>
                            <span class="badge-info">{{ $asignacionSeleccionada->existencia?->sucursal?->nombre ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="flex flex-col gap-2">
                        <span class="label-info">Cantidad original:</span>
                        <span class="badge-info">{{ $asignacionSeleccionada->cantidad_original ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="label-info">Cantidad actual:</span>
                        <span class="badge-info">
                            {{ $asignacionSeleccionada->cantidad ?? '-' }}
                            @if($asignacionSeleccionada->cantidad < $asignacionSeleccionada->cantidad_original)
                                <span class="text-xs text-emerald-600 font-semibold">(Usada en proceso)</span>
                                @endif
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="flex flex-col gap-2">
                        <span class="label-info">Item asignado:</span>
                        <span class="badge-info">
                            {{ $asignacionSeleccionada->existencia?->existenciable?->descripcion ?? '-' }}
                        </span>
                    </div>

                    <div class="flex flex-col gap-2">
                        <span class="label-info">Reposiciones usadas:</span>
                        <div class="badge-info">
                            @forelse($asignacionSeleccionada->reposiciones as $repo)
                            <div class="flex justify-between border-b border-gray-200 py-1">
                                <span>{{ $repo->codigo ?? 'Sin código' }}</span>
                                <span class="text-sm">x{{ $repo->pivot->cantidad }}</span>
                            </div>
                            @empty
                            <span>Sin reposiciones</span>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-2 mt-4">
                    <span class="label-info">Observaciones:</span>
                    <span class="badge-info">{{ $asignacionSeleccionada->observaciones ?? '-' }}</span>
                </div>
            </div>
            <div class="modal-footer mt-4">
                <button wire:click="cerrarModalDetalle" class="btn-cyan" title="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M10 10l4 4m0 -4l-4 4" />
                        <circle cx="12" cy="12" r="9" />
                    </svg>
                    CERRAR
                </button>
            </div>
        </div>
    </div>
    @endif



</div>
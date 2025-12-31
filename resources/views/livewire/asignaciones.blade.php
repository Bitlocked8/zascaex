<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3
            class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto">
            Asignaciones
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="searchCodigo" placeholder="Buscar por código..."
                class="input-minimal" />
            <button wire:click="abrirModal" class="btn-cyan" title="Agregar">
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

        @forelse($asignaciones as $asignado)
            @php
                $cantidadPivote = $asignado->reposiciones->sum(fn($r) => $r->pivot->cantidad);

            @endphp

            <div class="card-teal flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <div class="flex flex-wrap gap-2 mb-2">
                        @if($asignado->soplados()->exists())
                            <span class="bg-emerald-100 text-emerald-700 font-bold px-3 py-1 rounded-full inline-block">
                                Usado en Soplado
                            </span>
                        @endif

                        @if($asignado->llenados()->exists())
                            <span class="bg-blue-100 text-blue-700 font-bold px-3 py-1 rounded-full inline-block">
                                Usado en Llenado
                            </span>
                        @endif

                        @if($asignado->traspasos()->exists())
                            <span class="bg-orange-100 text-orange-700 font-bold px-3 py-1 rounded-full inline-block">
                                Usado en Traspaso
                            </span>
                        @endif
                    </div>

                    <p class="text-emerald-600 uppercase font-semibold">{{ $asignado->codigo ?? 'N/A' }}</p>

                    @foreach($asignado->reposiciones as $reposicion)
                        <p class="text-slate-600">
                            {{ class_basename($reposicion->existencia->existenciable ?? '') }}:
                            {{ $reposicion->existencia->existenciable->descripcion ?? 'N/A' }}
                            (Cantidad restante: {{ $reposicion->pivot->cantidad }})
                        </p>

                        <p class="text-slate-600">
                            {{ class_basename($reposicion->existencia->existenciable ?? '') }}:
                            {{ $reposicion->existencia->existenciable->descripcion ?? 'N/A' }}
                            ( Cantidad Asignada: {{ $reposicion->pivot->cantidad_original }})
                        </p>
                    @endforeach

                    <p><strong>Fecha de asignacion:</strong>
                        {{ \Carbon\Carbon::parse($asignado->fecha)->format('d/m/Y H:i') }}</p>
                    <p><strong>Cantidad de material combinado:</strong> {{ $asignado->cantidad }}</p>


                </div>

                <div class="flex flex-wrap justify-center md:justify-center gap-2 border-t border-gray-200 pt-3 pb-2">
                    <button wire:click="modaldetalle({{ $asignado->id }})" class="btn-cyan" title="Ver detalle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M8 12l0 .01" />
                            <path d="M12 12l0 .01" />
                            <path d="M16 12l0 .01" />
                        </svg>
                        Ver más
                    </button>

                    @if($cantidadPivote > 0)
                        <button wire:click="abrirModal('edit', {{ $asignado->id }})" class="btn-cyan" title="Editar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke="none" d="M0 0h24v24H0z" />
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                <path d="M16 5l3 3" />
                            </svg>
                            Editar
                        </button>

                        <button wire:click="confirmarEliminarAsignacion({{ $asignado->id }})" class="btn-cyan" title="Eliminar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
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
            <div class="modal-box max-w-4xl">
                <div class="modal-content flex flex-col gap-4">

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
                            <strong class="font-bold">¡Atención!</strong>
                            <span class="block sm:inline">Debes corregir los siguientes errores:</span>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                        <p class="text-u">
                            Código: <span>{{ $codigo }}</span>
                        </p>
                        <p class="font-semibold text-sm">
                            Fecha: <span>{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</span>
                        </p>
                        <p class="font-semibold text-sm">
                            Personal:
                            <span>{{ optional(\App\Models\Personal::find($personal_id))->nombres ?? 'Sin nombre' }}</span>
                        </p>
                    </div>
                    @php $usuario = auth()->user(); @endphp
                    @if($usuario && $usuario->rol_id === 1)
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold mb-2">Filtrar por Sucursal</label>

                                    <div class="flex flex-wrap justify-center gap-3 mt-2">
                                        <button type="button" wire:click="filtrarSucursalModal(null)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                        {{ $filtroSucursalModal === null
                            ? 'bg-cyan-600 text-white border-cyan-700 shadow-md'
                            : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                                            Todas
                                        </button>
                                        @foreach($sucursales as $sucursal)
                                                        <button type="button" wire:click="filtrarSucursalModal({{ $sucursal->id }})" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                                            {{ $filtroSucursalModal == $sucursal->id
                                                ? 'bg-cyan-600 text-white border-cyan-700 shadow-md'
                                                : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                                                            {{ $sucursal->nombre }}
                                                        </button>
                                        @endforeach

                                    </div>


                                    <div class="mt-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar existencia</label>
                                        <input type="text" wire:model.live="searchExistencia" class="input-minimal w-full"
                                            placeholder="Escribe la descripción...">
                                    </div>
                                </div>
                    @endif

                    <div class="border rounded-lg p-3 bg-white">
                        <h3 class="text-center font-semibold mb-3">Selecciona los productos a asignar</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 max-h-[300px] overflow-y-auto">
                            @forelse($existencias as $existencia)
                              @php
    $tipo = class_basename($existencia->existenciable_type);
    $cantidadDisponible = $existencia->reposiciones->where('estado_revision', true)->sum('cantidad');
    $descripcion = $existencia->existenciable->descripcion ?? 'Existencia #' . $existencia->id;

    $selectedIndex = collect($items)->search(fn($i) => $i['existencia_id'] === $existencia->id);
    $selected = $selectedIndex !== false;

    $sucursal = $existencia->sucursal->nombre ?? 'Sin sucursal';

    $color = $tipo === 'Tapa' ? ($existencia->existenciable->color ?? null) : null;
@endphp

                                <div
                                    class="p-4 rounded-lg border-2 transition text-center bg-white {{ $selected ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 hover:border-cyan-600 hover:text-cyan-600' }}">
                                    <span class="block text-u">{{ $tipo }}: {{ $descripcion }}</span>
                                    <span class="block text-xs mt-1 text-gray-500">
                                        Sucursal: <strong>{{ $sucursal }}</strong>
                                    </span>
@if($color)
    <span class="block text-xs mt-1">
        Color:
        <strong>{{ ucfirst($color) }}</strong>
    </span>
@endif
                                    <span
                                        class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold inline-block mt-2">
                                        {{ $cantidadDisponible }} disponibles
                                    </span>

                                    <div class="mt-3 flex justify-center items-center gap-2">
                                        @if($selected)
                                            <input type="number" class="input-minimal w-20 text-center"
                                                wire:model="items.{{ $selectedIndex }}.cantidad" min="1"
                                                max="{{ $cantidadDisponible }}">
                                            <button wire:click="quitarExistencia({{ $existencia->id }})"
                                                class="text-red-600 font-semibold text-sm hover:text-red-800">
                                                Quitar
                                            </button>
                                        @else
                                            <button wire:click="agregarExistencia({{ $existencia->id }})" class="btn-cyan text-xs">
                                                + Agregar
                                            </button>
                                        @endif
                                    </div>

                                </div>

                            @empty
                                <p class="text-gray-500 text-sm text-center col-span-full py-4">
                                    No hay existencias disponibles.
                                </p>
                            @endforelse
                        </div>
                    </div>


                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-4">
                        <div>
                            <label class="font-semibold text-sm">Motivo (Opcional)</label>
                            <input type="text" wire:model="motivo" class="input-minimal" placeholder="Motivo">
                        </div>

                        <div>
                            <label class="font-semibold text-sm">Observaciones (Opcional)</label>
                            <input type="text" wire:model="observaciones" class="input-minimal" placeholder="Observaciones">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button wire:click="cerrarModal" class="btn-cyan" title="Cerrar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                                <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
                            </svg>
                            CERRAR
                        </button>
                        <button wire:click="guardarAsignacion" class="btn-cyan">
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

    @if($modalDetalle && $asignacionSeleccionada)
        <div class="modal-overlay" wire:ignore.self>
            <div class="modal-box">
                <div class="modal-content flex flex-col gap-4">

                    <!-- Icono con inicial -->
                    <div class="flex justify-center items-center">
                        <div
                            class="w-20 h-20 rounded-full bg-cyan-600 text-white flex items-center justify-center text-2xl font-bold">
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
                                <span
                                    class="badge-info">{{ $asignacionSeleccionada->existencia?->sucursal?->nombre ?? 'N/A' }}</span>
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                            <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
                        </svg>
                        CERRAR
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($modalEliminar)
        <div class="modal-overlay">
            <div class="modal-box">

                <div class="modal-content">
                    <div class="flex flex-col gap-4 text-center">
                        <h2 class="text-lg font-semibold">¿Eliminar asignación?</h2>
                        <p class="text-gray-600">
                            Esta acción no se puede deshacer.
                        </p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" wire:click="eliminarConfirmado" class="btn-cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <path d="M5 12l5 5l10 -10" />
                        </svg>
                        Confirmar
                    </button>

                    <button type="button" wire:click="$set('modalEliminar', false)" class="btn-cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                        Cancelar
                    </button>
                </div>

            </div>
        </div>
    @endif





</div>
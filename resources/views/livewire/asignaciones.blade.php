<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3
            class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto">
            Asignaciones
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="searchCodigo" placeholder="Buscar por código..."
                class="input-minimal w-full" />
            <button wire:click="abrirModal" class="btn-cyan flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor">
                    <path
                        d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                </svg>
                Añadir
            </button>
        </div>

        @forelse($asignaciones as $asignado)
            @php
                $montoAsignado = $asignado->reposiciones->sum(fn($r) => $r->pivot->cantidad);
            @endphp

            <div class="card-teal flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <p class="text-emerald-600 uppercase font-semibold">{{ $asignado->codigo ?? 'N/A' }}</p>

                    {{-- Mostrar todas las reposiciones asociadas --}}
                    @foreach($asignado->reposiciones as $reposicion)
                        <p class="text-slate-600">
                            {{ class_basename($reposicion->existencia->existenciable ?? '') }}:
                            {{ $reposicion->existencia->existenciable->descripcion ?? 'N/A' }}
                            (Cantidad: {{ $reposicion->pivot->cantidad }})
                        </p>
                    @endforeach

                    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($asignado->fecha)->format('d/m/Y H:i') }}</p>

                    {{-- Ahora que cantidad_original no está en Asignado, mostramos solo la suma de pivot --}}
                    <p><strong>Cantidad total asignada:</strong> {{ $asignado->cantidad }}</p>

                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div
                            class="flex justify-between items-center bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 shadow-sm">
                            <span class="text-sm font-medium text-gray-700">Monto asignado:</span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $montoAsignado > 0 ? number_format($montoAsignado, 2, ',', '.') . ' Bs' : '—' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center md:justify-center gap-2 border-t border-gray-200 pt-3 pb-2">
                    <button wire:click="modaldetalle({{ $asignado->id }})" class="btn-cyan" title="Ver detalle">Ver más</button>

                    @if($asignado->cantidad > 0)
                        <button wire:click="abrirModal('edit', {{ $asignado->id }})"
                            class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Editar">Editar</button>
                        <button wire:click="confirmarEliminarAsignacion({{ $asignado->id }})" class="btn-cyan"
                            title="Eliminar">Eliminar</button>
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

                    {{-- Mensajes de error --}}
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

                    {{-- Datos generales --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                        <p class="font-semibold text-sm">
                            Código: <span class="text-u">{{ $codigo }}</span>
                        </p>
                        <p class="font-semibold text-sm">
                            Fecha: <span class="font-normal">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</span>
                        </p>
                        <p class="font-semibold text-sm">
                            Personal: <span
                                class="font-normal">{{ optional(\App\Models\Personal::find($personal_id))->nombres ?? 'Sin nombre' }}</span>
                        </p>
                    </div>

                    {{-- Filtros --}}
                    <div class="mb-4 border-b pb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Sucursal</label>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" wire:click="filtrarSucursalModal(null)"
                                class="px-3 py-1 rounded-full text-sm font-medium border 
                                {{ $filtroSucursalModal === null ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-blue-100' }}">
                                Todas
                            </button>
                            @foreach($sucursales as $sucursal)
                                <button type="button" wire:click="filtrarSucursalModal({{ $sucursal->id }})"
                                    class="px-3 py-1 rounded-full text-sm font-medium border 
                                            {{ $filtroSucursalModal == $sucursal->id ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-blue-100' }}">
                                    {{ $sucursal->nombre }}
                                </button>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar existencia</label>
                            <input type="text" wire:model.live="searchExistencia" class="input-minimal w-full"
                                placeholder="Escribe la descripción..." />
                        </div>
                    </div>

                    {{-- Lista de existencias --}}
                    <div class="border rounded-lg p-3 bg-gray-50">
                        <h3 class="font-semibold text-gray-800 mb-3 text-center">Selecciona los productos a asignar</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 overflow-y-auto max-h-[300px]">
                            @forelse($existencias as $existencia)
                                @php
                                    $tipo = class_basename($existencia->existenciable_type);
                                    $cantidadDisponible = $existencia->reposiciones->where('estado_revision', true)->sum('cantidad');
                                    $descripcion = $existencia->existenciable->descripcion ?? 'Existencia #' . $existencia->id;

                                    $seleccionadoIndex = collect($items ?? [])->search(fn($item) => $item['existencia_id'] === $existencia->id);
                                    $seleccionado = $seleccionadoIndex !== false;
                                @endphp

                                <div
                                    class="border-2 rounded-lg p-3 text-center transition {{ $seleccionado ? 'border-cyan-600 bg-white shadow' : 'border-gray-200 bg-white hover:border-cyan-400' }}">
                                    <span class="text-u block mb-1">{{ $tipo }}: {{ $descripcion }}</span>
                                    <span
                                        class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold inline-block mb-2">
                                        {{ $cantidadDisponible }} disponibles
                                    </span>

                                    <div class="flex justify-center items-center gap-2">
                                        @if($seleccionado)
                                            <input type="number" wire:model="items.{{ $seleccionadoIndex }}.cantidad" min="1"
                                                max="{{ $cantidadDisponible }}" class="input-minimal w-20 text-center"
                                                placeholder="Cant.">
                                            <button type="button" wire:click="quitarExistencia({{ $existencia->id }})"
                                                class="text-red-500 hover:text-red-700 text-sm font-semibold">
                                                Quitar
                                            </button>
                                        @else
                                            <button type="button" wire:click="agregarExistencia({{ $existencia->id }})"
                                                class="btn-cyan text-xs">
                                                + Agregar
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm text-center col-span-full py-4">
                                    No hay productos disponibles.
                                </p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Campos extra --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                        <div>
                            <label class="font-semibold text-sm">Motivo (Opcional)</label>
                            <input type="text" wire:model="motivo" class="input-minimal"
                                placeholder="Motivo de la asignación">
                        </div>

                        <div>
                            <label class="font-semibold text-sm">Observaciones (Opcional)</label>
                            <input type="text" wire:model="observaciones" class="input-minimal"
                                placeholder="Observaciones adicionales">
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="modal-footer mt-4 flex justify-between">
                        <button type="button" wire:click="cerrarModal" class="btn-cyan">Cerrar</button>
                        <button type="button" wire:click="guardarAsignacion" class="btn-cyan">Guardar</button>
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



</div>
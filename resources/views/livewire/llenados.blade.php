<div class="p-2 mt-20 flex justify-center bg-transparent">
    <div class="w-full max-w-screen-xl">

        <h3 class="text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto mb-4">
            Llenados
        </h3>

        <div class="flex items-center gap-2 mb-4 flex-wrap">
            <input type="text" wire:model.live="search" placeholder="Buscar por código o asignación..."
                class="input-minimal w-full sm:w-auto flex-1" />
            <button wire:click="abrirModal('create')" class="btn-cyan flex items-center gap-1">Añadir</button>
        </div>

        <div class="overflow-auto max-h-[500px] border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-teal-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Código</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Material Destino</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Fecha Llenado</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Cantidad Salida</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Merma</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Estado</th>
                        <th class="px-4 py-2 text-center text-teal-700 font-semibold">Acciones</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($llenados as $llenado)
                    <tr class="hover:bg-teal-50">
                        <td class="px-4 py-2">{{ $llenado->codigo ?? 'N/A' }}</td>
                        <td class="px-4 py-2">
                            {{ class_basename($llenado->existenciaDestino?->existenciable ?? '') }}:
                            {{ $llenado->existenciaDestino?->existenciable?->descripcion ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($llenado->fecha)->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2">{{ $llenado->cantidad }}</td>
                        <td class="px-4 py-2">{{ $llenado->merma }}</td>
                        <td class="px-4 py-2">
                            <span
                                class="{{ $llenado->estado == 0 ? 'text-yellow-600' : '' }} {{ $llenado->estado == 1 ? 'text-blue-600' : '' }} {{ $llenado->estado == 2 ? 'text-green-600' : '' }}">
                                {{ $llenado->estado == 0 ? 'Pendiente' : ($llenado->estado == 1 ? 'En Proceso' : 'Finalizado') }}
                            </span>
                        </td>
                        <td class="px-4 py-2 flex justify-center gap-1">
                            <button wire:click="verDetalleLlenado({{ $llenado->id }})" class="btn-cyan" title="Ver detalle">Ver más</button>
                            <button wire:click="abrirModal('edit', {{ $llenado->id }})" class="btn-cyan" title="Editar">Editar</button>
                            @if($llenado->estado != 2)
                            <button wire:click="confirmarEliminarLlenado({{ $llenado->id }})" class="btn-cyan" title="Eliminar">Eliminar</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-600">No hay llenados registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
                    <span class="text-u"> fecha llenado: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</span>

                    <div>
                        <label class="text-u">Llenado (Elementos Asignados)</label>

                        @if($accion === 'edit')
                        @php
                        $as = collect($asignaciones)->firstWhere('id', $asignado_id) ?? ($llenadoSeleccionado->asignado ?? null);
                        @endphp

                        @if($as && count($as->reposiciones ?? []) > 0)
                        <div
                            class="w-full p-4 rounded-lg border-2 bg-white text-gray-800 flex flex-col gap-4 items-center text-center">
                            @foreach($as->reposiciones as $reposicion)
                            @php
                            $existencia = $reposicion->existencia;
                            $tipo = optional($existencia)->existenciable ? ucfirst(class_basename($existencia->existenciable_type)) : 'Desconocido';
                            $descripcion = optional($existencia->existenciable)->descripcion ?? 'Sin descripción';
                            $pivot = $reposicion->pivot;
                            @endphp
                            <div class="flex flex-col items-center gap-1 border-b border-gray-200 pb-3">
                                <p class="text-lg font-semibold text-u">{{ $tipo }}</p>
                                <p class="text-base text-u">{{ $descripcion }}</p>
                                <p class="text-sm text-gray-700">
                                    <span class="text-semibold">{{ $as->codigo ?? 'N/A' }}</span>
                                </p>
                                <p class="text-sm text-gray-700 mt-1">
                                    <span
                                        class="text-u">{{ optional($existencia->sucursal)->nombre ?? 'Sin sucursal' }}</span>
                                </p>
                                <p class="text-sm text-gray-700">
                                    Cantidad actual: <span class="text-semibold">{{ $pivot->cantidad ?? 0 }}</span>
                                </p>
                                <p class="text-sm text-gray-700">
                                    Cantidad que puede ser producida: <span
                                        class="text-semibold">{{ $pivot->cantidad_original ?? 0 }}</span>
                                </p>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-center text-gray-500 p-2">Asignación de llenado no disponible</p>
                        @endif
                        @else
                        <div class="flex-1 mb-2">
                            <label for="busquedaAsignacion" class="block text-sm font-medium text-gray-700">Buscar
                                Llenado</label>
                            <input id="busquedaAsignacion" type="search" wire:model.live="busquedaAsignacion"
                                class="input-minimal" placeholder="Buscar elementos de llenado..." />
                        </div>

                        @if(count($asignaciones) > 0)
                        <div
                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[250px]">
                            @foreach($asignaciones as $asignado)
                            <button type="button" wire:click="seleccionarAsignacion({{ $asignado->id }})"
                                class="w-full p-4 rounded-lg border-2 transition flex flex-col gap-3 items-center text-center
                                                                                                                            {{ $asignado_id == $asignado->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }} bg-white">

                                @foreach($asignado->reposiciones as $reposicion)
                                @php
                                $existencia = $reposicion->existencia;
                                $tipo = optional($existencia)->existenciable ? ucfirst(class_basename($existencia->existenciable_type)) : 'Desconocido';
                                $descripcion = optional($existencia->existenciable)->descripcion ?? 'Sin descripción';
                                $pivot = $reposicion->pivot;
                                @endphp
                                <div class="flex flex-col items-center gap-1 border-b border-gray-200 pb-3">
                                    <p class="text-lg font-semibold text-u">{{ $tipo }}</p>
                                    <p class="text-base text-u">{{ $descripcion }}</p>
                                    <p class="text-sm text-gray-700">
                                        Cantidad actual: <span class="text-semibold">{{ $pivot->cantidad ?? 0 }}</span>
                                    </p>
                                    <p class="text-sm text-gray-700">
                                        Cantidad original: <span
                                            class="text-semibold">{{ $pivot->cantidad_original ?? 0 }}</span>
                                    </p>
                                    <p class="text-sm text-gray-700">
                                        Sucursal: <span
                                            class="text-u">{{ optional($existencia->sucursal)->nombre ?? 'Sin sucursal' }}</span>
                                    </p>
                                </div>
                                @endforeach

                            </button>
                            @endforeach
                        </div>
                        @else
                        <p class="text-center text-gray-500 p-2">No hay elementos de llenado disponibles.</p>
                        @endif
                        @endif
                    </div>


                    <div>
                        <label class="text-u">Producto (Requerido)</label>
                        <div class="flex-1">
                            <label for="busquedaDestino" class="block text-sm font-medium text-gray-700">
                                Buscar Producto
                            </label>
                            <input id="busquedaDestino" type="search" wire:model.live="busquedaDestino"
                                class="input-minimal" placeholder="Buscar Producto..." />
                        </div>

                        @if($accion === 'edit')
                        @php
                        $ex = $existenciasDestino->firstWhere('id', $existencia_destino_id);
                        $tipo = $ex && $ex->existenciable ? class_basename($ex->existenciable_type) : 'Desconocido';
                        @endphp
                        <p
                            class="flex flex-col md:flex-row md:items-center gap-2 p-4 rounded-lg border-2 bg-white text-gray-800">
                            <span class="font-medium text-u">
                                {{ $tipo }}:
                                {{ optional($ex->existenciable)->descripcion ?? 'Existencia #' . $existencia_destino_id }}
                            </span>
                            <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                Disponible: {{ $ex->cantidad ?? 0 }}
                            </span>
                            <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                {{ optional($ex->sucursal)->nombre ?? 'Sin sucursal' }}
                            </span>
                        </p>
                        @else
                        <div
                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">

                            @foreach($existenciasDestino as $existencia)
                            @php
                            $tipo = optional($existencia->existenciable) ? class_basename($existencia->existenciable_type) : 'Desconocido';
                            $disabled = isset($existencia->existenciable->estado) && !$existencia->existenciable->estado;

                            $tipoContenido = $existencia->existenciable->tipoContenido ?? null;
                            @endphp

                            <button type="button" wire:click="$set('existencia_destino_id', {{ $existencia->id }})"
                                class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                        {{ $existencia_destino_id == $existencia->id
                                        ? 'border-cyan-600 text-cyan-600 bg-cyan-50'
                                        : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 hover:bg-cyan-50'
                                        }}
                                        {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}" @if($disabled) disabled @endif>

                                <span class="text-u font-medium">
                                    {{ $tipo }}:
                                    {{ optional($existencia->existenciable)->descripcion ?? 'Existencia #' . $existencia->id }}
                                </span>

                                {{-- Tipo Contenido (centrado y debajo de la descripción) --}}
                                @if($tipoContenido)
                                <span
                                    class="mt-1 bg-indigo-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                                    {{ $tipoContenido }}
                                </span>
                                @endif

                                <div class="flex flex-wrap justify-center gap-3 mt-2">

                                    <div class="flex flex-col items-center gap-1">
                                        <span
                                            class="bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                                            Disponible: {{ $existencia->cantidad ?? 0 }}
                                        </span>
                                    </div>

                                    <div class="flex flex-col items-center gap-1">
                                        <span
                                            class="bg-gray-700 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                                            {{ optional($existencia->sucursal)->nombre ?? 'Sin sucursal' }}
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
                        <label class="text-u">Cantidad a producir (Requerido)</label>

                        @php
                        $aproximado = 0;
                        if ($accion === 'edit') {
                        $reposiciones = $llenadoSeleccionado->asignado->reposiciones;
                        } else {
                        $asignadoSeleccionado = collect($asignaciones)->firstWhere('id', $asignado_id);
                        $reposiciones = $asignadoSeleccionado ? $asignadoSeleccionado->reposiciones : collect();
                        }

                        if ($reposiciones->isNotEmpty()) {
                        $sumPorTipo = $reposiciones->groupBy(fn($r) => $r->existencia->existenciable_type)
                        ->map(fn($g) => $g->sum(fn($r) => $r->pivot->cantidad_original ?? 0));
                        $aproximado = $sumPorTipo->min();
                        }
                        @endphp

                        <p class="text-sm text-gray-500 mb-1">
                            Aproximado que se puede producir: <span class="font-semibold">{{ $aproximado }}</span>
                        </p>

                        <input type="number" wire:model="cantidad" class="input-minimal"
                            placeholder="Ingrese la cantidad que se obtuvo">
                    </div>

                    <div>
                        <label class="font-semibold text-sm">Merma (Se genera automáticamente)</label>
                        <input type="number" wire:model="merma" class="input-minimal"
                            placeholder="Se genera automáticamente">
                    </div>
                    <div>
                        <label class="font-semibold text-sm">Observaciones (Opcional)</label>
                        <input type="text" wire:model="observaciones" class="input-minimal" placeholder="Observaciones">
                    </div>
                    <div class="text-center">
                        <label class="font-semibold text-sm mb-2 block">Estado</label>

                        <div class="flex flex-col sm:flex-row justify-center flex-wrap gap-3">

                            <!-- En Proceso -->
                            <button type="button" wire:click="$set('estado', 0)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                            {{ $estado === 0
            ? 'bg-cyan-600 text-white border-cyan-700 shadow-md'
            : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300'
                            }}">
                                En proceso
                            </button>

                            <!-- En Revisión -->
                            <button type="button" wire:click="$set('estado', 1)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                            {{ $estado === 1
            ? 'bg-cyan-600 text-white border-cyan-700 shadow-md'
            : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300'
                            }}">
                                En revisión
                            </button>

                            <!-- Confirmado -->
                            <button type="button" wire:click="$set('estado', 2)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                            {{ $estado === 2
            ? 'bg-cyan-600 text-white border-cyan-700 shadow-md'
            : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300'
                            }}">
                                Confirmado
                            </button>

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
                        Guardar</button>
                </div>

            </div>
        </div>
    </div>
    @endif

    @if($confirmingDeleteLlenadoId)
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 text-center">
                ¿Eliminar este llenado?
            </h2>

            <p class="text-gray-600 text-center mb-6">
                Esta acción no se puede deshacer. Se revertirán las cantidades en inventario.
            </p>

            <div class="flex justify-center gap-4">
                {{-- Cancelar --}}
                <button wire:click="$set('confirmingDeleteLlenadoId', null)"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                    Cancelar
                </button>

                {{-- Confirmar eliminación --}}
                <button wire:click="eliminarLlenadoConfirmado"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>
    @endif


    @if($modalDetalle && $llenadoSeleccionado)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">
                <div class="flex justify-center items-center">
                    <div
                        class="w-20 h-20 rounded-full bg-cyan-600 text-white flex items-center justify-center text-2xl font-bold">
                        {{ strtoupper(substr($llenadoSeleccionado->codigo ?? '-', 0, 1)) }}
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Código:</span>
                            <span class="badge-info">{{ $llenadoSeleccionado->codigo ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Fecha:</span>
                            <span
                                class="badge-info">{{ \Carbon\Carbon::parse($llenadoSeleccionado->fecha)->format('d/m/Y H:i') ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Estado:</span>
                            <span
                                class="px-2 py-1 rounded-full font-semibold text-sm{{ $llenadoSeleccionado->estado == 0 ? 'bg-yellow-600 text-white' : '' }}{{ $llenadoSeleccionado->estado == 1 ? 'bg-blue-600 text-white' : '' }}{{ $llenadoSeleccionado->estado == 2 ? 'bg-green-600 text-white' : '' }}">
                                {{ $llenadoSeleccionado->estado == 0 ? 'Pendiente' : ($llenadoSeleccionado->estado == 1 ? 'En proceso' : 'Finalizado') }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Responsable:</span>
                            <span class="badge-info">{{ $llenadoSeleccionado->personal?->nombres ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Reposición:</span>
                            <span
                                class="badge-info">{{ $llenadoSeleccionado->reposicion?->codigo ?? 'Sin reposición' }}</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="flex flex-col gap-2">
                        <span class="label-info">Cantidad:</span>
                        <span class="badge-info">{{ $llenadoSeleccionado->cantidad ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="label-info">Merma:</span>
                        <span class="badge-info">{{ $llenadoSeleccionado->merma ?? '-' }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="flex flex-col gap-2">
                        <span class="label-info">Asignado desde:</span>
                        <span class="badge-info">
                            {{ $llenadoSeleccionado->asignado?->existencia?->existenciable?->descripcion ?? '-' }}
                        </span>
                    </div>

                    <div class="flex flex-col gap-2">
                        <span class="label-info">Destino:</span>
                        <span class="badge-info">
                            {{ $llenadoSeleccionado->existencia?->existenciable?->descripcion ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col gap-2 mt-4">
                    <span class="label-info">Observaciones:</span>
                    <span class="badge-info">{{ $llenadoSeleccionado->observaciones ?? '-' }}</span>
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
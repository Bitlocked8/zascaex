<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3
            class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto">
            Llenados
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="search" placeholder="Buscar por código o asignación..."
                class="input-minimal w-full" />

            <button wire:click="abrirModal('create')" class="btn-cyan">
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

        @forelse($llenados as $llenado)
            @php
                $montoUsado = 0;
                $montoMerma = 0;

                if ($llenado->asignado) {
                    foreach ($llenado->asignado->reposiciones as $reposicion) {
                        $precioUnitario = $reposicion->cantidad_inicial > 0
                            ? $reposicion->comprobantes->sum('monto') / $reposicion->cantidad_inicial
                            : 0;
                        $montoUsado += $precioUnitario * $llenado->cantidad;
                        $montoMerma += $precioUnitario * ($llenado->merma ?? 0);
                    }
                }
            @endphp

            <div class="card-teal flex flex-col gap-4">
                <div class="flex flex-col gap-1">
                    <p class="text-emerald-600 uppercase font-semibold">
                        {{ class_basename($llenado->existenciaDestino?->existenciable ?? '') }}:
                        {{ $llenado->existenciaDestino?->existenciable?->descripcion ?? 'N/A' }}
                    </p>
                    <p class="text-slate-600">{{ $llenado->codigo }}</p>
                    <p><strong>Fecha del llenado:</strong> {{ \Carbon\Carbon::parse($llenado->fecha)->format('d/m/Y H:i') }}
                    </p>
                    <p><strong>Cantidad de salida:</strong> {{ $llenado->cantidad }}</p>
                    <p><strong>Merma:</strong> {{ $llenado->merma }}</p>
                    <p class="mt-1 text-sm font-semibold">
                        <span
                            class="{{ $llenado->estado == 0 ? 'text-yellow-600' : '' }} {{ $llenado->estado == 1 ? 'text-blue-600' : '' }} {{ $llenado->estado == 2 ? 'text-green-600' : '' }}">
                            {{ $llenado->estado == 0 ? 'Pendiente' : ($llenado->estado == 1 ? 'En Proceso' : 'Finalizado') }}
                        </span>
                    </p>
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div
                            class="flex justify-between items-center bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 shadow-sm">
                            <span class="text-sm font-medium text-gray-700">Monto usado:</span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ (floor($montoUsado) == $montoUsado) ? number_format($montoUsado, 0, ',', '.') : number_format($montoUsado, 2, ',', '.') }}
                                Bs
                            </span>
                        </div>
                        <div
                            class="flex justify-between items-center bg-red-50 border border-red-200 rounded-lg px-4 py-2 shadow-sm">
                            <span class="text-sm font-medium text-red-700">Monto merma:</span>
                            <span class="text-sm font-semibold text-red-900">
                                {{ (floor($montoMerma) == $montoMerma) ? number_format($montoMerma, 0, ',', '.') : number_format($montoMerma, 2, ',', '.') }}
                                Bs
                            </span>
                        </div>
                    </div>

                </div>
                <div class="flex flex-wrap justify-center md:justify-center gap-2 border-t border-gray-200 pt-3 pb-2">

                    <button wire:click="verDetalleLlenado({{ $llenado->id }})" class="btn-cyan" title="Ver detalle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M8 12l0 .01" />
                            <path d="M12 12l0 .01" />
                            <path d="M16 12l0 .01" />
                        </svg>
                        Ver mas
                    </button>
                    <button wire:click="abrirModal('edit', {{ $llenado->id }})" class="btn-cyan" title="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                            <path d="M16 5l3 3" />
                        </svg>
                        Editar
                    </button>

                    @if($llenado->estado != 2)

                        <button wire:click="confirmarEliminarLlenado({{ $llenado->id }})" class="btn-cyan"
                            title="Eliminar llenado">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
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
                No hay llenados registrados.
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
                        <span class="text-u"> fecha llenado: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</span>
                        <div class="mb-4">
                            <label class="block text-sm font-semibold mb-2">Sucursal del elemento</label>
                            @if($accion === 'create')
                                @if($sucursales->count() > 0)
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($sucursales as $sucursal)
                                            <button type="button" wire:click="filtrarSucursalElemento({{ $sucursal->id }})"
                                                class="flex-1 sm:flex-auto px-4 py-2 rounded-lg text-sm font-medium transition {{ $filtroSucursalElemento == $sucursal->id ? 'bg-cyan-600 text-white shadow-lg border-cyan-600' : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-cyan-100 hover:text-cyan-600 hover:border-cyan-600' }}">
                                                {{ $sucursal->nombre }}
                                            </button>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-center text-gray-500 p-2">No hay sucursales disponibles.</p>
                                @endif
                            @else
                                @php
                                    $sucursalNombre = $llenado->asignado?->existencia?->sucursal?->nombre ?? 'N/A';
                                @endphp
                                <span
                                    class="inline-block px-4 py-2 rounded-lg bg-gray-100 text-gray-800 border border-gray-300 font-medium">
                                    {{ $sucursalNombre }}
                                </span>
                            @endif
                        </div>

                        <div>
                            <label class="text-u">Llenado (Elementos Asignados)</label>

                            @if($accion === 'edit')
                                @php
                                    $as = $asignaciones->firstWhere('id', $asignado_id) ?? ($llenado->asignado ?? null);
                                @endphp

                                @if($as && $as->reposiciones->count() > 0)
                                    <button class="w-full p-4 rounded-lg border-2 bg-white text-gray-800 flex flex-col gap-2">
                                        @foreach($as->reposiciones as $reposicion)
                                            @php
                                                $existencia = $reposicion->existencia;
                                                $tipo = optional($existencia)->existenciable
                                                    ? class_basename($existencia->existenciable_type)
                                                    : 'Desconocido';
                                            @endphp
                                            <div class="flex justify-between items-center">
                                                <span class="font-medium text-u">
                                                    {{ $tipo }}:
                                                    {{ optional($existencia->existenciable)->descripcion ?? 'Asignado #' . $as->id }}
                                                </span>
                                                <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                                    {{ $reposicion->pivot->cantidad ?? 0 }} Disponibles
                                                </span>
                                                <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                                    {{ optional($existencia->sucursal)->nombre ?? 'Sin sucursal' }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </button>
                                @else
                                    <p class="text-center text-gray-500 p-2">Asignación de llenado no disponible</p>
                                @endif

                            @else
                                <div class="flex-1 mb-2">
                                    <label for="busquedaAsignacion" class="block text-sm font-medium text-gray-700">
                                        Buscar Llenado
                                    </label>
                                    <input id="busquedaAsignacion" type="search" wire:model.live="busquedaAsignacion"
                                        class="input-minimal" placeholder="Buscar elementos de llenado..." />
                                </div>

                                @if($asignaciones->count() > 0)
                                    <div
                                        class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[250px]">
                                        @foreach($asignaciones as $asignado)
                                            <button type="button" wire:click="$set('asignado_id', {{ $asignado->id }})"
                                                class="w-full p-4 rounded-lg border-2 transition flex flex-col gap-2
                                                                                            {{ $asignado_id == $asignado->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }} bg-white">

                                                @foreach($asignado->reposiciones as $reposicion)
                                                    @php
                                                        $existencia = $reposicion->existencia;
                                                        $tipo = optional($existencia)->existenciable
                                                            ? class_basename($existencia->existenciable_type)
                                                            : 'Desconocido';
                                                    @endphp

                                                    <div class="flex justify-between items-center">
                                                        <span class="text-u">
                                                            {{ $tipo }}:
                                                            {{ optional($existencia->existenciable)->descripcion ?? 'Asignado #' . $asignado->id }}
                                                        </span>
                                                        <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                                            {{ $reposicion->pivot->cantidad ?? 0 }} Disponibles
                                                        </span>
                                                        <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                                            {{ optional($existencia->sucursal)->nombre ?? 'Sin sucursal' }}
                                                        </span>
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
                            <label class="text-u">Base (Requerido)</label>
                            <div class="flex-1">
                                <label for="busquedaDestino" class="block text-sm font-medium text-gray-700">
                                    Buscar Base
                                </label>
                                <input id="busquedaDestino" type="search" wire:model.live="busquedaDestino"
                                    class="input-minimal" placeholder="Buscar base..." />
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
                                        @endphp
                                        <button type="button" wire:click="$set('existencia_destino_id', {{ $existencia->id }})"
                                            class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center {{ $existencia_destino_id == $existencia->id ? 'border-cyan-600 text-cyan-600 bg-cyan-50' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 hover:bg-cyan-50' }}{{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            @if($disabled) disabled @endif>
                                            <span class="text-u font-medium">
                                                {{ $tipo }}:
                                                {{ optional($existencia->existenciable)->descripcion ?? 'Existencia #' . $existencia->id }}
                                            </span>
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
                                <button type="button" wire:click="$set('estado', 0)"
                                    class="flex-1 sm:flex-auto px-4 py-2 rounded-lg text-sm font-medium transition {{ $estado == 0 ? 'bg-yellow-500 text-white shadow-lg' : 'bg-gray-200 text-gray-700 hover:bg-yellow-400' }}">
                                    En proceso
                                </button>
                                <button type="button" wire:click="$set('estado', 1)"
                                    class="flex-1 sm:flex-auto px-4 py-2 rounded-lg text-sm font-medium transition {{ $estado == 1 ? 'bg-blue-500 text-white shadow-lg' : 'bg-gray-200 text-gray-700 hover:bg-blue-400' }}">
                                    En revisión
                                </button>
                                <button type="button" wire:click="$set('estado', 2)"
                                    class="flex-1 sm:flex-auto px-4 py-2 rounded-lg text-sm font-medium transition {{ $estado == 2 ? 'bg-green-500 text-white shadow-lg' : 'bg-gray-200 text-gray-700 hover:bg-green-400' }}">
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
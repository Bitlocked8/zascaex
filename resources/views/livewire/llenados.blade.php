<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3
            class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto">
            Llenado
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="search" placeholder="Buscar por código..."
                class="input-minimal w-full" />
            <button wire:click="abrirModal('create')" class="btn-cyan flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor">
                    <path
                        d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                </svg>
                Añadir
            </button>
        </div>

        @forelse($llenados as $llenado)
            <div class="card-teal flex flex-col gap-4">
                @php
                    $montoUsado = 0;
                    $montoMermaBase = 0;
                    $montoMermaTapa = 0;
                    if ($llenado->asignadoBase && $llenado->asignadoBase->reposiciones) {
                        foreach ($llenado->asignadoBase->reposiciones as $reposicionBase) {
                            $precioUnitarioBase = $reposicionBase->cantidad_inicial > 0
                                ? $reposicionBase->comprobantes->sum('monto') / $reposicionBase->cantidad_inicial
                                : 0;
                            $montoUsado += $precioUnitarioBase * $llenado->cantidad;
                            $montoMermaBase += $precioUnitarioBase * ($llenado->merma_base ?? 0);
                        }
                    }
                    if ($llenado->asignadoTapa && $llenado->asignadoTapa->reposiciones) {
                        foreach ($llenado->asignadoTapa->reposiciones as $reposicionTapa) {
                            $precioUnitarioTapa = $reposicionTapa->cantidad_inicial > 0
                                ? $reposicionTapa->comprobantes->sum('monto') / $reposicionTapa->cantidad_inicial
                                : 0;
                            $montoUsado += $precioUnitarioTapa * $llenado->cantidad;
                            $montoMermaTapa += $precioUnitarioTapa * ($llenado->merma_tapa ?? 0);
                        }
                    }

                    $montoTotalMerma = $montoMermaBase + $montoMermaTapa;
                @endphp

                <div class="flex flex-col gap-2">

                    <p class="text-emerald-600 uppercase font-semibold">
                        {{ class_basename($llenado->existenciaDestino->existenciable ?? '') }}:
                        {{ $llenado->existenciaDestino->existenciable->descripcion ?? 'N/A' }}
                    </p>

                    <p class="text-slate-600">{{ $llenado->codigo }}</p>
                    <p><strong>Fecha del llenado:</strong> {{ \Carbon\Carbon::parse($llenado->fecha)->format('d/m/Y H:i') }}
                    </p>
                    <p><strong>Cantidad producida:</strong> {{ $llenado->cantidad }}</p>
                    <p><strong>Merma Base:</strong> {{ $llenado->merma_base ?? 0 }}</p>
                    <p><strong>Merma Tapa:</strong> {{ $llenado->merma_tapa ?? 0 }}</p>
                    <p><strong>Personal:</strong> {{ $llenado->personal->nombres ?? 'N/A' }}</p>

                    <p class="mt-1 text-sm font-semibold">
                        <span
                            class="{{ $llenado->estado == 0 ? 'text-yellow-600' : '' }} {{ $llenado->estado == 1 ? 'text-blue-600' : '' }} {{ $llenado->estado == 2 ? 'text-green-600' : '' }}">
                            {{ $llenado->estado == 0 ? 'Pendiente' : ($llenado->estado == 1 ? 'En Proceso' : 'Finalizado') }}
                        </span>
                    </p>
                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div
                            class="flex justify-between items-center bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 shadow-sm">
                            <span class="text-sm font-medium text-gray-700">Monto usado:</span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $montoUsado > 0 ? number_format($montoUsado, 2, ',', '.') . ' Bs' : '—' }}
                            </span>
                        </div>

                        <div
                            class="flex justify-between items-center bg-red-50 border border-red-200 rounded-lg px-4 py-2 shadow-sm">
                            <span class="text-sm font-medium text-red-700">Monto merma total:</span>
                            <span class="text-sm font-semibold text-red-900">
                                {{ $montoTotalMerma > 0 ? number_format($montoTotalMerma, 2, ',', '.') . ' Bs' : '—' }}
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
                    <button wire:click="abrirModal('edit', {{ $llenado->id }})"
                        class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                            <path d="M16 5l3 3" />
                        </svg>
                        Editar
                    </button>
                    @if($llenado->estado != 2)

                        <button wire:click="confirmarEliminarLlenado({{ $llenado->id }})" class="btn-cyan" title="Eliminar">
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
                        <div class="mb-4">
                            <label class="block text-sm font-semibold mb-2">Sucursal del elemento</label>
                            @if($accion === 'create')
                                @if($sucursales->count() > 0)
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($sucursales as $sucursal)
                                                    <button type="button" wire:click="filtrarSucursalElemento({{ $sucursal->id }})"
                                                        class="flex-1 sm:flex-auto px-4 py-2 rounded-lg text-sm font-medium transition 
                                                                                                                                                                                                    {{ $filtroSucursalElemento == $sucursal->id
                                            ? 'bg-cyan-600 text-white shadow-lg border-cyan-600'
                                            : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-cyan-100 hover:text-cyan-600 hover:border-cyan-600' }}">
                                                        {{ $sucursal->nombre }}
                                                    </button>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-center text-gray-500 p-2">No hay sucursales disponibles.</p>
                                @endif
                            @else
                                @php
                                    $sucursalNombre = optional($llenadoSeleccionado->existenciaDestino->sucursal ?? null)->nombre ?? 'N/A';
                                @endphp
                                <span
                                    class="inline-block px-4 py-2 rounded-lg bg-gray-100 text-gray-800 border border-gray-300 font-medium">
                                    {{ $sucursalNombre }}
                                </span>
                            @endif
                        </div>

                        <div>
                            <label class="font-semibold text-sm mb-2 block">Asignación (Base)</label>

                            @if($accion === 'edit')
                                @php

                                    $asBase = $asignacionesBase->firstWhere('id', $asignado_base_id)
                                        ?? \App\Models\Asignado::with('existencia.existenciable', 'existencia.sucursal')->find($asignado_base_id);

                                    $tipoBase = $asBase ? class_basename($asBase->existencia->existenciable_type) : 'Desconocido';
                                @endphp

                                <p
                                    class="flex flex-col items-center gap-2 p-4 rounded-lg border-2 bg-white text-gray-800 text-center">
                                    <span class="font-medium">
                                        {{ $tipoBase }}:
                                        {{ $asBase->existencia->existenciable->descripcion ?? ('Asignado #' . $asignado_base_id) }}
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
                                    <input id="busquedaAsignacionBase" type="search" wire:model.live="busquedaAsignacionBase"
                                        class="input-minimal" placeholder="Buscar base..." />
                                </div>

                                <div
                                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                                    @forelse($asignacionesBase as $asignado)
                                        @php
                                            $tipo = class_basename($asignado->existencia->existenciable_type);
                                            $disabled = isset($asignado->existencia->existenciable->estado) && !$asignado->existencia->existenciable->estado;
                                        @endphp

                                        <button type="button" wire:click="$set('asignado_base_id', {{ $asignado->id }})"
                                            class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center{{ $asignado_base_id == $asignado->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}bg-white">

                                            <span class="text-u">
                                                {{ $tipo }}:
                                                {{ $asignado->existencia->existenciable->descripcion ?? 'Asignado #' . $asignado->id }}
                                            </span>

                                            <div class="flex flex-wrap justify-center gap-3 mt-2">
                                                <div class="flex flex-col items-center gap-1">
                                                    <span class="text-xs font-medium text-gray-600">
                                                        {{ $asignado->existencia->sucursal->nombre ?? 'Sin sucursal' }}
                                                    </span>
                                                    <span
                                                        class="bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
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
                                    $asTapa = $asignacionesTapa->firstWhere('id', $asignado_tapa_id)
                                        ?? \App\Models\Asignado::with('existencia.existenciable', 'existencia.sucursal')->find($asignado_tapa_id);

                                    $tipoTapa = $asTapa ? class_basename($asTapa->existencia->existenciable_type) : 'Desconocido';
                                @endphp

                                <p
                                    class="flex flex-col items-center gap-2 p-4 rounded-lg border-2 bg-white text-gray-800 text-center">
                                    <span class="font-medium">
                                        {{ $tipoTapa }}:
                                        {{ $asTapa->existencia->existenciable->descripcion ?? ('Asignado #' . $asignado_tapa_id) }}
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
                                    <input id="busquedaAsignacionTapa" type="search" wire:model.live="busquedaAsignacionTapa"
                                        class="input-minimal" placeholder="Buscar tapa..." />
                                </div>

                                <div
                                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                                    @forelse($asignacionesTapa as $asignado)
                                        @php
                                            $tipo = class_basename($asignado->existencia->existenciable_type);
                                        @endphp

                                        <button type="button" wire:click="$set('asignado_tapa_id', {{ $asignado->id }})"
                                            class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center {{ $asignado_tapa_id == $asignado->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }} bg-white">

                                            <span class="text-u">
                                                {{ $tipo }}:
                                                {{ $asignado->existencia->existenciable->descripcion ?? 'Asignado #' . $asignado->id }}
                                            </span>

                                            <div class="flex flex-wrap justify-center gap-3 mt-2">
                                                <div class="flex flex-col items-center gap-1">
                                                    <span class="text-xs font-medium text-gray-600">
                                                        {{ $asignado->existencia->sucursal->nombre ?? 'Sin sucursal' }}
                                                    </span>
                                                    <span
                                                        class="bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
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

                                <p
                                    class="flex flex-col items-center gap-2 p-4 rounded-lg border-2 bg-white text-gray-800 text-center">
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
                                    <input id="busquedaDestino" type="search" wire:model.live="busquedaDestino"
                                        class="input-minimal" placeholder="Buscar producto lleno..." />
                                </div>
                                <div
                                    class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                                    @foreach($existenciasDestino as $existencia)
                                        @php
                                            $disabled = isset($existencia->existenciable->estado) && !$existencia->existenciable->estado;
                                        @endphp

                                        <button type="button" wire:click="$set('existencia_destino_id', {{ $existencia->id }})"
                                            class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center {{ $existencia_destino_id == $existencia->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}bg-white">
                                            <span class="text-u">
                                                {{ $existencia->existenciable->descripcion ?? 'Destino #' . $existencia->id }}
                                            </span>

                                            <div class="flex flex-wrap justify-center gap-3 mt-2">
                                                <div class="flex flex-col items-center gap-1">
                                                    <span class="text-xs font-medium text-gray-600">
                                                        {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}
                                                    </span>
                                                    @if(isset($existencia->cantidad))
                                                        <span
                                                            class="bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
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

                        <div class="grid grid-cols-1 gap-2">
                            <div>
                                <label class="font-semibold text-sm">Cantidad producida (Opcional)</label>
                                <input type="number" wire:model="cantidad" class="input-minimal" min="1"
                                    placeholder="Ej. 250">
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
                        <div class="text-center">
                            <label class="font-semibold text-sm mb-2 block">Estado</label>
                            <div class="flex flex-col sm:flex-row justify-center flex-wrap gap-3">
                                <button type="button" wire:click="$set('estado', 0)"
                                    class="flex-1 sm:flex-auto px-4 py-2 rounded-lg text-sm font-medium transition
                                                                                                    {{ $estado == 0 ? 'bg-yellow-500 text-white shadow-lg' : 'bg-gray-200 text-gray-700 hover:bg-yellow-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="inline mr-1">
                                        <circle cx="10" cy="10" r="9" />
                                        <polyline points="10 5 10 10 13 12" />
                                    </svg>
                                    En proceso
                                </button>

                                <button type="button" wire:click="$set('estado', 1)"
                                    class="flex-1 sm:flex-auto px-4 py-2 rounded-lg text-sm font-medium transition
                                                                                                    {{ $estado == 1 ? 'bg-blue-500 text-white shadow-lg' : 'bg-gray-200 text-gray-700 hover:bg-blue-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="inline mr-1">
                                        <path d="M4 12l4 4L18 6" />
                                    </svg>
                                    Revisado
                                </button>

                                <button type="button" wire:click="$set('estado', 2)"
                                    class="flex-1 sm:flex-auto px-4 py-2 rounded-lg text-sm font-medium transition
                                                                                                    {{ $estado == 2 ? 'bg-green-500 text-white shadow-lg' : 'bg-gray-200 text-gray-700 hover:bg-green-400' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="inline mr-1">
                                        <path d="M16 4L4 16" />
                                        <path d="M4 4l12 12" />
                                    </svg>
                                    Confirmado
                                </button>
                            </div>
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
                        <button wire:click="guardar" class="btn-cyan">
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

    @if($confirmingDeleteLlenadoId)
        <div class="modal-overlay fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="modal-box bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
                <div class="modal-content mb-6">
                    <div class="flex flex-col gap-4 text-center">
                        <h2 class="text-lg font-semibold">¿Estás seguro?</h2>
                        <p class="text-gray-600">
                            El registro de llenado seleccionado se eliminará y se revertirán las cantidades utilizadas.
                        </p>
                    </div>
                </div>

                <div class="modal-footer flex justify-end gap-3">
                    <button type="button" wire:click="eliminarLlenadoConfirmado" class="btn-cyan flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 12l5 5l10 -10" />
                            <path d="M2 12l5 5m5 -5l5 -5" />
                        </svg>
                        Confirmar
                    </button>

                    <button type="button" wire:click="$set('confirmingDeleteLlenadoId', null)"
                        class="btn-gray flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                            <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
                        </svg>
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    @endif


    @if($modalDetalle && $llenadoSeleccionado)
        <div class="modal-overlay">
            <div class="modal-box max-w-3xl">
                <div class="modal-content flex flex-col gap-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex flex-col gap-3">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Código:</span>
                                <span class="badge-info">{{ $llenadoSeleccionado->codigo }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Personal:</span>
                                <span class="badge-info">{{ $llenadoSeleccionado->personal->nombres ?? '-' }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Fecha:</span>
                                <span
                                    class="badge-info">{{ \Carbon\Carbon::parse($llenadoSeleccionado->fecha)->format('d M Y, H:i') }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Estado:</span>
                                <span class="inline-block px-2 py-1 rounded-full text-sm font-semibold 
                                                                                                                    {{ $llenadoSeleccionado->estado == 0
            ? 'bg-cyan-600 text-white'
            : ($llenadoSeleccionado->estado == 1
                ? 'bg-emerald-600 text-white'
                : 'bg-red-600 text-white') }}">
                                    {{ $llenadoSeleccionado->estado == 0
            ? 'Pendiente'
            : ($llenadoSeleccionado->estado == 1
                ? 'En proceso'
                : 'Completado') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-2">

                    <h3 class="font-semibold text-lg">Detalle de materiales:</h3>
                    <div class="divide-y divide-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center py-2">
                            <div class="flex flex-col gap-1">
                                <span class="font-medium">Base:
                                    {{ $llenadoSeleccionado->asignadoBase->existencia->existenciable->descripcion ?? '-' }}</span>
                                <span class="font-medium">Cantidad usada: {{ $llenadoSeleccionado->cantidad }}</span>
                                <span class="font-medium">Merma base: {{ $llenadoSeleccionado->merma_base }}</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="font-medium">Tapa:
                                    {{ $llenadoSeleccionado->asignadoTapa->existencia->existenciable->descripcion ?? '-' }}</span>
                                <span class="font-medium">Cantidad usada: {{ $llenadoSeleccionado->cantidad }}</span>
                                <span class="font-medium">Merma tapa: {{ $llenadoSeleccionado->merma_tapa }}</span>
                            </div>
                        </div>
                    </div>

                    <h3 class="font-semibold text-lg mt-4">Destino / Producto final:</h3>
                    <div class="flex flex-col gap-2">
                        <span class="font-medium">Producto:
                            {{ $llenadoSeleccionado->existenciaDestino->existenciable->descripcion ?? '-' }}</span>
                        <span class="font-medium">Cantidad final: {{ $llenadoSeleccionado->cantidad }}</span>
                        <span class="font-medium">Reposición asociada:
                            {{ $llenadoSeleccionado->reposicion->codigo ?? '-' }}</span>
                    </div>

                    <div class="mt-4">
                        <span class="label-info block mb-1">Observaciones:</span>
                        <div class="bg-gray-100 rounded p-2 text-sm text-gray-700">
                            {{ $llenadoSeleccionado->observaciones ?? 'Sin observaciones' }}
                        </div>
                    </div>
                </div>

                <div class="modal-footer mt-4">
                    <button wire:click="$set('modalDetalle', false)" class="btn-cyan" title="Cerrar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                            <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
                        </svg>
                        CERRAR
                    </button>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
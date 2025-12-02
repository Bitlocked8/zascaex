<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <h3
            class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto">
            Traspasos
        </h3>
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="search" placeholder="Buscar por código u observación..."
                class="input-minimal w-full" />
            <button wire:click="abrirModal('create')" class="btn-cyan" title="Agregar">
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

        @forelse($traspasos as $traspaso)
            <div class="card-teal flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <p class="text-emerald-600 uppercase font-semibold">{{ $traspaso->codigo ?? 'N/A' }}</p>
                    <p> {{ $traspaso->fecha_traspaso ?? 'N/A' }}</p>
                    <p><strong>Personal:</strong> {{ $traspaso->personal?->nombres ?? 'N/A' }}</p>
                    <p><strong>De Sucursal:</strong>
                        @if($traspaso->asignacion && $traspaso->asignacion->reposiciones->isNotEmpty())
                            {{ $traspaso->asignacion->reposiciones->pluck('existencia.sucursal.nombre')->unique()->join(', ') }}
                        @else
                            N/A
                        @endif
                    </p>
                    <p><strong>A Sucusal:</strong>
                        {{ $traspaso->reposicionDestino?->existencia?->sucursal?->nombre ?? 'N/A' }}
                    </p>
                    <p><strong>Cantidad:</strong> {{ $traspaso->cantidad ?? 'N/A' }}</p>
                </div>

                <div class="flex flex-wrap justify-center md:justify-center gap-2 border-t border-gray-200 pt-3 pb-2">
                    <button wire:click="verDetalle({{ $traspaso->id }})" class="btn-cyan" title="Ver detalle">
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
                    <button wire:click="abrirModal('edit', {{ $traspaso->id }})" class="btn-cyan" title="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                            <path d="M16 5l3 3" />
                        </svg>
                        Editar
                    </button>
                    <button wire:click="$set('confirmingDeleteId', {{ $traspaso->id }})" class="btn-cyan" title="Eliminar">
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
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-4 text-gray-600">
                No hay traspasos registrados.
            </div>
        @endforelse
    </div>



    @if($modal)
        <div class="modal-overlay">
            <div class="modal-box">
                <div class="modal-content">
                    <div class="flex flex-col gap-4">

                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                                <strong class="font-bold">¡Atención!</strong>
                                <ul class="mt-2 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="flex flex-col gap-2">
                            <p class="font-semibold text-sm">
                                Código: <span class="font-normal">{{ $codigo }}</span>
                            </p>
                            <p class="font-semibold text-sm">
                                Fecha de Traspaso: <span class="font-normal">{{ $fecha_traspaso }}</span>
                            </p>
                            <p class="font-semibold text-sm">
                                Personal Responsable:
                                <span class="font-normal">{{ auth()->user()->personal->nombres ?? 'N/A' }}</span>
                            </p>
                        </div>


                        <div class="mt-4">
                            <label class="text-u font-semibold mb-2 block">Origen</label>

                            @if($accion === 'edit')
                                @php
                                    $item = $reposicionesOrigen->firstWhere('asignacion.id', $origen_id);
                                    $tipo = $item && $item->existencia && $item->existencia->existenciable ? class_basename($item->existencia->existenciable_type) : 'Desconocido';
                                    $descripcion = $item && $item->existencia && $item->existencia->existenciable ? $item->existencia->existenciable->descripcion : '';
                                    $sucursal = $item && $item->existencia && $item->existencia->sucursal ? $item->existencia->sucursal->nombre : 'N/A';
                                @endphp

                                <div
                                    class="w-full p-4 rounded-lg border-2 bg-white text-gray-800 flex flex-col gap-2 items-center text-center">
                                    <p class="text-lg font-semibold text-u">{{ $tipo }}</p>
                                    <p class="text-base text-u">{{ $descripcion }}</p>
                                    <p class="text-sm text-gray-700">Sucursal: <span class="text-u">{{ $sucursal }}</span></p>
                                    <p class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold mt-2">
                                        Disponible: {{ $item->totalDisponible ?? 0 }}
                                    </p>
                                </div>
                            @else
                                <div class="w-full grid grid-cols-1 gap-3 overflow-y-auto max-h-[250px]">
                                    @foreach($reposicionesOrigen as $item)
                                        @php
                                            $tipo = $item->existencia && $item->existencia->existenciable ? class_basename($item->existencia->existenciable_type) : 'Desconocido';
                                            $descripcion = $item->existencia && $item->existencia->existenciable ? $item->existencia->existenciable->descripcion : '';
                                            $sucursal = $item->existencia && $item->existencia->sucursal ? $item->existencia->sucursal->nombre : 'N/A';
                                        @endphp
                                        <button type="button" wire:click="$set('origen_id', {{ $item->asignacion->id }})"
                                            class="w-full p-4 rounded-lg border-2 transition flex flex-col gap-2 items-center text-center
                                                                                                                {{ $origen_id == $item->asignacion->id ? 'border-cyan-600 text-cyan-600 bg-cyan-50' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 hover:bg-cyan-50' }} bg-white">
                                            <p class="text-lg font-semibold text-u">{{ $tipo }}</p>
                                            <p class="text-base text-u">{{ $descripcion }}</p>
                                            <p class="text-sm text-gray-700">Sucursal: <span class="text-u">{{ $sucursal }}</span>
                                            </p>
                                            <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold mt-1">
                                                Disponible: {{ $item->totalDisponible }}
                                            </span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Destino -->
                        <div class="mt-4">
                            <label class="text-u font-semibold mb-2 block">Destino</label>

                            @if($accion === 'edit')
                                @php
                                    $dest = $reposicionesDestino->firstWhere('existencia.id', $destino_id);
                                    $tipo = $dest && $dest->existencia && $dest->existencia->existenciable ? class_basename($dest->existencia->existenciable_type) : 'Desconocido';
                                    $descripcion = $dest && $dest->existencia && $dest->existencia->existenciable ? $dest->existencia->existenciable->descripcion : '';
                                    $sucursal = $dest && $dest->existencia && $dest->existencia->sucursal ? $dest->existencia->sucursal->nombre : 'N/A';
                                @endphp

                                <div
                                    class="w-full p-4 rounded-lg border-2 bg-white text-gray-800 flex flex-col gap-2 items-center text-center">
                                    <p class="text-lg font-semibold text-u">{{ $tipo }}</p>
                                    <p class="text-base text-u">{{ $descripcion }}</p>
                                    <p class="text-sm text-gray-700">Sucursal: <span class="text-u">{{ $sucursal }}</span></p>
                                    <p class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold mt-2">
                                        Stock: {{ $dest->totalDisponible ?? 0 }}
                                    </p>
                                </div>
                            @else
                                <div class="w-full grid grid-cols-1 gap-3 overflow-y-auto max-h-[250px]">
                                    @foreach($reposicionesDestino as $item)
                                        @if($item->existencia)
                                            @php
                                                $tipo = $item->existencia->existenciable ? class_basename($item->existencia->existenciable_type) : 'Desconocido';
                                                $descripcion = $item->existencia->existenciable->descripcion ?? '';
                                                $sucursal = $item->existencia->sucursal->nombre ?? 'N/A';
                                            @endphp
                                            <button type="button" wire:click="$set('destino_id', {{ $item->existencia->id }})"
                                                class="w-full p-4 rounded-lg border-2 transition flex flex-col gap-2 items-center text-center
                                                                                                                                            {{ $destino_id == $item->existencia->id ? 'border-cyan-600 text-cyan-600 bg-cyan-50' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600 hover:bg-cyan-50' }} bg-white">
                                                <p class="text-lg font-semibold text-u">{{ $tipo }}</p>
                                                <p class="text-base text-u">{{ $descripcion }}</p>
                                                <p class="text-sm text-gray-700">Sucursal: <span class="text-u">{{ $sucursal }}</span>
                                                </p>
                                                <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold mt-1">
                                                    Stock: {{ $item->totalDisponible }}
                                                </span>
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>



                        <div>
                            <label class="font-semibold text-sm">Observaciones</label>
                            <textarea wire:model="observaciones" class="input-minimal w-full"
                                placeholder="Observaciones"></textarea>
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

                    @if($accion === 'create')
                        <button type="button" wire:click="guardar" class="btn-cyan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" />
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M14 4l0 4l-6 0l0 -4" />
                            </svg>
                            Guardar
                        </button>

                    @elseif($accion === 'edit')
                        <button type="button" wire:click="guardarObservaciones" class="btn-cyan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" />
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M14 4l0 4l-6 0l0 -4" />
                            </svg>
                            Guardar observacion
                        </button>

                    @endif
                </div>

            </div>
        </div>
    @endif


    @if($detalleModal && $traspasoSeleccionado)
        <div class="modal-overlay">
            <div class="modal-box">
                <div class="modal-content flex flex-col gap-4">
                    <div class="flex justify-center items-center">
                        <div
                            class="w-20 h-20 rounded-full bg-cyan-600 text-white flex items-center justify-center text-2xl font-bold">
                            {{ strtoupper(substr($traspasoSeleccionado->codigo, 0, 1)) }}
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-3">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Código:</span>
                                <span class="badge-info">{{ $traspasoSeleccionado->codigo }}</span>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Fecha:</span>
                                <span class="badge-info">{{ $traspasoSeleccionado->fecha_traspaso }}</span>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Cantidad:</span>
                                <span class="badge-info">{{ $traspasoSeleccionado->cantidad }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">


                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Responsable:</span>
                                <span class="badge-info">{{ $traspasoSeleccionado->personal->nombres ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div class="flex flex-col gap-2">
                            <span class="label-info">Origen:</span>
                            <span class="badge-info">
                                {{ $traspasoSeleccionado->reposicionOrigen->existencia->existenciable->descripcion ?? '-' }}
                                ({{ $traspasoSeleccionado->reposicionOrigen->existencia->sucursal->nombre ?? 'Sin sucursal' }})
                            </span>
                        </div>

                        <div class="flex flex-col gap-2">
                            <span class="label-info">Destino:</span>
                            <span class="badge-info">
                                {{ $traspasoSeleccionado->reposicionDestino->existencia->existenciable->descripcion ?? '-' }}
                                ({{ $traspasoSeleccionado->reposicionDestino->existencia->sucursal->nombre ?? 'Sin sucursal' }})
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="label-info">Observaciones:</span>
                        <p class="badge-info break-words">{{ $traspasoSeleccionado->observaciones ?? '-' }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('detalleModal', false)" class="btn-cyan" title="Cerrar">
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
    @if($modalError)
        <div wire:click.self="$set('modalError', false)"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white text-red-700 rounded-lg shadow-lg w-full max-w-md p-6 relative">
                <h2 class="text-lg font-semibold mb-4">Error</h2>
                <p>{{ $mensajeError }}</p>

            </div>
        </div>
    @endif
    @if($confirmingDeleteId)
        <div class="modal-overlay">
            <div class="modal-box">
                <div class="modal-content">
                    <div class="flex flex-col gap-4 text-center">
                        <h2 class="text-lg font-semibold">¿Estás seguro?</h2>
                        <p class="text-gray-600">
                            El traspaso seleccionado se eliminará y se revertirán los cambios correspondientes.
                        </p>
                    </div>
                </div>

                <div class="modal-footer flex justify-center gap-3 mt-4">
                    <button type="button" wire:click="eliminarTraspaso" class="btn-cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 12l5 5l10 -10" />
                        </svg>
                        Confirmar
                    </button>

                    <button type="button" wire:click="$set('confirmingDeleteId', null)" class="btn-cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
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
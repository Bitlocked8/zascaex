<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por código u observación..."
                class="input-minimal w-full" />
            <button wire:click="abrirModal('create')" class="btn-circle btn-cyan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                    <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                </svg>
            </button>
        </div>

        @forelse($traspasos as $traspaso)
        <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
            <div class="flex flex-col col-span-9 text-left space-y-1">
                <p><strong>Código:</strong> {{ $traspaso->codigo ?? 'N/A' }}</p>
                <p><strong>Fecha:</strong> {{ $traspaso->fecha_traspaso ?? 'N/A' }}</p>
                <p><strong>Personal:</strong> {{ $traspaso->personal?->nombres ?? 'N/A' }}</p>
                <p><strong>Origen:</strong> {{ $traspaso->reposicionOrigen?->existencia?->sucursal?->nombre ?? 'N/A' }}</p>
                <p><strong>Destino:</strong> {{ $traspaso->reposicionDestino?->existencia?->sucursal?->nombre ?? 'N/A' }}</p>
                <p><strong>Cantidad:</strong> {{ $traspaso->cantidad ?? 'N/A' }}</p>
                <p><strong>Observaciones:</strong> {{ $traspaso->observaciones ?? 'N/A' }}</p>
            </div>

            <!-- Botones de acción -->
            <div class="flex flex-col items-end gap-4 col-span-3">
                <button wire:click="abrirModal('edit', {{ $traspaso->id }})" class="btn-circle btn-cyan">
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
                <button wire:click="$set('confirmingDeleteId', {{ $traspaso->id }})" class="btn-circle btn-cyan"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 7l16 0" />
                        <path d="M10 11l0 6" />
                        <path d="M14 11l0 6" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg></button>
                <button wire:click="verDetalle({{ $traspaso->id }})"
                    class="btn-circle btn-cyan"
                    title="Ver Detalle">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9h.01" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 12h1v4h1" />
                    </svg>
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-4 text-gray-600">
            No hay traspasos registrados. {{-- ← Cambiado el mensaje --}}
        </div>
        @endforelse
    </div>


    @if($modal)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content">
                <div class="flex flex-col gap-4">

                    <!-- Código y fecha -->
                    <div class="flex flex-col gap-2">
                        <p class="font-semibold text-sm">
                            Código: <span class="font-normal">{{ $codigo }}</span>
                        </p>
                        <p class="font-semibold text-sm">
                            Fecha de Traspaso: <span class="font-normal">{{ $fecha_traspaso }}</span>
                        </p>
                        <p class="font-semibold text-sm">
                            Personal Responsable:
                            <span class="font-normal">
                                {{ $accion === 'create' ? auth()->user()->personal->nombres : ($traspaso_id ? $traspaso->personal->nombres : 'N/A') }}
                            </span>
                        </p>
                        <p class="font-semibold text-sm">
                            Sucursal:
                            <span class="font-normal">
                                {{ $accion === 'create' 
            ? (auth()->user()->personal->trabajos()->where('estado',1)->first()->sucursal->nombre ?? 'N/A') 
            : ($traspaso_id ? $traspaso->personal->trabajos()->where('estado',1)->first()->sucursal->nombre ?? 'N/A' : 'N/A') }}
                            </span>
                        </p>

                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-2 block">Origen</label>

                        @if($accion === 'edit')
                        @php
                        $repo = $reposicionesOrigen->firstWhere('id', $origen_id);
                        $ex = $repo ? $repo->existencia : null;
                        $tipo = $ex ? class_basename($ex->existenciable_type) : 'Desconocido';
                        $comprobante = $repo ? $repo->comprobantes->sum('monto') : 0;
                        @endphp
                        <p class="flex items-center gap-2">
                            <span>{{ $tipo }}: {{ $ex->existenciable->descripcion ?? 'Existencia #' . $origen_id }}</span>
                            <span class="bg-cyan-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                Disponible: {{ $repo->cantidad ?? 0 }}
                            </span>
                            <span class="bg-cyan-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                {{ $ex->sucursal->nombre ?? 'Sin sucursal' }}
                            </span>
                            <span class="bg-cyan-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                Monto: Bs {{ number_format($comprobante) }}
                            </span>
                        </p>
                        @else
                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                            @foreach($reposicionesOrigen as $repo)
                            @php
                            $ex = $repo->existencia;
                            $tipo = $ex ? class_basename($ex->existenciable_type) : 'Desconocido';
                            $descripcion = $ex ? $ex->existenciable->descripcion : 'Existencia #' . $repo->id;
                            $comprobante = $repo->comprobantes->sum('monto');
                            @endphp
                            <button
                                type="button"
                                wire:click="$set('origen_id', {{ $repo->id }})"
                                class="w-full px-3 py-2 rounded-md border text-sm text-left flex justify-between items-center transition
                        {{ $origen_id == $repo->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                <span>{{ $tipo }}: {{ $descripcion }}</span>
                                <span class="flex items-center gap-2">
                                    <span class="bg-cyan-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        Disponible: {{ $repo->cantidad }}
                                    </span>
                                    <span class="bg-cyan-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        {{ $ex->sucursal->nombre ?? 'Sin sucursal' }}
                                    </span>
                                    <span class="bg-cyan-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        Monto: Bs {{ number_format($comprobante) }}
                                    </span>
                                </span>
                            </button>
                            @endforeach
                        </div>
                        @endif

                        @error('origen_id') <span class="error-message">{{ $message }}</span> @enderror
                    </div>


                    <div>
                        <label class="font-semibold text-sm mb-2 block">Destino</label>

                        @if($accion === 'edit')
                        @php
                        $repo = $reposicionesDestino->firstWhere('id', $destino_id);
                        $ex = $repo ? $repo->existencia : null;
                        $tipo = $ex ? class_basename($ex->existenciable_type) : 'Desconocido';
                        $comprobante = $repo ? $repo->comprobantes->sum('monto') : 0;
                        @endphp
                        <p class="flex items-center gap-2">
                            <span>{{ $tipo }}: {{ $ex->existenciable->descripcion ?? 'Existencia #' . $destino_id }}</span>
                            <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                Disponible: {{ $repo->cantidad ?? 0 }}
                            </span>
                            <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                {{ $ex->sucursal->nombre ?? 'Sin sucursal' }}
                            </span>
                            <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                Monto: Bs {{ number_format($comprobante) }}
                            </span>
                        </p>
                        @else
                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                            @foreach($reposicionesDestino as $repo)
                            @php
                            $ex = $repo->existencia;
                            $tipo = $ex ? class_basename($ex->existenciable_type) : 'Desconocido';
                            $descripcion = $ex ? $ex->existenciable->descripcion : 'Existencia #' . $repo->id;
                            $comprobante = $repo->comprobantes->sum('monto');
                            @endphp
                            <button
                                type="button"
                                wire:click="$set('destino_id', {{ $repo->id }})"
                                class="w-full px-3 py-2 rounded-md border text-sm text-left flex justify-between items-center transition
                        {{ $destino_id == $repo->id ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-green-100' }}">
                                <span>{{ $tipo }}: {{ $descripcion }}</span>
                                <span class="flex items-center gap-2">
                                    <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        Disponible: {{ $repo->cantidad }}
                                    </span>
                                    <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        {{ $ex->sucursal->nombre ?? 'Sin sucursal' }}
                                    </span>
                                    <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        Monto: Bs {{ number_format($comprobante) }}
                                    </span>
                                </span>
                            </button>
                            @endforeach
                        </div>
                        @endif

                        @error('destino_id') <span class="error-message">{{ $message }}</span> @enderror
                    </div>


                    <div>
                        <label class="font-semibold text-sm mb-2 block">Cantidad</label>

                        @if($accion === 'edit')
                        <p class="flex items-center gap-2">
                            <span>Cantidad de traspaso :</span>
                            <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                {{ $cantidad ?? 0 }}
                            </span>
                        </p>
                        @else
                        <input type="number" wire:model="cantidad" placeholder="Cantidad a traspasar" class="input-minimal">
                        @error('cantidad') <span class="error-message">{{ $message }}</span> @enderror
                        @endif
                    </div>


                    <div>
                        <label class="font-semibold text-sm">Observaciones</label>
                        <textarea wire:model="observaciones" placeholder="Observaciones" class="input-minimal"></textarea>
                        @error('observaciones') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" wire:click="guardar" class="btn-circle btn-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2H6a2 2 0 0 1 -2-2V6a2 2 0 0 1 2-2z" />
                        <circle cx="12" cy="14" r="2" />
                        <path d="M14 4v4H8V4" />
                    </svg>
                </button>

                <button type="button" wire:click="cerrarModal" class="btn-circle btn-cyan">
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
    @if($detalleModal && $traspasoSeleccionado)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">

                <!-- Icono con inicial -->
                <div class="flex justify-center items-center">
                    <div class="w-20 h-20 rounded-full bg-cyan-600 text-white flex items-center justify-center text-2xl font-bold">
                        {{ strtoupper(substr($traspasoSeleccionado->codigo,0,1)) }}
                    </div>
                </div>

                <!-- Información del Traspaso -->
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

                <!-- Origen / Destino -->
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

                <!-- Observaciones -->
                <div class="mt-4">
                    <span class="label-info">Observaciones:</span>
                    <p class="badge-info break-words">{{ $traspasoSeleccionado->observaciones ?? '-' }}</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button wire:click="$set('detalleModal', false)" class="btn-circle btn-cyan" title="Cerrar">
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


    @if($confirmingDeleteId)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content">
                <div class="flex flex-col gap-4 text-center">
                    <h2 class="text-lg font-semibold">¿Estás seguro?</h2>
                    <p class="text-gray-600">
                        El traspaso seleccionado se eliminará y se revertirán los cambios en stock.
                    </p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" wire:click="eliminarTraspasoConfirmado" class="btn-circle btn-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M18.333 2c1.96 0 3.56 1.537 3.662 3.472l.005 .195v12.666c0 1.96 -1.537 3.56 -3.472 3.662l-.195 .005h-12.666a3.667 3.667 0 0 1 -3.662 -3.472l-.005 -.195v-12.666c0 -1.96 1.537 -3.56 3.472 -3.662l.195 -.005h12.666zm-2.626 7.293a1 1 0 0 0 -1.414 0l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.32 1.497l2 2l.094 .083a1 1 0 0 0 1.32 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                    </svg>
                </button>

                <button type="button" wire:click="$set('confirmingDeleteId', null)" class="btn-circle btn-cyan">
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
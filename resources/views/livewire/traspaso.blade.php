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
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 13v6a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h6" />
                    </svg>
                </button>
                <button wire:click="eliminarTraspaso({{ $traspaso->id }})" class="btn-circle btn-red">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12" />
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
                        $comprobante = $repo && $repo->comprobantes->first() ? $repo->comprobantes->first()->monto : 0;
                        @endphp
                        <p class="flex items-center gap-2">
                            <span>{{ $tipo }}: {{ $ex->existenciable->descripcion ?? 'Existencia #' . $destino_id }}</span>
                            <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                Disponible: {{ $repo->cantidad ?? 0 }}
                            </span>
                            <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                {{ $ex->sucursal->nombre ?? 'Sin sucursal' }}
                            </span>
                            <span class="bg-yellow-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                Monto: ${{ number_format($comprobante, 2) }}
                            </span>
                        </p>
                        @else
                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                            @foreach($reposicionesDestino as $repo)
                            @php
                            $ex = $repo->existencia;
                            $tipo = $ex ? class_basename($ex->existenciable_type) : 'Desconocido';
                            $descripcion = $ex ? $ex->existenciable->descripcion : 'Existencia #' . $repo->id;
                            $comprobante = $repo->comprobantes->first() ? $repo->comprobantes->first()->monto : 0;
                            @endphp
                            <button
                                type="button"
                                wire:click="$set('destino_id', {{ $repo->id }})"
                                class="w-full px-3 py-2 rounded-md border text-sm text-left flex justify-between items-center transition
                        {{ $destino_id == $repo->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                <span>{{ $tipo }}: {{ $descripcion }}</span>
                                <span class="flex items-center gap-2">
                                    <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        Disponible: {{ $repo->cantidad }}
                                    </span>
                                    <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        {{ $ex->sucursal->nombre ?? 'Sin sucursal' }}
                                    </span>
                                    <span class="bg-yellow-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                        Monto: ${{ number_format($comprobante, 2) }}
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


                    <!-- Estado -->
                    <div>
                        <label class="font-semibold text-sm">Estado</label>
                        <div class="flex flex-wrap justify-center gap-2">
                            @foreach(['pendiente' => 'Pendiente', 'terminado' => 'Terminado'] as $st => $label)
                            <button type="button"
                                wire:click="$set('estado','{{ $st }}')"
                                class="px-4 py-2 rounded-full text-sm flex items-center justify-center
                     {{ $estado === $st ? 'bg-cyan-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
                                {{ $label }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Personal -->
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Personal Responsable</label>
                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[170px]">
                            @foreach($personals as $p)
                            <button type="button" wire:click="$set('personal_id', {{ $p->id }})"
                                class="w-full px-3 py-2 rounded-md border text-sm text-left transition
                {{ $personal_id == $p->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                {{ $p->nombres }}
                            </button>
                            @endforeach
                        </div>
                        @error('personal_id') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Observaciones -->
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


</div>
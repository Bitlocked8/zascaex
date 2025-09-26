<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Buscar + Crear Reposición -->
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por capacidad, descripción u observación..."
                class="input-minimal w-full" />
            <button wire:click="abrirModal('create')" class="btn-circle btn-cyan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                    <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                </svg>
            </button>
            <button wire:click="abrirModalConfigGlobal" class="btn-circle btn-cyan" title="Config. Global">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 6l16 0" />
                    <path d="M4 12l16 0" />
                    <path d="M4 18l12 0" />
                </svg>
            </button>

        </div>

        @forelse($reposiciones as $repo)
        <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">

            <!-- Información Reposición -->
            <div class="flex flex-col col-span-9 space-y-1 text-left">
                <p><strong>Nombre:</strong> {{ $repo->existencia->existenciable->descripcion ?? 'N/A' }}</p>
                <p><strong>Cantidad:</strong> {{ $repo->cantidad }}</p>
                <p><strong>Precio Unitario:</strong> {{ $repo->precio_unitario ?? 'N/A' }}</p>
                <p><strong>Proveedor:</strong> {{ $repo->proveedor->razonSocial ?? 'Sin proveedor' }}</p>
                <p><strong>Observaciones:</strong> {{ $repo->observaciones ?? 'N/A' }}</p>

            </div>

            <!-- Botones de acción -->
            <div class="flex flex-col items-end gap-4 col-span-3">
                <button wire:click="abrirModal('edit', {{ $repo->id }})" class="btn-circle btn-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke="none" d="M0 0h24v24H0z" />
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

                <button wire:click="modaldetalle({{ $repo->id }})" class="btn-circle btn-cyan" title="Ver Detalle">
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
            No hay reposiciones registradas.
        </div>
        @endforelse
    </div>

    @if($modal)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">


                <div>
                    <label class="font-semibold text-sm">Fecha</label>
                    <input type="date" wire:model="fecha" class="input-minimal">
                    @error('fecha') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-wrap justify-center gap-2 mb-2">
                    @foreach($sucursales as $sucursal)
                    <button
                        type="button"
                        wire:click="filtrarPorSucursal({{ $sucursal->id }})"
                        class="badge-info {{ $sucursalSeleccionada == $sucursal->id ? 'bg-cyan-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        @if($sucursalSeleccionada == $sucursal->id)<span>&#10003;</span>@endif
                        {{ $sucursal->nombre }}
                    </button>
                    @endforeach
                </div>

                <div>
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Producto/Existencia</label>
                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[170px]">
                            @foreach($existencias as $existencia)
                            <button type="button"
                                wire:click="$set('existencia_id', {{ $existencia->id }})"
                                class="w-full px-3 py-2 rounded-md border text-sm text-left transition
                                    {{ $existencia_id == $existencia->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                {{ $existencia->existenciable->nombre ?? 'Existencia #' . $existencia->id }}
                                (Disponible: {{ $existencia->cantidad }})
                            </button>
                            @endforeach
                        </div>
                        @error('existencia_id') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="font-semibold text-sm mb-2 block">Personal Responsable</label>
                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[170px]">
                            @foreach($personal as $persona)
                            <button type="button"
                                wire:click="$set('personal_id', {{ $persona->id }})"
                                class="w-full px-3 py-2 rounded-md border text-sm text-left transition
                                    {{ $personal_id == $persona->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                {{ $persona->nombres ?? 'Personal #' . $persona->id }}
                            </button>
                            @endforeach
                        </div>
                        @error('personal_id') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="font-semibold text-sm mb-2 block">Proveedor (Opcional)</label>
                    <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[170px]">
                        <button type="button"
                            wire:click="$set('proveedor_id', null)"
                            class="w-full px-3 py-2 rounded-md border text-sm text-left transition
                            {{ $proveedor_id === null ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                            Sin proveedor
                        </button>
                        @foreach($proveedores as $proveedor)
                        <button type="button"
                            wire:click="$set('proveedor_id', {{ $proveedor->id }})"
                            class="w-full px-3 py-2 rounded-md border text-sm text-left transition
                                {{ $proveedor_id == $proveedor->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                            {{ $proveedor->nombre ?? 'Proveedor #' . $proveedor->id }}
                        </button>
                        @endforeach
                    </div>
                    @error('proveedor_id') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-2 mt-2">
                    <div>
                        <label class="font-semibold text-sm">Cantidad</label>
                        <input type="number" wire:model="cantidad" class="input-minimal" min="1">
                        @error('cantidad') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="font-semibold text-sm">Precio Unitario (Opcional)</label>
                        <input type="number" wire:model="precio_unitario" class="input-minimal" step="0.01" min="0">
                        @error('precio_unitario') <span class="error-message">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label class="font-semibold text-sm mb-2 block">Imagen</label>
                    <input type="file" wire:model="imagen" class="input-minimal">
                    @if($imagen)
                    <div class="mt-2 flex justify-center">
                        <img src="{{ is_string($imagen) ? asset('storage/'.$imagen) : $imagen->temporaryUrl() }}"
                            class="w-50 h-50 object-cover rounded"
                            alt="Imagen de reposición">
                    </div>
                    @endif
                    @error('imagen') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="font-semibold text-sm">Observaciones</label>
                    <textarea wire:model="observaciones" class="input-minimal" placeholder="Observaciones" rows="3"></textarea>
                    @error('observaciones') <span class="error-message">{{ $message }}</span> @enderror
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" wire:click="guardar" class="btn-circle btn-cyan" title="Guardar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M14 4l0 4l-6 0l0 -4" />
                    </svg>
                </button>
                <button type="button" wire:click="cerrarModal" class="btn-circle btn-cyan" title="Cerrar">
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



    @if($modalConfigGlobal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-auto p-4">
        <div class="bg-white p-6 rounded-lg w-full max-w-3xl">
            <h2 class="text-lg font-semibold mb-4">Configurar existencias</h2>

            <div class="space-y-4 max-h-[60vh] overflow-y-auto">
                @foreach($existencias as $ex)
                <div class="grid grid-cols-12 gap-2 items-center border-b pb-2">
                    <div class="col-span-4">
                        {{ $ex->existenciable->descripcion ?? 'Sin descripción' }}: {{ $ex->cantidad }}
                    </div>




                    <div class="col-span-4">
                        <input type="number"
                            wire:model="configExistencias.{{ $ex->id }}.cantidad_minima"
                            class="input-minimal w-full"
                            placeholder="Cantidad mínima">
                    </div>

                    <div class="col-span-4">
                        <select wire:model="configExistencias.{{ $ex->id }}.sucursal_id" class="input-minimal w-full">
                            <option value="">Seleccionar sucursal</option>
                            @foreach($sucursales as $suc)
                            <option value="{{ $suc->id }}">{{ $suc->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button wire:click="guardarConfigGlobal" class="bg-cyan-500 text-white px-4 py-2 rounded">Guardar</button>
                <button wire:click="$set('modalConfigGlobal', false)" class="bg-gray-300 px-4 py-2 rounded">Cancelar</button>
            </div>
        </div>
    </div>
    @endif

    @if($modalDetalle)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-6">
                <!-- Imagen -->
                <div class="flex justify-center items-center">
                    @if($existenciaSeleccionada->existenciable->imagen)
                    <img src="{{ asset('storage/'.$existenciaSeleccionada->existenciable->imagen) }}"
                        class="w-50 h-50 object-cover rounded" alt="Imagen Stock">
                    @else
                    <span class="badge-info">Sin imagen</span>
                    @endif
                </div>

                <!-- Información -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                            <span class="label-info">Cantidad:</span>
                            <span class="badge-info">{{ $existenciaSeleccionada->cantidad }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                            <span class="label-info">Observaciones:</span>
                            <span class="badge-info">{{ $existenciaSeleccionada->existenciable->observaciones ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                            <span class="label-info">Nombre:</span>
                            <span class="badge-info">{{ $existenciaSeleccionada->existenciable->descripcion ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                            <span class="label-info">Sucursal:</span>
                            <span class="badge-info">{{ $existenciaSeleccionada->sucursal->nombre ?? '-' }}</span>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button wire:click="$set('modalDetalle', false)" class="btn-circle btn-cyan" title="Cerrar">
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





</div>
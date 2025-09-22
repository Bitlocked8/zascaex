<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Buscar + Crear Reposición -->
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="search" placeholder="Buscar por existencia o descripción..."
                class="flex-1 border rounded px-3 py-2" />

            <button wire:click="abrirModal('create')"
                class="bg-cyan-500 hover:bg-cyan-600 rounded-xl px-4 py-2 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9" />
                    <line x1="12" y1="9" x2="12" y2="15" />
                    <line x1="9" y1="12" x2="15" y2="12" />
                </svg>
            </button>

            <button wire:click="abrirModalConfigGlobal"
                class="bg-cyan-500 hover:bg-cyan-600 rounded-xl px-4 py-2 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 6l16 0" />
                    <path d="M4 12l16 0" />
                    <path d="M4 18l12 0" />
                </svg>
            </button>
        </div>

        @forelse($reposiciones as $repo)
        <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
            <!-- Columna izquierda: Imagen + Info -->
            <div class="flex flex-col items-center md:items-start text-center md:text-left col-span-8">
                @if ($repo->imagen)
                <img src="{{ asset('storage/' . $repo->imagen) }}"
                    alt="Imagen Reposición"
                    class="w-56 h-56 object-cover rounded-lg shadow-md mb-3"
                    loading="lazy">
                @else
                <div class="w-56 h-56 bg-gray-200 flex items-center justify-center rounded-lg shadow mb-3">
                    <span class="text-gray-500">Sin imagen</span>
                </div>
                @endif

                <h3 class="text-lg font-semibold uppercase text-cyan-600">
                    {{ class_basename($repo->existencia->existenciable) }}:
                    {{ $repo->existencia->existenciable->descripcion ?? 'Sin descripción' }}
                </h3>


                <p class="text-cyan-950"><strong>Cantidad:</strong> {{ $repo->cantidad }}</p>
                <p class="text-cyan-950"><strong>Precio Unitario:</strong> {{ $repo->precio_unitario ?? 'N/A' }}</p>
                <p class="text-cyan-950"><strong>Proveedor:</strong> {{ $repo->proveedor->razonSocial ?? 'Sin proveedor' }}</p>

                <div class="mt-2">
                    @if($repo->existencia->cantidad >= $repo->existencia->cantidadMinima)
                    <span class="px-3 py-1 text-sm font-semibold text-white bg-green-600 rounded-full shadow">
                        Stock suficiente
                    </span>
                    @else
                    <span class="px-3 py-1 text-sm font-semibold text-white bg-red-600 rounded-full shadow">
                        Stock bajo
                    </span>
                    @endif
                </div>
            </div>

            <!-- Columna derecha: botones -->
            <div class="flex flex-col items-center md:items-end gap-4 col-span-4">
                <!-- Editar -->
                <button wire:click="abrirModal('edit', {{ $repo->id }})"
                    class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600">
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

                <!-- Ver Detalle -->
                <button wire:click="modaldetalle({{ $repo->id }})"
                    class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-cyan-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
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
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white text-cyan-950 rounded-lg shadow-lg w-full max-w-4xl p-6 relative overflow-y-auto max-h-[90vh]">

            <h2 class="text-xl font-semibold mb-6 text-center">
                {{ $accion === 'create' ? 'Registrar Reposición' : 'Editar Reposición' }}
            </h2>

            <div class="grid grid-cols-12 gap-4">

                <!-- Columna 1: Campos principales -->
                <div class="col-span-12 md:col-span-6 flex flex-col gap-4">


                    <input type="file" wire:model="imagen" class="input-minimal">
                    @error('imagen') <span class="error-message">{{ $message }}</span> @enderror

                    @if ($imagen)
                    <div class="mt-2">
                        <img src="{{ is_object($imagen) ? $imagen->temporaryUrl() : asset('storage/' . $imagen) }}"
                            alt="Imagen reposición" class="w-56 h-56 object-cover rounded-lg shadow">
                    </div>
                    @endif

                    <select wire:model="existencia_id" class="input-minimal">
                        <option value="">Seleccionar Existencia</option>
                        @foreach($existencias as $ex)
                        <option value="{{ $ex->id }}">
                            {{ class_basename($ex->existenciable) }}: {{ $ex->existenciable->descripcion ?? 'Sin descripción' }} (Stock: {{ $ex->cantidad }})
                        </option>

                        @endforeach
                    </select>
                    @error('existencia_id') <span class="error-message">{{ $message }}</span> @enderror

                    <input type="number" wire:model="cantidad" placeholder="Cantidad" class="input-minimal">
                    @error('cantidad') <span class="error-message">{{ $message }}</span> @enderror

                    <input type="number" step="0.01" wire:model="precio_unitario" placeholder="Precio Unitario" class="input-minimal">
                    @error('precio_unitario') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Columna 2: Proveedor, Personal, Observaciones, Fecha -->
                <div class="col-span-12 md:col-span-6 flex flex-col gap-4">
                    <select wire:model="proveedor_id" class="input-minimal">
                        <option value="">Seleccionar Proveedor (opcional)</option>
                        @foreach($proveedores as $prov)
                        <option value="{{ $prov->id }}">{{ $prov->razonSocial }}</option>
                        @endforeach
                    </select>
                    @error('proveedor_id') <span class="error-message">{{ $message }}</span> @enderror

                    <select wire:model="personal_id" class="input-minimal">
                        <option value="">Seleccionar Personal</option>
                        @foreach($personal as $pers)
                        <option value="{{ $pers->id }}">{{ $pers->nombres }} {{ $pers->apellidos }}</option>
                        @endforeach
                    </select>
                    @error('personal_id') <span class="error-message">{{ $message }}</span> @enderror

                    <input type="text" wire:model="observaciones" placeholder="Observaciones" class="input-minimal">
                    @error('observaciones') <span class="error-message">{{ $message }}</span> @enderror

                    <input type="date" wire:model="fecha" class="input-minimal">
                    @error('fecha') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Botones acción -->
                <div class="col-span-12 flex justify-center md:justify-end gap-4 mt-4">
                    <button type="button" wire:click="guardar"
                        class="bg-cyan-500 hover:bg-cyan-600 rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                        </svg>
                    </button>
                    <button type="button" wire:click="cerrarModal"
                        class="bg-gray-300 hover:bg-gray-400 rounded-xl w-12 h-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

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





</div>
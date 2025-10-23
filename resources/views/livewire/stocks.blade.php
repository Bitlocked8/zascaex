<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3 class="inline-block bg-teal-700 text-white px-5 py-2 rounded-full text-xl font-bold uppercase shadow-md">
            Reposiciones
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input
                type="text"
                wire:model.live="searchCodigo"
                placeholder="Buscar por código..."
                class="input-minimal w-full" />

            <button wire:click="abrirModal('create')" class="btn-cyan flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                </svg>
                Añadir
            </button>

            @php $usuario = auth()->user(); @endphp
            @if($usuario && $usuario->rol_id === 1)
            <button wire:click="abrirModalConfigGlobal" class="btn-cyan flex items-center gap-1" title="Config. Global">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 6l16 0" />
                    <path d="M4 12l16 0" />
                    <path d="M4 18l12 0" />
                </svg>
                Config
            </button>
            @endif
        </div>

        @forelse($reposiciones as $repo)
        <div class="card-teal flex flex-col gap-4">
            <div class="flex flex-col gap-1">
                <p class="text-u">{{ $repo->codigo ?? 'N/A' }}</p>
                <p><strong>Nombre:</strong> {{ $repo->existencia->existenciable->descripcion ?? 'N/A' }}</p>
                <p><strong>Cantidad inicial:</strong> {{ $repo->cantidad_inicial }}</p>
                <p><strong>Cantidad disponible:</strong> {{ $repo->cantidad }}</p>
                <p><strong>Proveedor:</strong> {{ $repo->proveedor->razonSocial ?? 'Sin proveedor' }}</p>
                <p><strong>Observaciones:</strong> {{ $repo->observaciones ?? 'N/A' }}</p>
                <p><strong>Estado:</strong>
                    <span class="inline-block px-2 py-1 rounded-full text-sm font-semibold
            {{ $repo->estado_revision ? 'bg-emerald-600 text-white' : 'bg-yellow-500 text-white' }}">
                        {{ $repo->estado_revision ? 'Confirmado' : 'En Revisión' }}
                    </span>
                </p>

                @if($repo->soplados()->exists() && $repo->comprobantes && $repo->comprobantes->count() > 0)
                <div class="mt-3 border-t border-gray-200 pt-2">
                    <div class="flex flex-wrap gap-2">
                        @foreach($repo->comprobantes as $comprobante)
                        <span class="inline-block bg-cyan-700 text-white px-3 py-1 rounded-full text-sm font-semibold uppercase">
                            {{ $comprobante->codigo }} - {{ number_format($comprobante->monto, 2, ',', '.') }} Bs
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="flex gap-2 overflow-x-auto no-scrollbar border-t border-gray-200 pt-3 pb-1 justify-start md:justify-between">
                <button wire:click="abrirModal('edit', {{ $repo->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Editar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                        <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                        <path d="M16 5l3 3" />
                    </svg>
                    Editar
                </button>

                <button wire:click="verDetalleReposicion({{ $repo->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Ver Detalle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9h.01" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 12h1v4h1" />
                    </svg>
                    Detalles
                </button>

                <button wire:click="abrirModalPagos({{ $repo->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Pagos">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 19h-6a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v4.5" />
                        <path d="M3 10h18" />
                        <path d="M16 19h6" />
                        <path d="M19 16l3 3l-3 3" />
                        <path d="M7.005 15h.005" />
                        <path d="M11 15h2" />
                    </svg>
                    Pagos
                </button>

                @if(!$repo->estado_revision)
                <button wire:click="confirmarEliminarReposicion({{ $repo->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Eliminar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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

                @if($repo->cantidad === $repo->cantidad_inicial)
                <button wire:click="toggleEstado({{ $repo->id }})"
                    class="btn-cyan flex items-center gap-1 flex-shrink-0 {{ $repo->estado_revision ? 'bg-cyan-600 text-white' : 'bg-white text-cyan-600 border border-cyan-600' }}"
                    title="{{ $repo->estado_revision ? 'Confirmado' : 'En Revisión' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 11l3 3l8 -8" />
                        <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                    </svg>
                    {{ $repo->estado_revision ? 'Confirmado' : 'Revisar' }}
                </button>
                @endif
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
                <div class="grid grid-cols-1 gap-2 mt-2">
                    <p class="text-u">
                        Código: <span>{{ $codigo }}</span>
                    </p>
                    <p class="text-u">
                        Fecha: <span>{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</span>
                    </p>
                    <p class="text-u">
                        Personal: <span>
                            {{ optional(\App\Models\Personal::find($personal_id))->nombres ?? 'Sin nombre' }}
                        </span>
                    </p>
                    <p class="text-u">
                        Sucursal: <span>
                            {{ optional($existencias->firstWhere('id', $existencia_id))->sucursal->nombre ?? 'Sin sucursal' }}
                        </span>
                    </p>
                </div>
                <div class="grid grid-cols-1 gap-2 mt-2">
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Producto (Requerido)</label>

                        @if($accion === 'edit')
                        @php
                        $ex = $existencias->firstWhere('id', $existencia_id);
                        $tipo = $ex ? class_basename($ex->existenciable_type) : 'Desconocido';
                        $cantidadDisponible = $ex ? $ex->reposiciones->sum('cantidad') : 0;
                        @endphp

                        <p class="flex flex-col items-center gap-2 p-4 rounded-lg border-2 bg-white text-gray-800 text-center">
                            <span class="font-medium">
                                {{ $tipo }}: {{ $ex->existenciable->descripcion ?? ('Existencia #' . $existencia_id) }}
                            </span>

                            <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                Disponible: {{ $cantidadDisponible }}
                            </span>

                            <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                {{ $ex->sucursal->nombre ?? 'Sin sucursal' }}
                            </span>
                        </p>
                        @else

                        <div class="flex-1">
                            <label for="busquedaProducto" class="block text-sm font-medium text-gray-700">
                                Buscar producto
                            </label>
                            <div class="flex gap-4 mb-4 col-span-full">
                                <!-- Filtro por Sucursal -->
                                <select wire:model="filtroSucursal" class="input-minimal">
                                    <option value="">Todas las sucursales</option>
                                    @foreach($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                    @endforeach
                                </select>

                                <!-- Filtro por Tipo -->
                                <select wire:model="filtroTipo" class="input-minimal">
                                    <option value="">Todos los tipos</option>
                                    <option value="Base">Base</option>
                                    <option value="Producto">Producto</option>
                                    <option value="Etiqueta">Etiqueta</option>
                                    <option value="Tapa">Tapa</option>
                                    <option value="Preforma">Preforma</option>
                                </select>
                            </div>

                        </div>

                        <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                            @forelse($existencias as $existencia)
                            @php
                            $tipo = class_basename($existencia->existenciable_type);
                            $disabled = isset($existencia->existenciable->estado) && !$existencia->existenciable->estado;
                            $cantidadDisponible = $existencia->reposiciones->sum('cantidad');
                            @endphp

                            <button
                                type="button"
                                wire:click="$set('existencia_id', {{ $existencia->id }})"
                                class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                   {{ $existencia_id == $existencia->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}
                                  bg-white {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if($disabled) disabled @endif>

                                <span class="text-u">
                                    {{ $tipo }}: {{ $existencia->existenciable->descripcion ?? 'Existencia #' . $existencia->id }}
                                </span>

                                <div class="flex flex-wrap justify-center gap-3 mt-2">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-xs font-medium text-gray-600">
                                            {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}
                                        </span>
                                        <span class="bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                                            {{ $cantidadDisponible }} Disponibles
                                        </span>
                                    </div>
                                </div>
                            </button>
                            @empty
                            <p class="text-gray-500 text-sm text-center py-2 col-span-full">
                                No hay productos disponibles
                            </p>
                            @endforelse
                        </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 gap-2 mt-2">
                        <div>
                            <label class="font-semibold text-sm">Cantidad (Requerido)</label>
                            @if($accion === 'edit')
                            <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">{{ $cantidad }}</span>
                            @else
                            <input type="number" wire:model="cantidad" placeholder="ingrese una cantidad" class="input-minimal" min="1">
                            @endif
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 mt-2">
                        <div>
                            <label class="font-semibold text-sm mb-2 block">Proveedor (Opcional)</label>

                            <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                                <button type="button"
                                    wire:click="$set('proveedor_id', null)"
                                    class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                       {{ $proveedor_id === null ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}
                                           bg-white">
                                    <span class="font-medium">Sin proveedor</span>
                                </button>
                                @foreach($proveedores as $proveedor)
                                <button type="button"
                                    wire:click="$set('proveedor_id', {{ $proveedor->id }})"
                                    class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                      {{ $proveedor_id == $proveedor->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}
                                        bg-white">
                                    <span class="text-u">
                                        {{ $proveedor->razonSocial ?? 'Proveedor #' . $proveedor->id }}
                                    </span>

                                    <div class="flex flex-wrap justify-center gap-3 mt-2">
                                        <span class="bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold uppercase">
                                            {{ $proveedor->servicio ?? 'Sin servicio' }}
                                        </span>
                                        <span class="bg-gray-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold uppercase">
                                            {{ $proveedor->tipo ?? 'Sin dirección' }}
                                        </span>
                                    </div>
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="font-semibold text-sm">Observaciones (Opcional)</label>
                        <input wire:model="observaciones" class="input-minimal" placeholder="Observaciones del producto">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="cerrarModal" class="btn-cyan" title="Cerrar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M10 10l4 4m0 -4l-4 4" />
                            <circle cx="12" cy="12" r="9" />
                        </svg>
                        CERRAR</button>
                    <button type="button" wire:click="guardar" class="btn-cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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


    @if($modalConfigGlobal)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content">
                @forelse($existencias as $ex)
                <div class="grid grid-cols-12 gap-4 items-start border-b pb-4">
                    <div class="col-span-6 flex flex-col gap-1">
                        <span class="uppercase text-cyan-600 font-semibold">
                            {{ class_basename($ex->existenciable_type) }}
                        </span>
                        <span class="font-medium">
                            {{ $ex->existenciable->descripcion ?? 'Sin Nombre' }}
                        </span>
                        <span class="bg-cyan-600 text-white text-xs px-2 py-1 rounded-full font-semibold w-max">
                            Disponible: {{ $ex->cantidad }}
                        </span>
                    </div>

                    <div class="col-span-6 flex flex-col gap-2">
                        <div>
                            <label class="font-semibold text-sm mb-2 block">Sucursal (Opcional)</label>
                            <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 max-h-[150px] overflow-y-auto">
                                <button type="button"
                                    wire:click="$set('configExistencias.{{ $ex->id }}.sucursal_id', null)"
                                    class="w-full px-3 py-2 rounded-md border text-sm text-left transition
                                  {{ $configExistencias[$ex->id]['sucursal_id'] === null ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                    Sin sucursal
                                </button>

                                @foreach($sucursales as $suc)
                                <button type="button"
                                    wire:click="$set('configExistencias.{{ $ex->id }}.sucursal_id', {{ $suc->id }})"
                                    class="w-full px-3 py-2 rounded-md border text-sm text-left transition
                                   {{ $configExistencias[$ex->id]['sucursal_id'] == $suc->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                    {{ $suc->nombre }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">
                    No hay existencias disponibles para asignar a una sucursal.
                </div>
                @endforelse
            </div>

            <div class="modal-footer">
                <button wire:click="guardarConfigGlobal" class="btn-circle btn-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M14 4l0 4l-6 0l0 -4" />
                    </svg>
                </button>
                <button wire:click="$set('modalConfigGlobal', false)" class="btn-circle btn-cyan" title="Cerrar">
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
    @if($modalPagos)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">
                <div class="space-y-4">
                    @foreach($pagos as $index => $pago)
                    <div class="border p-4 rounded flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <strong>Código: {{ $pago['codigo'] }}</strong>
                            <p class="text-sm text-gray-600">
                                Fecha: {{ $pago['fecha'] }}
                            </p>
                            <button type="button" wire:click="confirmarEliminarPago({{ $index }})" class="btn-circle btn-cyan"
                                title="Eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 7l16 0" />
                                    <path d="M10 11l0 6" />
                                    <path d="M14 11l0 6" />
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                            </button>


                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                            <div class="sm:col-span-2">
                                <label class="font-semibold text-sm">Monto (Requerido)</label>
                                <input type="number" wire:model="pagos.{{ $index }}.monto" class="input-minimal" min="0">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="font-semibold text-sm">Observaciones (Opcional)</label>
                                <input type="text" wire:model="pagos.{{ $index }}.observaciones" class="input-minimal">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="font-semibold text-sm">Imagen (Opcional)</label>
                                <input type="file" wire:model="pagos.{{ $index }}.imagen" class="input-minimal">

                                @php
                                $imagenUrl = null;
                                if (isset($pagos[$index]['imagen'])) {
                                $imagenUrl = is_string($pagos[$index]['imagen'])
                                ? Storage::url($pagos[$index]['imagen'])
                                : $pagos[$index]['imagen']->temporaryUrl();
                                }
                                @endphp

                                @if($imagenUrl)
                                <div class="mt-2 flex flex-col items-center space-y-2">
                                    <img src="{{ $imagenUrl }}"
                                        alt="Imagen"
                                        class="w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg object-cover rounded-lg shadow cursor-pointer"
                                        wire:click="$set('imagenPreviewModal', '{{ $imagenUrl }}'); $set('modalImagenAbierta', true)">
                                    @if(is_string($pagos[$index]['imagen']))
                                    <a href="{{ $imagenUrl }}" download
                                        class="btn-circle btn-cyan">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                            <path d="M7 11l5 5l5 -5" />
                                            <path d="M12 4l0 12" />
                                        </svg>
                                    </a>
                                    @endif
                                </div>
                                @endif
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="agregarPago" class="btn-cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                            <path d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                        </svg>
                        añadir pago
                    </button>

                    <button type="button" wire:click="guardarPagos" class="btn-cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M14 4l0 4l-6 0l0 -4" />
                        </svg>
                        guardar pago
                    </button>
                    <button type="button" wire:click="$set('modalPagos', false)" class="btn-cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <path d="M10 10l4 4m0 -4l-4 4" />
                            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                        </svg>
                        cerrar
                    </button>

                </div>

            </div>
        </div>
    </div>
    @endif
    @if($modalDetalle && $reposicionSeleccionada)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">
                <div class="flex justify-center items-center">
                    <div class="w-20 h-20 rounded-full bg-cyan-600 text-white flex items-center justify-center text-2xl font-bold">
                        {{ strtoupper(substr($reposicionSeleccionada->codigo ?? '-', 0, 1)) }}
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Código:</span>
                            <span class="badge-info">{{ $reposicionSeleccionada->codigo ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Fecha:</span>
                            <span class="badge-info">{{ \Carbon\Carbon::parse($reposicionSeleccionada->fecha)->format('d/m/Y H:i') ?? '-' }}</span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Cantidad:</span>
                            <span class="badge-info">{{ $reposicionSeleccionada->cantidad ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Responsable:</span>
                            <span class="badge-info">{{ $reposicionSeleccionada->personal?->nombres ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="flex flex-col gap-2">
                        <span class="label-info">Nombre:</span>
                        <span class="badge-info">{{ $reposicionSeleccionada->existencia->existenciable?->descripcion ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="label-info">Observaciones:</span>
                        <span class="badge-info">{{ $reposicionSeleccionada->observaciones ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer mt-4">
                <button wire:click="cerrarModalDetalleReposicion" class="btn-cyan" title="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M10 10l4 4m0 -4l-4 4" />
                        <circle cx="12" cy="12" r="9" />
                    </svg>
                    CERRAR
                </button>
            </div>
        </div>
    </div>
    @endif
    @if($confirmingDeleteReposicionId)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content">
                <div class="flex flex-col gap-4 text-center">
                    <h2 class="text-lg font-semibold">¿Estás seguro?</h2>
                    <p class="text-gray-600">
                        La reposición seleccionada se eliminará y se revertirán los cambios en stock.
                    </p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" wire:click="eliminarReposicionConfirmado" class="btn-circle btn-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M18.333 2c1.96 0 3.56 1.537 3.662 3.472l.005 .195v12.666c0 1.96 -1.537 3.56 -3.472 3.662l-.195 .005h-12.666a3.667 3.667 0 0 1 -3.662 -3.472l-.005 -.195v-12.666c0 -1.96 1.537 -3.56 3.472 -3.662l.195 -.005h12.666zm-2.626 7.293a1 1 0 0 0 -1.414 0l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.32 1.497l2 2l.094 .083a1 1 0 0 0 1.32 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                    </svg>
                </button>

                <!-- Botón Cancelar -->
                <button type="button" wire:click="$set('confirmingDeleteReposicionId', null)" class="btn-circle btn-cyan">
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
    @if($confirmingDeletePagoIndex !== null)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content">
                <div class="flex flex-col gap-4 text-center">
                    <h2 class="text-lg font-semibold">¿Estás seguro?</h2>
                    <p class="text-gray-600">
                        El comprobante de pago seleccionado se eliminará permanentemente.
                    </p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" wire:click="eliminarPagoConfirmado" class="btn-circle btn-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M18.333 2c1.96 0 3.56 1.537 3.662 3.472l.005 .195v12.666c0 1.96 -1.537 3.56 -3.472 3.662l-.195 .005h-12.666a3.667 3.667 0 0 1 -3.662 -3.472l-.005 -.195v-12.666c0 -1.96 1.537 -3.56 3.472 -3.662l.195 -.005h12.666zm-2.626 7.293a1 1 0 0 0 -1.414 0l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.32 1.497l2 2l.094 .083a1 1 0 0 0 1.32 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                    </svg>
                </button>

                <!-- Botón Cancelar -->
                <button type="button" wire:click="$set('confirmingDeletePagoIndex', null)" class="btn-circle btn-cyan">
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
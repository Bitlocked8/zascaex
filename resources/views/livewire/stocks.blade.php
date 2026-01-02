<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl">

        <h3 class="text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full shadow-sm mb-4">
            Reposición de materiales
        </h3>

        <div class="flex items-center gap-2 mb-4">
            <input type="text" wire:model.live="searchCodigo" placeholder="Buscar por código..."
                class="input-minimal w-full" />
            <button wire:click="abrirModal('create')" class="btn-cyan">
                Añadir
            </button>
            @php $usuario = auth()->user(); @endphp
            @if($usuario && $usuario->rol_id === 1)
            <button wire:click="abrirModalConfigGlobal" class="btn-cyan">
                Config
            </button>
            @endif
            <button wire:click="abrirModalNotificaciones" class="btn-cyan">
                Bajo stock
            </button>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Código</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tipo</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Descripción</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Capacidad</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Cantidad inicial</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Cantidad disponible</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Proveedor</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Estado</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reposiciones as $repo)
                    @php
                    $tipoModelo = $repo->existencia->existenciable_type ?? 'N/A';
                    $tipoModelo = $tipoModelo !== 'N/A' ? class_basename($tipoModelo) : 'N/A';
                    $descripcion = $repo->existencia->existenciable->descripcion ?? 'sin descripción';
                    $capacidad = $repo->existencia->existenciable->capacidad ?? '-';
                    $unidad = $repo->existencia->existenciable->unidad ?? '';
                    @endphp
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $repo->codigo ?? 'N/A' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $tipoModelo }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $descripcion }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $capacidad }} {{ $unidad }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $repo->cantidad_inicial }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $repo->cantidad }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $repo->proveedor->razonSocial ?? 'Sin proveedor' }}</td>
                        <td class="px-4 py-2 text-sm font-semibold {{ $repo->estado_revision ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $repo->estado_revision ? 'Confirmado' : 'En revisión' }}
                        </td>
                        <td class="px-4 py-2 flex flex-wrap justify-center gap-1">
                            <button wire:click="verDetalleReposicion({{ $repo->id }})" class="btn-cyan btn-sm">Ver</button>
                            <button wire:click="abrirModal('edit', {{ $repo->id }})" class="btn-cyan btn-sm">Editar</button>
                            <button wire:click="abrirModalPagos({{ $repo->id }})" class="btn-cyan btn-sm">Pagos</button>
                            @if(!$repo->soplados()->exists() && !$repo->llenados()->exists() && !$repo->adornados()->exists() && !$repo->tieneTraspasos())
                            @if(!$repo->estado_revision)
                            <button wire:click="confirmarEliminarReposicion({{ $repo->id }})" class="btn-cyan btn-sm">Eliminar</button>
                            @endif
                            @if($repo->cantidad === $repo->cantidad_inicial)
                            <button wire:click="toggleEstado({{ $repo->id }})" class="btn-cyan btn-sm {{ $repo->estado_revision ? 'bg-cyan-600 text-white' : 'bg-white text-cyan-600 border border-cyan-600' }}">
                                {{ $repo->estado_revision ? 'Confirmado' : 'Revisar' }}
                            </button>
                            @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-4 text-center text-gray-600">No hay reposiciones registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reposiciones->count() >= 50)
        <div class="col-span-full text-center mt-4 flex justify-center gap-2">
            <button wire:click="disminuirCantidad"
                class="btn-cyan px-4 py-2 rounded">
                Cargar menos
            </button>

            <button wire:click="aumentarCantidad"
                class="btn-cyan px-4 py-2 rounded">
                Cargar más
            </button>
        </div>
        @endif

    </div>




    @if($modal)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">
                <div class="grid grid-cols-1 gap-2 mt-2">
                    <p class="text-u">
                        Código: <span>{{ $codigo }}</span>
                    </p>
                    <p class="font-semibold text-sm">
                        Fecha: <span class="font-normal">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</span>
                    </p>
                    <p class="font-semibold text-sm">
                        Personal: <span class="font-normal">
                            {{ optional(\App\Models\Personal::find($personal_id))->nombres ?? 'Sin nombre' }}
                        </span>
                    </p>
                    <p class="font-semibold text-sm">
                        Sucursal: <span class="font-normal">
                            {{ optional($existencias->firstWhere('id', $existencia_id))->sucursal->nombre ?? 'Sin sucursal' }}
                        </span>
                    </p>
                </div>
                <div class="grid grid-cols-1 gap-2 mt-2">

                    <div>
                        <label class="text-u">Producto (Requerido)</label>

                        @if($accion === 'edit')
                        @php
                        $ex = $existencias->firstWhere('id', $existencia_id);
                        $tipo = $ex ? class_basename($ex->existenciable_type) : 'Desconocido';
                        $cantidadDisponible = $ex ? $ex->reposiciones->sum('cantidad') : 0;
                        @endphp

                        <p
                            class="flex flex-col items-center gap-2 p-4 rounded-lg border-2 bg-white text-gray-800 text-center">
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

                        @php $usuario = auth()->user(); @endphp

                        @if($usuario && $usuario->rol_id === 1)
                        <div class="mb-4">
                            <label class="block text-sm font-semibold mb-2">Filtrar por Sucursal</label>
                            <div class="flex flex-wrap gap-3">

                                @foreach($sucursales as $sucursal)
                                <button type="button" wire:click="filtrarSucursalModal({{ $sucursal->id }})"
                                    class="flex-1 sm:flex-auto px-4 py-2 rounded-lg text-sm font-medium transition 
                                                                                                                                                                                                                                                                                                            {{ $filtroSucursalModal == $sucursal->id
                                                ? 'bg-cyan-600 text-white shadow-lg border border-cyan-600'
                                                : 'bg-gray-200 text-gray-700 border border-gray-300 hover:bg-cyan-100 hover:text-cyan-600 hover:border-cyan-600' }}">
                                    {{ $sucursal->nombre }}
                                </button>
                                @endforeach

                            </div>
                        </div>
                        @endif



                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar producto</label>
                            <input type="text" wire:model.live="searchExistencia" class="input-minimal w-full"
                                placeholder="Escribe la descripción..." />
                        </div>
                        <div
                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[300px]">
                            @forelse($existencias as $existencia)
                            @php
                            $tipo = class_basename($existencia->existenciable_type);
                            $disabled = isset($existencia->existenciable->estado) && !$existencia->existenciable->estado;
                            $cantidadDisponible = $existencia->reposiciones->sum('cantidad');
                            $capacidad = $existencia->existenciable->capacidad ?? null;
                            $unidad = $existencia->existenciable->unidad ?? null;
                            $color = $existencia->existenciable->color ?? null;

                            @endphp
                            <button type="button" wire:click="$set('existencia_id', {{ $existencia->id }})"
                                class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center {{ $existencia_id == $existencia->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}bg-white {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if($disabled) disabled @endif>
                                <span class="text-u font-medium">
                                    {{ $tipo }}:
                                    {{ $existencia->existenciable->descripcion ?? 'Existencia #' . $existencia->id }}
                                </span>
                                @if($capacidad)
                                <span class="text-sm font-semibold">
                                    Capacidad: {{ $capacidad }}
                                </span>
                                @endif
                                @if($unidad)
                                <span class="text-sm font-semibold">
                                    Unidad: {{ $unidad }}
                                </span>
                                @endif
                                @if($color)
                                <span class="text-sm font-semibold">
                                    color: {{ $color }}
                                </span>
                                @endif


                                <div class="flex flex-wrap justify-center gap-3 mt-2">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-xs font-medium text-gray-600">
                                            {{ $existencia->sucursal->nombre ?? 'Sin sucursal' }}
                                        </span>
                                        <span
                                            class="bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
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
                            <label class="text-u">Cantidad (Requerido)</label>
                            @if($accion === 'edit')
                            <span
                                class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold">{{ $cantidad }}</span>
                            @else
                            <input type="number" wire:model="cantidad" placeholder="ingrese una cantidad"
                                class="input-minimal" min="1">
                            @endif
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 mt-2">
                        <div>
                            <label class="font-semibold text-sm mb-2 block">Proveedor (Opcional)</label>

                            <div
                                class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[150px]">
                                <button type="button" wire:click="$set('proveedor_id', null)"
                                    class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center{{ $proveedor_id === null ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}bg-white">
                                    <span class="font-medium">Sin proveedor</span>
                                </button>
                                @foreach($proveedores as $proveedor)
                                <button type="button" wire:click="$set('proveedor_id', {{ $proveedor->id }})"
                                    class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center{{ $proveedor_id == $proveedor->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }}bg-white">
                                    <span class="text-u">
                                        {{ $proveedor->razonSocial ?? 'Proveedor #' . $proveedor->id }}
                                    </span>

                                    <div class="flex flex-wrap justify-center gap-3 mt-2">
                                        <span
                                            class="bg-teal-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold uppercase">
                                            {{ $proveedor->servicio ?? 'Sin servicio' }}
                                        </span>
                                        <span
                                            class="bg-gray-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold uppercase">
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
                        <input wire:model="observaciones" class="input-minimal"
                            placeholder="Observaciones del producto">
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


    @if($modalConfigGlobal)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">
                <div class="grid grid-cols-1 gap-3 mt-2">

                    @forelse($existencias as $ex)
                    @php
                    $tipo = class_basename($ex->existenciable_type);
                    $cantidadDisponible = $ex->cantidad;
                    @endphp
                    <div class="w-full border rounded-lg p-4 bg-white shadow-sm flex flex-col gap-3">
                        <div class="flex flex-col gap-1">
                            <span class="uppercase text-cyan-600 font-semibold text-sm">{{ $tipo }}</span>
                            <span class="font-medium text-base">
                                {{ $ex->existenciable->descripcion ?? 'Sin nombre' }}
                            </span>
                            <span class="bg-teal-600 text-white text-xs px-2 py-1 rounded-full font-semibold w-max">
                                Disponible: {{ $cantidadDisponible }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-semibold text-center">Sucursal (Opcional)</label>

                            <div class="flex flex-col gap-2 max-h-[150px] overflow-y-auto">
                                <button type="button"
                                    wire:click="$set('configExistencias.{{ $ex->id }}.sucursal_id', null)" class="w-full px-3 py-2 rounded-lg border font-medium text-sm text-center transition
                                                                                                                                                                            {{ $configExistencias[$ex->id]['sucursal_id'] === null
                            ? 'bg-cyan-600 text-white shadow-md'
                            : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                    Sin sucursal
                                </button>
                                @foreach($sucursales as $suc)
                                <button type="button"
                                    wire:click="$set('configExistencias.{{ $ex->id }}.sucursal_id', {{ $suc->id }})"
                                    class="w-full px-3 py-2 rounded-lg border font-medium text-sm text-center transition
                                                                                                                                                                                                                                                                                                                                                {{ $configExistencias[$ex->id]['sucursal_id'] == $suc->id
                                                    ? 'bg-cyan-600 text-white shadow-md'
                                                    : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                                    {{ $suc->nombre }}
                                </button>
                                @endforeach
                            </div>
                        </div>

                    </div>

                    @empty
                    <p class="text-gray-500 text-center py-4">
                        No hay existencias disponibles para asignar a una sucursal.
                    </p>
                    @endforelse

                </div>
                <div class="modal-footer">
                    <button wire:click="$set('modalConfigGlobal', false)" class="btn-cyan" title="Cerrar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                            <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
                        </svg>
                        CERRAR
                    </button>
                    <button wire:click="guardarConfigGlobal" class="btn-cyan">
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
                            <button type="button" wire:click="confirmarEliminarPago({{ $index }})"
                                class="btn-circle btn-cyan" title="Eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
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
                                <label class="text-u">Monto (Requerido)</label>
                                <input type="number" wire:model="pagos.{{ $index }}.monto" class="input-minimal"
                                    min="0">
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
                                    <img src="{{ $imagenUrl }}" alt="Imagen"
                                        class="w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg object-cover rounded-lg shadow cursor-pointer"
                                        wire:click="$set('imagenPreviewModal', '{{ $imagenUrl }}'); $set('modalImagenAbierta', true)">
                                    @if(is_string($pagos[$index]['imagen']))
                                    <a href="{{ $imagenUrl }}" download class="btn-circle btn-cyan">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                            <path
                                d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                        </svg>
                        añadir pago
                    </button>

                    <button type="button" wire:click="guardarPagos" class="btn-cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M14 4l0 4l-6 0l0 -4" />
                        </svg>
                        guardar pago
                    </button>
                    <button type="button" wire:click="$set('modalPagos', false)" class="btn-cyan" title="Cerrar">
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
    </div>
    @endif
    @if($modalDetalle && $reposicionSeleccionada)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">
                <div class="flex justify-center items-center">
                    <div
                        class="w-20 h-20 rounded-full bg-cyan-600 text-white flex items-center justify-center text-2xl font-bold">
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
                            <span
                                class="badge-info">{{ \Carbon\Carbon::parse($reposicionSeleccionada->fecha)->format('d/m/Y H:i') ?? '-' }}</span>
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
                        <span
                            class="badge-info">{{ $reposicionSeleccionada->existencia->existenciable?->descripcion ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="label-info">Observaciones:</span>
                        <span class="badge-info">{{ $reposicionSeleccionada->observaciones ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="cerrarModalDetalleReposicion" class="btn-cyan" title="Cerrar">
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
                <button type="button" wire:click="eliminarReposicionConfirmado" class="btn-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                    Confirmar
                </button>
                <button type="button" wire:click="$set('confirmingDeleteReposicionId', null)" class="btn-cyan">
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M18.333 2c1.96 0 3.56 1.537 3.662 3.472l.005 .195v12.666c0 1.96 -1.537 3.56 -3.472 3.662l-.195 .005h-12.666a3.667 3.667 0 0 1 -3.662 -3.472l-.005 -.195v-12.666c0 -1.96 1.537 -3.56 3.472 -3.662l.195 -.005h12.666zm-2.626 7.293a1 1 0 0 0 -1.414 0l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.32 1.497l2 2l.094 .083a1 1 0 0 0 1.32 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                    </svg>
                </button>
                <button type="button" wire:click="$set('confirmingDeletePagoIndex', null)" class="btn-circle btn-cyan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 10l4 4m0-4l-4 4" />
                        <circle cx="12" cy="12" r="9" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if($modalNotificaciones)
    <div class="modal-overlay">
        <div class="modal-box max-w-lg">
            <div class="modal-content flex flex-col gap-4">
                <div class="flex flex-col items-center justify-center text-center">
                    <div
                        class="w-16 h-16 rounded-full bg-red-600 text-white flex items-center justify-center text-3xl font-bold mb-3">
                        !
                    </div>
                    <h2 class="text-lg font-bold text-gray-800">Productos con Stock Bajo</h2>
                    <p class="text-sm text-gray-500">Estos productos están en o por debajo de su cantidad mínima.</p>
                </div>
                <div class="max-h-80 overflow-y-auto mt-2">
                    @foreach($alertasBajoStock as $item)
                    <div class="flex justify-between items-center border-b border-gray-200 py-2">
                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $item->existenciable->descripcion ?? 'Sin descripción' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $item->sucursal->nombre ?? 'Sin sucursal' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-red-600">
                                Stock: {{ $item->cantidad_total ?? $item->cantidad }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Mínimo: {{ $item->cantidadMinima }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                    @if($alertasBajoStock->isEmpty())
                    <p class="text-center text-gray-500 py-4">No hay productos con stock bajo.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button wire:click="cerrarModalNotificaciones" class="btn-cyan" title="Cerrar">
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
    </div>
    @endif
</div>
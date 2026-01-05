<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl">

        <h3 class="text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full shadow-sm mb-4">
            Distribuciones
        </h3>

        <div class="flex items-center gap-2 mb-4 flex-wrap">
            <input
                type="text"
                wire:model.live="search"
                placeholder="Buscar por código, personal o coche..."
                class="input-minimal w-full sm:w-auto flex-1" />

            <button wire:click="$set('modalDistribucion', true)" class="btn-cyan">
                Añadir
            </button>
        </div>

        <div class="overflow-auto max-h-[400px] border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-teal-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Código</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Personal</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Coche</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Pedidos</th>
                        <th class="px-4 py-2 text-center text-teal-700 font-semibold">Estado</th>
                        <th class="px-4 py-2 text-center text-teal-700 font-semibold">Acciones</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($distribuciones as $dist)
                    <tr class="hover:bg-teal-50 align-top">
                        <td class="px-4 py-2 font-semibold text-cyan-700">
                            {{ $dist->codigo }}

                            <div class="text-xs text-gray-500 mt-1">
                                <div>Asignación: {{ $dist->fecha_asignacion ? \Carbon\Carbon::parse($dist->fecha_asignacion)->format('d/m/Y H:i') : 'N/A' }}</div>
                                <div>Finalización: {{ $dist->fecha_entrega ? \Carbon\Carbon::parse($dist->fecha_entrega)->format('d/m/Y H:i') : 'N/A' }}</div>
                            </div>
                        </td>

                        <td class="px-4 py-2">
                            {{ $dist->personal->nombres ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-2">
                            {{ $dist->coche->placa ?? 'N/A' }}
                        </td>

                        <td class="px-4 py-2">
                            <div class="flex flex-col gap-1 text-sm">
                                @forelse($dist->pedidos as $pedido)
                                <div class="border rounded px-2 py-1 bg-gray-50">
                                    <span class="font-semibold text-cyan-700">
                                        {{ $pedido->codigo }}
                                    </span>

                                    <div class="text-xs text-gray-600">
                                        {{ $pedido->cliente?->nombre
                                                ?? $pedido->solicitudPedido?->cliente?->nombre
                                                ?? 'Cliente N/A'
                                            }}
                                    </div>
                                </div>
                                @empty
                                <span class="text-gray-400 italic text-sm">
                                    Sin pedidos
                                </span>
                                @endforelse
                            </div>
                        </td>

                        <td class="px-4 py-2 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $dist->estado == 0 ? 'bg-yellow-600 text-white' : 'bg-emerald-500 text-white' }}">
                                {{ $dist->estado == 0 ? 'En entrega' : 'Finalizada' }}
                            </span>
                        </td>

                        <td class="px-4 py-2 flex flex-col items-center gap-1">
                            <button wire:click="editarDistribucion({{ $dist->id }})"
                                class="btn-cyan text-xs w-full">
                                Editar
                            </button>

                            <button wire:click="verPedidos({{ $dist->id }})"
                                class="btn-cyan text-xs w-full">
                                Ver más
                            </button>

                            @if($dist->estado == 0)
                            <button wire:click="confirmarEliminar({{ $dist->id }})"
                                class="btn-cyan text-xs bg-cyan-500 hover:bg-cyan-600 w-full">
                                Eliminar
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-600">
                            No hay distribuciones registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="col-span-full text-center mt-4 flex justify-center gap-2">
            @if($distribuciones->count() >= $cantidadDistribuciones)
            <button wire:click="$set('cantidadDistribuciones', $cantidadDistribuciones + 50)"
                class="btn-cyan px-4 py-2 rounded">
                Cargar más
            </button>
            @endif

            @if($cantidadDistribuciones > 50)
            <button wire:click="$set('cantidadDistribuciones', $cantidadDistribuciones - 50)"
                class="btn-cyan px-4 py-2 rounded">
                Cargar menos
            </button>
            @endif
        </div>

    </div>

    @if($modalDistribucion)
    <div class="modal-overlay">
        <div class="modal-box w-full max-w-3xl">
            <div class="modal-content flex flex-col gap-4">
                <div class="grid grid-cols-1 gap-4 mb-4">
                    <div class="flex flex-col items-center justify-center gap-4">
                        <div>
                            <span class="text-u">Código: {{ $codigo ?? 'Se generará automáticamente' }}</span>
                        </div>

                        <div>
                            <span class="text-u">
                                Fecha asignado:
                                {{ $fecha_asignacion ? \Carbon\Carbon::parse($fecha_asignacion)->format('d/m/Y H:i:s') : 'Se generará al guardar' }}
                            </span>
                        </div>

                        <div class="w-full flex flex-col items-center">
                            <label class="font-semibold text-sm mb-1 block text-center">Fecha de Entrega</label>
                            <div class="flex items-center justify-center gap-2 w-full">
                                <input type="text" wire:model.lazy="fecha_entrega" class="input-minimal flex-1 max-w-xs"
                                    placeholder="MM/DD/YY HH:mm:ss">
                                <button type="button" wire:click="establecerFechaActual" class="btn-cyan"
                                    title="Usar fecha y hora actual">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="mr-1">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    Ahora
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-2 text-center">
                        <label class="font-semibold text-sm mb-2 block">Estado</label>
                        <div class="flex justify-center gap-3 mt-2">
                            <button type="button" wire:click="$set('estado', 0)"
                                class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                                                        {{ $estado == 0 ? 'bg-yellow-600 text-white border-yellow-700 shadow-md' : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                                En entrega
                            </button>
                            <button type="button" wire:click="$set('estado', 1)"
                                class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                                                        {{ $estado == 1 ? 'bg-emerald-500 text-white border-emerald-600 shadow-md' : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                                Finalizado
                            </button>
                        </div>
                        @error('estado')<span class="error-message">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 mb-4">
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Coche (requerido)</label>
                        <div
                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[500px]">
                            @foreach($coches as $c)
                            <button type="button" wire:click="$set('coche_id', {{ $c->id }})"
                                class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                                                                {{ $coche_id == $c->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }} bg-white">
                                <span class="font-semibold text-u">{{ $c->placa }}</span>
                                <span class="text-sm text-gray-600">{{ $c->marca }} {{ $c->modelo }}</span>
                                @if($c->color)<span class="text-gray-500">Color: {{ $c->color }}</span>@endif
                            </button>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Personal asignado (requerido)</label>
                        <div
                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 overflow-y-auto max-h-[500px]">
                            @foreach($personals as $p)
                            <button type="button" wire:click="$set('personal_id', {{ $p->id }})"
                                class="w-full p-4 rounded-lg border-2 transition flex flex-col items-center text-center
                                                                                {{ $personal_id == $p->id ? 'border-cyan-600 text-cyan-600' : 'border-gray-300 text-gray-800 hover:border-cyan-600 hover:text-cyan-600' }} bg-white">
                                <span class="font-semibold text-u">{{ $p->nombres }} {{ $p->apellidos }}</span>
                                @if($p->cargo)<span class="text-sm text-gray-600">({{ $p->cargo }})</span>@endif
                                @if(optional($p->trabajos->last())->sucursal)
                                <span class="text-sm text-gray-500">Sucursal:
                                    {{ $p->trabajos->last()->sucursal->nombre }}</span>
                                @endif
                            </button>
                            @endforeach
                        </div>
                        @error('personal_id')<span
                            class="error-message text-red-500 text-sm mt-1 block">{{ $message }}</span>@enderror
                    </div>
                </div>

                @php
                $pedidosDisponibles = $pedidos->filter(fn($p) => !in_array($p->id, $pedidos_seleccionados))->values();
                @endphp
                <div class="grid grid-cols-1 gap-2 mt-4">
                    <label class="font-semibold text-sm block">Pedidos disponibles</label>

                    <div class="w-full border border-gray-300 rounded-md bg-white
                flex flex-col gap-2 p-2
                overflow-y-auto max-h-[500px]">

                        @forelse($pedidosDisponibles as $pedido)
                        <div class="w-full rounded-md border hover:border-cyan-600
                    transition bg-white shadow-sm p-3 text-sm relative">

                            <div class="mb-1">
                                <span class="font-semibold text-cyan-700">
                                    {{ $pedido->cliente?->nombre ?? $pedido->solicitudPedido?->cliente?->nombre ?? 'Cliente N/A' }}
                                </span>

                                <div class="text-xs text-gray-500">
                                    Código: {{ $pedido->codigo }} · {{ $pedido->fecha_pedido ?? 'N/D' }}
                                </div>

                                <div class="text-xs text-gray-600">
                                    {{ $pedido->solicitudPedido?->cliente?->departamento_localidad ?? 'Sin ubicación' }}
                                </div>
                            </div>

                            <div class="mt-2">
                                <p class="font-semibold text-xs mb-1">Productos</p>

                                <ul class="space-y-1 text-xs">

                                    {{-- Productos de la solicitud --}}
                                    @foreach($pedido->solicitudPedido?->detalles ?? [] as $detalle)
                                    <li class="border-b pb-1">
                                        <div class="font-medium">
                                            {{ ($detalle->producto ?? $detalle->otro)?->descripcion ?? 'Producto N/A' }}
                                        </div>

                                        <div class="text-gray-500">
                                            @if($detalle->tapa)
                                            • Tapa: {{ $detalle->tapa->descripcion }}
                                            @endif
                                            @if($detalle->etiqueta)
                                            <br>• Etiqueta: {{ $detalle->etiqueta->descripcion }}
                                            @endif
                                        </div>

                                        <div class="text-gray-600">
                                            Cant.: {{ $detalle->cantidad }}
                                        </div>
                                    </li>
                                    @endforeach
                                    @foreach($pedido->detalles as $detalle)
                                    <li class="border-b pb-1">
                                        <div class="font-medium">
                                            {{ $detalle->existencia?->existenciable?->descripcion ?? 'Item sin descripción' }}
                                        </div>

                                        <div class="text-gray-500">
                                            @if($detalle->existencia?->tapa)
                                            • Tapa: {{ $detalle->existencia->tapa->descripcion }}
                                            @endif
                                            @if($detalle->existencia?->etiqueta)
                                            <br>• Etiqueta: {{ $detalle->existencia->etiqueta->descripcion }}
                                            @endif
                                        </div>

                                        <div class="text-gray-600">
                                            Cant.: {{ $detalle->cantidad }}
                                        </div>
                                    </li>
                                    @endforeach

                                    @if(
                                    !$pedido->solicitudPedido?->detalles?->count()
                                    && !$pedido->detalles->count()
                                    )
                                    <li class="text-gray-400 italic">
                                        No hay productos
                                    </li>
                                    @endif

                                </ul>
                            </div>

                            <button wire:click.prevent="agregarPedido({{ $pedido->id }})"
                                class="mt-2 w-full text-xs py-1 rounded
                       bg-cyan-600 hover:bg-cyan-700 text-white">
                                Añadir
                            </button>
                        </div>

                        @empty
                        <p class="text-gray-500 text-xs text-center py-2">
                            No hay pedidos disponibles
                        </p>
                        @endforelse
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-2 mt-4">
                    <label class="font-semibold text-sm block">Pedidos asignados</label>

                    <div class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white
        flex flex-col gap-2
        overflow-y-auto max-h-[500px]">

                        @forelse($pedidosAsignados as $pedido)
                        <div class="w-full p-2 rounded-md border transition
            flex flex-col gap-1 text-left
            border-gray-300 bg-white shadow-sm
            hover:border-cyan-600">

                            <span class="font-semibold text-sm text-cyan-700 leading-tight">
                                {{ $pedido->cliente?->nombre
                    ?? $pedido->solicitudPedido?->cliente?->nombre
                    ?? 'Cliente N/A'
                }}
                            </span>

                            <span class="text-xs text-gray-600 leading-tight">
                                {{ $pedido->cliente?->departamento_localidad
                    ?? $pedido->solicitudPedido?->cliente?->departamento_localidad
                    ?? 'Sin ubicación'
                }}
                            </span>

                            <div class="text-xs text-gray-500 flex justify-between">
                                <span>Cód: {{ $pedido->codigo }}</span>
                                <span>
                                    {{ $pedido->fecha_pedido
                        ? \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y')
                        : 'N/D'
                    }}
                                </span>
                            </div>

                            <div class="mt-1">
                                <p class="text-xs font-semibold mb-1">Productos:</p>

                                <ul class="text-xs text-gray-600 space-y-1 max-h-28 overflow-y-auto">

                                    @foreach($pedido->solicitudPedido?->detalles ?? [] as $detalle)
                                    <li class="border-b pb-1">
                                        <div class="font-medium">
                                            {{ ($detalle->producto ?? $detalle->otro)?->descripcion ?? 'Producto N/A' }}
                                        </div>
                                        <div class="text-[11px] text-gray-500 ml-2">
                                            @if($detalle->tapa)
                                            • Tapa: {{ $detalle->tapa->descripcion }}
                                            @endif
                                            @if($detalle->etiqueta)
                                            <br>• Etiqueta: {{ $detalle->etiqueta->descripcion }}
                                            @endif
                                        </div>
                                        <div class="text-[11px]">
                                            Cant: <span class="font-semibold">{{ $detalle->cantidad }}</span>
                                        </div>
                                    </li>
                                    @endforeach

                                    @foreach($pedido->detalles as $detalle)
                                    <li class="border-b pb-1">
                                        <div class="font-medium">
                                            {{ $detalle->existencia?->existenciable?->descripcion ?? 'Item sin descripción' }}
                                            @if($detalle->existencia?->existenciable?->tipoContenido)
                                            · {{ $detalle->existencia->existenciable->tipoContenido }}
                                            @endif

                                            @if($detalle->existencia?->existenciable?->tipoProducto)
                                            · {{ $detalle->existencia->existenciable->tipoProducto }}
                                            @endif
                                            @if($detalle->existencia?->existenciable?->capacidad)
                                            · {{ $detalle->existencia->existenciable->capacidad }}
                                            @endif
                                            @if($detalle->existencia?->existenciable?->unidad)
                                            · {{ $detalle->existencia->existenciable->unidad }}
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-gray-500 ml-2">
                                            @if($detalle->existencia?->tapa)
                                            • Tapa: {{ $detalle->existencia->tapa->descripcion }}
                                            @endif
                                            @if($detalle->existencia?->etiqueta)
                                            <br>• Etiqueta: {{ $detalle->existencia->etiqueta->descripcion }}
                                            @endif
                                        </div>
                                        <div class="text-[11px]">
                                            Cant: <span class="font-semibold">{{ $detalle->cantidad }}</span>
                                        </div>
                                    </li>
                                    @endforeach


                                    @if(!$pedido->solicitudPedido?->detalles?->count() && !$pedido->detalles->count())
                                    <li class="text-gray-400 italic text-[11px]">No hay productos</li>
                                    @endif

                                </ul>
                            </div>

                            <button type="button"
                                wire:click="quitarPedido({{ $pedido->id }})"
                                class="self-end mt-1 text-xs px-2 py-1 rounded
                bg-cyan-500 hover:bg-cyan-600 text-white transition">
                                Quitar
                            </button>

                        </div>
                        @empty
                        <p class="text-gray-500 text-sm text-center py-2">No hay pedidos asignados</p>
                        @endforelse
                    </div>
                </div>


                <div>
                    <label class="font-semibold text-sm mb-2 block">Observaciones</label>
                    <textarea wire:model="observaciones" rows="3"
                        class="w-full border-2 border-gray-300 rounded-lg p-3 text-sm text-gray-800 bg-white focus:border-cyan-600 focus:ring-2 focus:ring-cyan-200 transition resize-none"
                        placeholder="Sin observaciones..."></textarea>
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
                    <button wire:click="guardarDistribucion" class="btn-cyan">
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


    @if($confirmingDeleteId)
    <div class="modal-overlay">
        <div class="modal-box">

            <div class="modal-content">
                <div class="flex flex-col gap-4 text-center">
                    <h2 class="text-lg font-semibold">¿Eliminar distribución?</h2>
                    <p class="text-gray-600">
                        Esta acción no se puede deshacer.
                    </p>
                </div>
            </div>

            <div class="modal-footer flex justify-center gap-2 mt-4">
                <button type="button" wire:click="eliminarDistribucion" class="btn-cyan flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg>
                    Confirmar
                </button>

                <button type="button" wire:click="$set('confirmingDeleteId', false)"
                    class="btn-cyan flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                    Cancelar
                </button>
            </div>

        </div>
    </div>
    @endif


    @if($modalPedidos)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">
                <div class="flex justify-center items-center mb-4">
                    <div
                        class="w-20 h-20 rounded-full bg-cyan-600 text-white flex items-center justify-center text-2xl font-bold">
                        {{ strtoupper(substr($distribucionModel->codigo ?? 'D', 0, 1)) }}
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Código:</span>
                            <span class="badge-info">{{ $distribucionModel->codigo ?? 'N/A' }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Personal:</span>
                            <span class="badge-info">{{ $distribucionModel->personal->nombres ?? 'N/A' }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Coche:</span>
                            <span class="badge-info">
                                {{ $distribucionModel->coche->placa ?? 'N/A' }}
                                {{ $distribucionModel->coche->marca ?? '' }}
                                {{ $distribucionModel->coche->modelo ?? '' }}
                            </span>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Fecha asignación:</span>
                            <span class="badge-info">
                                {{ $distribucionModel->fecha_asignacion ? \Carbon\Carbon::parse($distribucionModel->fecha_asignacion)->format('d/m/Y H:i:s') : 'N/A' }}
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Fecha entrega:</span>
                            <span class="badge-info">
                                {{ $distribucionModel->fecha_entrega ? \Carbon\Carbon::parse($distribucionModel->fecha_entrega)->format('d/m/Y H:i:s') : 'N/A' }}
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <span class="label-info">Estado:</span>
                            <span
                                class="badge-info {{ $distribucionModel->estado == 0 ? 'bg-yellow-600 text-white' : 'bg-emerald-500 text-white' }}">
                                {{ $distribucionModel->estado == 0 ? 'En entrega' : 'Finalizada' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <span class="label-info mb-1 block">Observaciones:</span>
                        <div class="border p-3 rounded-md bg-gray-50 text-gray-700 text-sm h-full">
                            {{ $distribucionModel->observaciones ?? 'Sin observaciones' }}
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="font-semibold mb-2 label-info">Pedidos asignados ({{ $pedidosDeDistribucion->count() }})
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 overflow-y-auto max-h-[500px]">
                        @forelse($pedidosDeDistribucion as $pedido)
                        <div
                            class="border rounded-lg p-4 bg-white shadow-sm hover:shadow-md transition relative flex flex-col gap-2">
                            <div class="flex justify-between items-start">
                                <div class="space-y-1">
                                    <span class="font-semibold text-u">
                                        {{ $pedido->solicitudPedido?->cliente?->nombre ?? 'Cliente N/A' }}
                                    </span>
                                    <br>
                                    <span class=" text-gray-500"> {{ $pedido->codigo }}</span>
                                    <span class=" text-gray-500">Fecha:
                                        {{ $pedido->fecha_pedido ?? 'N/D' }}</span>
                                </div>


                            </div>
                            <div class="mt-2">
                                <p class="font-semibold text-sm mb-1 label-info">Productos:</p>
                                <ul class="list-disc list-inside  text-gray-600 max-h-32 overflow-y-auto">
                                    @foreach($pedido->detalles as $detalle)
                                    <li>
                                        {{ $detalle->existencia?->existenciable?->descripcion ?? 'Producto N/A' }}
                                        @if($detalle->cantidad)
                                        - Cantidad: {{ number_format($detalle->cantidad, 2) }}
                                        @endif
                                        @if(isset($detalle->precio))
                                        - Precio: {{ number_format($detalle->precio, 2) }}
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm text-center py-2 col-span-full">No hay pedidos asignados</p>
                        @endforelse
                    </div>
                </div>

                <div class="modal-footer">
                    <button wire:click="$set('modalPedidos', false)" class="btn-cyan" title="Cerrar">
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
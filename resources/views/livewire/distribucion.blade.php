<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3
            class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto">
            Distribuciones
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="search" placeholder="Buscar por código, personal o coche..."
                class="input-minimal w-full" />
            <button wire:click="$set('modalDistribucion', true)" class="btn-cyan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                    <path
                        d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
                </svg>
                añadir
            </button>
        </div>

        @forelse($distribuciones as $dist)
            <div class="card-teal flex flex-col gap-4">
                <div class="flex flex-col gap-1">
                    <p class="text-u">{{ $dist->codigo }}</p>
                    <p><strong>Personal:</strong> {{ $dist->personal->nombres ?? 'N/A' }}</p>
                    <p><strong>Coche:</strong> {{ $dist->coche->placa ?? 'N/A' }} {{ $dist->coche->marca ?? '' }}
                        {{ $dist->coche->modelo ?? '' }}
                    </p>
                    <p><strong>Pedidos asignados:</strong> {{ $dist->pedidos->count() }}</p>
                    <p><strong>Estado:</strong>
                        <span
                            class="inline-block px-2 py-1 rounded-full text-sm font-semibold {{ $dist->estado == 0 ? 'bg-yellow-600 text-white' : 'bg-emerald-400 text-white' }}">
                            {{ $dist->estado == 0 ? 'En entrega' : 'Finalizada' }}
                        </span>
                    </p>
                </div>

                <div class="flex flex-wrap justify-center md:justify-center gap-2 border-t border-gray-200 pt-3 pb-2">
                    <button wire:click="editarDistribucion({{ $dist->id }})" class="btn-cyan" title="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                            <path d="M16 5l3 3" />
                        </svg>
                        Editar
                    </button>

                    <button wire:click="verPedidos({{ $dist->id }})" class="btn-cyan" title="Ver detalle">
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
                    <button wire:click="confirmarEliminar({{ $dist->id }})" class="btn-cyan" title="Eliminar">
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
                No hay distribuciones registradas.
            </div>
        @endforelse
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
                        <label class="font-semibold text-sm mb-2 block">Pedidos disponibles</label>
                        <div
                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 overflow-y-auto max-h-[500px]">
                            @forelse($pedidosDisponibles as $pedido)
                                <div
                                    class="w-full p-4 rounded-lg border-2 transition flex flex-col items-start text-left hover:border-cyan-600 hover:text-cyan-600 border-gray-300 bg-white shadow-sm relative">
                                    <div class="mb-2">
                                        <span
                                            class="font-semibold text-u">{{ $pedido->solicitudPedido?->cliente?->nombre ?? 'Cliente N/A' }}</span>
                                        <div class="text-gray-500 mt-1">Código: {{ $pedido->codigo }}</div>
                                        <div class="text-gray-500 mt-1">Fecha: {{ $pedido->fecha_pedido ?? 'N/D' }}</div>
                                        <div class="text-u mt-1">Ubicación:
                                            {{ $pedido->solicitudPedido?->cliente?->departamento_localidad ?? 'Sin ubicación' }}
                                        </div>
                                    </div>
                                    <div class="w-full mt-2">
                                        <p class="font-semibold text-sm mb-1">Productos:</p>
                                        <ul class="text-gray-600 space-y-1">
                                            @foreach($pedido->solicitudPedido->detalles as $detalle)
                                                <li class="border-b pb-1">
                                                    <div class="font-semibold">
                                                        @if($detalle->producto)
                                                            {{ $detalle->producto->descripcion }}
                                                        @elseif($detalle->otro)
                                                            {{ $detalle->otro->descripcion }}
                                                        @else
                                                            Producto base N/A
                                                        @endif
                                                    </div>

                                                    <div class="text-sm text-gray-500 ml-2">
                                                        @if($detalle->tapa)
                                                            • Tapa: {{ $detalle->tapa->descripcion }}
                                                        @endif

                                                        @if($detalle->etiqueta)
                                                            <br>• Etiqueta: {{ $detalle->etiqueta->descripcion }}
                                                        @endif

                                                        @if($detalle->otro)
                                                            <br>• Otro: {{ $detalle->otro->descripcion }}
                                                        @endif
                                                    </div>

                                                    <div class="mt-1">
                                                        Cantidad de paquetes: <span class="font-semibold">{{ $detalle->cantidad }}
                                                        </span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>

                                    </div>
                                    <button wire:click.prevent="agregarPedido({{ $pedido->id }})"
                                        class="btn-cyan mt-3 self-center w-full flex items-center justify-center gap-2">Añadir</button>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm text-center py-2 col-span-full">No hay pedidos disponibles</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 mt-4">
                        <label class="font-semibold text-sm mb-2 block">Pedidos asignados</label>
                        <div
                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 overflow-y-auto max-h-[500px]">
                            @forelse($pedidosAsignados as $pedido)
                                <div
                                    class="w-full p-4 rounded-lg border-2 transition flex flex-col text-left hover:border-cyan-600 hover:text-cyan-600 border-gray-300 bg-white shadow-sm relative">
                                    <span
                                        class="font-semibold text-u">{{ $pedido->solicitudPedido?->cliente?->nombre ?? 'Cliente N/A' }}</span>
                                    <div class="text-u mt-1">Ubicación:
                                        {{ $pedido->solicitudPedido?->cliente?->departamento_localidad ?? 'Sin ubicación' }}
                                    </div>
                                    <span class="mt-1">Código: {{ $pedido->codigo }}</span>
                                    <span class="text-gray-500 mt-1">Fecha:
                                        {{ $pedido->fecha_pedido ? date('d/m/Y', strtotime($pedido->fecha_pedido)) : 'N/D' }}</span>
                                    <div class="w-full mt-3">
                                        <p class="font-semibold mb-1">Productos:</p>
                                        <ul class="text-gray-600 max-h-32 overflow-y-auto space-y-1">
                                            @foreach($pedido->solicitudPedido->detalles as $detalle)
                                                <li class="border-b pb-1">
                                                    <div class="font-semibold">
                                                        @if($detalle->producto)
                                                            {{ $detalle->producto->descripcion }}
                                                        @elseif($detalle->otro)
                                                            {{ $detalle->otro->descripcion }}
                                                        @else
                                                            Producto base N/A
                                                        @endif
                                                    </div>

                                                    <div class="text-sm text-gray-500 ml-2">
                                                        @if($detalle->tapa)
                                                            • Tapa: {{ $detalle->tapa->descripcion }}
                                                        @endif

                                                        @if($detalle->etiqueta)
                                                            <br>• Etiqueta: {{ $detalle->etiqueta->descripcion }}
                                                        @endif

                                                        @if($detalle->otro)
                                                            <br>• Otro: {{ $detalle->otro->descripcion }}
                                                        @endif
                                                    </div>

                                                    <div class="mt-1">
                                                        Cantidad de paquetes:
                                                        <span class="font-semibold">{{ $detalle->cantidad }}</span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>

                                    </div>
                                    <button type="button" wire:click="quitarPedido({{ $pedido->id }})"
                                        class="absolute top-2 right-2 p-1.5 rounded-full bg-red-500 hover:bg-red-600 text-white shadow transition"
                                        title="Eliminar">X</button>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm text-center py-2 col-span-full">No hay pedidos asignados</p>
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
<div class="p-2 mt-20 flex justify-center bg-transparent">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Botón Crear Lote -->
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <button wire:click="abrirModal"
                class="bg-cyan-500 hover:bg-cyan-600 rounded-xl px-4 py-2 flex items-center justify-center text-white font-semibold">
                Crear Lote
            </button>
        </div>

        @forelse($itemPromos->groupBy('codigo') as $codigo => $items)
        <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">

            <!-- Columna izquierda: info lote -->
            <div class="col-span-8 flex flex-col">
                <h2 class="text-lg font-semibold text-cyan-600">{{ $codigo }}</h2>
                <span class="text-cyan-500 text-sm mb-3">
                    Fecha: {{ $items->first()->fecha_asignacion?->format('d/m/Y') }}
                </span>

                <div class="space-y-2">
                    @foreach($items->groupBy('cliente_id') as $clienteId => $clienteItems)
                    <div class="p-2 border rounded bg-white">
                        <h3 class=" text-cyan-700 uppercase font-semibold">
                            Cliente: {{ $clienteItems->first()->cliente->nombre ?? 'N/A' }}
                        </h3>
                        <ul class="list-disc list-inside ml-4 mt-1 uppercase">
                            @foreach($clienteItems as $item)

                            <span class="font-semibold text-cyan-700">
                                {{ $item->promo->nombre ?? 'N/A' }}
                            </span>
                            <br>
                            <span class="text-sm text-cyan-700">
                                {{ $item->promo->tipo_descuento }}
                            </span>
                            <br>
                            <span class="text-sm text-cyan-700">
                                {{ $item->promo->valor_descuento }}
                            </span>

                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Columna derecha: botones -->
            <div class="flex flex-col items-center md:items-end gap-4 col-span-4">
                <button wire:click="editarLote('{{ $codigo }}')"
                    class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600">
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

                <button wire:click="confirmarEliminarLote('{{ $codigo }}')"
                    class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 7l16 0" />
                        <path d="M10 11l0 6" />
                        <path d="M14 11l0 6" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg>
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-4 text-gray-600">
            No hay promociones asignadas.
        </div>
        @endforelse
    </div>


    @if($modal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white text-cyan-950 rounded-lg shadow-lg w-full max-w-4xl p-6 relative overflow-y-auto max-h-[90vh]">
            <h2 class="text-xl font-semibold mb-6 text-center">
                {{ $editando ? 'Editar Lote de Promociones' : 'Crear Lote de Promociones' }}
            </h2>
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-4 flex flex-col gap-4">
                    <input type="text" wire:model="codigo" class="input-minimal bg-gray-100" readonly placeholder="Código del Lote">
                    @error('codigo') <span class="error-message">{{ $message }}</span> @enderror

                    <input type="date" wire:model="fechaAsignacion" class="input-minimal">
                    @error('fechaAsignacion') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                <div class="col-span-12 md:col-span-4 flex flex-col gap-2">
                    <label class="font-medium">Clientes</label>
                    <div class="max-h-40 overflow-y-auto border rounded p-2">
                        @foreach($clientes as $cliente)
                        @if(!in_array($cliente->id, $clientesSeleccionados))
                        <div class="flex justify-between items-center p-1 bg-gray-50 rounded hover:bg-gray-100 mb-1">
                            <span>{{ $cliente->nombre }}</span>
                            <button wire:click.prevent="agregarCliente({{ $cliente->id }})"
                                class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 18l-2 -4l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5l-3.361 9.308" />
                                    <path d="M16 19h6" />
                                    <path d="M19 16v6" />
                                </svg>
                            </button>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    <div class="max-h-40 overflow-y-auto border rounded p-2 bg-gray-50">
                        @foreach($clientes->whereIn('id', $clientesSeleccionados) as $cliente)
                        <div class="flex justify-between items-center p-3 bg-white text-cyan-600 uppercase rounded mb-2">
                            <span>{{ $cliente->nombre }}</span>
                            <button wire:click.prevent="quitarCliente({{ $cliente->id }})"
                                class="bg-white rounded-xl p-2 w-10 h-10 flex items-center justify-center hover:bg-gray-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M4 7h16" />
                                    <path d="M10 11v6" />
                                    <path d="M14 11v6" />
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                    <path d="M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-span-12 md:col-span-4 flex flex-col gap-2">
                    <label class="font-medium">Promociones</label>
                    <div class="max-h-40 overflow-y-auto border rounded p-2">
                        @foreach($promos as $promo)
                        @if(!in_array($promo->id, $promosSeleccionadas))
                        <div class="flex justify-between items-center p-1 bg-gray-50 rounded hover:bg-gray-100 mb-1">
                            <span>{{ $promo->nombre }} ({{ $promo->tipo_descuento }} - {{ $promo->valor_descuento }})</span>
                            <button wire:click.prevent="agregarPromo({{ $promo->id }})"
                                class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 18l-2 -4l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5l-3.361 9.308" />
                                    <path d="M16 19h6" />
                                    <path d="M19 16v6" />
                                </svg>
                            </button>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <div class="max-h-40 overflow-y-auto border rounded p-2 bg-gray-50">
                        @foreach($promos->whereIn('id', $promosSeleccionadas) as $promo)
                        <div class="flex justify-between items-center p-3 bg-white text-cyan-600 uppercase rounded mb-2">

                            <!-- Información de la promo -->
                            <div class="flex flex-col">
                                <span class="font-bold">{{ $promo->nombre }}</span>
                                <span class="text-sm">{{ $promo->tipo_descuento }}</span>
                                <span class="text-sm">{{ $promo->valor_descuento }}</span>
                            </div>

                            <!-- Botón quitar -->
                            <button wire:click.prevent="quitarPromo({{ $promo->id }})"
                                class="bg-white rounded-xl p-2 w-10 h-10 flex items-center justify-center hover:bg-gray-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M4 7h16" />
                                    <path d="M10 11v6" />
                                    <path d="M14 11v6" />
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                    <path d="M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>

                </div>

                <!-- Columna 4: Botones acción -->
                <div class="col-span-12 md:col-span-1 flex flex-col justify-center items-center gap-4">
                    @if($editando)
                    <button type="button" wire:click="actualizarLote" class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0v.001z" />
                            <path d="M9 13h6" />
                            <path d="M12 10v6" />
                        </svg>
                    </button>
                    @else
                    <button type="button" wire:click="guardarLote" class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0v.001z" />
                            <path d="M9 13h6" />
                            <path d="M12 10v6" />
                        </svg>
                    </button>
                    @endif

                    <button type="button" wire:click="cerrarModal" class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M10 10l4 4m0 -4l-4 4" />
                            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif


    @if($modalConfirmar)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <div class="p-6 text-center">
                <h2 class="text-lg font-bold text-cyan-800 mb-4">¿Eliminar Lote?</h2>
                <p class="text-gray-600 mb-6">
                    Estás a punto de eliminar el lote con código
                    <span class="font-semibold text-red-600">{{ $codigoAEliminar }}</span>.
                    <br>Esta acción no se puede deshacer.
                </p>

                <div class="flex justify-center gap-4">
                    <button wire:click="$set('modalConfirmar', false)"
                        class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                        Cancelar
                    </button>
                    <button wire:click="eliminarLoteConfirmado"
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
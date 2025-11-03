<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3 class="inline-block bg-teal-700 text-white px-5 py-2 rounded-full text-xl font-bold uppercase shadow-md">
            Coches
        </h3>

        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="search" placeholder="Buscar por marca, modelo o placa..."
                class="input-minimal w-full" />
            <button wire:click="abrirModal('create')" class="btn-cyan flex items-center gap-1">
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

        @forelse($coches as $coche)
            <div class="card-teal flex flex-col gap-4">
                <div class="flex flex-col gap-1">
                    <p class="text-u">{{ $coche->movil }}</p>
                    <p><strong>Marca:</strong> {{ $coche->marca }}</p>
                    <p><strong>Modelo:</strong> {{ $coche->modelo }}</p>
                    <p><strong>Placa:</strong> {{ $coche->placa }}</p>
                    <p><strong>Estado:</strong>
                        <span class="inline-block px-2 py-1 rounded-full text-sm font-semibold
                            {{ $coche->estado ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white' }}">
                            {{ $coche->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </p>
                </div>
                <div
                    class="flex gap-2 overflow-x-auto no-scrollbar border-t border-gray-200 pt-3 pb-1 justify-start md:justify-between">
                    <button wire:click="editarCoche({{ $coche->id }})"
                        class="btn-cyan flex items-center gap-1 flex-shrink-0" title="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                            <path d="M16 5l3 3" />
                        </svg>
                        Editar
                    </button>
                    <button wire:click="verDetalle({{ $coche->id }})" class="btn-cyan flex items-center gap-1 flex-shrink-0"
                        title="Ver Detalle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9h.01" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 12h1v4h1" />
                        </svg>
                        Detalles
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-4 text-gray-600">
                No hay coches registrados.
            </div>
        @endforelse
    </div>

    @if($modal)
        <div class="modal-overlay">
            <div class="modal-box">
                <div class="modal-content">
                    <div class="flex flex-col gap-4">
                        <div>
                            <label class="text-u">Móvil (Requerido)</label>
                            <input wire:model="movil" class="input-minimal" placeholder="Coche" />
                        </div>
                        <div>
                            <label class="font-semibold text-sm mb-1 block">Marca (Opcional)</label>
                            <input wire:model="marca" class="input-minimal" placeholder="Marca del coche" />
                        </div>
                        <div>
                            <label class="font-semibold text-sm mb-1 block">Modelo (Opcional)</label>
                            <input wire:model="modelo" class="input-minimal" placeholder="Modelo del coche" />
                        </div>
                        <div>
                            <label class="font-semibold text-sm mb-1 block">Año (Opcional)</label>
                            <input type="number" wire:model="anio" class="input-minimal" placeholder="Año del coche" />
                        </div>
                        <div>
                            <label class="font-semibold text-sm mb-1 block">Color (Opcional)</label>
                            <input wire:model="color" class="input-minimal" placeholder="Color" />
                        </div>
                        <div>
                            <label class="text-u">Placa (Requerido)</label>
                            <input wire:model="placa" class="input-minimal" placeholder="Placa" />
                        </div>
                        <div class="flex flex-wrap justify-center gap-2 mt-2">
                            @foreach([1 => 'Activo', 0 => 'Inactivo'] as $key => $label)
                                <button type="button" wire:click="$set('estado', {{ $key }})"
                                    class="px-4 py-2 rounded-full text-sm flex items-center justify-center
                                            {{ $estado == $key ? 'bg-cyan-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-cyan-100' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="cerrarModal" class="btn-cyan">Cerrar</button>
                    <button type="button" wire:click="guardarCoche" class="btn-cyan">Guardar</button>
                </div>
            </div>
        </div>
    @endif

    @if($detalleModal && $cocheSeleccionado)
        <div class="modal-overlay">
            <div class="modal-box">
                <div class="modal-content flex flex-col gap-4">
                    <div class="flex justify-center items-center">
                        <div
                            class="w-20 h-20 rounded-full bg-cyan-600 text-white flex items-center justify-center text-2xl font-bold">
                            {{ strtoupper(substr($cocheSeleccionado->placa, 0, 1)) }}
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-3">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Placa:</span>
                                <span class="badge-info">{{ $cocheSeleccionado->placa }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Marca:</span>
                                <span class="badge-info">{{ $cocheSeleccionado->marca }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Modelo:</span>
                                <span class="badge-info">{{ $cocheSeleccionado->modelo }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Color:</span>
                                <span class="badge-info">{{ $cocheSeleccionado->color }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Año:</span>
                                <span class="badge-info">{{ $cocheSeleccionado->anio }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <span class="label-info">Estado:</span>
                                <span class="badge-info">{{ $cocheSeleccionado->estado ? 'Activo' : 'Inactivo' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('detalleModal', false)" class="btn-cyan" title="Cerrar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path d="M10 10l4 4m0 -4l-4 4" />
                            <circle cx="12" cy="12" r="9" />
                        </svg>
                        CERRAR
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
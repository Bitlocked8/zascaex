<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- BUSCADOR Y BOTÓN CREAR --}}
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <input type="text" wire:model.live="search" placeholder="Buscar por código..." class="input-minimal w-full" />

            <button wire:click="abrirModal('create')" class="btn-circle btn-cyan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor">
                    <path d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                </svg>
            </button>
        </div>

        {{-- LISTADO DE LLENADOS --}}
        @forelse($llenados as $llenado)
        <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">
            <div class="flex flex-col col-span-9 space-y-1 text-left">
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($llenado->fecha)->format('d/m/Y H:i') }}</p>
                <p><strong>Código:</strong> {{ $llenado->codigo }}</p>

                <p><strong>Base:</strong> {{ $llenado->asignadoBase->existencia->existenciable->descripcion ?? 'N/A' }}</p>
                <p><strong>Tapa:</strong> {{ $llenado->asignadoTapa->existencia->existenciable->descripcion ?? 'N/A' }}</p>
                <p><strong>Destino (Producto):</strong> {{ $llenado->existenciaDestino->existenciable->descripcion ?? 'N/A' }}</p>

                <p><strong>Cantidad:</strong> {{ $llenado->cantidad }}</p>
                <p><strong>Merma:</strong> {{ $llenado->merma }}</p>
                <p><strong>Personal:</strong> {{ $llenado->personal->nombre ?? 'N/A' }}</p>
                <p><strong>Observaciones:</strong> {{ $llenado->observaciones ?? 'N/A' }}</p>

                {{-- Estado --}}
                <p>
                    <strong>Estado:</strong>
                    <span class="
                        {{ $llenado->estado == 0 ? 'bg-yellow-600 text-white' : '' }}
                        {{ $llenado->estado == 1 ? 'bg-blue-600 text-white' : '' }}
                        {{ $llenado->estado == 2 ? 'bg-green-600 text-white' : '' }}
                        font-semibold px-2 py-1 rounded-full">
                        {{ $llenado->estado == 0 ? 'Pendiente' : ($llenado->estado == 1 ? 'En Proceso' : 'Finalizado') }}
                    </span>
                </p>

                {{-- Monto total de comprobantes --}}
                <p><strong>Comprobantes:</strong>
                    {{ number_format($llenado->comprobantes->sum('monto'), 2, ',', '.') }} Bs
                </p>
            </div>

            {{-- BOTONES --}}
            <div class="flex flex-col items-end gap-4 col-span-3">
                <button wire:click="abrirModal('edit', {{ $llenado->id }})" class="btn-circle btn-cyan" title="Editar">
                    ✏️
                </button>

                <button wire:click="verDetalleLlenado({{ $llenado->id }})" class="btn-circle btn-cyan" title="Ver Detalle">
                    👁️
                </button>

                @if($llenado->estado != 2)
                <button wire:click="confirmarEliminarLlenado({{ $llenado->id }})" class="btn-circle btn-cyan" title="Eliminar">
                    🗑️
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-4 text-gray-600">
            No hay llenados registrados.
        </div>
        @endforelse
    </div>

    {{-- MODAL --}}
    @if($modal)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content flex flex-col gap-4">

                <div class="grid grid-cols-1 gap-2 mt-2">
                    <p class="font-semibold text-sm">Código: <span class="font-normal">{{ $codigo }}</span></p>
                    <p class="font-semibold text-sm">Fecha:
                        <span class="font-normal">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</span>
                    </p>

                    {{-- Asignación Base --}}
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Asignación (Base)</label>
                        <select wire:model="asignado_base_id" class="input-minimal w-full">
                            <option value="">Seleccionar base</option>
                            @foreach($asignacionesBase as $asignado)
                            <option value="{{ $asignado->id }}">
                                {{ $asignado->existencia->existenciable->descripcion }} ({{ $asignado->cantidad }} disp.)
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Asignación Tapa --}}
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Asignación (Tapa)</label>
                        <select wire:model="asignado_tapa_id" class="input-minimal w-full">
                            <option value="">Seleccionar tapa</option>
                            @foreach($asignacionesTapa as $asignado)
                            <option value="{{ $asignado->id }}">
                                {{ $asignado->existencia->existenciable->descripcion }} ({{ $asignado->cantidad }} disp.)
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Existencia Destino --}}
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Destino (Producto lleno)</label>
                        <select wire:model="existencia_destino_id" class="input-minimal w-full">
                            <option value="">Seleccionar destino</option>
                            @foreach($existenciasDestino as $existencia)
                            <option value="{{ $existencia->id }}">
                                {{ $existencia->existenciable->descripcion }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Personal --}}
                    <div>
                        <label class="font-semibold text-sm mb-2 block">Personal encargado</label>
                        <select wire:model="personal_id" class="input-minimal w-full">
                            <option value="">Seleccionar personal</option>
                            @foreach($personales as $p)
                            <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Cantidad / Merma --}}
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="font-semibold text-sm">Cantidad producida</label>
                            <input type="number" wire:model="cantidad" class="input-minimal" min="1">
                        </div>
                        <div>
                            <label class="font-semibold text-sm">Merma</label>
                            <input type="number" wire:model="merma" class="input-minimal" min="0">
                        </div>
                    </div>

                    {{-- Observaciones --}}
                    <div>
                        <label class="font-semibold text-sm">Observaciones</label>
                        <input type="text" wire:model="observaciones" class="input-minimal">
                    </div>

                    {{-- Estado --}}
                    <div>
                        <label class="font-semibold text-sm">Estado</label>
                        <select wire:model="estado" class="input-minimal w-full">
                            <option value="0">En proceso</option>
                            <option value="1">Revisado</option>
                            <option value="2">Confirmado</option>
                        </select>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="modal-footer">
                    <button wire:click="guardar" class="btn-circle btn-cyan" title="Guardar">💾</button>
                    <button wire:click="cerrarModal" class="btn-circle btn-cyan" title="Cerrar">❌</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

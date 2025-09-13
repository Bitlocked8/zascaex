<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Botón Crear Promo -->
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <button wire:click="abrirModal('create')"
                class="bg-cyan-500 hover:bg-cyan-600 rounded-xl px-4 py-2 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9" />
                    <line x1="12" y1="9" x2="12" y2="15" />
                    <line x1="9" y1="12" x2="15" y2="12" />
                </svg>

            </button>

        </div>

        <!-- Cards de Promos -->
        @forelse ($promos as $promo)
        <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">

            <!-- Información de la Promo -->
            <div class="flex flex-col items-center md:items-start text-center md:text-left col-span-8">
                <h3 class="text-lg font-semibold uppercase text-cyan-600">{{ $promo->nombre }}</h3>
                <p class="text-cyan-950"><strong>Tipo:</strong> {{ ucfirst($promo->tipo_descuento) }}</p>
                <p class="text-cyan-950"><strong>Valor:</strong>
                    {{ $promo->tipo_descuento === 'porcentaje' ? $promo->valor_descuento . '%' : 'Bs ' . $promo->valor_descuento }}
                </p>
                <p class="text-cyan-950"><strong>Vigencia:</strong>
                    {{ $promo->fecha_inicio?->format('d/m/Y') ?? 'N/A' }} -
                    {{ $promo->fecha_fin?->format('d/m/Y') ?? 'N/A' }}
                </p>
                <div class="mt-2">
                    @if ($promo->activo)
                    <span class="px-3 py-1 text-sm font-semibold text-white bg-green-600 rounded-full shadow">
                        Activo
                    </span>
                    @else
                    <span class="px-3 py-1 text-sm font-semibold text-white bg-red-600 rounded-full shadow">
                        Inactivo
                    </span>
                    @endif
                </div>
            </div>

            <!-- Botones: Editar y Asignar Clientes -->
            <div class="flex flex-col items-center md:items-end gap-2 col-span-4">
                <button wire:click="abrirModal('edit', {{ $promo->id }})"
                    class="bg-white rounded-xl p-2 w-12 h-12 flex items-center justify-center shadow hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600">
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


            </div>

        </div>
        @empty
        <div class="col-span-full text-center py-4 text-gray-600">
            No hay promociones registradas.
        </div>
        @endforelse

    </div>

    <!-- Modal Crear/Editar -->
    @if ($modal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white text-cyan-950 rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
            <h2 class="text-xl font-semibold mb-6 text-center">
                {{ $accion === 'create' ? 'Registrar Promo' : 'Editar Promo' }}
            </h2>

            <div class="flex flex-col gap-4">
                <!-- Nombre -->
                <input type="text" wire:model="nombre" placeholder="Nombre" class="input-minimal">

                <!-- Tipo de descuento -->
                <select wire:model="tipo_descuento" class="input-minimal">
                    <option value="porcentaje">Porcentaje</option>
                    <option value="monto">Monto fijo</option>
                </select>

                <!-- Valor descuento -->
                <input type="number" wire:model="valor_descuento" placeholder="Valor Descuento" class="input-minimal">

                <!-- Usos -->
                <div class="grid grid-cols-2 gap-4">
                    <input type="number" wire:model="usos_realizados" placeholder="Usos Realizados" class="input-minimal">
                    <input type="number" wire:model="uso_maximo" placeholder="Uso Máximo" class="input-minimal">
                </div>

                <!-- Fechas -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Fecha Asignada</label>
                        <input type="date" wire:model="fecha_asignada" class="input-minimal">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Fecha Expiración</label>
                        <input type="date" wire:model="fecha_expiracion" class="input-minimal">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Fecha Inicio</label>
                        <input type="date" wire:model="fecha_inicio" class="input-minimal">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Fecha Fin</label>
                        <input type="date" wire:model="fecha_fin" class="input-minimal">
                    </div>
                </div>

                <!-- Checkbox Activo -->
                <div class="flex items-center gap-2 mt-2">
                    <input type="checkbox" id="activo" wire:model="activo" class="h-5 w-5 text-cyan-600 rounded">
                    <label for="activo" class="text-cyan-950 font-semibold">Activo</label>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-2 mt-6">
                <button wire:click="guardar" class="bg-cyan-500 hover:bg-cyan-600 rounded-xl px-4 py-2 text-white">
                    Guardar
                </button>
                <button wire:click="cerrarModal" class="bg-gray-300 hover:bg-gray-400 rounded-xl px-4 py-2">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
    @endif



</div>
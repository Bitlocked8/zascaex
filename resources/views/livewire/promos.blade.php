<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Buscar + Crear Promo -->
        <div class="flex items-center gap-2 mb-4 col-span-full">
            <!-- Input de búsqueda (opcional) -->
            <input type="text" wire:model.live="search"
                placeholder="Buscar por promoción..."
                class="flex-1 border rounded px-3 py-2" />

            <button wire:click="abrirModal('create')"
                class="bg-cyan-500 hover:bg-cyan-600 rounded-xl px-4 py-2 flex items-center justify-center text-white font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9" />
                    <line x1="12" y1="9" x2="12" y2="15" />
                    <line x1="9" y1="12" x2="15" y2="12" />
                </svg>
            </button>
        </div>

        @forelse($promos as $promo)
        <div class="bg-gray-50 shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">

            <!-- Columna Izquierda: Info -->
            <div class="col-span-8 flex flex-col gap-2">
                <h3 class="text-lg font-semibold uppercase text-cyan-600">{{ $promo->nombre }}</h3>
                <p class="text-gray-700"><strong>Tipo:</strong> {{ ucfirst($promo->tipo_descuento) }}</p>
                <p class="text-gray-700"><strong>Valor:</strong> {{ $promo->valor_descuento }} {{ $promo->tipo_descuento == 'porcentaje' ? '%' : 'Bs.' }}</p>
                <p class="text-gray-500 text-sm">
                    <strong>Vigencia:</strong>
                    {{ $promo->fecha_inicio?->format('d/m/Y') ?? '---' }} a {{ $promo->fecha_fin?->format('d/m/Y') ?? '---' }}
                </p>
                <p class="mt-1">
                    @if($promo->activo)
                    <span class="px-3 py-1 text-sm font-semibold text-white bg-green-600 rounded-full shadow">Activo</span>
                    @else
                    <span class="px-3 py-1 text-sm font-semibold text-white bg-red-600 rounded-full shadow">Inactivo</span>
                    @endif
                </p>
            </div>

            <!-- Columna Derecha: Botones -->
            <div class="flex flex-col items-center md:items-end gap-4 col-span-4">
                <!-- Editar Promo -->
                <button wire:click="abrirModal('edit', {{ $promo->id }})"
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

                <!-- Activar/Desactivar Promo -->
                <button wire:click="toggleActivo({{ $promo->id }})"
                    class="{{ $promo->activo ? 'bg-white' : 'bg-cyan-600 ' }} rounded-xl p-2 w-12 h-12 flex items-center justify-center">
                    @if ($promo->activo)
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="text-cyan-600">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12.01 2.011a3.2 3.2 0 0 1 2.113 .797l.154 .145l.698 .698a1.2 1.2 0 0 0 .71 .341l.135 .008h1a3.2 3.2 0 0 1 3.195 3.018l.005 .182v1c0 .27 .092 .533 .258 .743l.09 .1l.697 .698a3.2 3.2 0 0 1 .147 4.382l-.145 .154l-.698 .698a1.2 1.2 0 0 0 -.341 .71l-.008 .135v1a3.2 3.2 0 0 1 -3.018 3.195l-.182 .005h-1a1.2 1.2 0 0 0 -.743 .258l-.1 .09l-.698 .697a3.2 3.2 0 0 1 -4.382 .147l-.154 -.145l-.698 -.698a1.2 1.2 0 0 0 -.71 -.341l-.135 -.008h-1a3.2 3.2 0 0 1 -3.195 -3.018l-.005 -.182v-1a1.2 1.2 0 0 0 -.258 -.743l-.09 -.1l-.697 -.698a3.2 3.2 0 0 1 -.147 -4.382l.145 -.154l.698 -.698a1.2 1.2 0 0 0 .341 -.71l.008 -.135v-1l.005 -.182a3.2 3.2 0 0 1 3.013 -3.013l.182 -.005h1a1.2 1.2 0 0 0 .743 -.258l.1 -.09l.698 -.697a3.2 3.2 0 0 1 2.269 -.944zm3.697 7.282a1 1 0 0 0 -1.414 0l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.32 1.497l2 2l.094 .083a1 1 0 0 0 1.32 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                    </svg>
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 7.2a2.2 2.2 0 0 1 2.2 -2.2h1a2.2 2.2 0 0 0 1.55 -.64l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7c.412 .41 .97 .64 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1c0 .58 .23 1.138 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.64 1.55v1a2.2 2.2 0 0 1 -2.2 2.2h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1" />
                        <path d="M9 12l2 2l4 -4" />
                    </svg>
                    @endif
                </button>
            </div>

        </div>
        @empty
        <div class="col-span-full text-center py-6 text-gray-600">
            No hay promociones registradas.
        </div>
        @endforelse
    </div>

    <!-- Modal -->
    @if($modal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white text-cyan-950 rounded-lg shadow-lg w-full max-w-2xl p-6 relative overflow-y-auto max-h-[90vh]">

            <h2 class="text-xl font-semibold mb-6 text-center">
                {{ $promoId ? 'Editar Promoción' : 'Nueva Promoción' }}
            </h2>

            <div class="grid grid-cols-12 gap-4">

                <!-- Formulario -->
                <div class="col-span-12 md:col-span-8 flex flex-col gap-4">
                    <input type="text" wire:model.defer="nombre" placeholder="Nombre de la promoción" class="input-minimal">
                    @error('nombre') <span class="error-message">{{ $message }}</span> @enderror

                    <select wire:model.defer="tipo_descuento" class="input-minimal">
                        <option value="porcentaje">Porcentaje</option>
                        <option value="monto">Monto</option>
                    </select>
                    @error('tipo_descuento') <span class="error-message">{{ $message }}</span> @enderror

                    <input type="number" wire:model.defer="valor_descuento" placeholder="Valor del descuento" class="input-minimal">
                    @error('valor_descuento') <span class="error-message">{{ $message }}</span> @enderror

                    <input type="date" wire:model.defer="fecha_inicio" class="input-minimal">
                    @error('fecha_inicio') <span class="error-message">{{ $message }}</span> @enderror

                    <input type="date" wire:model.defer="fecha_fin" class="input-minimal">
                    @error('fecha_fin') <span class="error-message">{{ $message }}</span> @enderror

                    <div class="flex items-center gap-2 mt-2">
                        <input type="checkbox" wire:model.defer="activo" id="activo" class="rounded">
                        <label for="activo">Activo</label>
                    </div>
                </div>

                <!-- Botones -->
                <div class="col-span-12 md:col-span-4 flex flex-col justify-center items-center gap-4">
                    <button wire:click="guardarPromo" class="bg-cyan-500 hover:bg-cyan-600 rounded-xl w-full py-2 text-white font-semibold">
                        {{ $promoId ? 'Actualizar' : 'Guardar' }}
                    </button>
                    <button wire:click="cerrarModal" class="bg-gray-300 hover:bg-gray-400 rounded-xl w-full py-2 font-semibold">
                        Cancelar
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endif
</div>
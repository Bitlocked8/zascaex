<div class="p-2 mt-20 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl">

        <h3 class="text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto mb-4">
            Ordenes del dia
        </h3>
        <div class="flex items-center gap-2 mb-4 flex-wrap">
            <input type="text" wire:model.live="search" placeholder="Buscar por detalle..." class="input-minimal w-full sm:w-auto flex-1" />
            <button wire:click="abrirModal('create')" class="btn-cyan flex items-center gap-1">Añadir</button>
            @if(auth()->user()?->rol_id !== 4)
            <button wire:click="$toggle('mostrarCompletadas')" class="btn-cyan">
                {{ $mostrarCompletadas ? 'Ocultar completadas' : 'Ver completadas' }}
            </button>
            @endif

        </div>
        <div class="overflow-auto max-h-[500px] border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-teal-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Fecha</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Detalle</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Cantidad Total</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Cantidad Preparada</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Cantidad Faltante</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Estado</th>
                        <th class="px-4 py-2 text-center text-teal-700 font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ordenes as $orden)
                    <tr class="hover:bg-teal-50">
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y H:i') }}</td>

                        <td class="px-4 py-2">{{ $orden->detalle ?? 'Sin detalle' }}</td>
                        <td class="px-4 py-2">{{ $orden->cantidad_total }}</td>
                        <td class="px-4 py-2">{{ $orden->cantidad_preparada }}</td>
                        <td class="px-4 py-2">{{ $orden->cantidad_total - $orden->cantidad_preparada }}</td>
                        <td class="px-4 py-2">
                            <span class="{{ $orden->estado == 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $orden->estado == 0 ? 'Pendiente' : 'Completado' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 flex justify-center gap-1">

                            <button wire:click="abrirModal('edit', {{ $orden->id }})" class="btn-cyan" title="Editar">Editar</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-600">No hay órdenes registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    @if($modal)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content">
                <div class="flex flex-col gap-4">
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Detalle (Opcional)</label>
                        <textarea wire:model="detalle" class="input-minimal" placeholder="Detalle de la orden"></textarea>
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Cantidad Total (Requerido)</label>
                        <input type="number" wire:model="cantidad_total" class="input-minimal" min="0" placeholder="Cantidad total">
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Cantidad Preparada</label>
                        <input type="number" wire:model="cantidad_preparada" class="input-minimal" min="0" placeholder="Cantidad preparada">
                    </div>
                    <div class="flex flex-wrap justify-center gap-2 mt-2">
                        @foreach([1 => 'Completado', 0 => 'Pendiente'] as $key => $label)
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
                <button type="button" wire:click="guardar" class="btn-cyan">Guardar</button>
            </div>

        </div>
    </div>
    @endif


</div>
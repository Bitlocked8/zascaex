<div class="p-2 mt-20 flex justify-center bg-transparent">
    <div class="w-full max-w-screen-xl">

        <h3
            class="text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto mb-4">
            Ordenes del día
        </h3>

        @if ($mensaje)
            <div class="mb-4 text-center text-green-700 bg-green-100 px-4 py-2 rounded">
                {{ $mensaje }}
            </div>
        @endif

        <div class="flex items-center gap-2 mb-4 flex-wrap">
            <input type="text" wire:model.live="search" placeholder="Buscar por detalle..."
                class="input-minimal w-full sm:w-auto flex-1" />

            <button wire:click="abrirModal('create')" class="btn-cyan">
                Añadir
            </button>

            @if (auth()->user()?->rol_id !== 4)
                <button wire:click="$toggle('mostrarCompletadas')" class="btn-cyan">
                    {{ $mostrarCompletadas ? 'Ocultar completadas' : 'Ver completadas' }}
                </button>
            @endif
        </div>

        <div class="overflow-auto max-h-[500px] border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-teal-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 text-left">Fecha</th>
                        <th class="px-4 py-2 text-left">Detalle</th>
                        <th class="px-4 py-2 text-left">Cantidad Total</th>
                        <th class="px-4 py-2 text-left">Estado</th>
                        <th class="px-4 py-2 text-left">Finalizado</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($ordenes as $orden)
                        <tr class="hover:bg-teal-50">
                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y H:i') }}
                            </td>

                            <td class="px-4 py-2">
                                {{ $orden->detalle ?? 'Sin detalle' }}
                            </td>

                            <td class="px-4 py-2">
                                {{ $orden->cantidad_total }}
                            </td>

                            <td
                                class="px-4 py-2 font-semibold {{ $orden->estado ? 'text-green-600' : 'text-red-600' }}">
                                {{ $orden->estado ? 'Completado' : 'Pendiente' }}
                            </td>

                            <td class="px-4 py-2">
                                @if ($orden->estado && $orden->fecha_fin)
                                    {{ \Carbon\Carbon::parse($orden->fecha_fin)->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-gray-400 italic">—</span>
                                @endif
                            </td>

                            <td class="px-4 py-2 flex justify-center gap-1">
                                <button wire:click="abrirModal('edit', {{ $orden->id }})" class="btn-cyan">
                                    Editar
                                </button>

                                <button wire:click="confirmarEliminacion({{ $orden->id }})" class="btn-cyan">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-600">
                                No hay órdenes registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($modal)
        <div class="modal-overlay">
            <div class="modal-box">
                <div class="modal-content flex flex-col gap-4">
                    <div>
                        <label class="font-semibold text-sm block mb-1">
                            Detalle
                        </label>
                        <textarea wire:model="detalle" class="input-minimal"></textarea>
                    </div>

                    <div>
                        <label class="font-semibold text-sm block mb-1">
                            Cantidad Total
                        </label>
                        <input type="number" wire:model="cantidad_total" class="input-minimal" min="0">
                    </div>

                    <div class="flex justify-center gap-2">
                        @foreach ([1 => 'Completado', 0 => 'Pendiente'] as $key => $label)
                            <button type="button" wire:click="$set('estado', {{ $key }})"
                                class="px-4 py-2 rounded-full text-sm
                                    {{ $estado == $key ? 'bg-cyan-600 text-white' : 'bg-gray-200' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <button wire:click="cerrarModal" class="btn-cyan">
                        Cancelar
                    </button>
                    <button wire:click="guardar" class="btn-cyan">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($confirmarEliminar)
        <div class="modal-overlay">
            <div class="modal-box text-center">
                <h3 class="text-lg font-semibold mb-4">
                    ¿Eliminar esta orden?
                </h3>

                <div class="flex justify-center gap-4">
                    <button wire:click="cancelarEliminacion" class="btn-cyan">
                        Cancelar
                    </button>

                    <button wire:click="eliminarConfirmado" class="btn-cyan bg-red-600">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

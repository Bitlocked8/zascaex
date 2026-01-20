<div class="p-2 mt-20 flex justify-center bg-transparent">
    <div class="w-full max-w-screen-xl">

        <h3 class="text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto mb-4">
            Mis Gastos
        </h3>

        <div class="flex items-center gap-2 mb-4 flex-wrap">
            <input type="text" wire:model.live="search" placeholder="Buscar por descripción..." class="input-minimal w-full sm:w-auto flex-1" />
            <button wire:click="abrirModal()" class="btn-cyan flex items-center gap-1">Añadir Gasto</button>
            <button
                wire:click="$toggle('soloHoy')"
                class="btn-cyan
               {{ $soloHoy ? 'bg-teal-600 text-white' : 'bg-cyan-600 text-white' }}">
                {{ $soloHoy ? ' todos' : ' hoy' }}
            </button>
        </div>

        <div class="overflow-auto max-h-[500px] border border-gray-200 rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-teal-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Fecha</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Descripción</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Monto</th>
                        <th class="px-4 py-2 text-left text-teal-700 font-semibold">Evidencia</th>
                        <th class="px-4 py-2 text-center text-teal-700 font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($gastos as $gasto)
                    <tr class="hover:bg-teal-50">
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($gasto->fecha)->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2">{{ $gasto->descripcion }}</td>
                        <td class="px-4 py-2">{{ number_format($gasto->monto, 2) }}</td>
                        <td class="px-4 py-2">
                            @if($gasto->archivo_evidencia)
                            <a href="{{ asset('storage/'.$gasto->archivo_evidencia) }}" target="_blank" class="text-blue-600 underline">Ver</a>
                            @endif
                        </td>
                        <td class="px-4 py-2 flex justify-center gap-1">
                            @if($gasto->personal_id === auth()->user()->personal->id || auth()->user()->rol_id === 1)
                            <button wire:click="abrirModal({{ $gasto->id }})" class="btn-cyan" title="Editar">Editar</button>
                            <button wire:click="eliminarGasto({{ $gasto->id }})" class="btn-cyan" title="Eliminar">Eliminar</button>
                            @else
                            <span class="text-gray-400 text-sm">Sin permisos</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-600">No hay gastos registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 font-bold text-right">
            Total: {{ number_format($gastos->sum('monto'), 2) }}
        </div>

    </div>

    <!-- Modal -->
    @if($modalVisible)
    <div class="modal-overlay">
        <div class="modal-box">
            <div class="modal-content">
                <div class="flex flex-col gap-4">
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Descripción</label>
                        <textarea wire:model="descripcion" class="input-minimal" placeholder="Descripción del gasto"></textarea>
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Monto</label>
                        <input type="number" wire:model="monto" class="input-minimal" min="0" placeholder="Monto del gasto">
                    </div>
                    <div>
                        <label class="font-semibold text-sm mb-1 block">Archivo de Evidencia (opcional)</label>
                        <input type="file" wire:model="archivo_evidencia" class="input-minimal">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" wire:click="cerrarModal" class="btn-cyan">Cerrar</button>
                <button type="button" wire:click="guardarGasto" class="btn-cyan">
                    {{ $gastoSeleccionado ? 'Actualizar' : 'Agregar' }}
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl">
    <h3 class="text-center text-2xl font-bold uppercase text-white bg-cyan-600 px-6 py-2 rounded-full mx-auto shadow-sm mb-4">
      Trabajos
    </h3>
    <div class="flex items-center gap-2 mb-4 flex-wrap">
      <input type="text" wire:model.live="search" placeholder="Buscar por sucursal o personal..."
        class="input-minimal w-full sm:w-auto flex-1" />

      <button wire:click="abrirModal('create')" class="btn-cyan flex items-center gap-1">Añadir</button>
      <button wire:click="abrirModalLabores" class="btn-cyan flex items-center gap-1">Labores</button>
    </div>

    <div class="overflow-auto max-h-[500px] border border-gray-200 rounded-md">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-teal-50 sticky top-0">
          <tr>
            <th class="px-4 py-2 text-left text-teal-700 font-semibold">Personal</th>
            <th class="px-4 py-2 text-left text-teal-700 font-semibold">Sucursal</th>
            <th class="px-4 py-2 text-left text-teal-700 font-semibold">Fecha inicio</th>
            <th class="px-4 py-2 text-left text-teal-700 font-semibold">Fecha final</th>
            <th class="px-4 py-2 text-left text-teal-700 font-semibold">Estado</th>
            <th class="px-4 py-2 text-center text-teal-700 font-semibold">Acciones</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @forelse($trabajos as $trabajo)
            <tr class="hover:bg-teal-50">
              <td class="px-4 py-2">{{ $trabajo->personal->nombres ?? 'No asignado' }}</td>
              <td class="px-4 py-2">{{ $trabajo->sucursal->nombre ?? 'Sin Sucursal' }}</td>
              <td class="px-4 py-2">{{ \Carbon\Carbon::parse($trabajo->fechaInicio)->format('d/m/Y H:i') }}</td>
              <td class="px-4 py-2">{{ $trabajo->fechaFinal ? \Carbon\Carbon::parse($trabajo->fechaFinal)->format('d/m/Y H:i') : 'N/A' }}</td>
              <td class="px-4 py-2">
                <span class="{{ $trabajo->estado == 0 ? 'text-red-600' : 'text-green-600' }}">
                  {{ $trabajo->estado == 0 ? 'Inactivo' : 'Activo' }}
                </span>
              </td>
              <td class="px-4 py-2 flex justify-center gap-1 flex-wrap">
                <button wire:click="abrirModal('edit', {{ $trabajo->id }})" class="btn-cyan" title="Editar">Editar</button>
                <button wire:click="modaldetalle({{ $trabajo->id }})" class="btn-cyan" title="Ver detalle">Ver</button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-4 text-gray-600">No hay trabajos registrados.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>




  @if($modal)
    <div class="modal-overlay">
      <div class="modal-box">
        <div class="modal-content flex flex-col gap-4">

          <div class="grid grid-cols-1 gap-2 mt-2">
            <p class="font-semibold text-sm">
              Fecha de Inicio:
              <span class="font-normal">{{ $fechaInicio ?? 'N/A' }}</span>
            </p>
            <label class="font-semibold text-sm">Fecha Final</label>
            <input type="date" wire:model="fechaFinal" class="input-minimal text-center">
            @error('fechaFinal')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>

          <div class="sm:col-span-2">
            <label class="font-semibold text-sm">Estado</label>

            <div class="flex justify-center gap-3 mt-2">
              <button type="button" wire:click="$set('estado', 1)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                          {{ $estado == 1
      ? 'bg-green-600 text-white border-green-700 shadow-md'
      : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                Activo
              </button>
              <button type="button" wire:click="$set('estado', 0)" class="px-4 py-2 rounded-lg border text-sm font-semibold transition
                          {{ $estado == 0
      ? 'bg-red-600 text-white border-red-700 shadow-md'
      : 'bg-gray-200 text-gray-700 border-gray-300 hover:bg-gray-300' }}">
                Inactivo
              </button>

            </div>

            @error('estado')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>

          <div>
            <label class="font-semibold text-sm mb-2 block">Sucursal</label>

            <div
              class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white  grid grid-cols-1 gap-2 max-h-[170px] overflow-y-auto">

              @forelse($sucursales as $s)
                      <button type="button" wire:click="$set('sucursal_id', {{ $s->id }})" class="w-full px-3 py-2 rounded-md border text-left transition
                                              {{ $sucursal_id == $s->id
                ? 'bg-cyan-600 text-white border-cyan-700 shadow'
                : 'bg-gray-100 text-gray-800 hover:bg-cyan-100'
                                              }}">
                        <p class="font-semibold text-sm">{{ $s->nombre }}</p>
                        <p class="text-xs text-gray-600 {{ $sucursal_id == $s->id ? 'text-cyan-100' : '' }}">
                          {{ $s->empresa?->nombre ?? 'Sin empresa' }}
                        </p>
                        <p class="text-xs text-gray-600 {{ $sucursal_id == $s->id ? 'text-cyan-100' : '' }}">
                          {{ $s->telefono ?? 'Sin teléfono' }}
                        </p>

                      </button>
              @empty
                <p class="text-center text-gray-500 py-3 text-sm">
                  No hay sucursales disponibles
                </p>
              @endforelse

            </div>

            @error('sucursal_id')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>


          <div>
            <label class="font-semibold text-sm mb-2 block">Personal Responsable</label>

            <div
              class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 max-h-[170px] overflow-y-auto">

              @forelse($personales as $p)
                      <button type="button" wire:click="$set('personal_id', {{ $p->id }})" class="w-full px-3 py-2 rounded-md border text-left transition
                                                                  {{ $personal_id == $p->id
                ? 'bg-cyan-600 text-white border-cyan-700 shadow'
                : 'bg-gray-100 text-gray-800 hover:bg-cyan-100'
                                                                  }}">
                        <p class="font-semibold text-sm">
                          {{ $p->nombres }} {{ $p->apellidos }}
                        </p>
                        <p class="text-xs text-gray-600 {{ $personal_id == $p->id ? 'text-cyan-100' : '' }}">
                          {{ $p->celular ?? 'Sin número' }}
                        </p>

                      </button>
              @empty
                <p class="text-center text-gray-500 py-3 text-sm">
                  No hay personal disponible
                </p>
              @endforelse

            </div>

            @error('personal_id')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>


          <div>
            <label class="font-semibold text-sm mb-2 block">Labor</label>

            <div
              class="w-full border border-gray-300 rounded-md shadow-sm p-2 bg-white grid grid-cols-1 gap-2 max-h-[170px] overflow-y-auto">

              @forelse(\App\Models\Labor::where('estado', 1)->get() as $l)
                <button type="button" wire:click="$set('labor_id', {{ $l->id }})"
                  class="w-full px-3 py-2 rounded-md border text-sm text-left transition
                                        {{ $labor_id == $l->id ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-800 hover:bg-cyan-100' }}">
                  {{ $l->nombre }}
                </button>

              @empty
                <p class="text-center text-gray-500 py-3 text-sm">
                  No hay labores disponibles
                </p>
              @endforelse

            </div>

            @error('labor_id')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>

          <div class="modal-footer">
            <button type="button" wire:click="cerrarModal" class="btn-cyan" title="Cerrar">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M5 5l3.585 3.585a4.83 4.83 0 0 0 6.83 0l3.585 -3.585" />
                <path d="M5 19l3.585 -3.585a4.83 4.83 0 0 1 6.83 0l3.585 3.584" />
              </svg>
              CERRAR
            </button>
            <button type="button" wire:click="guardar" class="btn-cyan">
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

  @if($modalLabores)
    <div class="modal-overlay">
      <div class="modal-box">
        <div class="modal-content flex flex-col gap-4">
          <div class="space-y-4 max-h-[400px] overflow-y-auto">
            @foreach($labores as $index => $labor)
              <div class="border p-4 rounded flex flex-col gap-2">
                <div class="flex justify-between items-center">
                  <strong>Labor #{{ $index + 1 }}</strong>
                  <button type="button" wire:click="eliminarLabor({{ $index }})" class="btn-circle btn-cyan"><svg
                      xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M4 7l16 0" />
                      <path d="M10 11l0 6" />
                      <path d="M14 11l0 6" />
                      <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                      <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg></button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                  <div>
                    <label class="font-semibold text-sm">Nombre</label>
                    <input type="text" wire:model="labores.{{ $index }}.nombre" class="input-minimal">
                  </div>
                  <div>
                    <label class="font-semibold text-sm">Descripción</label>
                    <input type="text" wire:model="labores.{{ $index }}.descripcion" class="input-minimal">
                  </div>
                  <div class="sm:col-span-2">
                    <label class="font-semibold text-sm">Estado</label>
                    <select wire:model="labores.{{ $index }}.estado" class="input-minimal w-full">
                      <option value="1">Activo</option>
                      <option value="0">Inactivo</option>
                    </select>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <div class="modal-footer">
            <button type="button" wire:click="agregarLabor" class="btn-cyan">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                  d="M18.333 2a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-4.333 4a1 1 0 0 0 -1 1v2h-2a1 1 0 0 0 0 2h2v2a1 1 0 0 0 2 0v-2h2a1 1 0 0 0 0 -2h-2v-2a1 1 0 0 0 -1 -1" />
                <path
                  d="M3.517 6.391a1 1 0 0 1 .99 1.738c-.313 .178 -.506 .51 -.507 .868v10c0 .548 .452 1 1 1h10c.284 0 .405 -.088 .626 -.486a1 1 0 0 1 1.748 .972c-.546 .98 -1.28 1.514 -2.374 1.514h-10c-1.652 0 -3 -1.348 -3 -3v-10.002a3 3 0 0 1 1.517 -2.605" />
              </svg>
              añadir labor
            </button>

            <button type="button" wire:click="guardarLabores" class="btn-cyan">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M14 4l0 4l-6 0l0 -4" />
              </svg>
              guardar labor
            </button>

            <button type="button" wire:click="$set('modalLabores', false)" class="btn-cyan" title="Cerrar">
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

  @if($modalDetalle)
    <div class="modal-overlay">
      <div class="modal-box">
        <div class="modal-content flex flex-col gap-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="flex flex-col gap-3">
              <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                <span class="label-info">Sucursal:</span>
                <span class="badge-info">{{ $trabajoSeleccionado->sucursal->nombre ?? '-' }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                <span class="label-info">Personal:</span>
                <span class="badge-info">{{ $trabajoSeleccionado->personal->nombres ?? '-' }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                <span class="label-info">Labor:</span>
                <span class="badge-info">{{ $trabajoSeleccionado->labor->nombre ?? '-' }}</span>
              </div>
            </div>

            <div class="flex flex-col gap-3">
              <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                <span class="label-info">Estado:</span>
                <span class="badge-info">
                  {{ $trabajoSeleccionado->estado ? 'Activo' : 'Inactivo' }}
                </span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                <span class="label-info">Fecha Inicio:</span>
                <span
                  class="badge-info">{{ \Carbon\Carbon::parse($trabajoSeleccionado->fechaInicio)->format('Y-m-d') ?? '-' }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-start gap-2">
                <span class="label-info">Fecha Final:</span>
                <span
                  class="badge-info">{{ $trabajoSeleccionado->fechaFinal ? \Carbon\Carbon::parse($trabajoSeleccionado->fechaFinal)->format('Y-m-d') : '-' }}</span>
              </div>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button wire:click="$set('modalDetalle', false)" class="btn-cyan" title="Cerrar">
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
  @endif




</div>
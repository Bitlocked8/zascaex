<div class="flex justify-center mt-20 px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-6xl">
        <div
            class="p-6 bg-gradient-to-r from-cyan-500 to-cyan-400 text-white rounded-3xl shadow-xl border border-cyan-300">
            <h2 class="text-2xl font-extrabold mb-6">Usuario Logueado</h2>

            @if($usuario)
                <div class="space-y-2">
                    <p><span class="font-semibold">Email:</span> {{ $usuario->email ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Rol:</span> {{ $usuario->rol->nombre ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Creación:</span> {{ $usuario->created_at->format('d/m/Y H:i') }}</p>
                    <p><span class="font-semibold">Última actualización:</span>
                        {{ $usuario->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                @if(session()->has('mensaje'))
                    <div class="mt-4 p-2 bg-green-200 text-green-800 rounded">
                        {{ session('mensaje') }}
                    </div>
                @endif
                <div class="mt-6">
                    <label class="block mb-1 font-semibold">Actualizar Correo</label>
                    <input type="email" wire:model.defer="nuevo_correo" class="border p-2 w-full rounded text-black">
                    @error('nuevo_correo') <span class="text-red-200 text-sm">{{ $message }}</span> @enderror
                    <button wire:click="actualizarCorreo"
                        class="mt-2 bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white">
                        Guardar Correo
                    </button>
                </div>
                <div class="mt-6">
                    <label class="block mb-1 font-semibold">Nueva Contraseña</label>
                    <input type="password" wire:model.defer="nueva_password" placeholder="Nueva contraseña"
                        class="border p-2 w-full rounded text-black mb-2">
                    <input type="password" wire:model.defer="nueva_password_confirm" placeholder="Confirmar contraseña"
                        class="border p-2 w-full rounded text-black mb-2">
                    @error('nueva_password') <span class="text-red-200 text-sm">{{ $message }}</span> @enderror
                    <button wire:click="actualizarPassword"
                        class="mt-2 bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-white">
                        Guardar Contraseña
                    </button>
                </div>

            @else
                <p class="italic text-gray-200">No hay usuario logueado.</p>
            @endif
        </div>
        <div class="p-6 bg-white rounded-3xl shadow-xl border border-gray-200">
            <h2 class="text-2xl font-extrabold mb-6 text-teal-700">Datos del Cliente Asociado</h2>

            @if($cliente)
                <div class="space-y-2 text-gray-700">
                    <p><span class="font-semibold">Código:</span> {{ $cliente->codigo ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Nombre:</span> {{ $cliente->nombre ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Empresa:</span> {{ $cliente->empresa ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Teléfono:</span> {{ $cliente->telefono ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Correo:</span> {{ $cliente->correo ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Latitud:</span> {{ $cliente->latitud ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Longitud:</span> {{ $cliente->longitud ?? 'N/A' }}</p>
                    <p>
                        <span class="font-semibold">Categoría:</span>
                        @php
                            $categoriaTexto = match ($cliente->categoria) {
                                1 => ' Cliente Nuevo',
                                2 => ' Cliente Regular',
                                3 => ' Cliente Antiguo',
                                default => 'Sin categoría'
                            };
                        @endphp

                        {{ $categoriaTexto }}
                    </p>

                    <p><span class="font-semibold">Estado:</span>
                        <span class="{{ $cliente->estado ? 'text-green-600' : 'text-red-600' }}">
                            {{ $cliente->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </p>                   
                </div>
            @else
                <p class="italic text-gray-400">No hay cliente asociado.</p>
            @endif
        </div>

    </div>
</div>
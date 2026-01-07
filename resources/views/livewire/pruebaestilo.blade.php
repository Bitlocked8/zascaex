<div class="flex justify-center mt-20 px-4">
    <div class="grid grid-cols-1 {{ $cliente ? 'md:grid-cols-2' : '' }} gap-6 w-full max-w-6xl">
        <div
            class="p-6 bg-white rounded-3xl shadow-xl border border-cyan-200">
            <h2 class="text-2xl font-extrabold mb-6 text-cyan-700">
                Usuario Logueado
            </h2>

            @if($usuario)
                <div class="space-y-2 text-gray-700">
                    <p><span class="font-semibold text-cyan-600">Usuario:</span> {{ $usuario->email ?? 'N/A' }}</p>
                    <p><span class="font-semibold text-cyan-600">Creación:</span>
                        {{ $usuario->created_at->format('d/m/Y H:i') }}
                    </p>
                    <p><span class="font-semibold text-cyan-600">Última actualización:</span>
                        {{ $usuario->updated_at->format('d/m/Y H:i') }}
                    </p>
                </div>

                @if(session()->has('mensaje'))
                    <div class="mt-4 p-2 bg-cyan-100 text-cyan-800 rounded">
                        {{ session('mensaje') }}
                    </div>
                @endif
                <div class="mt-6">
                    <label class="block mb-1 font-semibold text-cyan-700">Actualizar Correo</label>
                    <input type="email" wire:model.defer="nuevo_correo"
                        class="border border-cyan-300 p-2 w-full rounded focus:ring-2 focus:ring-cyan-400">
                    @error('nuevo_correo')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <button wire:click="actualizarCorreo"
                        class="mt-2 bg-cyan-600 hover:bg-cyan-700 px-4 py-2 rounded text-white">
                        Guardar Correo
                    </button>
                </div>
                <div class="mt-6">
                    <label class="block mb-1 font-semibold text-cyan-700">Nueva Contraseña</label>

                    <input type="password" wire:model.defer="nueva_password"
                        placeholder="Nueva contraseña"
                        class="border border-cyan-300 p-2 w-full rounded mb-2">

                    <input type="password" wire:model.defer="nueva_password_confirm"
                        placeholder="Confirmar contraseña"
                        class="border border-cyan-300 p-2 w-full rounded mb-2">

                    @error('nueva_password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <button wire:click="actualizarPassword"
                        class="mt-2 bg-cyan-600 hover:bg-cyan-700 px-4 py-2 rounded text-white">
                        Guardar Contraseña
                    </button>
                </div>
            @else
                <p class="italic text-gray-400">No hay usuario logueado.</p>
            @endif
        </div>
        @if($cliente)
            <div class="p-6 bg-white rounded-3xl shadow-xl border border-cyan-200">
                <h2 class="text-2xl font-extrabold mb-6 text-cyan-700">
                    Datos del Cliente Asociado
                </h2>

                <div class="space-y-2 text-gray-700">
                    <p><span class="font-semibold text-cyan-600">Código:</span> {{ $cliente->codigo }}</p>
                    <p><span class="font-semibold text-cyan-600">Nombre:</span> {{ $cliente->nombre }}</p>
                    <p><span class="font-semibold text-cyan-600">Empresa:</span> {{ $cliente->empresa }}</p>
                    <p><span class="font-semibold text-cyan-600">Teléfono:</span> {{ $cliente->telefono }}</p>
                    <p><span class="font-semibold text-cyan-600">Correo:</span> {{ $cliente->correo }}</p>

                    <p>
                        <span class="font-semibold text-cyan-600">Categoría:</span>
                        @php
                            $categoriaTexto = match ($cliente->categoria) {
                                1 => 'Cliente Nuevo',
                                2 => 'Cliente Regular',
                                3 => 'Cliente Antiguo',
                                default => 'Sin categoría'
                            };
                        @endphp
                        {{ $categoriaTexto }}
                    </p>

                    <p>
                        <span class="font-semibold text-cyan-600">Estado:</span>
                        <span class="{{ $cliente->estado ? 'text-cyan-600' : 'text-red-500' }}">
                            {{ $cliente->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </p>
                </div>
            </div>
        @endif

    </div>
</div>

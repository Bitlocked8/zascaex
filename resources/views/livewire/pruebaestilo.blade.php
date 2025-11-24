<div class="flex justify-center mt-20 px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-6xl">

        <!-- Tarjeta Usuario -->
        <div class="p-6 bg-gradient-to-r from-cyan-500 to-cyan-400 text-white rounded-3xl shadow-xl border border-cyan-300">
            <h2 class="text-2xl font-extrabold mb-6">Usuario Logueado</h2>

            @if($usuario)
                <div class="space-y-2">
                    <p><span class="font-semibold">Email:</span> {{ $usuario->email ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Rol:</span> {{ $usuario->rol->nombre ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Creación:</span> {{ $usuario->created_at->format('d/m/Y H:i') }}</p>
                    <p><span class="font-semibold">Última actualización:</span> {{ $usuario->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            @else
                <p class="italic text-gray-200">No hay usuario logueado.</p>
            @endif
        </div>

        <!-- Tarjeta Cliente -->
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
                    <p><span class="font-semibold">Categoría:</span> {{ $cliente->categoria ?? 'N/A' }}</p>
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

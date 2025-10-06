<div class="p-6 bg-white rounded shadow-lg max-w-xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-4">Usuario Logueado</h2>

    @if($usuario)
    <p><strong>Nombre:</strong> {{ $usuario->nombre ?? 'N/A' }}</p>
    <p><strong>Email:</strong> {{ $usuario->email ?? 'N/A' }}</p>
    <p><strong>Rol:</strong> {{ $usuario->rol->nombre ?? 'N/A' }}</p>
    <p><strong>Fecha de creación:</strong> {{ $usuario->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>Última actualización:</strong> {{ $usuario->updated_at->format('d/m/Y H:i') }}</p>

    @if($cliente)
    <hr class="my-4 border-gray-300">
    <h3 class="text-xl font-semibold mb-2">Datos del Cliente Asociado</h3>
    <p><strong>Codigo:</strong> {{ $cliente->codigo ?? 'N/A' }}</p>
    <p><strong>Nombre:</strong> {{ $cliente->nombre ?? 'N/A' }}</p>
    <p><strong>Empresa:</strong> {{ $cliente->empresa ?? 'N/A' }}</p>
    <p><strong>Teléfono:</strong> {{ $cliente->telefono ?? 'N/A' }}</p>
    <p><strong>Correo Cliente:</strong> {{ $cliente->correo ?? 'N/A' }}</p>
    <p><strong>Latitud:</strong> {{ $cliente->latitud ?? 'N/A' }}</p>
    <p><strong>Longitud:</strong> {{ $cliente->longitud ?? 'N/A' }}</p>
    <p><strong>Categoria:</strong> {{ $cliente->categoria ?? 'N/A' }}</p>
    <p><strong>Estado:</strong> {{ $cliente->estado ? 'Activo' : 'Inactivo' }}</p>
    @endif
    @else
    <p>No hay usuario logueado.</p>
    @endif
</div>
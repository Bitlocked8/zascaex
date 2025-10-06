<div class="flex justify-center mt-16 px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-6xl">

        <!-- Card Usuario Logueado -->
        <div class="p-6 bg-cyan-600 text-white rounded-2xl shadow-lg">
            <h2 class="text-2xl font-bold mb-4">Usuario Logueado</h2>

            @if($usuario)
            <p><strong>Email:</strong> {{ $usuario->email ?? 'N/A' }}</p>
            <p><strong>Rol:</strong> {{ $usuario->rol->nombre ?? 'N/A' }}</p>
            <p><strong>Fecha de creación:</strong> {{ $usuario->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Última actualización:</strong> {{ $usuario->updated_at->format('d/m/Y H:i') }}</p>
            @else
            <p>No hay usuario logueado.</p>
            @endif
        </div>

        <!-- Card Datos del Cliente Asociado -->
        <div class="p-6 bg-cyan-600 text-white rounded-2xl shadow-lg">
            <h2 class="text-2xl font-bold mb-4">Datos del Cliente Asociado</h2>

            @if($cliente)
            <p><strong>Codigo:</strong> {{ $cliente->codigo ?? 'N/A' }}</p>
            <p><strong>Nombre:</strong> {{ $cliente->nombre ?? 'N/A' }}</p>
            <p><strong>Empresa:</strong> {{ $cliente->empresa ?? 'N/A' }}</p>
            <p><strong>Teléfono:</strong> {{ $cliente->telefono ?? 'N/A' }}</p>
            <p><strong>Correo Cliente:</strong> {{ $cliente->correo ?? 'N/A' }}</p>
            <p><strong>Latitud:</strong> {{ $cliente->latitud ?? 'N/A' }}</p>
            <p><strong>Longitud:</strong> {{ $cliente->longitud ?? 'N/A' }}</p>
            <p><strong>Categoria:</strong> {{ $cliente->categoria ?? 'N/A' }}</p>
            <p><strong>Estado:</strong> {{ $cliente->estado ? 'Activo' : 'Inactivo' }}</p>
            @else
            <p>No hay cliente asociado.</p>
            @endif
        </div>

        <!-- Card Reloj Digital -->
        <div class="md:col-span-2 p-6 bg-cyan-600 text-white rounded-2xl shadow-lg flex justify-center items-center">
            <div class="flex flex-col items-center justify-center">
                <!-- Icono más grande -->
                <svg xmlns="http://www.w3.org/2000/svg" width="150" height="150" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-4">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 12l2 3" />
                    <path d="M12 7v5" />
                </svg>

                <!-- Reloj -->
                <p class="text-6xl font-mono" id="reloj"></p>
            </div>
        </div>

    </div>
</div>

<script>
    function actualizarReloj() {
        const ahora = new Date();

        // Formato de hora con zona horaria de Bolivia
        const opciones = {
            timeZone: 'America/La_Paz',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };

        const hora = new Intl.DateTimeFormat('es-BO', opciones).format(ahora);

        // Fecha también en Bolivia
        const fecha = ahora.toLocaleDateString('es-BO', {
            timeZone: 'America/La_Paz'
        });

        document.getElementById('reloj').textContent = `${hora} - ${fecha}`;
    }

    // Actualiza el reloj cada segundo
    setInterval(actualizarReloj, 1000);
    actualizarReloj();
</script>
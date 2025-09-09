<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mapa de Clientes</title>

    {{-- CSS de Tailwind y Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    @vite(['resources/css/app.css'])
</head>

<body class="bg-white text-gray-900">

    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Registro de Clientes</h1>
            <a href="{{ route('home') }}"
                class="text-cyan-500 hover:text-cyan-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                    class="icon icon-tabler icons-tabler-filled icon-tabler-home">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M12.707 2.293l9 9c.63 .63 .184 1.707 -.707 1.707h-1v6a3 3 0 0 1 -3 3h-1v-7a3 3 0 0 0 -2.824 -2.995l-.176 -.005h-2a3 3 0 0 0 -3 3v7h-1a3 3 0 0 1 -3 -3v-6h-1c-.89 0 -1.337 -1.077 -.707 -1.707l9 -9a1 1 0 0 1 1.414 0m.293 11.707a1 1 0 0 1 1 1v7h-4v-7a1 1 0 0 1 .883 -.993l.117 -.007z" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Columna 1: Formulario de Registro -->
            <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg p-6 bg-white">


                <form id="clienteForm" action="{{ route('clientes.store') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                    @csrf

                    <!-- Nombre -->
                    <div class="mb-4">
                        <input type="text" id="nombre" name="nombre"
                            value="{{ old('nombre', $cliente->nombre ?? '') }}"
                            placeholder="Nombre"
                            class="input-minimal">
                        @error('nombre') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Empresa -->
                    <div class="mb-4">
                        <input type="text" id="empresa" name="empresa"
                            value="{{ old('empresa', $cliente->empresa ?? '') }}"
                            placeholder="Empresa"
                            class="input-minimal">
                        @error('empresa') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- Razón Social -->
                    <div class="mb-4">
                        <input type="text" id="razonSocial" name="razonSocial"
                            value="{{ old('razonSocial', $cliente->razonSocial ?? '') }}"
                            placeholder="Razón Social"
                            class="input-minimal">
                        @error('razonSocial') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <!-- NIT/CI -->
                    <div class="mb-4">
                        <input type="text" id="nitCi" name="nitCi"
                            value="{{ old('nitCi', $cliente->nitCi ?? '') }}"
                            placeholder="NIT/CI"
                            class="input-minimal">
                        @error('nitCi') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                </form>
            </div>

            <!-- Columna 2: Datos del Cliente -->
            <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg p-6 bg-white">

                <!-- Teléfono -->
                <div class="mb-4">
                    <input type="text" id="telefono" name="telefono"
                        value="{{ old('telefono', $cliente->telefono ?? '') }}"
                        placeholder="Teléfono"
                        class="input-minimal">
                    @error('telefono') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Correo -->
                <div class="mb-4">
                    <input type="email" id="correo" name="correo"
                        value="{{ old('correo', $cliente->correo ?? '') }}"
                        placeholder="Correo Empresa"
                        class="input-minimal">
                    @error('correo') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Email usuario -->
                <div class="mb-4">
                    <input type="email" id="email" name="email"
                        value="{{ old('email') }}"
                        placeholder="Email de usuario"
                        class="input-minimal"
                        required>
                    @error('email') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Contraseña -->
                <div class="mb-4">
                    <input type="password" id="password" name="password"
                        placeholder="Contraseña"
                        class="input-minimal"
                        required>
                    @error('password') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Categoría -->
                <div class="mb-4">
                    <select id="categoria" name="categoria" class="input-minimal">
                        <option value="1" {{ old('categoria', $cliente->categoria ?? 1) == 1 ? 'selected' : '' }}>Cliente Nuevo</option>
                        <option value="2" {{ old('categoria', $cliente->categoria ?? 1) == 2 ? 'selected' : '' }}>Cliente Regular</option>
                        <option value="3" {{ old('categoria', $cliente->categoria ?? 1) == 3 ? 'selected' : '' }}>Cliente VIP</option>
                    </select>
                    @error('categoria') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Estado -->
                <div class="mb-4">
                    <select id="estado" name="estado" class="input-minimal">
                        <option value="1" {{ old('estado', $cliente->estado ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado', $cliente->estado ?? 0) == 0 ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado') <span class="error-message">{{ $message }}</span> @enderror
                </div>

            </div>

            <!-- Columna 3: Mapa y Coordenadas -->
            <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg p-6 bg-white">

                <div id="mapa" class="w-full h-[200px] lg:h-[400px] rounded shadow-lg mb-4"></div>

                <!-- Coordenadas -->
                <div class="mb-4">
                    <input type="text" id="coordenadas" name="coordenadas"
                        value="{{ old('coordenadas', ($cliente->latitud ?? '') . ', ' . ($cliente->longitud ?? '')) }}"
                        placeholder="Coordenadas (Latitud, Longitud)"
                        class="input-minimal">
                    @error('coordenadas') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Latitud -->
                <div class="mb-4">
                    <input type="text" id="latitud" name="latitud"
                        value="{{ old('latitud', $cliente->latitud ?? '') }}"
                        placeholder="Latitud"
                        class="input-minimal">
                    @error('latitud') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <!-- Longitud -->
                <div class="mb-4">
                    <input type="text" id="longitud" name="longitud"
                        value="{{ old('longitud', $cliente->longitud ?? '') }}"
                        placeholder="Longitud"
                        class="input-minimal">
                    @error('longitud') <span class="error-message">{{ $message }}</span> @enderror
                </div>

            </div>
        </div>


    </div>

    {{-- JS de Leaflet y Vite --}}
    @vite('resources/js/app.js')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        let map;
        let marcadorSeleccionado = null;

        document.addEventListener('DOMContentLoaded', function() {
            console.log('Inicializando mapa en [-17.393993, -66.170568]');
            map = L.map('mapa').setView([-17.393993, -66.170568], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
            }).addTo(map);

            // Evento click para obtener coordenadas e insertarlas en el formulario
            map.on('click', function(e) {
                const {
                    lat,
                    lng
                } = e.latlng;

                if (marcadorSeleccionado) {
                    map.removeLayer(marcadorSeleccionado);
                }

                marcadorSeleccionado = L.marker([lat, lng])
                    .addTo(map)
                    .bindPopup(`
                        <div class="text-sm">
                            <strong>Coordenadas:</strong><br>
                            Latitud: ${lat.toFixed(6)}<br>
                            Longitud: ${lng.toFixed(6)}<br>
                            <button onclick="copiarCoordenadas(${lat.toFixed(6)}, ${lng.toFixed(6)})"
                                class="mt-2 bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">
                                Copiar coordenadas
                            </button>
                        </div>
                    `)
                    .openPopup();

                // Insertar coordenadas en los campos del formulario
                document.getElementById('coordenadas').value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                document.getElementById('latitud').value = lat.toFixed(6);
                document.getElementById('longitud').value = lng.toFixed(6);
            });
        });

        function copiarCoordenadas(lat, lng) {
            const texto = `${lat}, ${lng}`;
            navigator.clipboard.writeText(texto).then(() => {
                // Asignar valores a los campos de latitud y longitud
                document.getElementById('latitud').value = lat.toFixed(6);
                document.getElementById('longitud').value = lng.toFixed(6);
                alert("Coordenadas copiadas: " + texto);
            });
        }
    </script>
</body>

</html>
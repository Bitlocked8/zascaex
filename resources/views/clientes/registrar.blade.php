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

<body class="color-bg text-gray-900">

    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Mapa de Clientes</h1>
            <a href="{{ route('home') }}"
                class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                    class="icon icon-tabler icons-tabler-filled icon-tabler-home">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M12.707 2.293l9 9c.63 .63 .184 1.707 -.707 1.707h-1v6a3 3 0 0 1 -3 3h-1v-7a3 3 0 0 0 -2.824 -2.995l-.176 -.005h-2a3 3 0 0 0 -3 3v7h-1a3 3 0 0 1 -3 -3v-6h-1c-.89 0 -1.337 -1.077 -.707 -1.707l9 -9a1 1 0 0 1 1.414 0m.293 11.707a1 1 0 0 1 1 1v7h-4v-7a1 1 0 0 1 .883 -.993l.117 -.007z" />
                </svg>
            </a>
        </div>



       <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Mapa -->
    <div id="mapa" class="w-full h-[400px] lg:h-[600px] rounded shadow-lg"></div>

    <!-- Formulario de Registro/Edición de Clientes -->
    <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg p-6 bg-white">
        <form id="clienteForm" action="{{ route('clientes.store') }}" method="POST" class="space-y-4"
            enctype="multipart/form-data">
            @csrf
            <h3 class="text-lg font-semibold text-gray-900">Registrar Cliente</h3>

            <!-- Nombre -->
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-text input-g">
                @error('nombre') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Empresa -->
            <div>
                <label for="empresa" class="block text-sm font-medium text-gray-700">Empresa</label>
                <input type="text" id="empresa" name="empresa"
                    value="{{ old('empresa', $cliente->empresa ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-text input-g">
                @error('empresa') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Razón Social -->
            <div>
                <label for="razonSocial" class="block text-sm font-medium text-gray-700">Razón Social</label>
                <input type="text" id="razonSocial" name="razonSocial"
                    value="{{ old('razonSocial', $cliente->razonSocial ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-text input-g">
                @error('razonSocial') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- NIT/CI -->
            <div>
                <label for="nitCi" class="block text-sm font-medium text-gray-700">NIT/CI</label>
                <input type="text" id="nitCi" name="nitCi" value="{{ old('nitCi', $cliente->nitCi ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-text input-g">
                @error('nitCi') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Teléfono -->
            <div>
                <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" id="telefono" name="telefono"
                    value="{{ old('telefono', $cliente->telefono ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-text input-g">
                @error('telefono') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Correo -->
            <div>
                <label for="correo" class="block text-sm font-medium text-gray-700">Correo</label>
                <input type="email" id="correo" name="correo"
                    value="{{ old('correo', $cliente->correo ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-text input-g">
                @error('correo') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- 📌 Email de usuario (login) -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email de usuario</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-text input-g"
                    required>
                @error('email') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- 📌 Contraseña -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" id="password" name="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-text input-g"
                    required>
                @error('password') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Coordenadas Combinadas -->
            <div>
                <label for="coordenadas" class="block text-sm font-medium text-gray-700">Coordenadas (Latitud, Longitud)</label>
                <input type="text" id="coordenadas" name="coordenadas"
                    value="{{ old('coordenadas', ($cliente->latitud ?? '') . ', ' . ($cliente->longitud ?? '')) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-text input-g"
                    placeholder="-17.78, -63.17">
                @error('coordenadas') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Latitud -->
            <div>
                <label for="latitud" class="block text-sm font-medium text-gray-700">Latitud</label>
                <input type="text" id="latitud" name="latitud"
                    value="{{ old('latitud', $cliente->latitud ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-text input-g"
                    placeholder="-17.7833">
                @error('latitud') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Longitud -->
            <div>
                <label for="longitud" class="block text-sm font-medium text-gray-700">Longitud</label>
                <input type="text" id="longitud" name="longitud"
                    value="{{ old('longitud', $cliente->longitud ?? '') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-text input-g"
                    placeholder="-63.1821">
                @error('longitud') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Foto -->
            <div>
                <label for="foto" class="block text-sm font-medium text-gray-700">Foto</label>
                <input type="file" id="foto" name="foto" accept="image/*"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-text input-g">
                @error('foto') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Estado -->
            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                <select id="estado" name="estado"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-text input-g">
                    <option value="1" {{ old('estado', $cliente->estado ?? 1) == 1 ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('estado', $cliente->estado ?? 0) == 0 ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('estado') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Botones -->
            <div class="mt-6 flex justify-center w-full space-x-4">
                <button type="submit" id="guardarCliente"
                    class="text-indigo-500 hover:text-indigo-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
                    <!-- Icono Guardar -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                        class="icon icon-tabler icon-tabler-device-floppy">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M14 4l0 4l-6 0l0 -4" />
                    </svg>
                </button>
                <a href="{{ route('home') }}"
                    class="text-red-500 hover:text-red-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-full">
                    <!-- Icono Cancelar -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                        class="icon icon-tabler icon-tabler-x">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M18 6l-12 12" />
                        <path d="M6 6l12 12" />
                    </svg>
                </a>
            </div>
        </form>
    </div>
</div>


    </div>

    {{-- JS de Leaflet y Vite --}}
    @vite('resources/js/app.js')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        let map;
        let marcadorSeleccionado = null;
    
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Inicializando mapa en [-17.393993, -66.170568]');
            map = L.map('mapa').setView([-17.393993, -66.170568], 13);
    
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
            }).addTo(map);
    
            // Evento click para obtener coordenadas e insertarlas en el formulario
            map.on('click', function (e) {
                const { lat, lng } = e.latlng;
    
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
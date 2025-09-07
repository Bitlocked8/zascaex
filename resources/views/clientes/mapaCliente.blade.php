<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mapa del Cliente - {{ $cliente->nombre }}</title>

    <!-- CSS de Tailwind y Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    @vite(['resources/css/app.css'])
</head>
<body class="color-bg text-gray-900">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Mapa del Cliente: {{ $cliente->nombre }}</h1>
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

        <!-- Información del Cliente -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-900">Información del Cliente</h2>
            <p><strong>Nombre:</strong> {{ $cliente->nombre }}</p>
            <p><strong>Empresa:</strong> {{ $cliente->empresa ?? 'N/A' }}</p>
            <p>
                <strong>Coordenadas:</strong> {{ $cliente->latitud ?? 'N/A' }}, {{ $cliente->longitud ?? 'N/A' }}
                <a href="https://www.google.com/maps?q={{ $cliente->latitud ?? '' }},{{ $cliente->longitud ?? '' }}"
                   target="_blank"
                   class="text-blue-500 hover:text-blue-600 underline ml-2">
                    Ver en Google Maps
                </a>
            </p>
        </div>

        <!-- Mapa -->
        <div id="mapa" class="w-full h-[400px] lg:h-[600px] rounded shadow-lg"></div>
    </div>

    <!-- JS de Leaflet y Vite -->
    @vite('resources/js/app.js')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar el mapa con las coordenadas del cliente
            const lat = {{ $cliente->latitud ?? -17.393993 }};
            const lng = {{ $cliente->longitud ?? -66.170568 }};
            const map = L.map('mapa').setView([lat, lng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
            }).addTo(map);

            // Colocar un marcador en la ubicación del cliente
            L.marker([lat, lng])
                .addTo(map)
                .bindPopup(`
                    <div class="text-sm">
                        <strong>{{ $cliente->nombre }}</strong><br>
                        Empresa: {{ $cliente->empresa ?? 'N/A' }}<br>
                        Coordenadas: ${lat.toFixed(6)}, ${lng.toFixed(6)}
                    </div>
                `)
                .openPopup();
        });
    </script>
</body>
</html>
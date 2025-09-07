<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mapa de Clientes</title>

    {{-- CSS de Tailwind y Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    @vite(['resources/css/app.css'])
    @livewireStyles
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

            <!-- Tabla de Clientes -->
            <div class="relative mt-3 w-full overflow-x-auto shadow-md sm:rounded-lg">
                <table
                    class="w-full text-sm text-left border border-slate-200 dark:border-cyan-200 rounded-lg border-collapse">
                    <thead class="text-x uppercase color-bg">
                        <tr>
                            <th scope="col" class="px-6 py-3 p-text text-left">Información</th>
                            <th scope="col" class="px-6 py-3 p-text text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr class="color-bg border border-slate-200">
                                <td class="px-6 py-4 p-text text-left">
                                    <div class="mb-2">
                                        <span class="font-semibold block">Nombre:</span>
                                        <span>{{ $cliente->nombre }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="font-semibold block">Empresa:</span>
                                        <span>{{ $cliente->empresa }}</span>
                                    </div>
                                    <div>
                                        <span class="font-semibold block">Celular:</span>
                                        <span>{{ $cliente->celular }}</span>
                                    </div>
                                </td>
                                <td class="py-2 px-4 border-b text-right">
                                    <button onclick="enfocarEnCliente({{ $cliente->id }})"
                                        class="text-emerald-500 hover:text-emerald-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-eye-pin">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                            <path
                                                d="M12 18c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.362 0 6.202 1.745 8.517 5.234" />
                                            <path
                                                d="M21.121 20.121a3 3 0 1 0 -4.242 0c.418 .419 1.125 1.045 2.121 1.879c1.051 -.89 1.759 -1.516 2.121 -1.879z" />
                                            <path d="M19 18v.01" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="mt-4">
                    {{ $clientes->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

    {{-- JS de Leaflet y Vite --}}
    @vite('resources/js/app.js')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    @livewireScripts

    <script>
        let map;
        let marcadoresClientes = {};
        let marcadorSeleccionado = null;

        document.addEventListener('DOMContentLoaded', function () {
            map = L.map('mapa').setView([-17.7833, -63.1821], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
            }).addTo(map);

            // Crear marcadores por cada cliente
            @foreach ($clientes as $cliente)
                const marker{{ $cliente->id }} = L.marker([{{ $cliente->latitud }}, {{ $cliente->longitud }}])
                    .addTo(map)
                    .bindPopup(`
                                                    <strong>{{ $cliente->nombre }}</strong><br>
                                                    {{ $cliente->empresa ?? '' }}<br>
                                                    {{ $cliente->celular ?? '' }}
                                                `);

                marcadoresClientes[{{ $cliente->id }}] = marker{{ $cliente->id }};
            @endforeach

            // Evento click para obtener coordenadas
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
            });
        });

        function copiarCoordenadas(lat, lng) {
            const texto = `${lat}, ${lng}`;
            navigator.clipboard.writeText(texto).then(() => {
                alert("Coordenadas copiadas: " + texto);
            });
        }

        function enfocarEnCliente(id) {
            const marcador = marcadoresClientes[id];
            if (marcador) {
                map.setView(marcador.getLatLng(), 16);
                marcador.openPopup();
            }
        }
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Coordenadas - {{ $cliente->nombre }}</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    @vite(['resources/css/app.css'])
</head>

<body class="color-bg text-gray-900">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Editar Coordenadas: {{ $cliente->nombre }}</h1>
            <a href="{{ route('home') }}" class="btn-circle btn-cyan">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 12h6m3 0h1.5m3 0h.5" />
                    <path d="M5 12l6 6" />
                    <path d="M5 12l6 -6" />
                </svg>
            </a>
        </div>

        <!-- Información del cliente -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-900">Información del Cliente</h2>
            <p><strong>Nombre:</strong> {{ $cliente->nombre }}</p>
            <p><strong>Empresa:</strong> {{ $cliente->empresa ?? 'N/A' }}</p>
            <p>
                <strong>Coordenadas:</strong>
                <span id="coords-text">{{ $cliente->latitud ?? 'N/A' }}, {{ $cliente->longitud ?? 'N/A' }}</span>
            </p>

            <!-- Botón Guardar Coordenadas -->
            <button id="guardar-coords" class="btn-circle btn-cyan mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M14 4l0 4l-6 0l0 -4" />
                </svg>
            </button>
        </div>

        <!-- Mapa -->
        <div id="mapa" class="w-full h-[400px] lg:h-[600px] rounded shadow-lg"></div>
    </div>

    @vite('resources/js/app.js')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let lat = @json($cliente->latitud ?? -17.393993);
            let lng = @json($cliente->longitud ?? -66.170568);
            const clienteId = @json($cliente->id);


            const map = L.map('mapa').setView([lat, lng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
            }).addTo(map);

            let marker = L.marker([lat, lng], {
                    draggable: false
                }).addTo(map)
                .bindPopup(`<strong>@json($cliente->nombre)</strong><br>Haz clic en el mapa para cambiar coordenadas.`)
                .openPopup();

            map.on('click', function(e) {
                lat = e.latlng.lat;
                lng = e.latlng.lng;

                marker.setLatLng([lat, lng])
                    .getPopup()
                    .setContent(`<strong>@json($cliente->nombre)</strong><br>Coordenadas: ${lat.toFixed(6)}, ${lng.toFixed(6)}`)
                    .openOn(map);

                document.getElementById('coords-text').textContent = lat.toFixed(6) + ', ' + lng.toFixed(6);
            });

            document.getElementById('guardar-coords').addEventListener('click', function() {
                fetch(`/clientes/${clienteId}/actualizar-coordenadas`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            latitud: lat,
                            longitud: lng
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Coordenadas guardadas correctamente.');
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(err => {
                        alert('Error al guardar coordenadas.');
                        console.error(err);
                    });
            });
        });
    </script>

</body>

</html>
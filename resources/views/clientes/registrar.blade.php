<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro de Clientes</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    @vite(['resources/css/app.css'])
</head>

<body class="bg-white text-gray-900">

    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('home') }}" class="btn-cyan">Volver</a>
        </div>

        <form id="clienteForm" action="{{ route('clientes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 w-full shadow-md sm:rounded-lg p-6 bg-white">
                    <label class="font-semibold text-sm block mb-2">
                        Haga click en el mapa para asignar una dirección
                    </label>
                    <div id="mapa" class="w-full h-[300px] lg:h-[520px] rounded shadow-lg"></div>
                </div>

                <div class="lg:col-span-1 w-full shadow-md sm:rounded-lg p-6 bg-white">

                    <p class="mb-4 text-gray-700 text-sm">
                        Código de Cliente:
                        <span class="font-semibold text-cyan-600">
                            {{ $cliente->codigo ?? 'Se generará al guardar' }}
                        </span>
                    </p>

                    <div class="mb-4">
                        <label class="font-semibold text-sm">Pegar enlace Google Maps</label>
                        <input type="text" id="linkMapa" placeholder="https://maps.app.goo.gl/..." class="input-minimal">
                        <button type="button" id="btnProcesarLink" class="mt-2 w-full btn-cyan">
                            Cargar coordenadas
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <input type="text" id="latitud" name="latitud"
                            value="{{ old('latitud', $cliente->latitud ?? '') }}"
                            placeholder="Latitud" class="input-minimal">
                        <input type="text" id="longitud" name="longitud"
                            value="{{ old('longitud', $cliente->longitud ?? '') }}"
                            placeholder="Longitud" class="input-minimal">
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold text-sm">Nombre (Requerido)</label>
                        <input type="text" id="nombre" name="nombre"
                            value="{{ old('nombre', $cliente->nombre ?? '') }}"
                            class="input-minimal" required>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold text-sm">Asignar Personal</label>
                        <select name="personal_id" class="input-minimal">
                            <option value="">— Seleccione un personal —</option>
                            @foreach ($personales as $personal)
                            <option value="{{ $personal->id }}"
                                {{ old('personal_id', $cliente->personal_id ?? '') == $personal->id ? 'selected' : '' }}>
                                {{ $personal->nombres }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2 mb-6">
                        <input type="checkbox" id="fijar_personal" name="fijar_personal" value="1"
                            {{ old('fijar_personal', $cliente->fijar_personal ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-cyan-600">
                        <label for="fijar_personal" class="text-sm font-semibold">
                            Fijar personal asignado
                        </label>
                    </div>

                    <div class="mb-6 text-center">
                        <label class="font-semibold text-sm block mb-2">Categoría del cliente</label>
                        <div class="flex gap-2 justify-center">
                            <input type="radio" id="categoria1" name="categoria" value="1"
                                class="hidden peer/c1"
                                {{ old('categoria', $cliente->categoria ?? 1) == 1 ? 'checked' : '' }}>
                            <label for="categoria1"
                                class="px-4 py-2 text-sm rounded-lg border cursor-pointer
                                   peer-checked/c1:bg-cyan-600 peer-checked/c1:text-white">
                                Cliente Nuevo
                            </label>

                            <input type="radio" id="categoria2" name="categoria" value="2"
                                class="hidden peer/c2"
                                {{ old('categoria', $cliente->categoria ?? 1) == 2 ? 'checked' : '' }}>
                            <label for="categoria2"
                                class="px-4 py-2 text-sm rounded-lg border cursor-pointer
                                   peer-checked/c2:bg-cyan-600 peer-checked/c2:text-white">
                                Cliente Regular
                            </label>

                            <input type="radio" id="categoria3" name="categoria" value="3"
                                class="hidden peer/c3"
                                {{ old('categoria', $cliente->categoria ?? 1) == 3 ? 'checked' : '' }}>
                            <label for="categoria3"
                                class="px-4 py-2 text-sm rounded-lg border cursor-pointer
                                   peer-checked/c3:bg-cyan-600 peer-checked/c3:text-white">
                                Cliente Antiguo
                            </label>
                        </div>
                    </div>

                    <div class="mb-6 text-center">
                        <label class="font-semibold text-sm block mb-2">Estado del Cliente</label>
                        <div class="inline-flex gap-3">
                            <input type="radio" id="estado1" name="estado" value="1"
                                class="hidden peer/e1"
                                {{ old('estado', $cliente->estado ?? 1) == 1 ? 'checked' : '' }}>
                            <label for="estado1"
                                class="px-5 py-2 text-sm rounded-full border cursor-pointer
                                   peer-checked/e1:bg-green-600 peer-checked/e1:text-white">
                                Activo
                            </label>

                            <input type="radio" id="estado0" name="estado" value="0"
                                class="hidden peer/e0"
                                {{ old('estado', $cliente->estado ?? 1) == 0 ? 'checked' : '' }}>
                            <label for="estado0"
                                class="px-5 py-2 text-sm rounded-full border cursor-pointer
                                   peer-checked/e0:bg-red-500 peer-checked/e0:text-white">
                                Inactivo
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-center mt-6">
                        <button type="submit" class="btn-cyan">
                            Guardar
                        </button>
                    </div>
                </div>
            </div>


        </form>
    </div>

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

            // Función para colocar marcador
            function colocarMarcador(lat, lng) {
                if (marcadorSeleccionado) map.removeLayer(marcadorSeleccionado);

                marcadorSeleccionado = L.marker([lat, lng])
                    .addTo(map)
                    .bindPopup(`
                <div class="text-sm">
                    <strong>Coordenadas:</strong><br>
                    Latitud: ${lat.toFixed(6)}<br>
                    Longitud: ${lng.toFixed(6)}<br>
                    <button id="btnCopiar" class="mt-2 bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">
                        Copiar coordenadas
                    </button>
                </div>
            `).openPopup();

                document.getElementById('latitud').value = lat.toFixed(6);
                document.getElementById('longitud').value = lng.toFixed(6);

                setTimeout(() => {
                    const btn = document.getElementById('btnCopiar');
                    if (btn) {
                        btn.addEventListener('click', function(ev) {
                            ev.preventDefault();
                            alert(`Coordenadas copiadas:\nLatitud: ${lat.toFixed(6)}, Longitud: ${lng.toFixed(6)}`);
                        });
                    }
                }, 10);
            }

            // Clic en el mapa para marcar
            map.on('click', function(e) {
                colocarMarcador(e.latlng.lat, e.latlng.lng);
            });
            document.getElementById('btnProcesarLink').addEventListener('click', function() {
                const input = document.getElementById('linkMapa').value.trim();
                if (!input) return alert('Ingrese coordenadas o enlace de Google Maps');
                const coordDirectas = input.match(/^(-?\d+(\.\d+)?),\s*(-?\d+(\.\d+)?)$/);
                if (coordDirectas) {
                    const lat = parseFloat(coordDirectas[1]);
                    const lng = parseFloat(coordDirectas[3]);
                    colocarMarcador(lat, lng);
                    map.setView([lat, lng], 16);
                    return;
                }
                alert('Formato de coordenadas no reconocido');
            });

        });
    </script>


</body>

</html>
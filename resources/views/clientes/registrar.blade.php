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
                class="btn-circle btn-cyan">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 12h6m3 0h1.5m3 0h.5" />
                    <path d="M5 12l6 6" />
                    <path d="M5 12l6 -6" />
                </svg>
            </a>
        </div>

        <div>
            <form id="clienteForm" action="{{ route('clientes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="order-1 lg:order-1 w-full shadow-md sm:rounded-lg p-6 bg-white">
                        <div id="mapa" class="w-full h-[200px] lg:h-[400px] rounded shadow-lg mb-4"></div>

                        <div class="mb-4">
                            <input type="text" id="latitud" name="latitud"
                                value="{{ old('latitud', $cliente->latitud ?? '') }}"
                                placeholder="Latitud"
                                class="input-minimal">
                            @error('latitud') <span class="error-message">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <input type="text" id="longitud" name="longitud"
                                value="{{ old('longitud', $cliente->longitud ?? '') }}"
                                placeholder="Longitud"
                                class="input-minimal">
                            @error('longitud') <span class="error-message">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="order-2 lg:order-2 w-full grid grid-cols-1 lg:grid-cols-1 gap-6">

                        <div class="shadow-md sm:rounded-lg p-6 bg-white">
                            <p class="mb-4 text-gray-700">
                                Código de Cliente:
                                <span class="font-semibold text-cyan-600">
                                    {{ $cliente->codigo ?? 'Se generará al guardar' }}
                                </span>
                            </p>

                            <div class="mb-4">
                                <input type="text" id="nombre" name="nombre"
                                    value="{{ old('nombre', $cliente->nombre ?? '') }}"
                                    placeholder="Nombre"
                                    class="input-minimal">
                                @error('nombre') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <input type="text" id="empresa" name="empresa"
                                    value="{{ old('empresa', $cliente->empresa ?? '') }}"
                                    placeholder="Empresa"
                                    class="input-minimal">
                                @error('empresa') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <input type="text" id="razonSocial" name="razonSocial"
                                    value="{{ old('razonSocial', $cliente->razonSocial ?? '') }}"
                                    placeholder="Razón Social"
                                    class="input-minimal">
                                @error('razonSocial') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <input type="text" id="nitCi" name="nitCi"
                                    value="{{ old('nitCi', $cliente->nitCi ?? '') }}"
                                    placeholder="NIT/CI"
                                    class="input-minimal">
                                @error('nitCi') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <input type="text" id="telefono" name="telefono"
                                    value="{{ old('telefono', $cliente->telefono ?? '') }}"
                                    placeholder="Teléfono"
                                    class="input-minimal">
                                @error('telefono') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <input type="email" id="correo" name="correo"
                                    value="{{ old('correo', $cliente->correo ?? '') }}"
                                    placeholder="Correo Empresa"
                                    class="input-minimal">
                                @error('correo') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <input type="email" id="email" name="email"
                                    value="{{ old('email') }}"
                                    placeholder="Email de usuario"
                                    class="input-minimal"
                                    required>
                                @error('email') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <input type="password" id="password" name="password"
                                    placeholder="Contraseña"
                                    class="input-minimal"
                                    required>
                                @error('password') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <select id="categoria" name="categoria" class="input-minimal">
                                    <option value="1" {{ old('categoria', $cliente->categoria ?? 1) == 1 ? 'selected' : '' }}>Cliente Nuevo</option>
                                    <option value="2" {{ old('categoria', $cliente->categoria ?? 1) == 2 ? 'selected' : '' }}>Cliente Regular</option>
                                    <option value="3" {{ old('categoria', $cliente->categoria ?? 1) == 3 ? 'selected' : '' }}>Cliente VIP</option>
                                </select>
                                @error('categoria') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <select id="estado" name="estado" class="input-minimal">
                                    <option value="0" {{ old('estado', $cliente->estado ?? 0) == 0 ? 'selected' : '' }}>Inactivo</option>
                                    <option value="1" {{ old('estado', $cliente->estado ?? 1) == 1 ? 'selected' : '' }}>Activo</option>

                                </select>
                                @error('estado') <span class="error-message">{{ $message }}</span> @enderror
                            </div>
                        </div>

                    </div>

                </div>
                <div class="flex justify-center mt-6">
                    <button type="submit"
                        class="btn-circle btn-cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M14 4l0 4l-6 0l0 -4" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
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

            // Click para colocar marcador y actualizar campos
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
                    <button id="btnCopiar" class="mt-2 bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">
                        Copiar coordenadas
                    </button>
                </div>
            `)
                    .openPopup();

                // Insertar coordenadas en los campos automáticamente
                document.getElementById('latitud').value = lat.toFixed(6);
                document.getElementById('longitud').value = lng.toFixed(6);

                // Agregar listener al botón Copiar del popup
                setTimeout(() => {
                    const btn = document.getElementById('btnCopiar');
                    if (btn) {
                        btn.addEventListener('click', function(ev) {
                            ev.preventDefault(); // Evita recargar
                            alert(`Coordenadas copiadas:\nLatitud: ${lat.toFixed(6)}, Longitud: ${lng.toFixed(6)}`);
                        });
                    }
                }, 10); // Pequeño delay para que el DOM del popup se renderice
            });
        });
    </script>

</body>

</html>
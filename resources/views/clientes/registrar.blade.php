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
            <h1 class="text-u">Crear Nuevos Clientes</h1>
            <a href="{{ route('home') }}"
                class="btn-cyan" title="Cerrar">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M10 10l4 4m0 -4l-4 4" />
                    <circle cx="12" cy="12" r="9" />
                </svg>
                CERRAR
            </a>
        </div>

        <div>
            <form id="clienteForm" action="{{ route('clientes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="order-1 lg:order-1 w-full shadow-md sm:rounded-lg p-6 bg-white">
                        <label class="font-semibold text-sm">Haga click en el mapa para asignar una direccion </label>
                        <div id="mapa" class="w-full h-[200px] lg:h-[400px] rounded shadow-lg mb-4"></div>
                        <label class="font-semibold text-sm">longitud y latitud (Automatico) </label>
                        <div class="mb-4">
                            <input type="text" id="latitud" name="latitud"
                                value="{{ old('latitud', $cliente->latitud ?? '') }}"
                                placeholder="Latitud"
                                class="input-minimal">
                        </div>
                        <div class="mb-4">
                            <input type="text" id="longitud" name="longitud"
                                value="{{ old('longitud', $cliente->longitud ?? '') }}"
                                placeholder="Longitud"
                                class="input-minimal">
                        </div>
                    </div>
                    <div class="order-1 lg:order-1 w-full shadow-md sm:rounded-lg p-6 bg-white">


                        <p class="mb-4 text-gray-700">
                            Código de Cliente:
                            <span class="font-semibold text-cyan-600">
                                {{ $cliente->codigo ?? 'Se generará al guardar' }}
                            </span>
                        </p>

                        <div class="mb-4">
                            <label class="font-semibold text-sm">Nombre (Requerido)</label>
                            <input type="text" id="nombre" name="nombre"
                                value="{{ old('nombre', $cliente->nombre ?? '') }}"
                                placeholder="Nombre"
                                class="input-minimal">
                        </div>

                        <div class="mb-4">
                            <label class="font-semibold text-sm">Nombre empresa (Opcional)</label>
                            <input type="text" id="empresa" name="empresa"
                                value="{{ old('empresa', $cliente->empresa ?? '') }}"
                                placeholder="Empresa"
                                class="input-minimal">

                        </div>

                        <div class="mb-4">
                            <label class="font-semibold text-sm">Razon Social (Opcional)</label>
                            <input type="text" id="razonSocial" name="razonSocial"
                                value="{{ old('razonSocial', $cliente->razonSocial ?? '') }}"
                                placeholder="Razón Social"
                                class="input-minimal">

                        </div>

                        <div class="mb-4">
                            <label class="font-semibold text-sm">NIT (Opcional)</label>
                            <input type="text" id="nitCi" name="nitCi"
                                value="{{ old('nitCi', $cliente->nitCi ?? '') }}"
                                placeholder="NIT/CI"
                                class="input-minimal">

                        </div>
                        <div class="mb-4">
                            <label class="font-semibold text-sm">Telefono (Opcional)</label>
                            <input type="text" id="telefono" name="telefono"
                                value="{{ old('telefono', $cliente->telefono ?? '') }}"
                                placeholder="Teléfono"
                                class="input-minimal">

                        </div>

                        <div class="mb-4">
                            <label class="font-semibold text-sm">Celular (Opcional)</label>
                            <input type="text" id="celular" name="celular"
                                value="{{ old('celular', $cliente->celular ?? '') }}"
                                placeholder="Celular"
                                class="input-minimal">
                        </div>
                        <div class="mb-4">
                            <label class="font-semibold text-sm">Direccion,Domcicilio (Opcional)</label>
                            <input type="text" id="direccion" name="direccion"
                                value="{{ old('direccion', $cliente->direccion ?? '') }}"
                                placeholder="Dirección"
                                class="input-minimal">
                        </div>

                        <div class="mb-4">
                            <label class="font-semibold text-sm">Nombre empresa (Opcional)</label>
                            <input type="text" id="ubicacion" name="ubicacion"
                                value="{{ old('ubicacion', $cliente->ubicacion ?? '') }}"
                                placeholder="Referencia / Ubicación"
                                class="input-minimal">

                        </div>

                        <div class="mb-4">
                            <label class="font-semibold text-sm">Nombre empresa (Opcional)</label>
                            <input type="text" id="departamento_localidad" name="departamento_localidad"
                                value="{{ old('departamento_localidad', $cliente->departamento_localidad ?? '') }}"
                                placeholder="Departamento / Localidad"
                                class="input-minimal">

                        </div>

                        <div class="mb-4">
                            <label class="font-semibold text-sm">Establecimiento (Opcional)</label>
                            <input type="text" id="establecimiento" name="establecimiento"
                                value="{{ old('establecimiento', $cliente->establecimiento ?? '') }}"
                                placeholder="Casa. empresa ,edificio"
                                class="input-minimal">
                        </div>
                    </div>
                    <div class="order-1 lg:order-1 w-full shadow-md sm:rounded-lg p-6 bg-white">

                        <div class="mb-4">
                            <label class="font-semibold text-sm">Disponibilidad del cliente (Opcional)</label>
                            <input type="text" id="disponible" name="disponible"
                                value="{{ old('disponible', $cliente->disponible ?? '') }}"
                                placeholder="Disponibilidad"
                                class="input-minimal">
                        </div>
                        <div class="mb-4">
                            <label class="font-semibold text-sm">Movil de Entrega (Opcional)</label>
                            <input type="text" id="movil" name="movil"
                                value="{{ old('movil', $cliente->movil ?? '') }}"
                                placeholder="Móvil asignado"
                                class="input-minimal">
                        </div>
                        <div class="mb-4">
                            <label class="font-semibold text-sm">Dias de atencion (Opcional)</label>
                            <input type="text" id="dias" name="dias"
                                value="{{ old('dias', $cliente->dias ?? '') }}"
                                placeholder="Días de visita / atención"
                                class="input-minimal">
                        </div>

                        <div class="mb-4">
                            <label class="font-semibold text-sm">Bot (Opcional)</label>
                            <input type="text" id="bot" name="bot"
                                value="{{ old('bot', $cliente->bot ?? '') }}"
                                placeholder="Bot / Fuente (opcional)"
                                class="input-minimal">
                        </div>
                        <div class="mb-4">
                            <label class="font-semibold text-sm">Correo de Ingreso (Requerido)</label>
                            <input type="email" id="email" name="email"
                                value="{{ old('email') }}"
                                placeholder="Email de usuario"
                                class="input-minimal"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="font-semibold text-sm">Contraseña (Requerido)</label>
                            <input type="password" id="password" name="password"
                                placeholder="Contraseña"
                                class="input-minimal"
                                required>

                        </div>

                        <div class="mb-6 text-center">
                            <label class="font-semibold text-sm block mb-2">Categoría del cliente (Automático)</label>
                            <div class="flex gap-2">
                                <input type="radio" id="categoria1" name="categoria" value="1"
                                    class="hidden peer/cat1"
                                    {{ old('categoria', $cliente->categoria ?? 1) == 1 ? 'checked' : '' }}>
                                <label for="categoria1"
                                    class="px-4 py-2 text-sm rounded-lg border border-gray-300 cursor-pointer transition-all duration-200
                   peer-checked/cat1:bg-cyan-600 peer-checked/cat1:text-white
                   hover:bg-cyan-100 hover:border-cyan-500">
                                    Cliente Nuevo
                                </label>

                                <input type="radio" id="categoria2" name="categoria" value="2"
                                    class="hidden peer/cat2"
                                    {{ old('categoria', $cliente->categoria ?? 1) == 2 ? 'checked' : '' }}>
                                <label for="categoria2"
                                    class="px-4 py-2 text-sm rounded-lg border border-gray-300 cursor-pointer transition-all duration-200
                   peer-checked/cat2:bg-cyan-600 peer-checked/cat2:text-white
                   hover:bg-cyan-100 hover:border-cyan-500">
                                    Cliente Regular
                                </label>

                                <input type="radio" id="categoria3" name="categoria" value="3"
                                    class="hidden peer/cat3"
                                    {{ old('categoria', $cliente->categoria ?? 1) == 3 ? 'checked' : '' }}>
                                <label for="categoria3"
                                    class="px-4 py-2 text-sm rounded-lg border border-gray-300 cursor-pointer transition-all duration-200
                   peer-checked/cat3:bg-cyan-600 peer-checked/cat3:text-white
                   hover:bg-cyan-100 hover:border-cyan-500">
                                    Cliente antiguo

                                </label>
                            </div>
                        </div>


                        <div class="mb-6 text-center">
                            <label class="font-semibold text-sm block mb-3">Estado del Cliente (Automático)</label>
                            <div class="inline-flex gap-3 justify-center">
                                <input type="radio" id="estado0" name="estado" value="0"
                                    class="hidden peer/inactivo"
                                    {{ old('estado', $cliente->estado ?? 1) == 0 ? 'checked' : '' }}>
                                <label for="estado0"
                                    class="px-5 py-2.5 text-sm rounded-full border border-gray-300 cursor-pointer transition-all duration-200
                   peer-checked/inactivo:bg-red-500 peer-checked/inactivo:text-white
                   hover:bg-red-100 hover:border-red-500">
                                    Inactivo
                                </label>

                                <input type="radio" id="estado1" name="estado" value="1"
                                    class="hidden peer/activo"
                                    {{ old('estado', $cliente->estado ?? 1) == 1 ? 'checked' : '' }}>
                                <label for="estado1"
                                    class="px-5 py-2.5 text-sm rounded-full border border-gray-300 cursor-pointer transition-all duration-200
                   peer-checked/activo:bg-green-600 peer-checked/activo:text-white
                   hover:bg-green-100 hover:border-green-500">
                                    Activo
                                </label>
                            </div>
                        </div>



                    </div>
                </div>

                <div class="flex justify-center mt-6">
                    <button type="submit"
                        class="btn-cyan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M14 4l0 4l-6 0l0 -4" />
                        </svg>
                        Guardar
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
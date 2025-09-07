<div class="flex justify-center items-center min-h-screen">
    <!-- Botón para abrir el modal -->
    <button wire:click="abrirModal" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
        Abrir Modal
    </button>

    <!-- Modal -->
    @if ($modal)
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="bg-gray-900/75 absolute inset-0" wire:click="cerrarModal"></div>

            <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg z-50">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Buscar y Guardar Dirección</h2>

                <!-- Campo para buscar dirección -->
                <input type="text" wire:model="direccion" wire:change="buscarDireccion"
                    class="w-full px-4 py-2 border rounded-lg mb-4"
                    placeholder="Ingrese una dirección">

                <!-- Mapa -->
                <div id="mapa" class="w-full h-64 bg-gray-200 rounded-lg"></div>

                <!-- Mostrar la dirección ingresada -->
                <p class="text-gray-600 mt-2">Dirección: <strong>{{ $direccion }}</strong></p>

                <div class="mt-4 flex justify-between">
                    <button wire:click="guardarDireccion" class="px-4 py-2 bg-green-600 text-white rounded-lg">
                        Guardar Dirección
                    </button>
                    <button wire:click="cerrarModal" class="px-4 py-2 bg-red-600 text-white rounded-lg">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Scripts para el mapa -->
<script>
    // Declaramos initMap en el ámbito global para que Google Maps pueda invocarla.
    window.initMap = function () {
        let mapaElement = document.getElementById('mapa');
        if (!mapaElement) return; // Si no existe el contenedor, se detiene la función.
        let map = new google.maps.Map(mapaElement, {
            center: { lat: -34.603722, lng: -58.381592 }, // Centro por defecto (Buenos Aires)
            zoom: 12
        });
        let marker = new google.maps.Marker({
            position: { lat: -34.603722, lng: -58.381592 },
            map: map
        });
        // Guardamos la referencia del mapa y el marcador en el objeto global.
        window.map = map;
        window.marker = marker;
    };

    // Evento para actualizar la posición del mapa según la dirección ingresada.
    window.addEventListener('buscarDireccion', event => {
        const direccion = event.detail.direccion;
        let geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'address': direccion }, function (results, status) {
            if (status === 'OK') {
                const location = results[0].geometry.location;
                if(window.map && window.marker) {
                    window.map.setCenter(location);
                    window.marker.setPosition(location);
                }
            } else {
                alert('No se encontró la dirección: ' + status);
            }
        });
    });

    // Cada vez que Livewire actualiza el DOM, se revisa si existe el contenedor del mapa.
    // Si está presente (por ejemplo, al abrir el modal), se llama a initMap().
    document.addEventListener('livewire:load', function () {
        Livewire.hook('message.processed', (message, component) => {
            if (document.getElementById('mapa')) {
                // Se agrega un pequeño retraso para asegurarse de que el modal se renderice completamente.
                setTimeout(() => {
                    initMap();
                }, 300);
            }
        });
    });
</script>

<!-- Cargar la API de Google Maps (reemplaza TU_API_KEY por tu clave real) -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=TU_API_KEY&callback=initMap"></script>
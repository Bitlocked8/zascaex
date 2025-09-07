<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ config('app.name', 'Laravel') }}</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <!-- ========== All CSS files linkup ========= -->
  <!-- <link rel="stylesheet" href="{{ asset('css/lineicons.css') }}"/>
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" /> -->
  @vite(['resources/css/app.css'])
  @livewireStyles()
</head>

<body>
  @livewire('base')
  <!-- <div id="mapa" class="w-99 h-80 rounded-lg shadow-md"></div> -->
  <!-- ========= All Javascript files linkup ======== -->
  @vite('resources/js/app.js')
  <!-- <script src="{{ asset('js/main.js') }}"></script> -->
  @livewireScripts()


  {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    const menuToggleButton = document.getElementById("menu-toggle");
    const menu = document.getElementById("menu");
    const mainContent = document.getElementById("main-content");

    // Función para alternar la visibilidad del menú
    menuToggleButton.addEventListener("click", function () {
        let header = document.querySelector("header");

        // Ajustar la posición del menú en relación con el header
        let headerHeight = header ? header.offsetHeight : 0;
        menu.style.top = `${headerHeight + 6}px`;

        // Alternar visibilidad y efectos de animación
        if (menu.classList.contains("hidden")) {
            menu.classList.remove("hidden", "opacity-0");
            menu.classList.add("opacity-100", "backdrop-blur-md", "z-20"); // Aplicar desenfoque y mostrar el menú

            // Restablecer el scroll del contenido al principio
            mainContent.scrollTop = 0;
        } else {
            menu.classList.add("opacity-0");
            menu.classList.remove("backdrop-blur-md", "z-20"); // Eliminar desenfoque y z-index cuando se oculta
            setTimeout(() => menu.classList.add("hidden"), 50);
        }
    });

    // También puedes agregar un evento para cerrar el menú si se hace clic fuera de él
    document.addEventListener("click", function (event) {
        if (!menu.contains(event.target) && !menuToggleButton.contains(event.target)) {
            menu.classList.add("opacity-0");
            menu.classList.remove("backdrop-blur-md", "z-20"); // Eliminar desenfoque y z-index cuando se oculta
            setTimeout(() => menu.classList.add("hidden"), 300);
        }
    });
  </script>
</body>

</html>
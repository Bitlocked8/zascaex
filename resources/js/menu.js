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
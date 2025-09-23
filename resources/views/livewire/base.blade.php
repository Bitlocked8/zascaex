<div>
    <header
        class="bg-white bg-opacity-95 backdrop-blur-md rounded-full px-6 py-1 shadow-lg w-[95%] max-w-[1700px] mx-auto transition-all duration-50  fixed top-[5px] left-0 right-0 z-10">
        <div class="flex justify-between items-center">
            <!-- Menú Toggle -->
            <button id="menu-toggle"
                class="text-cyan-600 transition-transform duration-200 ease-in-out hover:scale-110 focus:outline-none rounded-full p-1 md:p-2"
                title="Menú">
                <svg class="w-6 h-6 md:w-5 md:h-5" viewBox="0 0 24 24" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
            <div class="flex justify-center items-center flex-grow space-x-2">
                <h5 class="text-cyan-600">VERZASCA</h5>

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                    class="text-cyan-600">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M13 1a2 2 0 0 1 1.995 1.85l.005 .15v.5c0 1.317 .381 2.604 1.094 3.705l.17 .25l.05 .072a9.093 9.093 0 0 1 1.68 4.92l.006 .354v6.199a3 3 0 0 1 -2.824 2.995l-.176 .005h-6a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-6.2a9.1 9.1 0 0 1 1.486 -4.982l.2 -.292l.05 -.069a6.823 6.823 0 0 0 1.264 -3.957v-.5a2 2 0 0 1 1.85 -1.995l.15 -.005h2zm.362 5h-2.724a8.827 8.827 0 0 1 -1.08 2.334l-.194 .284l-.05 .069a7.091 7.091 0 0 0 -1.307 3.798l-.003 .125a3.33 3.33 0 0 1 1.975 -.61a3.4 3.4 0 0 1 2.833 1.417c.27 .375 .706 .593 1.209 .583a1.4 1.4 0 0 0 1.166 -.583a3.4 3.4 0 0 1 .81 -.8l.003 .183c0 -1.37 -.396 -2.707 -1.137 -3.852l-.228 -.332a8.827 8.827 0 0 1 -1.273 -2.616z" />
                </svg>

                <small class="text-cyan-600">
                    {{ $roles[Auth::user()->rol_id] ?? 'Sin rol' }}: <br>
                    {{ Auth::user()->personal?->nombres 
            ?? Auth::user()->cliente?->nombre 
            ?? 'Sin nombre disponible' }}
                </small>
            </div>


            <!-- Botón de Logout -->
            <button
                class="text-cyan-600 transition-transform duration-200 ease-in-out hover:scale-110 focus:outline-none rounded-full p-1 md:p-2"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                title="Cerrar sesión">
                <svg class="w-6 h-6 md:w-5 md:h-5" viewBox="0 0 512 512" fill="currentColor">
                    <path
                        d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z">
                    </path>
                </svg>
            </button>

            <!-- Formulario de logout oculto -->
            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                @csrf
            </form>
        </div>
    </header>
    <nav id="menu" class="w-[95%] max-w-[1700px] text-cyan-600 bg-white px-6 py-4 shadow-lg fixed left-1/2 -translate-x-1/2 top-[65px] hidden transition-all rounded-xl backdrop-blur-md z-20 ">
        <div class="max-h-[80vh] overflow-y-auto ">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-4">

                <!-- Gestión de Usuarios -->
                @if(in_array(auth()->user()->rol_id, [1, 2]))
                <div>
                    <h3 class="text-cyan-600 font-semibold mb-2  text-center">GESTIÓN DE USUARIOS</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3  gap-4">

                        <!-- PERSONAL -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Personal')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                         transition-colors
                                 ($seleccion == 'Compras')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Personal</span>
                        </button>

                        <!-- ROLES -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Roles')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                 transition-colors
                                 ($seleccion == 'Compras')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h2.5" />
                                <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M19.001 15.5v1.5" />
                                <path d="M19.001 21v1.5" />
                                <path d="M22.032 17.25l-1.299 .75" />
                                <path d="M17.27 20l-1.3 .75" />
                                <path d="M15.97 17.25l1.3 .75" />
                                <path d="M20.733 20l1.3 .75" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Roles</span>
                        </button>

                        <!-- PROMOS -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Promos')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                 transition-colors
                                 ($seleccion == 'Compras')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12.705 19.765l-3.705 1.235v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v.5" />
                                <path d="M16 21l5 -5" />
                                <path d="M21 21v.01" />
                                <path d="M16 16v.01" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Promos</span>
                        </button>

                        <!-- PROMOCIONES -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Promociones')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                 transition-colors
                                 ($seleccion == 'Compras')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 17h-11v-14h-2" />
                                <path d="M6 5l14 1l-1 7h-13" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Promociones</span>
                        </button>

                    </div>
                </div>
                @endif


                @if(in_array(auth()->user()->rol_id, [1, 2]))
                <div>
                    <h3 class="text-cyan-600 font-semibold mb-2  text-center">GESTIÓN DE COMPRAS</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-4">

                        <!-- COMPRAS -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Compras')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                 transition-colors
                             ($seleccion == 'Compras')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 17h-11v-14h-2" />
                                <path d="M6 5l14 1l-1 7h-13" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate  w-full">
                                Compras
                            </span>

                        </button>

                        <!-- PROVEEDORES -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Proveedores')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                 transition-colors
                             ($seleccion == 'Proveedores')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 21h18" />
                                <path d="M19 21v-4" />
                                <path d="M19 17a2 2 0 0 0 2 -2v-2a2 2 0 1 0 -4 0v2a2 2 0 0 0 2 2z" />
                                <path d="M14 21v-14a3 3 0 0 0 -3 -3h-4a3 3 0 0 0 -3 3v14" />
                                <path d="M8 13h2" />
                                <path d="M8 9h2" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate  w-full">Proveedores</span>
                        </button>

                    </div>
                </div>
                @endif

                @if(in_array(auth()->user()->rol_id, [1, 2]))
                <div>
                    <h3 class="text-cyan-600 font-semibold mb-2  text-center">ALMACÉN</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-4">

                        <!-- STOCK -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Stocks')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                 transition-colors
                                 ($seleccion == 'Stocks')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="currentColor" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M20.894 15.553a1 1 0 0 1 -.447 1.341l-8 4a1 1 0 0 1 -.894 0l-8 -4a1 1 0 0 1 .894 -1.788l7.553 3.774l7.554 -3.775a1 1 0 0 1 1.341 .447m0 -4a1 1 0 0 1 -.447 1.341l-8 4a1 1 0 0 1 -.894 0l-8 -4a1 1 0 0 1 .894 -1.788l7.552 3.775l7.554 -3.775a1 1 0 0 1 1.341 .447m-8.887 -8.552q .056 0 .111 .007l.111 .02l.086 .024l.012 .006l.012 .002l.029 .014l.05 .019l.016 .009l.012 .005l8 4a1 1 0 0 1 0 1.788l-8 4a1 1 0 0 1 -.894 0l-8 -4a1 1 0 0 1 0 -1.788l8 -4l.011 -.005l.018 -.01l.078 -.032l.011 -.002l.013 -.006l.086 -.024l.11 -.02l.056 -.005z" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate  w-full">Stock</span>
                        </button>

                        <!-- TAPAS -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Tapas')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                 transition-colors
                                 ($seleccion == 'Tapas')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 28"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M4 16c5.713 -2.973 11 -3.5 13.449 -11.162" />
                                <path d="M5 17.5c5.118 -2.859 15 0 14 -11" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate  w-full">Tapas</span>
                        </button>

                        <!-- ETIQUETAS -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Etiquetas')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                 transition-colors
                                 ($seleccion == 'Etiquetas')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M15 5l0 2" />
                                <path d="M15 11l0 2" />
                                <path d="M15 17l0 2" />
                                <path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate  w-full">Etiquetas</span>
                        </button>

                        <!-- PRODUCTOS -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Productos')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                 transition-colors
                                 ($seleccion == 'Productos')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 21l18 0" />
                                <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4" />
                                <path d="M5 21l0 -10.15" />
                                <path d="M19 21l0 -10.15" />
                                <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate  w-full">Productos</span>
                        </button>

                        <!-- BASES -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Bases')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                 transition-colors
                                 ($seleccion == 'Bases')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 21h8a1 1 0 0 0 1 -1v-10a3 3 0 0 0 -3 -3h-4a3 3 0 0 0 -3 3v10a1 1 0 0 0 1 1z" />
                                <path d="M10 14h4" />
                                <path d="M12 12v4" />
                                <path d="M10 7v-3a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v3" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate  w-full">Bases</span>
                        </button>

                        <!-- PREFORMAS -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Preformas')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                 transition-colors
                                 ($seleccion == 'Preformas')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M15 15.5l-11.476 -6.216a1 1 0 0 1 -.524 -.88v-4.054a1.35 1.35 0 0 1 2.03 -1.166l9.97 5.816v10.65a1.35 1.35 0 0 1 -2.03 1.166l-3.474 -2.027a1 1 0 0 1 -.496 -.863v-11.926" />
                                <path d="M15 15.5l5.504 -3.21a1 1 0 0 0 .496 -.864v-3.576a1.35 1.35 0 0 0 -2.03 -1.166l-3.97 2.316" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate  w-full">Preformas</span>
                        </button>

                    </div>
                </div>
                @endif
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-4">
                @if(in_array(auth()->user()->rol_id, [1, 2, 4]))
                <div>
                    <h3 class="text-cyan-600 font-semibold mb-2  text-center"> GESTIÓN DE PRODUCCIÓN</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-4">

                        <!-- ELABORACIÓN -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Elaboracion')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                 transition-colors
                             ($seleccion == 'Elaboracion')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-square-rounded-number-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 2l.642 .005l.616 .017l.299 .013l.579 .034l.553 .046c4.687 .455 6.65 2.333 7.166 6.906l.03 .29l.046 .553l.041 .727l.006 .15l.017 .617l.005 .642l-.005 .642l-.017 .616l-.013 .299l-.034 .579l-.046 .553c-.455 4.687 -2.333 6.65 -6.906 7.166l-.29 .03l-.553 .046l-.727 .041l-.15 .006l-.617 .017l-.642 .005l-.642 -.005l-.616 -.017l-.299 -.013l-.579 -.034l-.553 -.046c-4.687 -.455 -6.65 -2.333 -7.166 -6.906l-.03 -.29l-.046 -.553l-.041 -.727l-.006 -.15l-.017 -.617l-.004 -.318v-.648l.004 -.318l.017 -.616l.013 -.299l.034 -.579l.046 -.553c.455 -4.687 2.333 -6.65 6.906 -7.166l.29 -.03l.553 -.046l.727 -.041l.15 -.006l.617 -.017c.21 -.003 .424 -.005 .642 -.005zm.994 5.886c-.083 -.777 -1.008 -1.16 -1.617 -.67l-.084 .077l-2 2l-.083 .094a1 1 0 0 0 0 1.226l.083 .094l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l.293 -.293v5.586l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-8l-.006 -.114z" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate  w-full">Elaboración</span>
                        </button>

                        <!-- EMBOTELLADO -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Embotellado')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                 transition-colors
                             ($seleccion == 'Embotellado')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10.425 1.414a3.33 3.33 0 0 1 3.216 0l6.775 3.995c.067 .04 .127 .084 .18 .133l.008 .007l.107 .076a3.223 3.223 0 0 1 1.284 2.39l.005 .203v7.284c0 1.175 -.643 2.256 -1.623 2.793l-6.804 4.302c-.98 .538 -2.166 .538 -3.2 -.032l-6.695 -4.237a3.226 3.226 0 0 1 -1.678 -2.826v-7.285a3.21 3.21 0 0 1 1.65 -2.808zm2.575 5.586h-3l-.117 .007a1 1 0 0 0 0 1.986l.117 .007h3v2h-2l-.15 .005a2 2 0 0 0 -1.844 1.838l-.006 .157v2l.005 .15a2 2 0 0 0 1.838 1.844l.157 .006h3l.117 -.007a1 1 0 0 0 0 -1.986l-.117 -.007h-3v-2h2l.15 -.005a2 2 0 0 0 1.844 -1.838l.006 -.157v-2l-.005 -.15a2 2 0 0 0 -1.838 -1.844l-.157 -.006z" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate  w-full">Embotellado</span>
                        </button>

                        <!-- ETIQUETADO -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Etiquetado')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                 transition-colors
                             ($seleccion == 'Etiquetado')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M18.333 2c1.96 0 3.56 1.537 3.662 3.472l.005 .195v12.666c0 1.96 -1.537 3.56 -3.472 3.662l-.195 .005h-12.666a3.667 3.667 0 0 1 -3.662 -3.472l-.005 -.195v-12.666c0 -1.96 1.537 -3.56 3.472 -3.662l.195 -.005h12.666zm-5.333 5h-2l-.15 .005a2 2 0 0 0 -1.85 1.995a1 1 0 0 0 1.974 .23l.02 -.113l.006 -.117h2v2h-2l-.133 .007c-1.111 .12 -1.154 1.73 -.128 1.965l.128 .021l.133 .007h2v2h-2l-.007 -.117a1 1 0 0 0 -1.993 .117a2 2 0 0 0 1.85 1.995l.15 .005h2l.15 -.005a2 2 0 0 0 1.844 -1.838l.006 -.157v-2l-.005 -.15a1.988 1.988 0 0 0 -.17 -.667l-.075 -.152l-.019 -.032l.02 -.03a2.01 2.01 0 0 0 .242 -.795l.007 -.174v-2l-.005 -.15a2 2 0 0 0 -1.838 -1.844l-.157 -.006z" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate  w-full">Etiquetado</span>
                        </button>

                        <!-- TRASPASO (solo roles 1,2) -->
                        @if(in_array(auth()->user()->rol_id, [1, 2]))
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Traspaso')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                 transition-colors
                                 ($seleccion == 'Traspaso')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 7h16" />
                                <path d="M4 17h16" />
                                <path d="M10 11l-2 2l2 2" />
                                <path d="M14 11l2 2l-2 2" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate  w-full">Traspaso</span>
                        </button>
                        @endif

                    </div>
                </div>
                @endif

                @if(in_array(auth()->user()->rol_id, [1, 2, 3]))
                <div class="mb-6">
                    <h3 class="text-cyan-600 font-semibold mb-2  text-center">GESTIÓN DE VENTAS</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-4">

                        <!-- CLIENTE (solo roles 1,2) -->
                        @if(in_array(auth()->user()->rol_id, [1, 2]))
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Cliente')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                         transition-colors
                                         ($seleccion == 'Cliente')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Cliente</span>
                        </button>
                        @endif

                        <!-- VENTA (roles 1,2,3) -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Venta')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                             transition-colors
                                             ($seleccion == 'Venta')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                <path d="M12 17v1m0 -8v1" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Venta</span>
                        </button>

                    </div>
                </div>
                @endif

                <!-- Gestión de Distribución -->
                @if(in_array(auth()->user()->rol_id, [1, 2, 3]))
                <div class="mb-6">
                    <h3 class="text-cyan-600 font-semibold mb-2  text-center">GESTIÓN DE DISTRIBUCIÓN</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-4">

                        <!-- DISTRIBUCION (roles 1,2,3) -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Distribucion')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                                 transition-colors
                                             ($seleccion == 'Distribucion')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                <path d="M12.02 21.485a1.996 1.996 0 0 1 -1.433 -.585l-4.244 -4.243a8 8 0 1 1 13.403 -3.651" />
                                <path d="M16 22l5 -5" />
                                <path d="M21 21.5v-4.5h-4.5" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Distribución</span>
                        </button>

                        <!-- PEDIDOS (roles 1,2,3,4) -->
                        @if(in_array(auth()->user()->rol_id, [1, 2, 3, 4]))
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Pedidos')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                             transition-colors
                                                 ($seleccion == 'Pedidos')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 17v1a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-1" />
                                <path d="M8 16h8" />
                                <path d="M8.322 12.582l7.956 .836" />
                                <path d="M8.787 9.168l7.826 1.664" />
                                <path d="M10.096 5.764l7.608 2.472" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Pedidos</span>
                        </button>
                        @endif

                        <!-- ASIGNACION (roles 1,2) -->
                        @if(in_array(auth()->user()->rol_id, [1, 2]))
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Asignacion')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                                 transition-colors
                                                 ($seleccion == 'Asignacion')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M15 4.5l-4 4l-4 1.5l-1.5 1.5l7 7l1.5 -1.5l1.5 -4l4 -4" />
                                <path d="M9 15l-4.5 4.5" />
                                <path d="M14.5 4l5.5 5.5" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Asignación</span>
                        </button>

                        <!-- COCHE (roles 1,2) -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Coche')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                                             transition-colors
                                             ($seleccion == 'Coche')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Coche</span>
                        </button>
                        @endif

                    </div>
                </div>
                @endif

            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-4">
                @if(in_array(auth()->user()->rol_id, [1, 2, 3, 4]))
                <div class="mb-6">
                    <h3 class="text-cyan-600 font-semibold mb-2  text-center">SUCURSALES</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-4">

                        <!-- EMPRESA (roles 1,2,3,4) -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Empresa')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                             transition-colors
                             ($seleccion == 'Empresa')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 4l18 0" />
                                <path d="M4 4v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-10" />
                                <path d="M12 16l0 4" />
                                <path d="M9 20l6 0" />
                                <path d="M8 12l3 -3l2 2l3 -3" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Empresa</span>
                        </button>

                        <!-- SUCURSAL (roles 1,2,3,4) -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Sucursal')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                             transition-colors
                             ($seleccion == 'Sucursal')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 21l18 0" />
                                <path d="M5 21v-14l8 -4v18" />
                                <path d="M19 21v-10l-6 -4" />
                                <path d="M9 9l0 .01" />
                                <path d="M9 12l0 .01" />
                                <path d="M9 15l0 .01" />
                                <path d="M13 9l0 .01" />
                                <path d="M13 12l0 .01" />
                                <path d="M13 15l0 .01" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Sucursal</span>
                        </button>

                        <!-- TRABAJADOR (solo roles 1,2) -->
                        @if(in_array(auth()->user()->rol_id, [1, 2]))
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Trabajador')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                             transition-colors
                             ($seleccion == 'Trabajador')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 17v1a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-1" />
                                <path d="M8 16h8" />
                                <path d="M8.322 12.582l7.956 .836" />
                                <path d="M8.787 9.168l7.826 1.664" />
                                <path d="M10.096 5.764l7.608 2.472" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Trabajador</span>
                        </button>
                        @endif

                    </div>
                </div>
                @endif


                @if(in_array(auth()->user()->rol_id, [1, 2]))
                <div class="mb-6">
                    <h3 class="text-cyan-600 font-semibold mb-2  text-center">GESTIÓN DE TESORERÍA</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-4">

                        <!-- INGRESO -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Ingreso')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                             transition-colors
                             ($seleccion == 'Ingreso')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mb-1" width="28" height="28"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 8V4M12 4L8 8M12 4L16 8" />
                                <path d="M4 12v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1 -1v-7" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Ingreso</span>
                        </button>

                        <!-- CREDITO -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Credito')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                             transition-colors
                             ($seleccion == 'Credito')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mb-1" width="28" height="28"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 7h16M4 11h16M10 15h4" />
                                <rect x="4" y="3" width="16" height="18" rx="2" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Crédito</span>
                        </button>

                        <!-- SALARIO -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Salario')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                             transition-colors
                             ($seleccion == 'Salario')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mb-1" width="28" height="28"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 8v-4" />
                                <path d="M12 16v4" />
                                <path d="M8 12h8" />
                                <circle cx="12" cy="12" r="9" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Salario</span>
                        </button>

                    </div>
                </div>
                @endif


                @if(in_array(auth()->user()->rol_id, [1, 2]))
                <div class="mb-6">
                    <h3 class="text-cyan-600 font-semibold mb-2  text-center">REPORTES</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-4">

                        <!-- REPORTE VENTA -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Reporteventa')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                             transition-colors
                             ($seleccion == 'Reporteventa')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mb-1" width="28" height="28"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 3v18h18" />
                                <path d="M18 14v4h4" />
                                <path d="M7 12l3 3 7-7" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Reporte Venta</span>
                        </button>

                        <!-- REPORTE COMPRA -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Reportecompra')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                             transition-colors
                             ($seleccion == 'Reportecompra')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mb-1" width="28" height="28"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 7h14M5 12h14M5 17h14" />
                                <path d="M3 3h18v18H3z" stroke="none" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Reporte Compra</span>
                        </button>

                        <!-- REPORTE STOCK -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Reportestock')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                             transition-colors
                             ($seleccion == 'Reportestock')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mb-1" width="28" height="28"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16v4H4zM4 12h16v4H4zM4 20h16v-4H4z" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Reporte Stock</span>
                        </button>

                        <!-- REPORTE CREDITOS PENDIENTES -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Reportecredito')"
                            class="flex flex-col items-center justify-center p-2 rounded-xl
                             transition-colors
                             ($seleccion == 'Reportecredito')  bg-white text-cyan-600 ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mb-1" width="28" height="28"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <path d="M3 10h18" />
                                <path d="M7 15h1" />
                                <path d="M10 15h2" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Reporte Créditos</span>
                        </button>

                    </div>
                </div>
                @endif

            </div>

        </div>
    </nav>

    <main class="w-full min-h-screen bg-white" id="main-content">
        <div class="w-full p-2  bg-white">
            @if ($seleccion == 'Compras')
            @livewire('compras')
            @endif
            @if ($seleccion == 'Personal')
            @livewire('personal')
            @endif
            @if ($seleccion == 'Proveedores')
            @livewire('proveedores')
            @endif
            @if ($seleccion == 'Roles')
            @livewire('roles')
            @endif
            @if ($seleccion == 'Elaboracion')
            @livewire('elaboracion')
            @endif
            @if ($seleccion == 'Etiquetas')
            @livewire('Etiquetas')
            @endif
            @if ($seleccion == 'Preformas')
            @livewire('preformas')
            @endif
            @if ($seleccion == 'Tapas')
            @livewire('tapas')
            @endif
            @if ($seleccion == 'Raiz')
            @livewire('raiz')
            @endif
            @if ($seleccion == 'Productos')
            @livewire('productos')
            @endif
            @if ($seleccion == 'Embotellado')
            @livewire('embotellado')
            @endif
            @if ($seleccion == 'Etiquetado')
            @livewire('etiquetado')
            @endif
            @if ($seleccion == 'Traspaso')
            @livewire('traspaso')
            @endif
            @if ($seleccion == 'Stocks')
            @livewire('stocks')
            @endif
            @if ($seleccion == 'Egresoingreso')
            @livewire('egresoingreso')
            @endif
            @if ($seleccion == 'Cliente')
            @livewire('cliente')
            @endif
            @if ($seleccion == 'Venta')
            @livewire('venta')
            @endif
            @if ($seleccion == 'Distribucion')
            @livewire('distribucion')
            @endif
            @if ($seleccion == 'Pedidos')
            @livewire('pedidos')
            @endif
            @if ($seleccion == 'Asignacion')
            @livewire('asignacion')
            @endif
            @if ($seleccion == 'Coche')
            @livewire('coche')
            @endif
            @if ($seleccion == 'Empresa')
            @livewire('empresa')
            @endif
            @if ($seleccion == 'Sucursal')
            @livewire('sucursal')
            @endif
            @if ($seleccion == 'Trabajador')
            @livewire('trabajador')
            @endif
            @if ($seleccion == 'Ingreso')
            @livewire('ingreso')
            @endif
            @if ($seleccion == 'Credito')
            @livewire('credito')
            @endif
            @if ($seleccion == 'Salario')
            @livewire('salario')
            @endif
            @if ($seleccion == 'Reporteventa')
            @livewire('reporteventa')
            @endif
            @if ($seleccion == 'Reportecompra')
            @livewire('reportecompra')
            @endif
            @if ($seleccion == 'Reportestock')
            @livewire('reportestock')
            @endif
            @if ($seleccion == 'Reportecredito')
            @livewire('reportecredito')
            @endif
            @if ($seleccion == 'Pruebaestilo')
            @livewire('pruebaestilo')
            @endif
            @if ($seleccion == 'Bases')
            @livewire('bases')
            @endif
            @if ($seleccion == 'Promos')
            @livewire('promos')
            @endif
            @if ($seleccion == 'Promociones')
            @livewire('promociones')
            @endif



        </div>

    </main>


</div>
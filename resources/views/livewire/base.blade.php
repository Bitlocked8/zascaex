<div>
   <header
    class="bg-gradient-to-r from-cyan-600 via-cyan-500 to-cyan-400 bg-opacity-95 backdrop-blur-md rounded-full px-6 py-2 shadow-xl w-[95%] max-w-[1700px] mx-auto fixed top-3 left-0 right-0 z-20 transition-all duration-300">
    
    <div class="flex justify-between items-center">

        <!-- Botón menú -->
        <button id="menu-toggle"
            class="text-white transition-transform duration-300 hover:scale-110 focus:outline-none rounded-full p-2 md:p-3 shadow-md hover:shadow-lg"
            title="Menú">
            <svg class="w-6 h-6 md:w-6 md:h-6" viewBox="0 0 24 24" fill="currentColor">
                <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>

        <!-- Información central -->
        <div class="flex justify-center items-center flex-grow space-x-3 md:space-x-4">
            <h5 class="text-white text-lg font-bold tracking-wide">VERZASCA</h5>

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                class="text-white">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M13 1a2 2 0 0 1 1.995 1.85l.005 .15v.5c0 1.317 .381 2.604 1.094 3.705l.17 .25l.05 .072a9.093 9.093 0 0 1 1.68 4.92l.006 .354v6.199a3 3 0 0 1 -2.824 2.995l-.176 .005h-6a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-6.2a9.1 9.1 0 0 1 1.486 -4.982l.2 -.292l.05 -.069a6.823 6.823 0 0 0 1.264 -3.957v-.5a2 2 0 0 1 1.85 -1.995l.15 -.005h2zm.362 5h-2.724a8.827 8.827 0 0 1 -1.08 2.334l-.194 .284l-.05 .069a7.091 7.091 0 0 0 -1.307 3.798l-.003 .125a3.33 3.33 0 0 1 1.975 -.61a3.4 3.4 0 0 1 2.833 1.417c.27 .375 .706 .593 1.209 .583a1.4 1.4 0 0 0 1.166 -.583a3.4 3.4 0 0 1 .81 -.8l.003 .183c0 -1.37 -.396 -2.707 -1.137 -3.852l-.228 -.332a8.827 8.827 0 0 1 -1.273 -2.616z" />
            </svg>

            <small class="text-white text-sm md:text-base text-center leading-tight">
                {{ $roles[Auth::user()->rol_id] ?? 'Sin rol' }} <br>
                {{ Auth::user()->personal?->nombres ?? Auth::user()->cliente?->nombre ?? 'Sin nombre' }}
            </small>
        </div>

        <!-- Botón logout -->
        <button
            class="text-white transition-transform duration-300 hover:scale-110 focus:outline-none rounded-full p-2 md:p-3 shadow-md hover:shadow-lg"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Cerrar sesión">
            <svg class="w-6 h-6 md:w-6 md:h-6" viewBox="0 0 512 512" fill="currentColor">
                <path
                    d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z">
                </path>
            </svg>
        </button>

        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>

    </div>
</header>

    <nav id="menu" class="w-[95%] max-w-[1700px] text-cyan-600 bg-white px-6 py-2 mt-4 shadow-lg fixed left-1/2 -translate-x-1/2 top-[65px] hidden transition-all rounded-xl backdrop-blur-md z-20 ">
        <div class="max-h-[80vh] overflow-y-auto ">
            <div class="">
                <div>
                    @if(auth()->user()->rol_id == 1)
                    <h3 class="bg-white/30 border-2 border-cyan-500 text-cyan-600 px-6 py-3 rounded-xl text-center font-semibold uppercase backdrop-blur-sm shadow-md">
                        Administración Personal / Cliente
                    </h3>


                    <div class="flex flex-wrap justify-center gap-4 mt-2">
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Personal')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Personal'
            ? 'border-teal-600 shadow-lg text-teal-700'
            : 'border-gray-200 text-gray-700 hover:border-cyan-600 hover:text-cyan-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Personal' ? 'bg-teal-100 text-teal-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                </svg>
                            </div>

                            <span class="mt-3 text-sm font-semibold text-center">Personal</span>
                        </button>

                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Roles')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Roles'
            ? 'border-teal-600 shadow-lg text-teal-700'
            : 'border-gray-200 text-gray-700 hover:border-cyan-600 hover:text-cyan-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Roles' ? 'bg-teal-100 text-teal-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                                </svg>
                            </div>

                            <span class="mt-3 text-sm font-semibold text-center">Roles</span>
                        </button>


                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Promos')"
                            class="w-24 h-24 flex flex-col items-center justify-center rounded-xl
                                {{   ($seleccion == 'Promos') ? 'bg-teal-600 text-white' : 'bg-cyan-600 text-white hover:bg-teal-600' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12.705 19.765l-3.705 1.235v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v.5" />
                                <path d="M16 21l5 -5" />
                                <path d="M21 21v.01" />
                                <path d="M16 16v.01" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Promos</span>
                        </button>
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Promociones')"
                            class="w-24 h-24 flex flex-col items-center justify-center rounded-xl                     
                                 {{  ($seleccion == 'Promociones') ? 'bg-teal-600 text-white' : 'bg-cyan-600 text-white hover:bg-teal-600' }}">
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
                        <!-- EMPRESA -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Empresa')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Empresa'
            ? 'border-teal-600 shadow-lg text-teal-700'
            : 'border-gray-200 text-gray-700 hover:border-cyan-600 hover:text-cyan-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Empresa' ? 'bg-teal-100 text-teal-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 4l18 0" />
                                    <path d="M4 4v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-10" />
                                    <path d="M12 16l0 4" />
                                    <path d="M9 20l6 0" />
                                    <path d="M8 12l3 -3l2 2l3 -3" />
                                </svg>
                            </div>

                            <span class="mt-3 text-sm font-semibold text-center">Empresa</span>
                        </button>

                        <!-- SUCURSAL -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Sucursal')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Sucursal'
            ? 'border-teal-600 shadow-lg text-teal-700'
            : 'border-gray-200 text-gray-700 hover:border-cyan-600 hover:text-cyan-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Sucursal' ? 'bg-teal-100 text-teal-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                            </div>

                            <span class="mt-3 text-sm font-semibold text-center">Sucursal</span>
                        </button>
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Trabajador')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Trabajador'
            ? 'border-teal-600 shadow-lg text-teal-700'
            : 'border-gray-200 text-gray-700 hover:border-cyan-600 hover:text-cyan-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Trabajador' ? 'bg-teal-100 text-teal-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M14 2a3 3 0 0 1 3 3v1h2a3 3 0 0 1 3 3v9a3 3 0 0 1 -3 3h-14a3 3 0 0 1 -3 -3v-9a3 3 0 0 1 3 -3h2v-1a3 3 0 0 1 3 -3zm0 2h-4a1 1 0 0 0 -1 1v1h6v-1a1 1 0 0 0 -1 -1" />
                                </svg>
                            </div>

                            <span class="mt-3 text-sm font-semibold text-center">Trabajos</span>
                        </button>


                    </div>
                    <br>
                    @endif
                    @if(in_array(auth()->user()->rol_id, [1,2]))
                    <h3 class="bg-white/30 border-2 border-indigo-500 text-indigo-600 px-6 py-3 rounded-xl text-center font-semibold uppercase backdrop-blur-sm shadow-md">

                        administracion almacen
                    </h3>

                    <div class="flex flex-wrap justify-center gap-4 mt-2">
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Proveedores')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Proveedores'
            ? 'border-indigo-600 shadow-lg text-indigo-700'
            : 'border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Proveedores' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 21h18" />
                                    <path d="M19 21v-4" />
                                    <path d="M19 17a2 2 0 0 0 2 -2v-2a2 2 0 1 0 -4 0v2a2 2 0 0 0 2 2z" />
                                    <path d="M14 21v-14a3 3 0 0 0 -3 -3h-4a3 3 0 0 0 -3 3v14" />
                                    <path d="M8 13h2" />
                                    <path d="M8 9h2" />
                                </svg>
                            </div>

                            <span class="mt-3 text-sm font-semibold text-center">Proveedores</span>
                        </button>

                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Stocks')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Stocks'
            ? 'border-indigo-600 shadow-lg text-indigo-700'
            : 'border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Stocks' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 8v-2a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-2" />
                                    <path d="M15 12h-12l3 -3" />
                                    <path d="M6 15l-3 -3" />
                                </svg>
                            </div>

                            <span class="mt-3 text-sm font-semibold text-center">Reposición</span>
                        </button>


                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Asignaciones')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Asignaciones'
            ? 'border-indigo-600 shadow-lg text-indigo-700'
            : 'border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Asignaciones' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                    <path d="M9 12h12l-3 -3" />
                                    <path d="M18 15l3 -3" />
                                </svg>
                            </div>

                            <span class="mt-3 text-sm font-semibold text-center">Distribucion</span>
                        </button>

                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Traspaso')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Traspaso'
            ? 'border-indigo-600 shadow-lg text-indigo-700'
            : 'border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Traspaso' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 7h16" />
                                    <path d="M4 17h16" />
                                    <path d="M10 11l-2 2l2 2" />
                                    <path d="M14 11l2 2l-2 2" />
                                </svg>
                            </div>

                            <span class="mt-3 text-sm font-semibold text-center">Traspaso</span>
                        </button>

                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Reportestock')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Reportestock'
            ? 'border-indigo-600 shadow-lg text-indigo-700'
            : 'border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Reportestock' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697" />
                                    <path d="M18 12v-5a2 2 0 0 0 -2 -2h-2" />
                                    <path d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                    <path d="M8 11h4" />
                                    <path d="M8 15h3" />
                                    <path d="M16.5 17.5m-2.5 0a2.5 2.5 0 1 0 5 0a2.5 2.5 0 1 0 -5 0" />
                                    <path d="M18.5 19.5l2.5 2.5" />
                                </svg>
                            </div>

                            <span class="mt-3 text-sm font-semibold text-center">Re Stock</span>
                        </button>

                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Reportecompra')"
                            class="w-24 h-24 flex flex-col items-center justify-center rounded-xl
                             {{ ($seleccion == 'Reportecompra') ? 'bg-teal-600 text-white' : 'bg-cyan-600 text-white hover:bg-teal-600' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697" />
                                <path d="M18 12v-5a2 2 0 0 0 -2 -2h-2" />
                                <path d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                <path d="M8 11h4" />
                                <path d="M8 15h3" />
                                <path d="M16.5 17.5m-2.5 0a2.5 2.5 0 1 0 5 0a2.5 2.5 0 1 0 -5 0" />
                                <path d="M18.5 19.5l2.5 2.5" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Re Compra</span>
                        </button>
                    </div>
                    <br>
                    <h3 class="bg-white/30 border-2 border-amber-500 text-amber-600 px-6 py-3 rounded-xl text-center font-semibold uppercase backdrop-blur-sm shadow-md">
                        productos
                    </h3>

                    <div class="flex flex-wrap justify-center gap-4 mt-2">
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Tapas')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Tapas'
            ? 'border-amber-500 shadow-lg text-amber-700'
            : 'border-gray-200 text-gray-700 hover:border-amber-500 hover:text-amber-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Tapas' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 28" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M4 16c5.713 -2.973 11 -3.5 13.449 -11.162" />
                                    <path d="M5 17.5c5.118 -2.859 15 0 14 -11" />
                                </svg>
                            </div>
                            <span class="mt-3 text-sm font-semibold text-center">Tapas</span>
                        </button>
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Etiquetas')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Etiquetas'
            ? 'border-amber-500 shadow-lg text-amber-700'
            : 'border-gray-200 text-gray-700 hover:border-amber-500 hover:text-amber-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Etiquetas' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M15 5l0 2" />
                                    <path d="M15 11l0 2" />
                                    <path d="M15 17l0 2" />
                                    <path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2" />
                                </svg>
                            </div>
                            <span class="mt-3 text-sm font-semibold text-center">Etiquetas</span>
                        </button>
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Productos')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Productos'
            ? 'border-amber-500 shadow-lg text-amber-700'
            : 'border-gray-200 text-gray-700 hover:border-amber-500 hover:text-amber-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Productos' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                    <path d="M12 12l8 -4.5" />
                                    <path d="M12 12l0 9" />
                                    <path d="M12 12l-8 -4.5" />
                                </svg>
                            </div>
                            <span class="mt-3 text-sm font-semibold text-center">Productos</span>
                        </button>
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Bases')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Bases'
            ? 'border-amber-500 shadow-lg text-amber-700'
            : 'border-gray-200 text-gray-700 hover:border-amber-500 hover:text-amber-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Bases' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M8 21h8a1 1 0 0 0 1 -1v-10a3 3 0 0 0 -3 -3h-4a3 3 0 0 0 -3 3v10a1 1 0 0 0 1 1z" />
                                    <path d="M10 14h4" />
                                    <path d="M12 12v4" />
                                    <path d="M10 7v-3a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v3" />
                                </svg>
                            </div>
                            <span class="mt-3 text-sm font-semibold text-center">Bases</span>
                        </button>
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Preformas')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Preformas'
            ? 'border-amber-500 shadow-lg text-amber-700'
            : 'border-gray-200 text-gray-700 hover:border-amber-500 hover:text-amber-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Preformas' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M15 15.5l-11.476 -6.216a1 1 0 0 1 -.524 -.88v-4.054a1.35 1.35 0 0 1 2.03 -1.166l9.97 5.816v10.65a1.35 1.35 0 0 1 -2.03 1.166l-3.474 -2.027a1 1 0 0 1 -.496 -.863v-11.926" />
                                    <path d="M15 15.5l5.504 -3.21a1 1 0 0 0 .496 -.864v-3.576a1.35 1.35 0 0 0 -2.03 -1.166l-3.97 2.316" />
                                </svg>
                            </div>
                            <span class="mt-3 text-sm font-semibold text-center">Preformas</span>
                        </button>

                    </div>
                    <br>
                    @endif
                    @if(in_array(auth()->user()->rol_id, [1,4]))
                    <h3 class="bg-white/30 border-2 border-lime-500 text-lime-600 px-6 py-3 rounded-xl text-center font-semibold uppercase backdrop-blur-sm shadow-md">
                        produccion
                    </h3>

                    <div class="flex flex-wrap justify-center gap-4 mt-2">
                        <!-- Soplado -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Soplados')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Soplados'
            ? 'border-lime-500 shadow-lg text-lime-700'
            : 'border-gray-200 text-gray-700 hover:border-lime-500 hover:text-lime-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Soplados' ? 'bg-lime-100 text-lime-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 2l.642 .005l.616 .017l.299 .013l.579 .034l.553 .046c4.687 .455 6.65 2.333 7.166 6.906l.03 .29l.046 .553l.041 .727l.006 .15l.017 .617l.005 .642l-.005 .642l-.017 .616l-.013 .299l-.034 .579l-.046 .553c-4.687 4.687 -2.333 6.65 -6.906 7.166l-.29 .03l-.553 .046l-.727 .041l-.15 .006l-.617 .017l-.642 .005l-.642 -.005l-.616 -.017l-.299 -.013l-.579 -.034l-.553 -.046c-4.687 -.455 -6.65 -2.333 -7.166 -6.906l-.03 -.29l-.046 -.553l-.041 -.727l-.006 -.15l-.017 -.617l-.004 -.318v-.648l.004 -.318l.017 -.616l.013 -.299l.034 -.579l.046 -.553c.455 -4.687 2.333 -6.65 6.906 -7.166l.29 -.03l.553 -.046l.727 -.041l.15 -.006l.617 -.017c.21 -.003 .424 -.005 .642 -.005zm.994 5.886c-.083 -.777 -1.008 -1.16 -1.617 -.67l-.084 .077l-2 2l-.083 .094a1 1 0 0 0 0 1.226l.083 .094l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l.293 -.293v5.586l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-8l-.006 -.114z" />
                                </svg>
                            </div>
                            <span class="mt-3 text-sm font-semibold text-center">Soplado</span>
                        </button>

                        <!-- Llenados -->
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Llenados')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
        {{ $seleccion == 'Llenados'
            ? 'border-lime-500 shadow-lg text-lime-700'
            : 'border-gray-200 text-gray-700 hover:border-lime-500 hover:text-lime-600 hover:shadow-md' }}">

                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Llenados' ? 'bg-lime-100 text-lime-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10.425 1.414a3.33 3.33 0 0 1 3.216 0l6.775 3.995c.067 .04 .127 .084 .18 .133l.008 .007l.107 .076a3.223 3.223 0 0 1 1.284 2.39l.005 .203v7.284c0 1.175 -.643 2.256 -1.623 2.793l-6.804 4.302c-.98 .538 -2.166 .538 -3.2 -.032l-6.695 -4.237a3.226 3.226 0 0 1 -1.678 -2.826v-7.285a3.21 3.21 0 0 1 1.65 -2.808zm2.575 5.586h-3l-.117 .007a1 1 0 0 0 0 1.986l.117 .007h3v2h-2l-.15 .005a2 2 0 0 0 -1.844 1.838l-.006 .157v2l.005 .15a2 2 0 0 0 1.838 1.844l.157 .006h3l.117 -.007a1 1 0 0 0 0 -1.986l-.117 -.007h-3v-2h2l.15 -.005a2 2 0 0 0 1.844 -1.838l.006 -.157v-2l-.005 -.15a2 2 0 0 0 -1.838 -1.844l-.157 -.006z" />
                                </svg>
                            </div>
                            <span class="mt-3 text-sm font-semibold text-center">Llenados</span>
                        </button>


                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Etiquetado')"
                            class="w-24 h-24 flex flex-col items-center justify-center rounded-xl
                             {{ ($seleccion == 'Etiquetado') ? 'bg-teal-600 text-white' : 'bg-cyan-600 text-white hover:bg-teal-600' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="currentColor" class="mb-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M18.333 2c1.96 0 3.56 1.537 3.662 3.472l.005 .195v12.666c0 1.96 -1.537 3.56 -3.472 3.662l-.195 .005h-12.666a3.667 3.667 0 0 1 -3.662 -3.472l-.005 -.195v-12.666c0 -1.96 1.537 -3.56 3.472 -3.662l.195 -.005h12.666zm-5.333 5h-2l-.15 .005a2 2 0 0 0 -1.85 1.995a1 1 0 0 0 1.974 .23l.02 -.113l.006 -.117h2v2h-2l-.133 .007c-1.111 .12 -1.154 1.73 -.128 1.965l.128 .021l.133 .007h2v2h-2l-.007 -.117a1 1 0 0 0 -1.993 .117a2 2 0 0 0 1.85 1.995l.15 .005h2l.15 -.005a2 2 0 0 0 1.844 -1.838l.006 -.157v-2l-.005 -.15a1.988 1.988 0 0 0 -.17 -.667l-.075 -.152l-.019 -.032l.02 -.03a2.01 2.01 0 0 0 .242 -.795l.007 -.174v-2l-.005 -.15a2 2 0 0 0 -1.838 -1.844l-.157 -.006z" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Etiquetado</span>
                        </button>

                    </div>
                    <br>
                    @endif
                    @if(in_array(auth()->user()->rol_id, [1,3]))
                    <h3 class="bg-white/30 border-2 border-red-500 text-red-600 px-6 py-3 rounded-xl text-center font-semibold uppercase backdrop-blur-sm shadow-md">
                        ventas y distribucion
                    </h3>

                    <div class="flex flex-wrap justify-center gap-4 mt-2">
                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Cliente')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
    {{ $seleccion == 'Cliente'
        ? 'border-red-500 shadow-lg text-red-700'
        : 'border-gray-200 text-gray-700 hover:border-red-500 hover:text-red-600 hover:shadow-md' }}">
                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Cliente' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                </svg>
                            </div>
                            <span class="mt-3 text-sm font-semibold text-center">Cliente</span>
                        </button>

                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Distribucion')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
    {{ $seleccion == 'Distribucion'
        ? 'border-red-500 shadow-lg text-red-700'
        : 'border-gray-200 text-gray-700 hover:border-red-500 hover:text-red-600 hover:shadow-md' }}">
                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Distribucion' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 19l4 -14" />
                                    <path d="M16 5l4 14" />
                                    <path d="M12 8v-2" />
                                    <path d="M12 13v-2" />
                                    <path d="M12 18v-2" />
                                </svg>
                            </div>
                            <span class="mt-3 text-sm font-semibold text-center">Distribución</span>
                        </button>

                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Pedidos')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
    {{ $seleccion == 'Pedidos'
        ? 'border-red-500 shadow-lg text-red-700'
        : 'border-gray-200 text-gray-700 hover:border-red-500 hover:text-red-600 hover:shadow-md' }}">
                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Pedidos' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M11 6h9" />
                                    <path d="M11 12h9" />
                                    <path d="M12 18h8" />
                                    <path d="M4 16a2 2 0 1 1 4 0c0 .591 -.5 1 -1 1.5l-3 2.5h4" />
                                    <path d="M6 10v-6l-2 2" />
                                </svg>
                            </div>
                            <span class="mt-3 text-sm font-semibold text-center">Pedidos</span>
                        </button>

                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Coche')"
                            class="w-32 h-32 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white
    {{ $seleccion == 'Coche'
        ? 'border-red-500 shadow-lg text-red-700'
        : 'border-gray-200 text-gray-700 hover:border-red-500 hover:text-red-600 hover:shadow-md' }}">
                            <div class="p-3 rounded-full 
        {{ $seleccion == 'Coche' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5" />
                                </svg>
                            </div>
                            <span class="mt-3 text-sm font-semibold text-center">Coche</span>
                        </button>



                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Reporteventa')"
                            class="w-24 h-24 flex flex-col items-center justify-center rounded-xl
                             {{ ($seleccion == 'Reporteventa') ? 'bg-teal-600 text-white' : 'bg-cyan-600 text-white hover:bg-teal-600' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697" />
                                <path d="M18 12v-5a2 2 0 0 0 -2 -2h-2" />
                                <path d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                <path d="M8 11h4" />
                                <path d="M8 15h3" />
                                <path d="M16.5 17.5m-2.5 0a2.5 2.5 0 1 0 5 0a2.5 2.5 0 1 0 -5 0" />
                                <path d="M18.5 19.5l2.5 2.5" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Re Venta</span>
                        </button>

                        <button
                            type="button"
                            wire:click="$set('seleccion', 'Reportecredito')"
                            class="w-24 h-24 flex flex-col items-center justify-center rounded-xl
                             {{ ($seleccion == 'Reportecredito') ? 'bg-teal-600 text-white' : 'bg-cyan-600 text-white hover:bg-teal-600' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697" />
                                <path d="M18 12v-5a2 2 0 0 0 -2 -2h-2" />
                                <path d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                <path d="M8 11h4" />
                                <path d="M8 15h3" />
                                <path d="M16.5 17.5m-2.5 0a2.5 2.5 0 1 0 5 0a2.5 2.5 0 1 0 -5 0" />
                                <path d="M18.5 19.5l2.5 2.5" />
                            </svg>
                            <span class="text-sm font-semibold text-center truncate w-full">Re pagos</span>
                        </button>

                    </div>
                    <br>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="w-full min-h-screen bg-white" id="main-content">
        <div class="w-full p-2  bg-white">

            @if ($seleccion == 'Personal')
            @livewire('personal')
            @endif
            @if ($seleccion == 'Proveedores')
            @livewire('proveedores')
            @endif
            @if ($seleccion == 'Roles')
            @livewire('roles')
            @endif
            @if ($seleccion == 'Soplados')
            @livewire('soplados')
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
            @if ($seleccion == 'Llenados')
            @livewire('llenados')
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
            @if ($seleccion == 'Asignaciones')
            @livewire('asignaciones')
            @endif




        </div>

    </main>


</div>
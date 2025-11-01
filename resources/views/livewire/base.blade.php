<div>
    <header
        class="bg-gradient-to-r from-cyan-600 via-cyan-500 to-cyan-400 bg-opacity-95 backdrop-blur-md rounded-full px-6 py-2 shadow-xl w-[95%] max-w-[1700px] mx-auto fixed top-3 left-0 right-0 z-20 transition-all duration-300">

        <div class="flex justify-between items-center">
            <button id="menu-toggle"
                class="text-white transition-transform duration-300 hover:scale-110 focus:outline-none rounded-full p-2 md:p-3 shadow-md hover:shadow-lg"
                title="Menú">
                <svg class="w-6 h-6 md:w-6 md:h-6" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
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
            <button
                class="text-white transition-transform duration-300 hover:scale-110 focus:outline-none rounded-full p-2 md:p-3 shadow-md hover:shadow-lg"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                title="Cerrar sesión">
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

    <nav id="menu"
        class="w-[95%] max-w-[1700px] text-cyan-600 bg-white px-6 py-2 mt-4 shadow-lg fixed left-1/2 -translate-x-1/2 top-[65px] hidden transition-all rounded-xl backdrop-blur-md z-20 ">
        <div class="max-h-[80vh] overflow-y-auto ">
            <div class="">
                <div>
                    @if(auth()->user()->rol_id == 1)
                        <div
                            class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-cyan-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                            <h3 class="text-cyan-600 text-lg font-semibold uppercase text-center sm:text-left">
                                Empresa
                            </h3>
                            <div class="flex flex-wrap justify-center gap-4">
                                <button type="button" wire:click="$set('seleccion', 'Roles')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Roles' ? 'border-cyan-600 shadow-lg text-cyan-700' : 'border-gray-200 text-gray-700 hover:border-cyan-600 hover:text-cyan-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Roles' ? 'bg-cyan-100 text-cyan-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                            <path d="M16 19h6" />
                                            <path d="M19 16v6" />
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Roles</span>
                                </button>
                                <button type="button" wire:click="$set('seleccion', 'Empresa')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Empresa' ? 'border-cyan-600 shadow-lg text-cyan-700' : 'border-gray-200 text-gray-700 hover:border-cyan-600 hover:text-cyan-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Empresa' ? 'bg-cyan-100 text-cyan-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M12 2c5.523 0 10 4.477 10 10s-4.477 10 -10 10s-10 -4.477 -10 -10s4.477 -10 10 -10zm.994 5.886c-.083 -.777 -1.008 -1.16 -1.617 -.67l-.084 .077l-2 2l-.083 .094a1 1 0 0 0 0 1.226l.083 .094l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l.293 -.293v5.586l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-8l-.006 -.114z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Empresa</span>
                                </button>
                                <button type="button" wire:click="$set('seleccion', 'Sucursal')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Sucursal' ? 'border-cyan-600 shadow-lg text-cyan-700' : 'border-gray-200 text-gray-700 hover:border-cyan-600 hover:text-cyan-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Sucursal' ? 'bg-cyan-100 text-cyan-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M10 8h3a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 0 -1 1v2a1 1 0 0 0 1 1h3" />
                                            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Sucursal</span>
                                </button>
                            </div>
                        </div>
                        <div
                            class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-cyan-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                            <h3 class="text-cyan-600 text-lg font-semibold uppercase text-center sm:text-left">
                                Asignaciones de trabajos
                            </h3>
                            <div class="flex flex-wrap justify-center gap-4">
                                <button type="button" wire:click="$set('seleccion', 'Personal')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Personal' ? 'border-cyan-600 shadow-lg text-cyan-700' : 'border-gray-200 text-gray-700 hover:border-cyan-600 hover:text-cyan-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Personal' ? 'bg-cyan-100 text-cyan-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M12 2c5.523 0 10 4.477 10 10s-4.477 10 -10 10s-10 -4.477 -10 -10s4.477 -10 10 -10zm.994 5.886c-.083 -.777 -1.008 -1.16 -1.617 -.67l-.084 .077l-2 2l-.083 .094a1 1 0 0 0 0 1.226l.083 .094l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l.293 -.293v5.586l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-8l-.006 -.114z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Personal empresa</span>
                                </button>
                                <button type="button" wire:click="$set('seleccion', 'Trabajador')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Trabajador' ? 'border-cyan-600 shadow-lg text-cyan-700' : 'border-gray-200 text-gray-700 hover:border-cyan-600 hover:text-cyan-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Trabajador' ? 'bg-cyan-100 text-cyan-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M10 8h3a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 0 -1 1v2a1 1 0 0 0 1 1h3" />
                                            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Asignar trabajos</span>
                                </button>
                            </div>
                        </div>
                    @endif
                    @if(in_array(auth()->user()->rol_id, [1, 2]))
                        <br>
                        <h3
                            class="bg-white/30 border-2 border-indigo-500 text-indigo-600 px-6 py-3 rounded-xl text-center font-semibold uppercase backdrop-blur-sm shadow-md">
                            PLANTA
                        </h3>
                        <div
                            class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-indigo-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                            <h3 class="text-indigo-600 text-lg font-semibold uppercase text-center sm:text-left">
                                Items de Almacen
                            </h3>
                            <div class="flex flex-wrap justify-center gap-4">
                                <button type="button" wire:click="$set('seleccion', 'Bases')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Bases' ? 'border-indigo-600 shadow-lg text-indigo-700' : 'border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Bases' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M12 2c5.523 0 10 4.477 10 10s-4.477 10 -10 10s-10 -4.477 -10 -10s4.477 -10 10 -10zm.994 5.886c-.083 -.777 -1.008 -1.16 -1.617 -.67l-.084 .077l-2 2l-.083 .094a1 1 0 0 0 0 1.226l.083 .094l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l.293 -.293v5.586l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-8l-.006 -.114z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Bases</span>
                                </button>
                                <button type="button" wire:click="$set('seleccion', 'Tapas')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Tapas' ? 'border-indigo-600 shadow-lg text-indigo-700' : 'border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Tapas' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M12 2c5.523 0 10 4.477 10 10s-4.477 10 -10 10s-10 -4.477 -10 -10s4.477 -10 10 -10zm.994 5.886c-.083 -.777 -1.008 -1.16 -1.617 -.67l-.084 .077l-2 2l-.083 .094a1 1 0 0 0 0 1.226l.083 .094l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l.293 -.293v5.586l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-8l-.006 -.114z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Tapas</span>
                                </button>
                                <button type="button" wire:click="$set('seleccion', 'Etiquetas')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Etiquetas' ? 'border-indigo-600 shadow-lg text-indigo-700' : 'border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Etiquetas' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M12 2c5.523 0 10 4.477 10 10s-4.477 10 -10 10s-10 -4.477 -10 -10s4.477 -10 10 -10zm.994 5.886c-.083 -.777 -1.008 -1.16 -1.617 -.67l-.084 .077l-2 2l-.083 .094a1 1 0 0 0 0 1.226l.083 .094l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l.293 -.293v5.586l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-8l-.006 -.114z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Etiquetas</span>
                                </button>
                                <button type="button" wire:click="$set('seleccion', 'Preformas')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Preformas' ? 'border-indigo-600 shadow-lg text-indigo-700' : 'border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Preformas' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M12 2c5.523 0 10 4.477 10 10s-4.477 10 -10 10s-10 -4.477 -10 -10s4.477 -10 10 -10zm.994 5.886c-.083 -.777 -1.008 -1.16 -1.617 -.67l-.084 .077l-2 2l-.083 .094a1 1 0 0 0 0 1.226l.083 .094l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l.293 -.293v5.586l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-8l-.006 -.114z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Preformas</span>
                                </button>
                                <button type="button" wire:click="$set('seleccion', 'Productos')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Productos' ? 'border-indigo-600 shadow-lg text-indigo-700' : 'border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Productos' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M12 2c5.523 0 10 4.477 10 10s-4.477 10 -10 10s-10 -4.477 -10 -10s4.477 -10 10 -10zm.994 5.886c-.083 -.777 -1.008 -1.16 -1.617 -.67l-.084 .077l-2 2l-.083 .094a1 1 0 0 0 0 1.226l.083 .094l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l.293 -.293v5.586l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-8l-.006 -.114z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Productos</span>
                                </button>

                            </div>
                        </div>
                        <div
                            class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-indigo-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                            <h3 class="text-indigo-600 text-lg font-semibold uppercase text-center sm:text-left">
                                Almacen
                            </h3>
                            <div class="flex flex-wrap justify-center gap-4">
                                <button type="button" wire:click="$set('seleccion', 'Stocks')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Stocks' ? 'border-indigo-600 shadow-lg text-indigo-700' : 'border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Stocks' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M10 8h3a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 0 -1 1v2a1 1 0 0 0 1 1h3" />
                                            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Almacen de items</span>
                                </button>
                                <button type="button" wire:click="$set('seleccion', 'Traspaso')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Traspaso' ? 'border-indigo-600 shadow-lg text-indigo-700' : 'border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Traspaso' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Traspaso entre
                                        sucursales</span>
                                </button>
                                <button type="button" wire:click="$set('seleccion', 'Asignaciones')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Asignaciones' ? 'border-indigo-600 shadow-lg text-indigo-700' : 'border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Asignaciones' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M19.875 6.27a2.225 2.225 0 0 1 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                                            <path
                                                d="M10 9a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2h2a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Distribucion por areas</span>
                                </button>
                            </div>
                        </div>
                        <div
                            class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-indigo-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                            <h3 class="text-indigo-600 text-lg font-semibold uppercase text-center sm:text-left">
                                produccion
                            </h3>
                            <div class="flex flex-wrap justify-center gap-4">
                                <button type="button" wire:click="$set('seleccion', 'Soplados')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Soplados' ? 'border-indigo-600 shadow-lg text-indigo-700' : 'border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Soplados' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 8v3a1 1 0 0 0 1 1h3" />
                                            <path d="M14 8v8" />
                                            <path
                                                d="M5 7.2a2.2 2.2 0 0 1 2.2 -2.2h1a2.2 2.2 0 0 0 1.55 -.64l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7c.412 .41 .97 .64 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1c0 .58 .23 1.138 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.64 1.55v1a2.2 2.2 0 0 1 -2.2 2.2h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Soplado de Preformas</span>
                                </button>
                                <button type="button" wire:click="$set('seleccion', 'Llenados')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Llenados' ? 'border-indigo-600 shadow-lg text-indigo-700' : 'border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Llenados' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M10 8v3a1 1 0 0 0 1 1h3" />
                                            <path d="M14 8v8" />
                                            <path
                                                d="M5 7.2a2.2 2.2 0 0 1 2.2 -2.2h1a2.2 2.2 0 0 0 1.55 -.64l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7c.412 .41 .97 .64 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1c0 .58 .23 1.138 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.64 1.55v1a2.2 2.2 0 0 1 -2.2 2.2h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Llenados y envasado</span>
                                </button>
                            </div>
                        </div>
                        <div
                            class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-indigo-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                            <h3 class="text-indigo-600 text-lg font-semibold uppercase text-center sm:text-left">
                                Reporte Almacen
                            </h3>
                            <div class="flex flex-wrap justify-center gap-4">
                                <button type="button" wire:click="$set('seleccion', 'Reportestock')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Reportestock' ? 'border-indigo-600 shadow-lg text-indigo-700' : 'border-gray-200 text-gray-700 hover:border-indigo-600 hover:text-indigo-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Reportestock' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M17.997 4.17a3 3 0 0 1 2.003 2.83v12a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 2.003 -2.83a4 4 0 0 0 3.997 3.83h4a4 4 0 0 0 3.98 -3.597zm-8.997 7.83a1 1 0 0 0 -1 1v4a1 1 0 0 0 2 0v-4a1 1 0 0 0 -1 -1m3 3a1 1 0 0 0 -1 1v1a1 1 0 0 0 1 1l.117 -.007a1 1 0 0 0 .883 -.993v-1a1 1 0 0 0 -1 -1m3 -1a1 1 0 0 0 -1 1v2a1 1 0 0 0 2 0v-2a1 1 0 0 0 -1 -1m-1 -12a2 2 0 1 1 0 4h-4a2 2 0 1 1 0 -4z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Reporte almacen</span>
                                </button>
                            </div>
                        </div>
                        <br>
                    @endif
                    @if(in_array(auth()->user()->rol_id, [1, 3]))
                        <h3
                            class="bg-white/30 border-2 border-red-500 text-red-600 px-6 py-3 rounded-xl text-center font-semibold uppercase backdrop-blur-sm shadow-md">
                            Ventas
                        </h3>
                        <div
                            class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-red-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                            <h3 class="text-red-600 text-lg font-semibold uppercase text-center sm:text-left">
                                Pedidos
                            </h3>
                            <div class="flex flex-wrap justify-center gap-4">
                                <button type="button" wire:click="$set('seleccion', 'Cliente')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Cliente' ? 'border-red-600 shadow-lg text-red-700' : 'border-gray-200 text-gray-700 hover:border-red-600 hover:text-red-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Cliente' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M12 2c5.523 0 10 4.477 10 10s-4.477 10 -10 10s-10 -4.477 -10 -10s4.477 -10 10 -10zm.994 5.886c-.083 -.777 -1.008 -1.16 -1.617 -.67l-.084 .077l-2 2l-.083 .094a1 1 0 0 0 0 1.226l.083 .094l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l.293 -.293v5.586l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-8l-.006 -.114z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Clientes</span>
                                </button>
                                <button type="button" wire:click="$set('seleccion', 'Pedidos')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Pedidos' ? 'border-red-600 shadow-lg text-red-700' : 'border-gray-200 text-gray-700 hover:border-red-600 hover:text-red-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Pedidos' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M10 8h3a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 0 -1 1v2a1 1 0 0 0 1 1h3" />
                                            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Pedidos de clientes</span>
                                </button>
                                <button type="button" wire:click="$set('seleccion', 'Adornados')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Adornados' ? 'border-red-600 shadow-lg text-red-700' : 'border-gray-200 text-gray-700 hover:border-red-600 hover:text-red-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Adornados' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M19.875 6.27a2.225 2.225 0 0 1 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                                            <path
                                                d="M10 9a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2h2a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Etiqeutado y plastificado</span>
                                </button>
                            </div>
                        </div>
                        <div
                            class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-red-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                            <h3 class="text-red-600 text-lg font-semibold uppercase text-center sm:text-left">
                                Distribucion
                            </h3>
                            <div class="flex flex-wrap justify-center gap-4">
                                <button type="button" wire:click="$set('seleccion', 'Coche')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Coche' ? 'border-red-600 shadow-lg text-red-700' : 'border-gray-200 text-gray-700 hover:border-red-600 hover:text-red-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Coche' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M12 2c5.523 0 10 4.477 10 10s-4.477 10 -10 10s-10 -4.477 -10 -10s4.477 -10 10 -10zm.994 5.886c-.083 -.777 -1.008 -1.16 -1.617 -.67l-.084 .077l-2 2l-.083 .094a1 1 0 0 0 0 1.226l.083 .094l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l.293 -.293v5.586l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-8l-.006 -.114z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Coches</span>
                                </button>
                                <button type="button" wire:click="$set('seleccion', 'Distribucion')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Distribucion' ? 'border-red-600 shadow-lg text-red-700' : 'border-gray-200 text-gray-700 hover:border-red-600 hover:text-red-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Distribucion' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M10 8h3a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 0 -1 1v2a1 1 0 0 0 1 1h3" />
                                            <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Distribucion de Pedidos</span>
                                </button>
                            </div>
                        </div>
                        <div
                            class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-red-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                            <h3 class="text-red-600 text-lg font-semibold uppercase text-center sm:text-left">
                                Reportes de Ventas
                            </h3>
                            <div class="flex flex-wrap justify-center gap-4">
                                <button type="button" wire:click="$set('seleccion', 'Reporteventa')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Reporteventa' ? 'border-red-600 shadow-lg text-red-700' : 'border-gray-200 text-gray-700 hover:border-red-600 hover:text-red-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Reporteventa' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M17.997 4.17a3 3 0 0 1 2.003 2.83v12a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 2.003 -2.83a4 4 0 0 0 3.997 3.83h4a4 4 0 0 0 3.98 -3.597zm-8.997 7.83a1 1 0 0 0 -1 1v4a1 1 0 0 0 2 0v-4a1 1 0 0 0 -1 -1m3 3a1 1 0 0 0 -1 1v1a1 1 0 0 0 1 1l.117 -.007a1 1 0 0 0 .883 -.993v-1a1 1 0 0 0 -1 -1m3 -1a1 1 0 0 0 -1 1v2a1 1 0 0 0 2 0v-2a1 1 0 0 0 -1 -1m-1 -12a2 2 0 1 1 0 4h-4a2 2 0 1 1 0 -4z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Reporte de de Ventas</span>
                                </button>
                                <button type="button" wire:click="$set('seleccion', 'Reportecredito')"
                                    class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Reportecredito' ? 'border-red-600 shadow-lg text-red-700' : 'border-gray-200 text-gray-700 hover:border-red-600 hover:text-red-600 hover:shadow-md' }}">
                                    <div
                                        class="p-3 rounded-full{{ $seleccion == 'Reportecredito' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M17.997 4.17a3 3 0 0 1 2.003 2.83v12a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 2.003 -2.83a4 4 0 0 0 3.997 3.83h4a4 4 0 0 0 3.98 -3.597zm-8.997 7.83a1 1 0 0 0 -1 1v4a1 1 0 0 0 2 0v-4a1 1 0 0 0 -1 -1m3 3a1 1 0 0 0 -1 1v1a1 1 0 0 0 1 1l.117 -.007a1 1 0 0 0 .883 -.993v-1a1 1 0 0 0 -1 -1m3 -1a1 1 0 0 0 -1 1v2a1 1 0 0 0 2 0v-2a1 1 0 0 0 -1 -1m-1 -12a2 2 0 1 1 0 4h-4a2 2 0 1 1 0 -4z" />
                                        </svg>
                                    </div>
                                    <span class="mt-2 text-sm font-semibold text-center">Reporte de de Ventas</span>
                                </button>
                            </div>
                        </div>
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
            @if ($seleccion == 'Adornados')
                @livewire('adornados')
            @endif
            @if ($seleccion == 'Reporteventa')
                @livewire('reporteventa')
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
<div>
    <header
        class="bg-cyan-600 backdrop-blur-md rounded-full px-6 py-2 shadow-xl w-[95%] max-w-[1700px] mx-auto fixed top-3 left-0 right-0 z-20 transition-all duration-300">

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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M6 4l6 16l6 -16" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Datos Empresa</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Sucursal')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Sucursal' ? 'border-cyan-600 shadow-lg text-cyan-700' : 'border-gray-200 text-gray-700 hover:border-cyan-600 hover:text-cyan-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Sucursal' ? 'bg-cyan-100 text-cyan-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 9a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9" />
                                        <path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Sucursales</span>
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 9a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9" />
                                        <path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Personal</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Trabajador')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Trabajador' ? 'border-cyan-600 shadow-lg text-cyan-700' : 'border-gray-200 text-gray-700 hover:border-cyan-600 hover:text-cyan-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Trabajador' ? 'bg-cyan-100 text-cyan-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 9a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9" />
                                        <path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Asignar Labor</span>
                            </button>
                        </div>
                    </div>
                    @endif
                    @if(in_array(auth()->user()->rol_id, [1, 2]))
                    <br>
                    <h3
                        class="bg-white/30 border-2 border-emerald-500 text-emerald-600 px-6 py-3 rounded-xl text-center font-semibold uppercase backdrop-blur-sm shadow-md">
                        ADMINISTRACION
                    </h3>
                    <div
                        class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-emerald-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                        <h3 class="text-emerald-600 text-lg font-semibold uppercase text-center sm:text-left">
                            Agregar Nuevo Producto
                        </h3>
                        <div class="flex flex-wrap justify-center gap-4">
                            <button type="button" wire:click="$set('seleccion', 'Etiquetas')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Etiquetas' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Etiquetas' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 6l-8 4l8 4l8 -4l-8 -4" />
                                        <path d="M4 14l8 4l8 -4" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Etiquetas</span>
                            </button>


                            <button type="button" wire:click="$set('seleccion', 'Productos')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Productos' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Productos' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M9 4a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1l0 -1" />
                                        <path d="M10 6v.98c0 .877 -.634 1.626 -1.5 1.77c-.866 .144 -1.5 .893 -1.5 1.77v8.48a2 2 0 0 0 2 2h6a2 2 0 0 0 2 -2v-8.48c0 -.877 -.634 -1.626 -1.5 -1.77a1.795 1.795 0 0 1 -1.5 -1.77v-.98" />
                                        <path d="M7 12h10" />
                                        <path d="M7 18h10" />
                                        <path d="M11 15h2" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Precios de productos</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Otros')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Otros' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Otros' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M9 4a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1l0 -1" />
                                        <path d="M10 6v.98c0 .877 -.634 1.626 -1.5 1.77c-.866 .144 -1.5 .893 -1.5 1.77v8.48a2 2 0 0 0 2 2h6a2 2 0 0 0 2 -2v-8.48c0 -.877 -.634 -1.626 -1.5 -1.77a1.795 1.795 0 0 1 -1.5 -1.77v-.98" />
                                        <path d="M7 12h10" />
                                        <path d="M7 18h10" />
                                        <path d="M11 15h2" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Precios de Equipos</span>
                            </button>

                        </div>
                    </div>
                    <div
                        class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-emerald-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                        <h3 class="text-emerald-600 text-lg font-semibold uppercase text-center sm:text-left">
                            Almacen y asignacion de items
                        </h3>
                        <div class="flex flex-wrap justify-center gap-4">

                            <button type="button" wire:click="$set('seleccion', 'Proveedores')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Proveedores' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Proveedores' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M7.5 10.625l-4.5 -2.813l4.5 -2.812l4.5 2.813m-4.5 2.812l4.5 -2.813m-4.5 2.813l-4.5 2.823l4.5 2.802m0 -5.625l4.5 2.823m0 -5.636l4.5 2.791l4.5 -2.812l-4.5 -2.791l-4.5 2.813m-4.5 8.438l4.5 -2.802m-4.5 2.802v1.123l4.5 2.627l4.5 -2.627v-1.123m-4.5 -2.802l4.5 -2.823l4.5 2.823l-4.5 2.802m-4.5 -2.802l4.5 2.802" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Proovedores</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Cliente')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Cliente' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Cliente' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h1.5" />
                                        <path d="M15 18a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                        <path d="M20.2 20.2l1.8 1.8" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Registrar
                                    Clientes</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Stocks')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Stocks' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Stocks' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M7.5 10.625l-4.5 -2.813l4.5 -2.812l4.5 2.813m-4.5 2.812l4.5 -2.813m-4.5 2.813l-4.5 2.823l4.5 2.802m0 -5.625l4.5 2.823m0 -5.636l4.5 2.791l4.5 -2.812l-4.5 -2.791l-4.5 2.813m-4.5 8.438l4.5 -2.802m-4.5 2.802v1.123l4.5 2.627l4.5 -2.627v-1.123m-4.5 -2.802l4.5 -2.823l4.5 2.823l-4.5 2.802m-4.5 -2.802l4.5 2.802" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Deposito de items</span>
                            </button>

                            <button type="button" wire:click="$set('seleccion', 'Pedidos')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Pedidos' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Pedidos' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M13 21h-7a1 1 0 0 1 -1 -1v-16a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1v6" />
                                        <path d="M11 17a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
                                        <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                        <path d="M19 21v1m0 -8v1" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Pedidos de clientes</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Pago-pedidos')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Pago-pedidos' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Pago-pedidos' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M13 21h-7a1 1 0 0 1 -1 -1v-16a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1v6" />
                                        <path d="M11 17a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
                                        <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                        <path d="M19 21v1m0 -8v1" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Ajuste de Precios y Pagos</span>
                            </button>
                        </div>
                    </div>
                    <div
                        class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-emerald-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                        <h3 class="text-emerald-600 text-lg font-semibold uppercase text-center sm:text-left">
                            Reportes de almacen
                        </h3>
                        <div class="flex flex-wrap justify-center gap-4">

                            <button type="button" wire:click="$set('seleccion', 'Reportestock')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Reportestock' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Reportestock' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                        <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                        <path d="M3 6l0 13" />
                                        <path d="M12 6l0 13" />
                                        <path d="M21 6l0 13" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">
                                    Reporte de compra e ingreso items
                                </span>
                            </button>

                            <button type="button" wire:click="$set('seleccion', 'Reportecompra')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Reportecompra' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Reportecompra' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                        <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                        <path d="M3 6l0 13" />
                                        <path d="M12 6l0 13" />
                                        <path d="M21 6l0 13" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">
                                    Egreso de material para produccion
                                </span>
                            </button>
                        </div>

                    </div>
                    <div
                        class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-emerald-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                        <h3 class="text-emerald-600 text-lg font-semibold uppercase text-center sm:text-left">
                            Reportes de Ventas
                        </h3>
                        <div class="flex flex-wrap justify-center gap-4">
                            <button type="button" wire:click="$set('seleccion', 'Reporteventa')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Reporteventa' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Reporteventa' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                        <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                        <path d="M3 6l0 13" />
                                        <path d="M12 6l0 13" />
                                        <path d="M21 6l0 13" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Recaudacion</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Reportecredito')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Reportecredito' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Reportecredito' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                        <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                        <path d="M3 6l0 13" />
                                        <path d="M12 6l0 13" />
                                        <path d="M21 6l0 13" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Registro de pedidos</span>
                            </button>
                        </div>
                    </div>
                    @endif
                    @if(in_array(auth()->user()->rol_id, [1, 4]))
                    <div
                        class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-emerald-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                        <h3 class="text-emerald-600 text-lg font-semibold uppercase text-center sm:text-left">
                            Agregar Nuevo Producto
                        </h3>
                        <div class="flex flex-wrap justify-center gap-4">
                            <button type="button" wire:click="$set('seleccion', 'Bases')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Bases' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Bases' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10 5h4v-2a1 1 0 0 0 -1 -1h-2a1 1 0 0 0 -1 1v2" />
                                        <path d="M14 3.5c0 1.626 .507 3.212 1.45 4.537l.05 .07a8.093 8.093 0 0 1 1.5 4.694v6.199a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2v-6.2c0 -1.682 .524 -3.322 1.5 -4.693l.05 -.07a7.823 7.823 0 0 0 1.45 -4.537" />
                                        <path d="M7 14.803a2.4 2.4 0 0 0 1 -.803a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 1 -.805" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Botellas</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Tapas')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Tapas' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Tapas' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                        <path d="M13 12h5" />
                                        <path d="M13 15h4" />
                                        <path d="M13 18h1" />
                                        <path d="M13 9h4" />
                                        <path d="M13 6h1" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Tapas</span>
                            </button>

                            <button type="button" wire:click="$set('seleccion', 'Preformas')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Preformas' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Preformas' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 6a7 3 0 1 0 14 0a7 3 0 1 0 -14 0" />
                                        <path d="M5 6v12c0 1.657 3.134 3 7 3s7 -1.343 7 -3v-12" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Preformas</span>
                            </button>

                        </div>
                    </div>
                    <div
                        class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-emerald-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                        <h3 class="text-emerald-600 text-lg font-semibold uppercase text-center sm:text-left">
                            produccion
                        </h3>

                        <div class="flex flex-wrap justify-center gap-4">
                            <button type="button" wire:click="$set('seleccion', 'Asignaciones')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Asignaciones' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Asignaciones' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M9 4.55a8 8 0 0 1 6 14.9m0 -4.45v5h5" />
                                        <path d="M5.63 7.16l0 .01" />
                                        <path d="M4.06 11l0 .01" />
                                        <path d="M4.63 15.1l0 .01" />
                                        <path d="M7.16 18.37l0 .01" />
                                        <path d="M11 19.94l0 .01" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Sacar items</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Traspaso')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Traspaso' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Traspaso' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Traspaso de sucursales</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Soplados')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Soplados' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Soplados' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 15a6 6 0 1 0 12 0a6 6 0 1 0 -12 0" />
                                        <path d="M7 15a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M17 5a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M14.218 17.975l6.619 -12.174" />
                                        <path d="M6.079 9.756l12.217 -6.631" />
                                        <path d="M7 15a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Soplado de Preformas</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Llenados')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Llenados' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Llenados' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 15a6 6 0 1 0 12 0a6 6 0 1 0 -12 0" />
                                        <path d="M7 15a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M17 5a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M14.218 17.975l6.619 -12.174" />
                                        <path d="M6.079 9.756l12.217 -6.631" />
                                        <path d="M7 15a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Llenados y envasado</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Adornados')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Adornados' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Adornados' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 6l-8 4l8 4l8 -4l-8 -4" />
                                        <path d="M4 14l8 4l8 -4" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Adornado y Etiquetado</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Reportecompra')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Reportecompra' ? 'border-emerald-600 shadow-lg text-emerald-700' : 'border-gray-200 text-gray-700 hover:border-emerald-600 hover:text-emerald-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Reportecompra' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                        <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                                        <path d="M3 6l0 13" />
                                        <path d="M12 6l0 13" />
                                        <path d="M21 6l0 13" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">
                                    Reporte Asignacion de area
                                </span>
                            </button>
                        </div>
                    </div>

                    @endif
                    <br>
                    @if(in_array(auth()->user()->rol_id, [1, 3]))

                    <h3
                        class="bg-white/30 border-2 border-orange-500 text-orange-600 px-6 py-3 rounded-xl text-center font-semibold uppercase backdrop-blur-sm shadow-md">
                        Ventas
                    </h3>
                    <div
                        class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-orange-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                        <h3 class="text-orange-600 text-lg font-semibold uppercase text-center sm:text-left">
                            Distribucion de pedidos
                        </h3>
                        <div class="flex flex-wrap justify-center gap-4">
                            <button type="button" wire:click="$set('seleccion', 'Cliente')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Cliente' ? 'border-orange-600 shadow-lg text-orange-700' : 'border-gray-200 text-gray-700 hover:border-orange-600 hover:text-orange-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Cliente' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h1.5" />
                                        <path d="M15 18a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                        <path d="M20.2 20.2l1.8 1.8" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Registrar
                                    Clientes</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Coche')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Coche' ? 'border-orange-600 shadow-lg text-orange-700' : 'border-gray-200 text-gray-700 hover:border-orange-600 hover:text-orange-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Coche' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 17a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M15 17a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Coches</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Pedidos')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Pedidos' ? 'border-orange-600 shadow-lg text-orange-700' : 'border-gray-200 text-gray-700 hover:border-orange-600 hover:text-orange-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Pedidos' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M13 21h-7a1 1 0 0 1 -1 -1v-16a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1v6" />
                                        <path d="M11 17a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
                                        <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                        <path d="M19 21v1m0 -8v1" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Pedidos de clientes</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Pago-pedidos')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Pago-pedidos' ? 'border-orange-600 shadow-lg text-orange-700' : 'border-gray-200 text-gray-700 hover:border-orange-600 hover:text-orange-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Pago-pedidos' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M13 21h-7a1 1 0 0 1 -1 -1v-16a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1v6" />
                                        <path d="M11 17a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
                                        <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                        <path d="M19 21v1m0 -8v1" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Ajuste de Precios y Pagos</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Pedidospersonal')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Pedidospersonal' ? 'border-orange-600 shadow-lg text-orange-700' : 'border-gray-200 text-gray-700 hover:border-orange-600 hover:text-orange-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Pedidospersonal' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M16 12.5l-5 -3l5 -3l5 3v5.5l-5 3l0 -5.5" />
                                        <path d="M11 9.5v5.5l5 3" />
                                        <path d="M16 12.545l5 -3.03" />
                                        <path d="M7 9h-5" />
                                        <path d="M7 12h-3" />
                                        <path d="M7 15h-1" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Entregas</span>
                            </button>
                        </div>
                    </div>

                    @endif

                    <br>
                    <h3
                        class="bg-white/30 border-2 border-slate-500 text-slate-600 px-6 py-3 rounded-xl text-center font-semibold uppercase backdrop-blur-sm shadow-md">
                        Verzasca
                    </h3>
                    <div
                        class="flex flex-wrap items-center justify-between gap-4 mt-2 bg-white/60 border-2 border-slate-500 rounded-xl p-4 backdrop-blur-sm shadow-md">
                        <h3 class="text-slate-600 text-lg font-semibold uppercase text-center sm:text-left">
                            Compras y pedidos
                        </h3>
                        <div class="flex flex-wrap justify-center gap-4">
                            @if(auth()->user()->rol_id == 5)
                            <button type="button" wire:click="$set('seleccion', 'Hubclientes')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Hubclientes' ? 'border-slate-600 shadow-lg text-slate-700' : 'border-gray-200 text-gray-700 hover:border-slate-600 hover:text-slate-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Hubclientes' ? 'bg-slate-100 text-slate-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M6.331 8h11.339a2 2 0 0 1 1.977 2.304l-1.255 8.152a3 3 0 0 1 -2.966 2.544h-6.852a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304z" />
                                        <path d="M9 11v-5a3 3 0 0 1 6 0v5" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Mis pedidos</span>
                            </button>
                            <button type="button" wire:click="$set('seleccion', 'Cliente-etiquetas')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Cliente-etiquetas' ? 'border-slate-600 shadow-lg text-slate-700' : 'border-gray-200 text-gray-700 hover:border-slate-600 hover:text-slate-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Cliente-etiquetas' ? 'bg-slate-100 text-slate-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 6l-8 4l8 4l8 -4l-8 -4" />
                                        <path d="M4 14l8 4l8 -4" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Mis etiquetas</span>
                            </button>
                            @endif
                            <button type="button" wire:click="$set('seleccion', 'Pruebaestilo')"
                                class="w-28 h-28 flex flex-col items-center justify-center rounded-2xl border transition-all bg-white {{ $seleccion == 'Pruebaestilo' ? 'border-slate-600 shadow-lg text-slate-700' : 'border-gray-200 text-gray-700 hover:border-slate-600 hover:text-slate-600 hover:shadow-md' }}">
                                <div
                                    class="p-3 rounded-full{{ $seleccion == 'Pruebaestilo' ? 'bg-slate-100 text-slate-700' : 'bg-gray-100 text-gray-700' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M6 4l6 16l6 -16" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-sm font-semibold text-center">Mi informacion</span>
                            </button>
                        </div>
                    </div>

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
            @if ($seleccion == 'Pago-pedidos')
            @livewire('pago-pedidos')
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
            @if ($seleccion == 'Otros')
            @livewire('otros')
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
            @if ($seleccion == 'Reportecompra')
            @livewire('reportecompra')
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
            @if ($seleccion == 'Hubclientes')
            @livewire('hubclientes')
            @endif
            @if ($seleccion == 'Pedidospersonal')
            @livewire('pedidospersonal')
            @endif
            @if ($seleccion == 'Cliente-etiquetas')
            @livewire('cliente-etiquetas')
            @endif
        </div>
    </main>


</div>
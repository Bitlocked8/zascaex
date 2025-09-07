<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Verzasca</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    <!-- Video de fondo -->
    <div class="fixed top-0 left-0 w-full h-full z-0 overflow-hidden">
        <video autoplay muted loop class="w-full h-full object-cover">
            <source src="/0501.mp4" type="video/mp4">
            Tu navegador no soporta videos HTML5.
        </video>
    </div>

    <!-- Contenido por encima del video -->
    <div class="relative z-10 min-h-screen flex flex-col items-center justify-center px-6 text-center">
        <h1 class="text-4xl md:text-6xl font-bold text-white mb-8 drop-shadow-lg inline-flex items-center">
            Verzasca
            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 w-16 h-16" viewBox="0 0 24 24" fill="currentColor">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M13 1a2 2 0 0 1 1.995 1.85l.005 .15v.5c0 1.317 .381 2.604 1.094 3.705l.17 .25l.05 .072a9.093 9.093 0 0 1 1.68 4.92l.006 .354v6.199a3 3 0 0 1 -2.824 2.995l-.176 .005h-6a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-6.2a9.1 9.1 0 0 1 1.486 -4.982l.2 -.292l.05 -.069a6.823 6.823 0 0 0 1.264 -3.957v-.5a2 2 0 0 1 1.85 -1.995l.15 -.005h2zm.362 5h-2.724a8.827 8.827 0 0 1 -1.08 2.334l-.194 .284l-.05 .069a7.091 7.091 0 0 0 -1.307 3.798l-.003 .125a3.33 3.33 0 0 1 1.975 -.61a3.4 3.4 0 0 1 2.833 1.417c.27 .375 .706 .593 1.209 .583a1.4 1.4 0 0 0 1.166 -.583a3.4 3.4 0 0 1 .81 -.8l.003 .183c0 -1.37 -.396 -2.707 -1.137 -3.852l-.228 -.332a8.827 8.827 0 0 1 -1.273 -2.616z" />
            </svg>
        </h1>

        @if (Route::has('login'))
        <div class="flex flex-col md:flex-row gap-4">
            @auth
            <a href="{{ url('/home') }}"
                class="rounded-md px-5 py-2 bg-white/80 text-black font-medium hover:bg-white hover:text-black transition">
                Regresar
            </a>
            @else
            <a href="{{ route('login') }}"
                class="rounded-md px-5 py-2 bg-white/80 text-black font-medium hover:bg-white hover:text-black transition">
                Log in
            </a>

            @if (Route::has('register'))
            <a href="{{ route('register') }}"
                class="rounded-md px-5 py-2 bg-white/80 text-black font-medium hover:bg-white hover:text-black transition">
                Register
            </a>
            @endif
            @endauth
        </div>
        @endif
    </div>
</body>

</html>
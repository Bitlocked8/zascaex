<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verzasca / Inicio</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <!-- Tailwind CSS -->
    @vite('resources/css/app.css')

    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}"> -->
    <!-- icheck bootstrap -->
    <!-- <link rel="stylesheet" href="{{ asset('css/icheck-bootstrap.min.css') }}"> -->
    <!-- Theme style (AdminLTE) -->
    <!-- <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}"> -->
</head>
<body class="m-0 p-0 overflow-hidden">
    <!-- Video de fondo -->
    <div class="fixed top-0 left-0 w-full h-full z-0 overflow-hidden">
        <video autoplay muted loop class="w-full h-full object-cover">
            <source src="/0501.mp4" type="video/mp4">
            Tu navegador no soporta videos HTML5.
        </video>
    </div>

    <!-- Capa oscura con opacidad -->
    <div class="fixed top-0 left-0 w-full h-full bg-black opacity-20 z-10"></div>

    <!-- Contenido -->
    <main class="relative z-20 flex items-center justify-center min-h-screen px-4">
        @yield('content')
    </main>

    @vite('resources/js/app.js')
    <!-- <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/adminlte.min.js') }}" defer></script> -->
</body>
</html>

@extends('layouts.guest')

@section('content')
<div class="relative min-h-screen flex items-center justify-center overflow-hidden">

    <!-- Formulario centrado con fondo blanco semitransparente -->
    <div class="relative z-10 max-w-md w-full p-10 rounded-lg shadow-lg">
        <div class="flex justify-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="white" class="icon icon-tabler icons-tabler-filled icon-tabler-bottle">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M13 1a2 2 0 0 1 1.995 1.85l.005 .15v.5c0 1.317 .381 2.604 1.094 3.705l.17 .25l.05 .072a9.093 9.093 0 0 1 1.68 4.92l.006 .354v6.199a3 3 0 0 1 -2.824 2.995l-.176 .005h-6a3 3 0 0 1 -2.995 -2.824l-.005 -.176v-6.2a9.1 9.1 0 0 1 1.486 -4.982l.2 -.292l.05 -.069a6.823 6.823 0 0 0 1.264 -3.957v-.5a2 2 0 0 1 1.85 -1.995l.15 -.005h2zm.362 5h-2.724a8.827 8.827 0 0 1 -1.08 2.334l-.194 .284l-.05 .069a7.091 7.091 0 0 0 -1.307 3.798l-.003 .125a3.33 3.33 0 0 1 1.975 -.61a3.4 3.4 0 0 1 2.833 1.417c.27 .375 .706 .593 1.209 .583a1.4 1.4 0 0 0 1.166 -.583a3.4 3.4 0 0 1 .81 -.8l.003 .183c0 -1.37 -.396 -2.707 -1.137 -3.852l-.228 -.332a8.827 8.827 0 0 1 -1.273 -2.616z" />
            </svg>
        </div>

        <form action="{{ route('login') }}" method="post">
            @csrf

            <div class="mb-4 ">
                <label class="text-white">Correo Electronico</label>
                <input
                    name="email"
                    class="bg-transparent text-white form-input w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror"
                    placeholder="Correo electrónico"
                    required
                    autofocus>
                @error('email')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="text-white">Contraseña</label>
                <input
                    name="password"
                    class="bg-transparent text-white form-input w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror"
                    placeholder="Contraseña"
                     style="-webkit-text-security: disc;"
                    required>
                @error('password')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>



            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="form-checkbox text-blue-600 h-4 w-4 mr-2">
                    <label for="remember" class="text-sm text-white">Recuerdame la contraseña</label>
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-950 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                {{ __('Login') }}
            </button>
        </form>

        @if (Route::has('password.request'))
        <p class="text-center mt-4">
            <a href="{{ route('password.request') }}" class="text-white hover:underline">Olvidaste tu contraseña ?</a>
        </p>
        @endif
    </div>
</div>
@endsection
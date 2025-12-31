@extends('layouts.guest')

@section('content')
    <div class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <div class="relative z-10 max-w-md w-full p-10 rounded-lg shadow-lg">
            <div class="flex justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none"
                    class="stroke-cyan-600" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M6 4l6 16l6 -16" />
                </svg>

            </div>

            <form action="{{ route('login') }}" method="post">
                @csrf

                <div class="mb-4 ">
                    <label class="text-white">Correo Electronico</label>
                    <input name="email"
                        class="bg-transparent text-white form-input w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500 @error('email') border-red-500 @enderror"
                        placeholder="Correo electrónico" required autofocus>
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4 relative">
                    <label class="text-white">Contraseña</label>

                    <input id="password" name="password" type="password"
                        class="bg-transparent text-white form-input w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500 @error('password') border-red-500 @enderror"
                        placeholder="Contraseña" required>

                    <button type="button" onclick="togglePassword()"
                        class="absolute right-3 top-9 text-white opacity-70 hover:opacity-100" tabindex="-1">
                        👁️
                    </button>

                    @error('password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>


                <button type="submit"
                    class="w-full bg-cyan-600 text-white py-2 px-4 rounded-md hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    Iniciar sesion
                </button>
            </form>
        </div>
    </div>
@endsection
<script>
    function togglePassword() {
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
<div class="p-2 mt-10 flex justify-center bg-white">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse ($roles as $rol)
        <div class="bg-white shadow rounded-lg p-4 grid grid-cols-12 gap-4 items-center">

            <!-- Columna Izquierda: Info del rol -->
            <div class="flex flex-col items-center md:items-start text-center md:text-left col-span-8">
                <h3 class="text-lg font-semibold uppercase text-cyan-600">
                    {{ $rol->nombre }}
                </h3>
                <p class="text-cyan-950">
                    <strong>Descripción:</strong> {{ $rol->descripcion ?? 'Sin descripción' }}
                </p>

              
            </div>

     
           
        </div>
        @empty
        <div class="col-span-full text-center py-4 text-gray-600">
            No hay roles registrados.
        </div>
        @endforelse
    </div>
</div>

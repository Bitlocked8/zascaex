<div class="p-2 mt-20 flex justify-center bg-transparent">
    <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <h3
            class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto">
            Roles
        </h3>

        @forelse ($roles as $rol)
            <div class="card-teal flex flex-col gap-3">
                <div class="flex flex-col gap-2">
                    <p class="text-teal-700 uppercase font-semibold">
                        {{ $rol->nombre }}
                    </p>
                    <p class="text-gray-600">
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

<div class="p-2 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <h3 class="col-span-full text-center bg-cyan-700 text-white px-6 py-3 rounded-full text-3xl font-bold uppercase shadow-md">
      Información de la Empresa
    </h3>

    @if ($empresa)
    <div>
      <label class="p-text">Nombre</label>
      <input type="text" wire:model.defer="nombre" class="input-minimal w-full" placeholder="Nombre de la empresa">
      @error('nombre') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
    </div>
    <div>
      <label class="p-text">Slogan</label>
      <input type="text" wire:model.defer="slogan" class="input-minimal w-full" placeholder="Slogan">
      @error('slogan') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
    </div>
    <div>
      <label class="p-text">Número de Contacto</label>
      <input type="text" wire:model.defer="nroContacto" class="input-minimal w-full" placeholder="Ej: +591 77777777">
      @error('nroContacto') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
    </div>
    <div>
      <label class="p-text">Facebook</label>
      <input type="text" wire:model.defer="facebook" class="input-minimal w-full" placeholder="URL de Facebook">
      @error('facebook') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- Instagram -->
    <div>
      <label class="p-text">Instagram</label>
      <input type="text" wire:model.defer="instagram" class="input-minimal w-full" placeholder="URL de Instagram">
      @error('instagram') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- TikTok -->
    <div>
      <label class="p-text">TikTok</label>
      <input type="text" wire:model.defer="tiktok" class="input-minimal w-full" placeholder="URL de TikTok">
      @error('tiktok') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- Misión -->
    <div class="col-span-full">
      <label class="p-text">Misión</label>
      <textarea wire:model.defer="mision" class="input-minimal w-full" rows="3" placeholder="Escribe la misión de la empresa"></textarea>
      @error('mision') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- Visión -->
    <div class="col-span-full">
      <label class="p-text">Visión</label>
      <textarea wire:model.defer="vision" class="input-minimal w-full" rows="3" placeholder="Escribe la visión de la empresa"></textarea>
      @error('vision') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- Botón Guardar -->
    <div class="col-span-full flex justify-center mt-6">
      <button wire:click="actualizarEmpresa"
        class="btn-circle btn-cyan hover:scale-105 transition-transform duration-200"
        title="Guardar cambios">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
          viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
          <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
          <path d="M14 4l0 4l-6 0l0 -4" />
        </svg>
      </button>
    </div>

    @else
    <p class="col-span-full text-center text-gray-600 py-4">
      No hay información de empresa registrada.
    </p>
    @endif

  </div>
</div>

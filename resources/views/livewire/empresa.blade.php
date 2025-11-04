<div class="p-4 mt-20 flex justify-center bg-white">
  <div class="w-full max-w-screen-xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <h3
      class="col-span-full text-center text-2xl font-bold uppercase text-teal-700 bg-teal-100 px-6 py-2 rounded-full mx-auto shadow-md">
      Información de la Empresa
    </h3>

    @if ($empresa)
      <div>
        <label class="text-sm font-semibold text-teal-700">Nombre</label>
        <input type="text" wire:model.defer="nombre" class="input-minimal w-full mt-1" placeholder="Nombre de la empresa">
        @error('nombre') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
      </div>

      <div>
        <label class="text-sm font-semibold text-teal-700">Slogan</label>
        <input type="text" wire:model.defer="slogan" class="input-minimal w-full mt-1" placeholder="Slogan">
        @error('slogan') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
      </div>

      <div>
        <label class="text-sm font-semibold text-teal-700">Número de Contacto</label>
        <input type="text" wire:model.defer="nroContacto" class="input-minimal w-full mt-1" placeholder="Ej: +591 77777777">
        @error('nroContacto') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
      </div>

      <div>
        <label class="text-sm font-semibold text-teal-700">Facebook</label>
        <input type="text" wire:model.defer="facebook" class="input-minimal w-full mt-1" placeholder="URL de Facebook">
        @error('facebook') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
      </div>

      <div>
        <label class="text-sm font-semibold text-teal-700">Instagram</label>
        <input type="text" wire:model.defer="instagram" class="input-minimal w-full mt-1" placeholder="URL de Instagram">
        @error('instagram') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
      </div>

      <div>
        <label class="text-sm font-semibold text-teal-700">TikTok</label>
        <input type="text" wire:model.defer="tiktok" class="input-minimal w-full mt-1" placeholder="URL de TikTok">
        @error('tiktok') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
      </div>

      <div class="col-span-full">
        <label class="text-sm font-semibold text-teal-700">Misión</label>
        <textarea wire:model.defer="mision" class="input-minimal w-full mt-1" rows="3"
          placeholder="Escribe la misión de la empresa"></textarea>
        @error('mision') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
      </div>

      <div class="col-span-full">
        <label class="text-sm font-semibold text-teal-700">Visión</label>
        <textarea wire:model.defer="vision" class="input-minimal w-full mt-1" rows="3"
          placeholder="Escribe la visión de la empresa"></textarea>
        @error('vision') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
      </div>

      <div class="col-span-full flex justify-center mt-6">
        <button wire:click="actualizarEmpresa"
          class="btn-cyan flex items-center gap-2 px-6 py-2 rounded-full font-semibold text-white shadow-md hover:scale-105 transition-transform duration-200"
          title="Guardar cambios">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
            viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path
              d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
            <path d="M14 4l0 4l-6 0l0 -4" />
          </svg>
          Guardar
        </button>
      </div>

    @else
      <p class="col-span-full text-center text-gray-600 py-4">
        No hay información de empresa registrada.
      </p>
    @endif
  </div>
</div>

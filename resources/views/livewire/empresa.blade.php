<div class="p-text p-2 mt-10 flex justify-center">
  <div class="w-full max-w-screen-md">
    <div class="relative flex w-full flex-col rounded-xl color-bg p-text shadow-md p-6">
      <h6 class="text-center text-xl font-bold text-gray-800 dark:text-white mb-4">Información de la Empresa</h6>

      @if ($empresa)
      <div class="grid grid-cols-2 gap-6">
        <div>
          <label class="p-text">Nombre</label>
          <input type="text" wire:model.defer="nombre" class="input1">
          @error('nombre') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
          <label class="p-text">Slogan</label>
          <input type="text" wire:model.defer="slogan" class="input1">
          @error('slogan') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
          <label class="p-text">Misión</label>
          <textarea wire:model.defer="mision" class="input1"></textarea>
          @error('mision') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
          <label class="p-text">Visión</label>
          <textarea wire:model.defer="vision" class="input1"></textarea>
          @error('vision') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
          <label class="p-text">Número de Contacto</label>
          <input type="text" wire:model.defer="nroContacto" class="input1">
          @error('nroContacto') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
          <label class="p-text">Facebook</label>
          <input type="text" wire:model.defer="facebook" class="input1">
          @error('facebook') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
          <label class="p-text">Instagram</label>
          <input type="text" wire:model.defer="instagram" class="input1">
          @error('instagram') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
          <label class="p-text">TikTok</label>
          <input type="text" wire:model.defer="tiktok" class="input1">
          @error('tiktok') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>
      </div>

      <div class="mt-6 flex justify-end">
        <button wire:click="actualizarEmpresa"
          class="text-indigo-500 hover:text-indigo-600 mx-1 transition-transform duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-full">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-device-floppy">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
            <path d="M14 4l0 4l-6 0l0 -4" />
          </svg>
        </button>
      </div>
      @else
      <p class="text-center text-gray-700 dark:text-gray-300">No hay información de empresa registrada.</p>
      @endif
    </div>
  </div>
</div>

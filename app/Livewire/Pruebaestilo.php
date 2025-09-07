<?php

namespace App\Livewire;

use Livewire\Component;

class Pruebaestilo extends Component
{
    public $modal = false;
    public $direccion = '';

    public function abrirModal()
    {
        $this->modal = true;
    }

    public function cerrarModal()
    {
        $this->modal = false;
    }

    public function buscarDireccion()
    {
        // Dispara un evento de navegador para buscar la dirección.
        // (En Livewire 3 se usa dispatch en lugar de dispatchBrowserEvent)
        $this->dispatch('buscarDireccion', direccion: $this->direccion);
    }

    public function guardarDireccion()
    {
        // Aquí podrías guardar la dirección en la base de datos.
        session()->flash('mensaje', 'Dirección guardada: ' . $this->direccion);
        $this->cerrarModal();
    }

    public function render()
    {
        return view('livewire.pruebaestilo');
    }
}

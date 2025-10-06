<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente;

class Pruebaestilo extends Component
{
    public $usuario;
    public $cliente;

    public function mount()
    {
        // Obtener el usuario logueado
        $this->usuario = Auth::user();

        // Obtener el cliente asociado al usuario (si existe)
        $this->cliente = $this->usuario && $this->usuario->cliente ? $this->usuario->cliente : null;
    }

    public function render()
    {
        return view('livewire.pruebaestilo');
    }
}

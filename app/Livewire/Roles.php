<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Rol;

class Roles extends Component
{
    public $search = '';

    public function render()
    {
        $roles = Rol::query()
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%');
            })
            ->get(); // Traemos todos, sin paginación

        return view('livewire.roles', compact('roles'));
    }
}

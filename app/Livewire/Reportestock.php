<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asignado;
use App\Models\Reposicion;

class Reportestock extends Component
{
    public $asignados;
    public $reposicions;

    public function render()
    {
        // Trae todos los registros de la tabla 'asignados'
        $this->asignados = Asignado::all();

        // Trae todos los registros de la tabla 'reposicions'
        $this->reposicions = Reposicion::all();

        return view('livewire.reportestock', [
            'asignados' => $this->asignados,
            'reposicions' => $this->reposicions,
        ]);
    }
}

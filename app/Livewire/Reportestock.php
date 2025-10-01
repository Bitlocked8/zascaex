<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asignado;
use App\Models\Reposicion;

class Reportestock extends Component
{
    public $asignados;
    public $reposicions;
    public $tablaActiva = 'asignados'; // 'asignados' o 'reposicions'

    public function mount()
    {
        $this->asignados = Asignado::all();
        $this->reposicions = Reposicion::all();
    }

    public function mostrarTabla($tabla)
    {
        $this->tablaActiva = $tabla;
    }

    public function render()
    {
        return view('livewire.reportestock', [
            'asignados' => $this->asignados,
            'reposicions' => $this->reposicions,
            'tablaActiva' => $this->tablaActiva,
        ]);
    }
}

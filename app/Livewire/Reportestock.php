<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asignado;
use App\Models\Reposicion;

class Reportestock extends Component
{
    public $asignados;
    public $reposicions;
    public $tablaActiva = null;
    public function mount()
    {
        $this->asignados = Asignado::with('reposiciones')->get();
        $this->reposicions = Reposicion::with('comprobantes', 'personal', 'proveedor', 'asignados')->get();
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

    public function deseleccionarTabla()
    {
        $this->tablaActiva = null;
    }
}

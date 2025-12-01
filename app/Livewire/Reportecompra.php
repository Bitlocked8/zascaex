<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asignado;

class Reportecompra extends Component
{
    public function render()
    {
        // Obtener asignaciones con relaciones
        $asignaciones = Asignado::with([
            'personal',
            'reposiciones',
            'soplados',
            'llenados',
            'traspasos',
            'asignadoReposicions.reposicion.existencia.sucursal',
        ])->get();

        // Calcular la merma para cada soplado
        foreach ($asignaciones as $asignacion) {
            foreach ($asignacion->soplados as $soplado) {

                // cantidad original según la reposición asignada
                $original = $asignacion->asignadoReposicions
                    ->where('reposicion_id', $soplado->reposicion_id)
                    ->value('cantidad_original');

                // calcular merma real
                $soplado->merma_calculada = max(0, ($original - $soplado->cantidad));
            }
        }

        return view('livewire.reportecompra', [
            'asignaciones' => $asignaciones
        ]);
    }
}

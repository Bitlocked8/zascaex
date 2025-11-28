<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Reposicion;
use App\Models\Personal;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteStock extends Component
{
    public $personal_id = '';
    public $search = '';
    public $search_desc = '';
    public $ocultar_monto = false;
    public $ocultar_cantidad = false;

    public $fecha_inicio_dia;
    public $fecha_inicio_mes;
    public $fecha_inicio_ano;
    public $fecha_inicio_hora;
    public $fecha_inicio_min;

    public $fecha_fin_dia;
    public $fecha_fin_mes;
    public $fecha_fin_ano;
    public $fecha_fin_hora;
    public $fecha_fin_min;

    public function toggleCantidad()
    {
        $this->ocultar_cantidad = !$this->ocultar_cantidad;
    }

    public function toggleMonto()
    {
        $this->ocultar_monto = !$this->ocultar_monto;
    }

    public function render()
    {
        $reposiciones = $this->obtenerReposicionesFiltradas();

        $existencias = $reposiciones->flatMap(function ($repo) {
            $lista = [];
            if ($repo->existencia?->existenciable->descripcion) {
                $lista[] = $repo->existencia->existenciable->descripcion;
            }
            foreach ($repo->asignados as $asignado) {
                foreach ($asignado->asignadoReposicions as $detalle) {
                    if ($detalle->existencia?->existenciable->descripcion) {
                        $lista[] = $detalle->existencia->existenciable->descripcion;
                    }
                }
            }
            return $lista;
        })->unique()->values();

        return view('livewire.reportestock', [
            'reposiciones' => $reposiciones,
            'personales' => Personal::orderBy('nombres')->get(),
            'existencias' => $existencias,
            'ocultar_monto' => $this->ocultar_monto,
            'ocultar_cantidad' => $this->ocultar_cantidad,
            'personal_id' => $this->personal_id, // <-- agregada
        ]);
    }

    private function obtenerReposicionesFiltradas()
    {
        $reposiciones = Reposicion::with([
            'proveedor',
            'personal',
            'comprobantes',
            'asignados',
            'asignados.personal',
            'asignados.asignadoReposicions',
            'asignados.asignadoReposicions.existencia',
            'asignados.asignadoReposicions.existencia.existenciable',
        ])->get();

        // Filtro por búsqueda de código
        if ($this->search) {
            $q = strtolower($this->search);
            $reposiciones = $reposiciones->filter(function ($repo) use ($q) {
                $participaRepo = str_contains(strtolower($repo->codigo), $q);
                $participaAsignado = $repo->asignados->contains(fn($as) => str_contains(strtolower($as->codigo), $q));
                return $participaRepo || $participaAsignado;
            })->values();
        }

        // Filtro por descripción
        if ($this->search_desc) {
            $q_desc = strtolower($this->search_desc);
            $reposiciones = $reposiciones->filter(function ($repo) use ($q_desc) {
                $participaRepoDesc = $repo->existencia?->existenciable->descripcion &&
                    str_contains(strtolower($repo->existencia->existenciable->descripcion), $q_desc);

                $participaAsignadoDesc = $repo->asignados->contains(function ($as) use ($q_desc) {
                    $detalle = $as->asignadoReposicions->first();
                    return $detalle && $detalle->existencia?->existenciable->descripcion &&
                        str_contains(strtolower($detalle->existencia->existenciable->descripcion), $q_desc);
                });

                return $participaRepoDesc || $participaAsignadoDesc;
            })->values();
        }

        // Filtro por personal
        if ($this->personal_id) {
            $reposiciones = $reposiciones->map(function ($repo) {
                $repoCopy = clone $repo;
                $repoCopy->asignados = $repo->asignados
                    ->filter(fn($as) => $as->personal_id == $this->personal_id)
                    ->values();
                if ($repo->personal_id == $this->personal_id || $repoCopy->asignados->count() > 0) {
                    return $repoCopy;
                }
                return null;
            })->filter()->values();
        }

        // Filtro por rango de fechas
        $fecha_inicio = $this->crearFecha(
            $this->fecha_inicio_ano,
            $this->fecha_inicio_mes,
            $this->fecha_inicio_dia,
            $this->fecha_inicio_hora,
            $this->fecha_inicio_min
        );

        $fecha_fin = $this->crearFecha(
            $this->fecha_fin_ano,
            $this->fecha_fin_mes,
            $this->fecha_fin_dia,
            $this->fecha_fin_hora,
            $this->fecha_fin_min
        );

        if ($fecha_inicio) {
            $reposiciones = $reposiciones->filter(fn($repo) => Carbon::parse($repo->fecha) >= $fecha_inicio)->values();
        }
        if ($fecha_fin) {
            $reposiciones = $reposiciones->filter(fn($repo) => Carbon::parse($repo->fecha) <= $fecha_fin)->values();
        }

        return $reposiciones;
    }

    private function crearFecha($ano, $mes, $dia, $hora, $min)
    {
        if ($ano && $mes && $dia && $hora !== null && $min !== null) {
            try {
                return Carbon::create($ano, $mes, $dia, $hora, $min);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    public function descargarPDF()
{
    $reposiciones = $this->obtenerReposicionesFiltradas();

    $existencias = $reposiciones->flatMap(function ($repo) {
        $lista = [];
        if ($repo->existencia?->existenciable->descripcion) {
            $lista[] = $repo->existencia->existenciable->descripcion;
        }
        foreach ($repo->asignados as $asignado) {
            foreach ($asignado->asignadoReposicions as $detalle) {
                if ($detalle->existencia?->existenciable->descripcion) {
                    $lista[] = $detalle->existencia->existenciable->descripcion;
                }
            }
        }
        return $lista;
    })->unique()->values();

    $pdf = Pdf::loadView('pdf.reportestock', [
        'reposiciones' => $reposiciones,
        'personales' => Personal::orderBy('nombres')->get(),
        'existencias' => $existencias,
        'ocultar_monto' => $this->ocultar_monto,
        'ocultar_cantidad' => $this->ocultar_cantidad,
        'personal_id' => $this->personal_id, // <<--- importante
    ])->setPaper('letter', 'landscape');

    return response()->streamDownload(
        fn() => print($pdf->output()),
        'reporte_stock.pdf'
    );
}

}

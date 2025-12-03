<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Reposicion;
use App\Models\Personal;
use App\Models\Existencia;
use App\Models\Sucursal;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
class ReporteStock extends Component
{
    public $personal_id = '';
    public $existencia_id = '';
    public $sucursal_id = '';
    public $search = '';
    public $search_desc = '';
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
    public $ocultar_cantidad = false;
    public $ocultar_monto = false;

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
        $fecha_inicio = null;
        $fecha_fin = null;

        if ($this->fecha_inicio_ano && $this->fecha_inicio_mes && $this->fecha_inicio_dia) {
            $fecha_inicio = Carbon::create(
                $this->fecha_inicio_ano,
                $this->fecha_inicio_mes,
                $this->fecha_inicio_dia,
                $this->fecha_inicio_hora ?? 0,
                $this->fecha_inicio_min ?? 0,
                0
            );
        }

        if ($this->fecha_fin_ano && $this->fecha_fin_mes && $this->fecha_fin_dia) {
            $fecha_fin = Carbon::create(
                $this->fecha_fin_ano,
                $this->fecha_fin_mes,
                $this->fecha_fin_dia,
                $this->fecha_fin_hora ?? 23,
                $this->fecha_fin_min ?? 59,
                59
            );
        }

        $reposiciones = Reposicion::with([
            'existencia',
            'existencia.existenciable',
            'existencia.sucursal',
            'personal',
            'asignadoReposicions.asignado.personal',
            'asignadoReposicions.existencia',
            'comprobantePagos',
        ])
            ->when($this->personal_id, fn($query) => $query->where('personal_id', $this->personal_id))
            ->when($this->existencia_id, fn($query) => $query->where('existencia_id', $this->existencia_id))
            ->when($this->sucursal_id, fn($query) => $query->whereHas(
                'existencia',
                fn($q) =>
                $q->where('sucursal_id', $this->sucursal_id)
            ))
            ->when($this->search, fn($query) => $query->where('codigo', 'like', "%{$this->search}%"))
            ->when(
                $this->search_desc,
                fn($query) =>
                $query->whereHas(
                    'existencia.existenciable',
                    fn($q) =>
                    $q->where('descripcion', 'like', "%{$this->search_desc}%")
                )
            )
            ->when($fecha_inicio, fn($query) => $query->where('fecha', '>=', $fecha_inicio))
            ->when($fecha_fin, fn($query) => $query->where('fecha', '<=', $fecha_fin))
            ->orderBy('fecha', 'desc')
            ->get();

        $personales = Personal::orderBy('nombres')->get();
        $existencias = Existencia::whereHas('reposiciones')->with('existenciable')->orderBy('id')->get();
        $sucursales = Sucursal::orderBy('nombre')->get();

        return view('livewire.reportestock', [
            'reposiciones' => $reposiciones,
            'personales' => $personales,
            'existencias' => $existencias,
            'sucursales' => $sucursales,
        ]);
    }
    public function descargarPDF()
    {
        $fecha_inicio = null;
        $fecha_fin = null;

        if ($this->fecha_inicio_ano && $this->fecha_inicio_mes && $this->fecha_inicio_dia) {
            $fecha_inicio = Carbon::create(
                $this->fecha_inicio_ano,
                $this->fecha_inicio_mes,
                $this->fecha_inicio_dia,
                $this->fecha_inicio_hora ?? 0,
                $this->fecha_inicio_min ?? 0,
                0
            );
        }

        if ($this->fecha_fin_ano && $this->fecha_fin_mes && $this->fecha_fin_dia) {
            $fecha_fin = Carbon::create(
                $this->fecha_fin_ano,
                $this->fecha_fin_mes,
                $this->fecha_fin_dia,
                $this->fecha_fin_hora ?? 23,
                $this->fecha_fin_min ?? 59,
                59
            );
        }

        $reposiciones = Reposicion::with([
            'existencia',
            'existencia.existenciable',
            'personal',
            'asignadoReposicions.asignado.personal',
            'asignadoReposicions.existencia',
            'comprobantePagos',
            'existencia.sucursal',
        ])
            ->when($this->personal_id, fn($q) => $q->where('personal_id', $this->personal_id))
            ->when($this->existencia_id, fn($q) => $q->where('existencia_id', $this->existencia_id))
            ->when($this->sucursal_id, fn($q) => $q->whereHas('existencia', fn($q2) => $q2->where('sucursal_id', $this->sucursal_id)))
            ->when($this->search, fn($q) => $q->where('codigo', 'like', "%{$this->search}%"))
            ->when(
                $this->search_desc,
                fn($q) =>
                $q->whereHas(
                    'existencia.existenciable',
                    fn($q2) =>
                    $q2->where('descripcion', 'like', "%{$this->search_desc}%")
                )
            )
            ->when($fecha_inicio, fn($q) => $q->where('fecha', '>=', $fecha_inicio))
            ->when($fecha_fin, fn($q) => $q->where('fecha', '<=', $fecha_fin))
            ->orderBy('fecha', 'desc')
            ->get();

        $personales = $this->personal_id ? [Personal::find($this->personal_id)] : Personal::all();
        $sucursal_nombre = $this->sucursal_id ? Sucursal::find($this->sucursal_id)?->nombre : 'Todas las sucursales';
        $existencia_nombre = $this->existencia_id ? Existencia::find($this->existencia_id)?->descripcion : 'Todas las existencias';

        $pdf = Pdf::loadView('pdf.reportestock', [
            'reposiciones' => $reposiciones,
            'ocultar_cantidad' => $this->ocultar_cantidad,
            'ocultar_monto' => $this->ocultar_monto,
            'personal_nombre' => $this->personal_id ? Personal::find($this->personal_id)?->nombres : 'Todo el personal',
            'sucursal_nombre' => $sucursal_nombre,
            'existencia_nombre' => $existencia_nombre,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
        ]);

        $filename = 'Reporte_Stock_' . now()->format('Ymd_His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Distribucion;
use App\Models\Coche;
use App\Models\Personal;
use App\Models\Sucursal;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class Reportecredito extends Component
{
    public $codigo = '';
    public $coche_id = '';
    public $personal_id = '';
    public $sucursal_id = '';
    public $estado = '';

    // Filtros de fecha
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

    public function filtrar()
    {
        $query = Distribucion::with(['personal.trabajos', 'coche', 'pedidos.detalles.existencia'])
            ->when($this->codigo, fn($q) => $q->where('codigo', 'like', "%$this->codigo%"))
            ->when($this->coche_id, fn($q) => $q->where('coche_id', $this->coche_id))
            ->when($this->personal_id, fn($q) => $q->where('personal_id', $this->personal_id))
            ->when($this->estado !== '', fn($q) => $q->where('estado', $this->estado))
            ->when($this->sucursal_id, function ($q) {
                $q->whereHas('personal.trabajos', function ($query) {
                    $query->where('sucursal_id', $this->sucursal_id)
                        ->where('estado', 1);
                });
            });

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

        if ($fecha_inicio)
            $query->where('fecha_asignacion', '>=', $fecha_inicio);
        if ($fecha_fin)
            $query->where('fecha_asignacion', '<=', $fecha_fin);

        return $query->orderBy('fecha_asignacion', 'desc')->get();
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
        $distribuciones = $this->filtrar();

        $pdf = Pdf::loadView('pdf.reportecredito', [
            'distribuciones' => $distribuciones,
            'codigo' => $this->codigo,
            'coche_id' => $this->coche_id,
            'personal_id' => $this->personal_id,
            'sucursal_id' => $this->sucursal_id,
            'estado' => $this->estado,
            'fecha_inicio' => $this->crearFecha($this->fecha_inicio_ano, $this->fecha_inicio_mes, $this->fecha_inicio_dia, $this->fecha_inicio_hora, $this->fecha_inicio_min),
            'fecha_fin' => $this->crearFecha($this->fecha_fin_ano, $this->fecha_fin_mes, $this->fecha_fin_dia, $this->fecha_fin_hora, $this->fecha_fin_min),
        ]);

        $filename = 'reporte-distribucion_' . now()->format('Ymd_His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function render()
    {
        return view('livewire.reportecredito', [
            'distribuciones' => $this->filtrar(),
            'coches' => Coche::all(),
            'personales' => Personal::all(),
            'sucursales' => Sucursal::all(),
        ]);
    }
}

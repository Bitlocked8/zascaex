<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asignado;
use App\Models\Personal;
use App\Models\Sucursal;
use Carbon\Carbon;

class Reportecompra extends Component
{
    public $fecha_inicio_dia, $fecha_inicio_mes, $fecha_inicio_ano, $fecha_inicio_hora, $fecha_inicio_min;
    public $fecha_fin_dia, $fecha_fin_mes, $fecha_fin_ano, $fecha_fin_hora, $fecha_fin_min;

    public $sucursal_id;
    public $personal_id;
    public $codigo;
    public $tipo = '';

    public $mostrarCantidades = true;
    public $mostrarMontos = true;
    public $mostrarMermas = false;

    public $errorFecha = null;

    public function render()
    {
        $this->errorFecha = null; // Limpiamos mensaje al render

        // Validar fecha de inicio
        if ($this->fecha_inicio_dia && $this->fecha_inicio_mes && $this->fecha_inicio_ano) {
            if (!checkdate($this->fecha_inicio_mes, $this->fecha_inicio_dia, $this->fecha_inicio_ano)
                || ($this->fecha_inicio_hora !== null && ($this->fecha_inicio_hora < 0 || $this->fecha_inicio_hora > 23))
                || ($this->fecha_inicio_min !== null && ($this->fecha_inicio_min < 0 || $this->fecha_inicio_min > 59))
            ) {
                $this->errorFecha = 'Fecha inicio inválida';
                $this->fecha_inicio = null;
            } else {
                $this->fecha_inicio = Carbon::create(
                    $this->fecha_inicio_ano,
                    $this->fecha_inicio_mes,
                    $this->fecha_inicio_dia,
                    $this->fecha_inicio_hora ?? 0,
                    $this->fecha_inicio_min ?? 0
                );
            }
        } else {
            $this->fecha_inicio = null;
        }

        // Validar fecha fin
        if ($this->fecha_fin_dia && $this->fecha_fin_mes && $this->fecha_fin_ano) {
            if (!checkdate($this->fecha_fin_mes, $this->fecha_fin_dia, $this->fecha_fin_ano)
                || ($this->fecha_fin_hora !== null && ($this->fecha_fin_hora < 0 || $this->fecha_fin_hora > 23))
                || ($this->fecha_fin_min !== null && ($this->fecha_fin_min < 0 || $this->fecha_fin_min > 59))
            ) {
                $this->errorFecha = 'Fecha fin inválida';
                $this->fecha_fin = null;
            } else {
                $this->fecha_fin = Carbon::create(
                    $this->fecha_fin_ano,
                    $this->fecha_fin_mes,
                    $this->fecha_fin_dia,
                    $this->fecha_fin_hora ?? 23,
                    $this->fecha_fin_min ?? 59
                );
            }
        } else {
            $this->fecha_fin = null;
        }

        $query = Asignado::with([
            'personal',
            'reposiciones',
            'soplados',
            'llenados',
            'traspasos',
            'asignadoReposicions.reposicion.existencia.sucursal',
        ]);

        // Solo ejecutar filtros si no hay error de fecha
        if (!$this->errorFecha) {
            if ($this->fecha_inicio) {
                $query->where('created_at', '>=', $this->fecha_inicio);
            }
            if ($this->fecha_fin) {
                $query->where('created_at', '<=', $this->fecha_fin);
            }

            $query->when($this->sucursal_id, function ($q) {
                $q->whereHas('asignadoReposicions.existencia.sucursal', function ($s) {
                    $s->where('id', $this->sucursal_id);
                });
            });

            $query->when($this->personal_id, fn($q) => $q->where('personal_id', $this->personal_id));
            $query->when($this->codigo, fn($q) => $q->where('codigo', 'LIKE', "%{$this->codigo}%"));
            if ($this->tipo) {
                $query->whereHas($this->tipo);
            }
        }

        $asignaciones = $query->get();

        // Calcular merma de soplados
        foreach ($asignaciones as $asignacion) {
            foreach ($asignacion->soplados as $soplado) {
                $original = $asignacion->asignadoReposicions
                    ->where('reposicion_id', $soplado->reposicion_id)
                    ->value('cantidad_original');
                $soplado->merma_calculada = max(0, ($original - $soplado->cantidad));
            }
        }

        return view('livewire.reportecompra', [
            'asignaciones' => $asignaciones,
            'personales' => Personal::all(),
            'sucursales' => Sucursal::all(),
            'errorFecha' => $this->errorFecha, // Pasamos mensaje al view
        ]);
    }

    public function descargarPDF()
    {
        // Tu lógica para generar PDF
    }
}

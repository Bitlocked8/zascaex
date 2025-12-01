<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asignado;
use App\Models\Personal;
use App\Models\Sucursal;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class Reportecompra extends Component
{
    public $fecha_inicio_dia, $fecha_inicio_mes, $fecha_inicio_ano, $fecha_inicio_hora, $fecha_inicio_min;
    public $fecha_fin_dia, $fecha_fin_mes, $fecha_fin_ano, $fecha_fin_hora, $fecha_fin_min;

    public $sucursal_id;
    public $personal_id;
    public $codigo;
    public $tipo = '';
    public $fecha_inicio;
    public $fecha_fin;
    public $mostrarCantidades = true;
    public $mostrarMontos = true;
    public $mostrarMermas = false;

    public $errorFecha = null;

    public function render()
    {
        $this->calcularFechas();

        $asignaciones = $this->obtenerAsignaciones();

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
            'errorFecha' => $this->errorFecha,
        ]);
    }

    private function calcularFechas()
    {
        $this->errorFecha = null;

        // Fecha inicio
        if ($this->fecha_inicio_dia && $this->fecha_inicio_mes && $this->fecha_inicio_ano) {
            if (
                !checkdate($this->fecha_inicio_mes, $this->fecha_inicio_dia, $this->fecha_inicio_ano)
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

        // Fecha fin
        if ($this->fecha_fin_dia && $this->fecha_fin_mes && $this->fecha_fin_ano) {
            if (
                !checkdate($this->fecha_fin_mes, $this->fecha_fin_dia, $this->fecha_fin_ano)
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
    }

    private function obtenerAsignaciones()
    {
        $query = Asignado::with([
            'personal',
            'reposiciones',
            'soplados',
            'llenados',
            'traspasos',
            'asignadoReposicions.reposicion.existencia.sucursal',
        ]);

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

        return $query->get();
    }

    public function descargarPDF()
    {
        $asignaciones = $this->obtenerAsignaciones();

        $cantidadTotal = 0;
        $montoTotal = 0;
        $reposicionesUnicas = [];
        $productosCount = [];

        foreach ($asignaciones->flatMap->asignadoReposicions as $detalle) {
            $reposicion = $detalle->reposicion;
            $montoReposicion = $reposicion?->comprobantes->sum('monto') ?? 0;
            $cantidadInicial = $reposicion?->cantidad_inicial ?? 1;
            $precioUnitario = $cantidadInicial > 0 ? $montoReposicion / $cantidadInicial : 0;

            $cantidadDetalle = $detalle->cantidad_original;
            $montoDetalle = $cantidadDetalle * $precioUnitario;

            $cantidadTotal += $cantidadDetalle;
            $montoTotal += $montoDetalle;

            $reposicionId = $reposicion?->id ?? 0;
            if ($reposicionId) {
                $reposicionesUnicas[$reposicionId] = true;
            }

            $item = $detalle->existencia?->existenciable ?? $reposicion;
            if ($item && isset($item->descripcion)) {
                $productoKey = $item->descripcion;
                $productosCount[$productoKey] = ($productosCount[$productoKey] ?? 0) + $cantidadDetalle;
            }
        }

        $cantidadReposicionesUnicas = count($reposicionesUnicas);
        $cantidadProductosDiferentes = count($productosCount);

        $pdf = Pdf::loadView('pdf.reportecompra', [
            'asignaciones' => $asignaciones,
            'personales' => Personal::all(),
            'sucursales' => Sucursal::all(),
            'mostrarCantidades' => $this->mostrarCantidades,
            'mostrarMontos' => $this->mostrarMontos,
            'mostrarMermas' => $this->mostrarMermas,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'codigo' => $this->codigo,
            'tipo' => $this->tipo,
            'sucursal_id' => $this->sucursal_id,
            'personal_id' => $this->personal_id,
            'cantidadTotal' => $cantidadTotal,
            'montoTotal' => $montoTotal,
            'cantidadReposicionesUnicas' => $cantidadReposicionesUnicas,
            'cantidadProductosDiferentes' => $cantidadProductosDiferentes,
            'productosCount' => $productosCount,
        ]);

        $filename = 'reporte-compra_' . now()->format('Ymd_His') . '.pdf';

        // 🔹 USAR STREAM DOWNLOAD PARA EVITAR PROBLEMAS DE UTF-8
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }


}

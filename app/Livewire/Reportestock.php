<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Reposicion;
use App\Models\Asignado;

class ReporteStock extends Component
{
    public function render()
    {
        $reposiciones = Reposicion::with([
            'personal',
            'proveedor',
            'existencia.sucursal',
            'existencia.existenciable',
            'comprobantes'
        ])->orderBy('fecha')->get();

        $stock = [];
        $movimientos = collect();

        foreach ($reposiciones as $r) {
            $monto_total = $r->comprobantes->sum('monto');
            $precio_unitario = $r->cantidad_inicial > 0 ? $monto_total / $r->cantidad_inicial : 0;

            $stock[] = [
                'reposicion_id' => $r->id,
                'fecha' => $r->fecha,
                'restante' => $r->cantidad,
                'precio_unitario' => $precio_unitario,
            ];

            $movimientos->push([
                'tipo' => 'Entrada',
                'codigo' => $r->codigo,
                'fecha' => $r->fecha,
                'cantidad_inicial' => $r->cantidad_inicial,
                'cantidad' => $r->cantidad,
                'nombre' => $r->existencia->existenciable->descripcion ?? '-',
                'existencia_type' => class_basename($r->existencia->existenciable ?? '-'), 
                'personal' => $r->personal->nombres ?? 'Sin personal',
                'proveedor' => $r->proveedor->nombre ?? 'Sin proveedor',
                'sucursal' => $r->existencia->sucursal->nombre ?? 'Sin sucursal',
                'monto_total' => $monto_total,
                'precio_unitario' => $precio_unitario,
                'es_asignacion' => false,
            ]);
        }

        $asignaciones = Asignado::with([
            'personal',
            'existencia.sucursal',
            'existencia.existenciable'
        ])->orderBy('fecha')->get();

        foreach ($asignaciones as $a) {
            $cantidad_pendiente = $a->cantidad;
            $total_costo = 0;

            foreach ($stock as &$lote) {
                if ($cantidad_pendiente <= 0)
                    break;
                if ($lote['restante'] <= 0)
                    continue;

                $usar = min($cantidad_pendiente, $lote['restante']);
                $total_costo += $usar * $lote['precio_unitario'];
                $lote['restante'] -= $usar;
                $cantidad_pendiente -= $usar;
            }

            $precio_unitario = $a->cantidad > 0 ? $total_costo / $a->cantidad : 0;

            $movimientos->push([
                'tipo' => 'Salida',
                'codigo' => $a->codigo,
                'fecha' => $a->fecha,
                'cantidad_inicial' => $a->cantidad_original,
                'cantidad' => $a->cantidad,
                'nombre' => $a->existencia->existenciable->descripcion ?? '-',
                'existencia_type' => class_basename($a->existencia->existenciable ?? '-'), // <-- aquí
                'personal' => $a->personal->nombres ?? 'Sin personal',
                'proveedor' => '-',
                'sucursal' => $a->existencia->sucursal->nombre ?? 'Sin sucursal',
                'monto_total' => $total_costo,
                'precio_unitario' => $precio_unitario,
                'es_asignacion' => true,
            ]);
        }

        $movimientos = $movimientos->sortBy('fecha')->values();

        return view('livewire.reportestock', compact('movimientos'));
    }
}

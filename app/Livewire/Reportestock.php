<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Reposicion;
use App\Models\Asignado;
use App\Models\Llenado;

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
                'compatibilidad' => $r->existencia->existenciable->compatibilidad ?? null,
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
            'asignadoReposicions.reposicion.proveedor',
            'asignadoReposicions.existencia.sucursal',
            'asignadoReposicions.existencia.existenciable',
        ])->orderBy('fecha')->get();

        foreach ($asignaciones as $a) {
            foreach ($a->asignadoReposicions as $ar) {
                $cantidad_pendiente = $ar->cantidad;
                $total_costo = 0;

                foreach ($stock as &$lote) {
                    if ($cantidad_pendiente <= 0) break;
                    if ($lote['restante'] <= 0) continue;

                    $usar = min($cantidad_pendiente, $lote['restante']);
                    $total_costo += $usar * $lote['precio_unitario'];
                    $lote['restante'] -= $usar;
                    $cantidad_pendiente -= $usar;
                }

                $precio_unitario = $ar->cantidad > 0 ? $total_costo / $ar->cantidad : 0;
                $existencia = $ar->existencia;

                $movimientos->push([
                    'tipo' => 'Salida',
                    'codigo' => $a->codigo,
                    'fecha' => $a->fecha,
                    'cantidad_inicial' => $ar->cantidad_original ?? $ar->cantidad,
                    'cantidad' => $ar->cantidad,
                    'nombre' => $existencia->existenciable->descripcion ?? '-',
                    'compatibilidad' => $existencia->existenciable->compatibilidad ?? null,
                    'existencia_type' => class_basename($existencia->existenciable ?? '-'),
                    'personal' => $a->personal->nombres ?? 'Sin personal',
                    'proveedor' => $ar->reposicion->proveedor->nombre ?? '-',
                    'sucursal' => $existencia->sucursal->nombre ?? 'Sin sucursal',
                    'monto_total' => $total_costo,
                    'precio_unitario' => $precio_unitario,
                    'es_asignacion' => true,
                ]);
            }
        }

        $llenados = Llenado::with([
            'personal',
            'existencia.sucursal',
            'existencia.existenciable',
            'reposicion.proveedor',
        ])->orderBy('fecha')->get();

        foreach ($llenados as $l) {
            $movimientos->push([
                'tipo' => 'Proceso',
                'codigo' => $l->codigo,
                'fecha' => $l->fecha,
                'cantidad_inicial' => $l->cantidad,
                'cantidad' => $l->cantidad,
                'nombre' => $l->existencia->existenciable->descripcion ?? '-',
                'compatibilidad' => $l->existencia->existenciable->compatibilidad ?? null,
                'existencia_type' => class_basename($l->existencia->existenciable ?? '-'),
                'personal' => $l->personal->nombres ?? 'Sin personal',
                'proveedor' => $l->reposicion->proveedor->nombre ?? '-',
                'sucursal' => $l->existencia->sucursal->nombre ?? 'Sin sucursal',
                'monto_total' => 0,
                'precio_unitario' => 0,
                'es_asignacion' => false,
            ]);
        }

        $movimientos = $movimientos->sortBy('fecha')->values();

        return view('livewire.reportestock', compact('movimientos'));
    }
}

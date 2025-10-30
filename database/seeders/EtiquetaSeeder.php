<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Etiqueta;
use App\Models\Existencia;

class EtiquetaSeeder extends Seeder
{
    public function run(): void
    {
        $etiquetas = [
            // tipo 1 = transparente, tipo 2 = brillante
            ['descripcion' => 'normal', 'capacidad' => '250', 'unidad' => 'ml', 'tipo' => 1],
            ['descripcion' => 'normal', 'capacidad' => '330', 'unidad' => 'ml', 'tipo' => 1],
            ['descripcion' => 'normal', 'capacidad' => '400', 'unidad' => 'ml', 'tipo' => 1],
            ['descripcion' => 'normal', 'capacidad' => '500', 'unidad' => 'ml', 'tipo' => 1],
            ['descripcion' => 'con gas', 'capacidad' => '530', 'unidad' => 'ml', 'tipo' => 1],
            ['descripcion' => 'normal', 'capacidad' => '600', 'unidad' => 'ml', 'tipo' => 1],
            ['descripcion' => 'alcalina', 'capacidad' => '600', 'unidad' => 'ml', 'tipo' => 1],
            ['descripcion' => 'bicentenario normal', 'capacidad' => '600', 'unidad' => 'ml', 'tipo' => 1],
            ['descripcion' => 'bicentenario alcalina', 'capacidad' => '600', 'unidad' => 'ml', 'tipo' => 1],
            ['descripcion' => 'normal', 'capacidad' => '750', 'unidad' => 'ml', 'tipo' => 1],
            ['descripcion' => 'alcalina', 'capacidad' => '750', 'unidad' => 'ml', 'tipo' => 1],
            ['descripcion' => 'normal', 'capacidad' => '1', 'unidad' => 'l', 'tipo' => 1],
            ['descripcion' => 'alcalina', 'capacidad' => '1', 'unidad' => 'l', 'tipo' => 1],
            ['descripcion' => 'normal', 'capacidad' => '1.5', 'unidad' => 'l', 'tipo' => 1],
            ['descripcion' => 'alcalina', 'capacidad' => '1.5', 'unidad' => 'l', 'tipo' => 1],
            ['descripcion' => 'dispenser', 'capacidad' => '20', 'unidad' => 'l', 'tipo' => 2],
            ['descripcion' => 'normal', 'capacidad' => '250', 'unidad' => 'ml', 'tipo' => 2],
            ['descripcion' => 'normal', 'capacidad' => '330', 'unidad' => 'ml', 'tipo' => 2],
            ['descripcion' => 'normal', 'capacidad' => '400', 'unidad' => 'ml', 'tipo' => 2],
            ['descripcion' => 'normal', 'capacidad' => '500', 'unidad' => 'ml', 'tipo' => 2],
            ['descripcion' => 'con gas', 'capacidad' => '530', 'unidad' => 'ml', 'tipo' => 2],
            ['descripcion' => 'normal', 'capacidad' => '600', 'unidad' => 'ml', 'tipo' => 2],
            ['descripcion' => 'alcalina', 'capacidad' => '600', 'unidad' => 'ml', 'tipo' => 2],
            ['descripcion' => 'bicentenario normal', 'capacidad' => '600', 'unidad' => 'ml', 'tipo' => 2],
            ['descripcion' => 'bicentenario alcalina', 'capacidad' => '600', 'unidad' => 'ml', 'tipo' => 2],
            ['descripcion' => 'normal', 'capacidad' => '750', 'unidad' => 'ml', 'tipo' => 2],
            ['descripcion' => 'alcalina', 'capacidad' => '750', 'unidad' => 'ml', 'tipo' => 2],
            ['descripcion' => 'normal', 'capacidad' => '1', 'unidad' => 'l', 'tipo' => 2],
            ['descripcion' => 'alcalina', 'capacidad' => '1', 'unidad' => 'l', 'tipo' => 2],
            ['descripcion' => 'normal', 'capacidad' => '1.5', 'unidad' => 'l', 'tipo' => 2],
            ['descripcion' => 'alcalina', 'capacidad' => '1.5', 'unidad' => 'l', 'tipo' => 2],
        ];

        foreach ($etiquetas as $data) {
            foreach ([1, 2] as $sucursal_id) {
                $etiqueta = Etiqueta::create([
                    'imagen' => null,
                    'descripcion' => $data['descripcion'],
                    'capacidad' => $data['capacidad'],
                    'unidad' => $data['unidad'],
                    'estado' => 1,
                    'tipo' => $data['tipo'],
                    'cliente_id' => null,
                ]);

                Existencia::create([
                    'existenciable_type' => Etiqueta::class,
                    'existenciable_id' => $etiqueta->id,
                    'cantidad' => 0,
                    'cantidadMinima' => 0,
                    'sucursal_id' => $sucursal_id,
                ]);
            }
        }
    }
}

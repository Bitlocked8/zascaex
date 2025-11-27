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
            ['descripcion' => 'normal', 'capacidad' => '250', 'unidad' => 'ml', 'tipo' => 1, 'imagen' => 'etiquetas/normal_250.jpg'],
            ['descripcion' => 'normal', 'capacidad' => '330', 'unidad' => 'ml', 'tipo' => 1, 'imagen' => 'etiquetas/normal_330.jpg'],
            ['descripcion' => 'normal', 'capacidad' => '400', 'unidad' => 'ml', 'tipo' => 1, 'imagen' => 'etiquetas/normal_400.jpg'],
            ['descripcion' => 'normal', 'capacidad' => '500', 'unidad' => 'ml', 'tipo' => 1, 'imagen' => 'etiquetas/normal_500.jpg'],
            ['descripcion' => 'con gas', 'capacidad' => '530', 'unidad' => 'ml', 'tipo' => 1, 'imagen' => 'etiquetas/congas_530.jpg'],
            ['descripcion' => 'normal', 'capacidad' => '600', 'unidad' => 'ml', 'tipo' => 1, 'imagen' => 'etiquetas/normal_600.jpg'],
            ['descripcion' => 'alcalina', 'capacidad' => '600', 'unidad' => 'ml', 'tipo' => 1, 'imagen' => 'etiquetas/alcalina_600.jpg'],
            ['descripcion' => 'bicentenario normal', 'capacidad' => '600', 'unidad' => 'ml', 'tipo' => 1, 'imagen' => 'etiquetas/bicentenarioe.jpg'],
            ['descripcion' => 'bicentenario alcalina', 'capacidad' => '600', 'unidad' => 'ml', 'tipo' => 1, 'imagen' => 'etiquetas/bicentenario_alcalina_600.jpg'],
            ['descripcion' => 'normal', 'capacidad' => '750', 'unidad' => 'ml', 'tipo' => 1, 'imagen' => 'etiquetas/normal_750.jpg'],
            ['descripcion' => 'alcalina', 'capacidad' => '750', 'unidad' => 'ml', 'tipo' => 1, 'imagen' => 'etiquetas/alcalina_750.jpg'],
            ['descripcion' => 'normal', 'capacidad' => '1', 'unidad' => 'l', 'tipo' => 1, 'imagen' => 'etiquetas/normal_1l.jpg'],
            ['descripcion' => 'alcalina', 'capacidad' => '1', 'unidad' => 'l', 'tipo' => 1, 'imagen' => 'etiquetas/alcalina_1l.jpg'],
            ['descripcion' => 'normal', 'capacidad' => '1.5', 'unidad' => 'l', 'tipo' => 1, 'imagen' => 'etiquetas/normal_1_5l.jpg'],
            ['descripcion' => 'alcalina', 'capacidad' => '1.5', 'unidad' => 'l', 'tipo' => 1, 'imagen' => 'etiquetas/alcalina_1_5l.jpg'],
            ['descripcion' => 'dispenser', 'capacidad' => '20', 'unidad' => 'l', 'tipo' => 2, 'imagen' => 'etiquetas/dispenser_20l.jpg'],
        ];

        foreach ($etiquetas as $data) {
            foreach ([1, 2] as $sucursal_id) {
                $etiqueta = Etiqueta::create([
                    'imagen' => $data['imagen'],
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

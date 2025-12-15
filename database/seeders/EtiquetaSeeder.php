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
            ['descripcion' => 'San valentin', 'capacidad' => '250', 'unidad' => 'ml', 'tipo' => 1, 'imagen' => 'etiquetas/et1.png'],
            ['descripcion' => 'Marino', 'capacidad' => '330', 'unidad' => 'ml', 'tipo' => 1, 'imagen' => 'etiquetas/et2.png'],
            ['descripcion' => 'Espacial', 'capacidad' => '400', 'unidad' => 'ml', 'tipo' => 1, 'imagen' => 'etiquetas/et3.png'],
            ['descripcion' => 'Espacial', 'capacidad' => '400', 'unidad' => 'ml', 'tipo' => 1, 'imagen' => 'etiquetas/et4.png'],

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

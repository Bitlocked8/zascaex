<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Etiqueta;
use App\Models\Existencia;

class EtiquetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $etiquetas = [
            // tipo 1 = transparente, tipo 2 = brillante
            ['descripcion' => 'normal', 'capacidad' => '250', 'unidad' => '300', 'tipo' => 1],
            ['descripcion' => 'normal', 'capacidad' => '330', 'unidad' => '300', 'tipo' => 1],
            ['descripcion' => 'normal', 'capacidad' => '400', 'unidad' => '300', 'tipo' => 1],
            ['descripcion' => 'normal', 'capacidad' => '500', 'unidad' => '300', 'tipo' => 1],
            ['descripcion' => 'con gas', 'capacidad' => '530', 'unidad' => '300', 'tipo' => 1],
            ['descripcion' => 'normal', 'capacidad' => '600', 'unidad' => '300', 'tipo' => 1],
            ['descripcion' => 'alcalina', 'capacidad' => '600', 'unidad' => '300', 'tipo' => 1],
            ['descripcion' => 'bicentenario normal', 'capacidad' => '600', 'unidad' => '300', 'tipo' => 1],
            ['descripcion' => 'bicentenario alcalina', 'capacidad' => '600', 'unidad' => '300', 'tipo' => 1],
            ['descripcion' => 'normal', 'capacidad' => '750', 'unidad' => '300', 'tipo' => 1],
            ['descripcion' => 'alcalina', 'capacidad' => '750', 'unidad' => '300', 'tipo' => 1],
            ['descripcion' => 'normal', 'capacidad' => '1', 'unidad' => '300', 'tipo' => 1],
            ['descripcion' => 'alcalina', 'capacidad' => '1', 'unidad' => '300', 'tipo' => 1],
            ['descripcion' => 'normal', 'capacidad' => '1.5', 'unidad' => '300', 'tipo' => 1],
            ['descripcion' => 'alcalina', 'capacidad' => '1.5', 'unidad' => '300', 'tipo' => 1],
            ['descripcion' => 'dispenser', 'capacidad' => '20', 'unidad' => '150', 'tipo' => 2],
            ['descripcion' => 'normal', 'capacidad' => '250', 'unidad' => '300', 'tipo' => 2],
            ['descripcion' => 'normal', 'capacidad' => '330', 'unidad' => '300', 'tipo' => 2],
            ['descripcion' => 'normal', 'capacidad' => '400', 'unidad' => '300', 'tipo' => 2],
            ['descripcion' => 'normal', 'capacidad' => '500', 'unidad' => '300', 'tipo' => 2],
            ['descripcion' => 'con gas', 'capacidad' => '530', 'unidad' => '300', 'tipo' => 2],
            ['descripcion' => 'normal', 'capacidad' => '600', 'unidad' => '300', 'tipo' => 2],
            ['descripcion' => 'alcalina', 'capacidad' => '600', 'unidad' => '300', 'tipo' => 2],
            ['descripcion' => 'bicentenario normal', 'capacidad' => '600', 'unidad' => '300', 'tipo' => 2],
            ['descripcion' => 'bicentenario alcalina', 'capacidad' => '600', 'unidad' => '300', 'tipo' => 2],
            ['descripcion' => 'normal', 'capacidad' => '750', 'unidad' => '300', 'tipo' => 2],
            ['descripcion' => 'alcalina', 'capacidad' => '750', 'unidad' => '300', 'tipo' => 2],
            ['descripcion' => 'normal', 'capacidad' => '1', 'unidad' => '300', 'tipo' => 2],
            ['descripcion' => 'alcalina', 'capacidad' => '1', 'unidad' => '300', 'tipo' => 2],
            ['descripcion' => 'normal', 'capacidad' => '1.5', 'unidad' => '300', 'tipo' => 2],
            ['descripcion' => 'alcalina', 'capacidad' => '1.5', 'unidad' => '300', 'tipo' => 2],
        ];

        foreach ($etiquetas as $data) {
            foreach ([1, 2] as $sucursal_id) {
                $etiqueta = Etiqueta::create([
                    'imagen' => null,
                    'descripcion' => $data['descripcion'],
                    'capacidad' => $data['capacidad'],
                    'unidad' => $data['unidad'],
                    'estado' => 1, // activo
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

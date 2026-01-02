<?php

namespace Database\Seeders;

use App\Models\Base;
use App\Models\Existencia;
use Illuminate\Database\Seeder;

class BaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bases = [
            ['descripcion' => 'Normal 500 ml', 'capacidad' => 500, 'tipo' => 'Normal', 'compatibilidad' => 'Preforsa 20.1 y marecbol 20.5 '],
            ['descripcion' => 'Normal 530 ml', 'capacidad' => 530, 'tipo' => 'Normal', 'compatibilidad' => 'Preforsa 20.1 y marecbol 20.5 '],
            ['descripcion' => 'Normal 600 ml', 'capacidad' => 600, 'tipo' => 'Normal', 'compatibilidad' => 'Preforsa 20.1 y marecbol 20.5 '],
            ['descripcion' => 'Bicentenario 600 ml', 'capacidad' => 600, 'tipo' => 'Bicentenario', 'compatibilidad' => 'Preforsa 20.1 y marecbol 20.5 '],
            ['descripcion' => 'Normal 750 ml', 'capacidad' => 750, 'tipo' => 'Normal', 'compatibilidad' => 'Preforsa 20.1 y marecbol 20.5 '],
            ['descripcion' => 'Normal 400 ml', 'capacidad' => 400, 'tipo' => 'Normal', 'compatibilidad' => 'Preforsa 20.1 y marecbol 20.5 '],

            ['descripcion' => 'Normal 1 litro ', 'capacidad' => 1000, 'tipo' => 'Normal', 'compatibilidad' => 'Marecbol 35.5'],
            ['descripcion' => 'Normal 1.5 litros ', 'capacidad' => 1500, 'tipo' => 'Normal', 'compatibilidad' => 'Marecbol 35.5'],


            ['descripcion' => 'Normal 250 ml bajo', 'capacidad' => 250, 'tipo' => 'Normal', 'compatibilidad' => 'Preforsa 17.6'],

            ['descripcion' => 'Normal 330 ml alto', 'capacidad' => 330, 'tipo' => 'Normal', 'compatibilidad' => 'Preforma base alta'],
            ['descripcion' => 'Normal 250 ml alto', 'capacidad' => 250, 'tipo' => 'Normal', 'compatibilidad' => 'Preforma base alta'],

            ['descripcion' => 'Normal 400 ml vidrio', 'capacidad' => 400, 'tipo' => 'Normal', 'compatibilidad' => 'vidrio'],

            ['descripcion' => 'Normal 400 ml vip', 'capacidad' => 400, 'tipo' => 'Normal', 'compatibilidad' => 'vip'],
            
            ['descripcion' => 'Normal 1 litro azul', 'capacidad' => 1000, 'tipo' => 'Normal', 'compatibilidad' => 'Preforma azul'],
            ['descripcion' => 'Normal 600 ml azul ', 'capacidad' => 600, 'tipo' => 'Normal', 'compatibilidad' => 'Preforma azul'],

        ];

        foreach ($bases as $data) {
            foreach ([1, 2] as $sucursal_id) {

                $descripcion = trim($data['descripcion'] . ' ' . $data['capacidad'] . 'ml');

                $base = Base::create([
                    'imagen' => null,
                    'descripcion' => $descripcion,
                    'capacidad' => $data['capacidad'],
                    'estado' => 1,
                    'tipo' => $data['tipo'],
                    'observaciones' => null,
                    'compatibilidad' => $data['compatibilidad'],
                ]);

                Existencia::create([
                    'existenciable_type' => Base::class,
                    'existenciable_id' => $base->id,
                    'cantidad' => 0,
                    'cantidadMinima' => 0,
                    'sucursal_id' => $sucursal_id,
                ]);
            }
        }
    }
}

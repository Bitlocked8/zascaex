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
            ['descripcion' => 'cuello corto', 'capacidad' => 250, 'tipo' => 'Normal'],
            ['descripcion' => 'cuello largo', 'capacidad' => 250, 'tipo' => 'Normal'],
            ['descripcion' => 'cuello corto', 'capacidad' => 330, 'tipo' => 'Normal'],
            ['descripcion' => '', 'capacidad' => 400, 'tipo' => 'Normal'],
            ['descripcion' => 'cuello corto', 'capacidad' => 500, 'tipo' => 'Normal'],
            ['descripcion' => 'cuello corto', 'capacidad' => 530, 'tipo' => 'Con gas'],
            ['descripcion' => 'cuello corto', 'capacidad' => 500, 'tipo' => 'Normal'],
            ['descripcion' => 'cuello corto', 'capacidad' => 530, 'tipo' => 'Con gas'],
            ['descripcion' => '', 'capacidad' => 600, 'tipo' => 'Normal'],
            ['descripcion' => '', 'capacidad' => 600, 'tipo' => 'Alcalina'],
            ['descripcion' => '', 'capacidad' => 750, 'tipo' => 'Normal'],
            ['descripcion' => '', 'capacidad' => 750, 'tipo' => 'Alcalina'],
            ['descripcion' => '', 'capacidad' => 1, 'tipo' => 'Normal'],
            ['descripcion' => '', 'capacidad' => 1, 'tipo' => 'Alcalina'],
            ['descripcion' => '', 'capacidad' => 1.5, 'tipo' => 'Normal'],
            ['descripcion' => '', 'capacidad' => 1.5, 'tipo' => 'Alcalina'],
            ['descripcion' => '', 'capacidad' => 20, 'tipo' => 'Normal'],
        ];

        

        foreach ($bases as $data) {
            foreach ([1, 2] as $sucursal_id) {
                $base = Base::create([
                    'imagen' => null,
                    'descripcion' => $data['descripcion'],
                    'capacidad' => $data['capacidad'],
                    'estado' => 1,
                    'tipo' => $data['tipo'],
                    'observaciones' => null,
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

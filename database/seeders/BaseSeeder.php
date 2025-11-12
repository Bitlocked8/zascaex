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
            ['descripcion' => 'cuello corto', 'capacidad' => 250, 'tipo' => 'Normal', 'compatibilidad' => 'Preforsa 17.6'],
            ['descripcion' => 'cuello largo', 'capacidad' => 250, 'tipo' => 'Alto', 'compatibilidad' => 'Pet Preform 16 gr'],
            ['descripcion' => 'cuello corto', 'capacidad' => 330, 'tipo' => 'Normal', 'compatibilidad' => 'Pet Preform 16 gr'],
            ['descripcion' => '', 'capacidad' => 400, 'tipo' => 'Normal', 'compatibilidad' => 'Pet Preform 42 gr'],
            ['descripcion' => 'cuello corto', 'capacidad' => 500, 'tipo' => 'Normal', 'compatibilidad' => 'Preforsa 20.1'],
            ['descripcion' => 'cuello corto', 'capacidad' => 530, 'tipo' => 'Normal', 'compatibilidad' => 'Preforsa 20.1'],
            ['descripcion' => 'cuello corto', 'capacidad' => 600, 'tipo' => 'Normal', 'compatibilidad' => 'Preforsa 20.1'],
            ['descripcion' => 'cuello corto', 'capacidad' => 600, 'tipo' => 'Bic.', 'compatibilidad' => 'Preforsa 20.1'],
            ['descripcion' => 'cuello corto', 'capacidad' => 750, 'tipo' => 'Normal', 'compatibilidad' => 'Preforsa 20.1'],
            ['descripcion' => '', 'capacidad' => 400, 'tipo' => 'Santa Cruz', 'compatibilidad' => 'Pet Preform 42 gr'],
            ['descripcion' => '', 'capacidad' => 1000, 'tipo' => 'Normal', 'compatibilidad' => 'Marecbol 35.5'],
            ['descripcion' => '', 'capacidad' => 1000, 'tipo' => 'Alcalina', 'compatibilidad' => 'Marecbol 35.5'],
            ['descripcion' => '', 'capacidad' => 1500, 'tipo' => 'Normal', 'compatibilidad' => 'Marecbol 35.5'],
            ['descripcion' => '', 'capacidad' => 600, 'tipo' => 'Alcalina', 'compatibilidad' => 'Preforsa 20.1'],
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

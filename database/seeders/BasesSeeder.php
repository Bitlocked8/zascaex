<?php

namespace Database\Seeders;

use App\Models\Base;
use Illuminate\Database\Seeder;

class BaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bases = [
            [
                'imagen' => 'base_plastica_20l.png',
                'descripcion' => 'Base plástica estándar para botellón de 20 litros',
                'capacidad' => 20,
                'estado' => 1,
                'observaciones' => 'Modelo económico, resistente y ligero',
            ],
            [
                'imagen' => 'base_metalica_20l.png',
                'descripcion' => 'Base metálica reforzada para botellón de 20 litros',
                'capacidad' => 20,
                'estado' => 1,
                'observaciones' => 'Ideal para uso industrial y lugares de alto tráfico',
            ],
            [
                'imagen' => 'base_vidrio_10l.png',
                'descripcion' => 'Base de vidrio templado para botellón de 10 litros',
                'capacidad' => 10,
                'estado' => 1,
                'observaciones' => 'Diseño elegante, recomendado para oficinas',
            ],
            [
                'imagen' => 'base_plastica_10l.png',
                'descripcion' => 'Base plástica compacta para botellón de 10 litros',
                'capacidad' => 10,
                'estado' => 1,
                'observaciones' => 'Versión ligera para espacios reducidos',
            ],
        ];

        foreach ($bases as $data) {
            Base::create($data);
        }
    }
}
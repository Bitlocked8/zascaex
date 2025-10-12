<?php

namespace Database\Seeders;

use App\Models\Tapa;
use Illuminate\Database\Seeder;

class TapaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tapas = [
            [
                'imagen' => 'tapa_azul.png',
                'descripcion' => 'Tapa azul estándar para botellón de 20 litros',
                'color' => 'Azul',
                'tipo' => 'Plástica',
                'estado' => 1,
            ],
            [
                'imagen' => 'tapa_blanca.png',
                'descripcion' => 'Tapa blanca reforzada, uso doméstico',
                'color' => 'Blanco',
                'tipo' => 'Plástica',
                'estado' => 1,
            ],
            [
                'imagen' => 'tapa_verde.png',
                'descripcion' => 'Tapa verde con sello de seguridad',
                'color' => 'Verde',
                'tipo' => 'Con sello',
                'estado' => 1,
            ],
            [
                'imagen' => 'tapa_roja.png',
                'descripcion' => 'Tapa roja resistente para botellón retornable',
                'color' => 'Rojo',
                'tipo' => 'Reforzada',
                'estado' => 1,
            ],
        ];

        foreach ($tapas as $data) {
            Tapa::create($data);
        }
    }
}
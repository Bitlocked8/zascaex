<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Preforma;
use App\Models\Existencia;

class PreformaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $preformas = [
            [
                'detalle' => null,
                'insumo' => null,
                'gramaje' => '17,6',
                'cuello' => 'corto',
                'descripcion' => 'Preforsa 17,6 gr',
                'capacidad' => null,
                'color' => null,
            ],
            [
                'detalle' => null,
                'insumo' => null,
                'gramaje' => '20,1',
                'cuello' => 'corto',
                'descripcion' => 'Preforsa 20,1 gr',
                'capacidad' => null,
                'color' => null,
            ],
            [
                'detalle' => null,
                'insumo' => null,
                'gramaje' => '20,5',
                'cuello' => 'corto',
                'descripcion' => 'Marecbol 20,5 gr',
                'capacidad' => null,
                'color' => null,
            ],
            [
                'detalle' => null,
                'insumo' => null,
                'gramaje' => '35,5',
                'cuello' => 'largo',
                'descripcion' => 'Marecbol 35,5 gr',
                'capacidad' => null,
                'color' => null,
            ],

            [
                'detalle' => null,
                'insumo' => null,
                'gramaje' => '0',
                'cuello' => 'largo',
                'descripcion' => 'Preforma azul',
                'capacidad' => null,
                'color' => null,
            ],

            [
                'detalle' => null,
                'insumo' => null,
                'gramaje' => '0',
                'cuello' => 'largo',
                'descripcion' => 'vidrio',
                'capacidad' => null,
                'color' => null,
            ],

            [
                'detalle' => null,
                'insumo' => null,
                'gramaje' => '0',
                'cuello' => 'largo',
                'descripcion' => 'Preforma base alta',
                'capacidad' => null,
                'color' => null,
            ],
            [
                'detalle' => null,
                'insumo' => null,
                'gramaje' => '0',
                'cuello' => 'largo',
                'descripcion' => 'VIP',
                'capacidad' => null,
                'color' => null,
            ],
        ];


        foreach ($preformas as $data) {
            foreach ([1, 2] as $sucursal_id) {
                $preforma = Preforma::create([
                    'imagen' => null,
                    'detalle' => $data['detalle'],
                    'insumo' => $data['insumo'],
                    'gramaje' => $data['gramaje'],
                    'cuello' => $data['cuello'],
                    'descripcion' => $data['descripcion'],
                    'capacidad' => $data['capacidad'],
                    'color' => $data['color'],
                    'estado' => 1,
                    'observaciones' => null,
                ]);
                Existencia::create([
                    'existenciable_type' => Preforma::class,
                    'existenciable_id' => $preforma->id,
                    'cantidad' => 0,
                    'cantidadMinima' => 0,
                    'sucursal_id' => $sucursal_id,
                ]);
            }
        }
    }
}
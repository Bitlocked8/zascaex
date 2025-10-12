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
            ['detalle' => 'mls', 'insumo' => 'botella', 'gramaje' => '250 - 330', 'cuello' => 'corto', 'descripcion' => 'normal', 'capacidad' => 5000, 'color' => 'transparente'],
            ['detalle' => 'mls', 'insumo' => 'botella', 'gramaje' => '250', 'cuello' => 'largo', 'descripcion' => 'normal', 'capacidad' => 5000, 'color' => 'transparente'],
            ['detalle' => 'mls', 'insumo' => 'botella', 'gramaje' => '500-600-750', 'cuello' => 'corto', 'descripcion' => 'normal, con gas, tornado bicentenario, everest', 'capacidad' => 5000, 'color' => 'transparente'],
            ['detalle' => 'mls', 'insumo' => 'botella', 'gramaje' => '500-600-750', 'cuello' => 'corto', 'descripcion' => 'normal, con gas, tornado bicentenario, everest', 'capacidad' => 5000, 'color' => 'azul'],
            ['detalle' => 'Lts', 'insumo' => 'botella', 'gramaje' => '1 - 1,5', 'cuello' => '', 'descripcion' => 'normal', 'capacidad' => 5000, 'color' => 'transparente'],
            ['detalle' => 'Lts', 'insumo' => 'botella', 'gramaje' => '1 - 1,5', 'cuello' => '', 'descripcion' => 'normal', 'capacidad' => 5000, 'color' => 'azul'],
            ['detalle' => 'Lts', 'insumo' => 'botellon', 'gramaje' => '20', 'cuello' => '', 'descripcion' => 'normal', 'capacidad' => 300, 'color' => 'azul'],
            ['detalle' => 'mls', 'insumo' => 'botella', 'gramaje' => '400', 'cuello' => '', 'descripcion' => 'normal', 'capacidad' => 5000, 'color' => 'transparente'],
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

                // Crear existencia en la sucursal correspondiente
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

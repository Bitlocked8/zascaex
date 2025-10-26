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
            // Preformas para 250 ml (normal)
            ['detalle' => '250 ml', 'insumo' => 'botella', 'gramaje' => '17,6', 'cuello' => 'corto', 'descripcion' => 'Preforsa 17,6', 'capacidad' => 5000, 'color' => 'transparente'],
            
            // Preformas para 250 ml (alto)
            ['detalle' => '250 ml', 'insumo' => 'botella', 'gramaje' => '16', 'cuello' => 'largo', 'descripcion' => 'Pet Preform 16 gr', 'capacidad' => 5000, 'color' => 'transparente'],
            
            // Preformas para 330 ml
            ['detalle' => '330 ml', 'insumo' => 'botella', 'gramaje' => '17,6', 'cuello' => 'corto', 'descripcion' => 'Preforsa 17,6', 'capacidad' => 5000, 'color' => 'transparente'],
            
            // Preformas para 400 ml
            ['detalle' => '400 ml', 'insumo' => 'botella', 'gramaje' => '42', 'cuello' => 'corto', 'descripcion' => 'Pet Preform 42 gr', 'capacidad' => 5000, 'color' => 'transparente'],
            
            // Preformas para 500 ml
            ['detalle' => '500 ml', 'insumo' => 'botella', 'gramaje' => '20,1', 'cuello' => 'corto', 'descripcion' => 'Preforsa 20,1', 'capacidad' => 5000, 'color' => 'transparente'],
            
            // Preformas para 530 ml
            ['detalle' => '530 ml', 'insumo' => 'botella', 'gramaje' => '20,1', 'cuello' => 'corto', 'descripcion' => 'Preforsa 20,1', 'capacidad' => 5000, 'color' => 'transparente'],
            
            // Preformas para 600 ml (normal y alcalina)
            ['detalle' => '600 ml', 'insumo' => 'botella', 'gramaje' => '20,1', 'cuello' => 'corto', 'descripcion' => 'Preforsa 20,1', 'capacidad' => 5000, 'color' => 'transparente'],
            
            // Preformas para 600 ml Bic.
            ['detalle' => '600 ml', 'insumo' => 'botella', 'gramaje' => '20,1', 'cuello' => 'corto', 'descripcion' => 'Tornado Bicentenario', 'capacidad' => 5000, 'color' => 'transparente'],
            
            // Preformas para 750 ml
            ['detalle' => '750 ml', 'insumo' => 'botella', 'gramaje' => '20,1', 'cuello' => 'corto', 'descripcion' => 'Preforsa 20,1', 'capacidad' => 5000, 'color' => 'transparente'],
            
            // Preformas para 1L (normal y alcalina)
            ['detalle' => '1L', 'insumo' => 'botella', 'gramaje' => '35,5', 'cuello' => 'corto', 'descripcion' => 'Marecbol 35,5', 'capacidad' => 5000, 'color' => 'transparente'],
            
            // Preformas para 1,5L
            ['detalle' => '1,5L', 'insumo' => 'botella', 'gramaje' => '35,5', 'cuello' => 'corto', 'descripcion' => 'Marecbol 35,5', 'capacidad' => 5000, 'color' => 'transparente'],
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
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Existencia;

class ProductoSeeder extends Seeder
{

    public function run(): void
    {
        $productos = [
            ['descripcion' => 'Pera 250', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 250, 'unidad' => 'ml', 'precioReferencia' => 3.6, 'precioAlternativo' => null, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/500pera.jpeg'],
            ['descripcion' => 'Pera 330', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 330, 'unidad' => 'ml', 'precioReferencia' => 3.7, 'precioAlternativo' => null, 'paquete' => 30, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/500pera.jpeg'],
            ['descripcion' => 'Pera 500', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 500, 'unidad' => 'ml', 'precioReferencia' => 5.15, 'precioAlternativo' => null, 'paquete' => 35, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/500pera.jpeg'],
            ['descripcion' => 'Pera 600', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 600, 'unidad' => 'ml', 'precioReferencia' => 5.30, 'precioAlternativo' => null, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/500pera.jpeg'],
            ['descripcion' => 'Pera 1.5', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 1.5, 'unidad' => 'litros', 'precioReferencia' => 11.5, 'precioAlternativo' => null, 'paquete' => 8, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/500pera.jpeg'],

            ['descripcion' => 'Pomelo 250', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 250, 'unidad' => 'ml', 'precioReferencia' => 3.6, 'precioAlternativo' => null, 'paquete' => 35, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/pomelo500.jpeg'],
            ['descripcion' => 'Pomelo 330', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 330, 'unidad' => 'ml', 'precioReferencia' => 3.7, 'precioAlternativo' => null, 'paquete' => 30, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/pomelo500.jpeg'],
            ['descripcion' => 'Pomelo 500', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 500, 'unidad' => 'ml', 'precioReferencia' => 5.15, 'precioAlternativo' => null, 'paquete' => 35, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/pomelo500.jpeg'],
            ['descripcion' => 'Pomelo 600', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 600, 'unidad' => 'ml', 'precioReferencia' => 5.30, 'precioAlternativo' => null, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/pomelo500.jpeg'],

            ['descripcion' => 'Agua 250', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 250, 'unidad' => 'ml', 'precioReferencia' => 3.2, 'precioAlternativo' => null, 'paquete' => 35, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada330.jpeg'],
            ['descripcion' => 'Agua 330', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 330, 'unidad' => 'ml', 'precioReferencia' => 3.4, 'precioAlternativo' => 3.9, 'paquete' => 30, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],
            ['descripcion' => 'Agua 400', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 400, 'unidad' => 'ml', 'precioReferencia' => 6.0, 'precioAlternativo' => 6.8, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],
            ['descripcion' => 'Agua 500', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 500, 'unidad' => 'ml', 'precioReferencia' => 4.0, 'precioAlternativo' => 4.6, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],
            ['descripcion' => 'Agua 530', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 530, 'unidad' => 'ml', 'precioReferencia' => 4.1, 'precioAlternativo' => 4.7, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],
            ['descripcion' => 'Agua 600', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 600, 'unidad' => 'ml', 'precioReferencia' => 4.1, 'precioAlternativo' => 4.7, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],
            ['descripcion' => 'Agua 750', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 750, 'unidad' => 'ml', 'precioReferencia' => 5.2, 'precioAlternativo' => 6.0, 'paquete' => 15, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],
            ['descripcion' => 'Agua 1', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 1, 'unidad' => 'litro', 'precioReferencia' => 5.5, 'precioAlternativo' => 6.3, 'paquete' => 10, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],
            ['descripcion' => 'Agua 1 alcalina', 'tipoContenido' => 'Agua alcalina', 'tipoProducto' => 0, 'capacidad' => 1, 'unidad' => 'litro', 'precioReferencia' => 6.5, 'precioAlternativo' => 7.5, 'paquete' => 8, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],
            ['descripcion' => 'Botella 400 vidrio', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 1, 'capacidad' => 400, 'unidad' => 'ml', 'precioReferencia' => 21.0, 'precioAlternativo' => 23.1, 'paquete' => null, 'tipo' => 'Vidrio', 'observaciones' => null, 'imagen' => 'productos/400.jpeg'],
        ];

        foreach ($productos as $data) {
            foreach ([1, 2] as $sucursal_id) {
                $producto = Producto::create([
                    'imagen' => $data['imagen'],
                    'unidad' => $data['unidad'],
                    'descripcion' => $data['descripcion'],
                    'tipoContenido' => $data['tipoContenido'],
                    'tipoProducto' => $data['tipoProducto'],
                    'capacidad' => $data['capacidad'],
                    'precioReferencia' => $data['precioReferencia'],
                    'paquete' => $data['paquete'],
                    'observaciones' => $data['observaciones'],
                    'estado' => 1,
                    'tipo' => $data['tipo'],
                ]);

                Existencia::create([
                    'existenciable_type' => Producto::class,
                    'existenciable_id' => $producto->id,
                    'cantidad' => 0,
                    'cantidadMinima' => 0,
                    'sucursal_id' => $sucursal_id,
                ]);
            }
        }
    }
}

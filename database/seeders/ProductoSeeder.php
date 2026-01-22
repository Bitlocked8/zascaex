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
            ['descripcion' => 'Pera 250', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 250, 'unidad' => 'ml', 'precioReferencia' => 3.5, 'precioAlternativo' => 3.7, 'paquete' => 35, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/500pera.jpeg'],
            ['descripcion' => 'Pera 330', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 330, 'unidad' => 'ml', 'precioReferencia' => 3.7, 'precioAlternativo' => 3.9, 'paquete' => 30, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/500pera.jpeg'],
            ['descripcion' => 'Pera 400', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 400, 'unidad' => 'ml', 'precioReferencia' => 6.0, 'precioAlternativo' => 6.8, 'paquete' => 24, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/500pera.jpeg'],
            ['descripcion' => 'Pera bot. vidrio 400 ', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 400, 'unidad' => 'ml', 'precioReferencia' => 32, 'precioAlternativo' => null, 'paquete' => 24, 'tipo' => 'Vidrio', 'observaciones' => null, 'imagen' => 'productos/400.jpeg'],
            ['descripcion' => 'Pera 500', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 500, 'unidad' => 'ml', 'precioReferencia' => 5, 'precioAlternativo' => 5.15, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/500pera.jpeg'],
            ['descripcion' => 'Pera 600', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 600, 'unidad' => 'ml', 'precioReferencia' => 5.10, 'precioAlternativo' => 5.3, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/500pera.jpeg'],
            ['descripcion' => 'Pera 750', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 750, 'unidad' => 'ml', 'precioReferencia' => 5.8, 'precioAlternativo' => 6.0, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/500pera.jpeg'],
            ['descripcion' => 'Pera 1', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 1, 'unidad' => 'litro', 'precioReferencia' => 6.1, 'precioAlternativo' => 6.3, 'paquete' => 10, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/500pera.jpeg'],
            ['descripcion' => 'Pera 1.5', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 1.5, 'unidad' => 'litros', 'precioReferencia' => 11.5, 'precioAlternativo' => 11.5, 'paquete' => 10, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/500pera.jpeg'],

            ['descripcion' => 'Pomelo 250', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 250, 'unidad' => 'ml', 'precioReferencia' => 3.5, 'precioAlternativo' => 3.7, 'paquete' => 35, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/pomelo500.jpeg'],
            ['descripcion' => 'Pomelo 330', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 330, 'unidad' => 'ml', 'precioReferencia' => 3.7, 'precioAlternativo' => 3.9, 'paquete' => 30, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/pomelo500.jpeg'],
            ['descripcion' => 'Pomelo 400', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 400, 'unidad' => 'ml', 'precioReferencia' => 6.0, 'precioAlternativo' => 6.8, 'paquete' => 24, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/pomelo500.jpeg'],
            ['descripcion' => 'Pomelo bot. vidrio 400 ', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 400, 'unidad' => 'ml', 'precioReferencia' => 32, 'precioAlternativo' => null, 'paquete' => 24, 'tipo' => 'Vidrio', 'observaciones' => null, 'imagen' => 'productos/pomelo500.jpeg'],
            ['descripcion' => 'Pomelo 500', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 500, 'unidad' => 'ml', 'precioReferencia' => 5, 'precioAlternativo' => 5.15, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/pomelo500.jpeg'],
            ['descripcion' => 'Pomelo 600', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 600, 'unidad' => 'ml', 'precioReferencia' => 5.10, 'precioAlternativo' => 5.3, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/pomelo500.jpeg'],
            ['descripcion' => 'Pomelo 750', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 750, 'unidad' => 'ml', 'precioReferencia' => 5.8, 'precioAlternativo' => 6.0, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/pomelo500.jpeg'],
            ['descripcion' => 'Pomelo 1', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 1, 'unidad' => 'litro', 'precioReferencia' => 6.1, 'precioAlternativo' => 6.3, 'paquete' => 10, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/pomelo500.jpeg'],
            ['descripcion' => 'Pomelo 1.5', 'tipoContenido' => 'Agua saborizada', 'tipoProducto' => 0, 'capacidad' => 1.5, 'unidad' => 'litros', 'precioReferencia' => 11.5, 'precioAlternativo' => 11.5, 'paquete' => 10, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/pomelo500.jpeg'],


            ['descripcion' => 'Agua 250', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 250, 'unidad' => 'ml', 'precioReferencia' => 3.2, 'precioAlternativo' => 3.7, 'paquete' => 35, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada330.jpeg'],
            ['descripcion' => 'Agua 330', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 330, 'unidad' => 'ml', 'precioReferencia' => 3.4, 'precioAlternativo' => 3.9, 'paquete' => 30, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],
            ['descripcion' => 'Agua 400', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 400, 'unidad' => 'ml', 'precioReferencia' => 6.0, 'precioAlternativo' => 6.8, 'paquete' => 24, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],
            ['descripcion' => 'Agua bot. vidrio 400 ', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 1, 'capacidad' => 400, 'unidad' => 'ml', 'precioReferencia' => 32, 'precioAlternativo' => null, 'paquete' => 24, 'tipo' => 'Vidrio', 'observaciones' => null, 'imagen' => 'productos/vidrio.png'],
            ['descripcion' => 'Agua 500', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 500, 'unidad' => 'ml', 'precioReferencia' => 4.0, 'precioAlternativo' => 4.6, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],
            ['descripcion' => 'Agua 530', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 530, 'unidad' => 'ml', 'precioReferencia' => 4.1, 'precioAlternativo' => 4.7, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],
            ['descripcion' => 'Agua 600', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 600, 'unidad' => 'ml', 'precioReferencia' => 4.1, 'precioAlternativo' => 4.7, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],
            ['descripcion' => 'Agua 750', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 750, 'unidad' => 'ml', 'precioReferencia' => 5.2, 'precioAlternativo' => 6.0, 'paquete' => 20, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],
            ['descripcion' => 'Agua bot. azul 750', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 750, 'unidad' => 'ml', 'precioReferencia' => 5.2, 'precioAlternativo' => 6.0, 'paquete' => 10, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],
            ['descripcion' => 'Agua 1', 'tipoContenido' => 'Agua normal', 'tipoProducto' => 0, 'capacidad' => 1, 'unidad' => 'litro', 'precioReferencia' => 5.5, 'precioAlternativo' => 6.3, 'paquete' => 10, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],
            ['descripcion' => 'Agua alcalina 1 ', 'tipoContenido' => 'Agua alcalina', 'tipoProducto' => 0, 'capacidad' => 1, 'unidad' => 'litro', 'precioReferencia' => 6.5, 'precioAlternativo' => 7.5, 'paquete' => 10, 'tipo' => 'Plastico', 'observaciones' => null, 'imagen' => 'productos/personalizada500.jpeg'],

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
                    'precioAlternativo' => $data['precioAlternativo'],
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

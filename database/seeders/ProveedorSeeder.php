<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedorSeeder extends Seeder
{

    public function run(): void
    {

        Proveedor::create([
            'razonSocial' => 'MARECBOL',
            'nombreContacto' => 'MARIO HUMBERTO CARMONA COVARRUBIAS',
            'direccion' => 'A media cuadra entrando la Av. Chalancalle, Atahuallpa Nro. S/N, Zona Sirpita Mocko, Cochabamba - Bolivia',
            'telefono' => '44038533',
            'correo' => 'info@marecbol.com',
            'tipo' => 'Proveedor de preformas PET',
            'servicio' => 'Venta de preformas cristal 70/30 FDA',
            'descripcion' => 'Proveedor de preformas de 20.5g color cristal (3100 unidades por caja). Calidad FDA 70/30.',
            'precio' => null,
            'tiempoEntrega' => null,
            'estado' => 1,
        ]);
        Proveedor::create([
            'razonSocial' => 'PREFORSA S.R.L.',
            'nombreContacto' => null,
            'direccion' => 'Av. 6 de Marzo Nro. 17, Zona Rosas Pampa, entre calles Walter Ibañez y Tiwanaku, El Alto - Bolivia',
            'telefono' => '69722784',
            'correo' => null,
            'tipo' => 'Proveedor de preformas y envases PET',
            'servicio' => null,
            'descripcion' => null,
            'precio' => null,
            'tiempoEntrega' => null,
            'estado' => 1,
        ]);

        Proveedor::create([
            'razonSocial' => 'INDUSTRIAS APOLO S.R.L.',
            'nombreContacto' => null,
            'direccion' => 'Av. 24 de Junio Km 3½ S/N, Carretera Vinto - Oruro, Oruro - Bolivia',
            'telefono' => '5289326',
            'correo' => null,
            'tipo' => 'Proveedor industrial',
            'servicio' => null,
            'descripcion' => 'Casa matriz ubicada en Oruro, dedicada a la fabricación y distribución de productos industriales.',
            'precio' => null,
            'tiempoEntrega' => null,
            'estado' => 1,
        ]);
        Proveedor::create([
            'razonSocial' => 'IMPROMAT',
            'nombreContacto' => 'IMPROMAT',
            'direccion' => 'CALLE LADISLAO CABRERA NRO. S/N ZONA BARRIO ATAHUALLPA SUD COCHABAMBA',
            'telefono' => null,
            'correo' => null,
            'tipo' => 'Proveedor de insumos para envasado',
            'servicio' => 'Venta de tapas y precintos para botellones',
            'descripcion' => 'IMPROMAT es el proveedor que vende a VERZASCA S.R.L. Productos: tapa botellón short 4g (3.000 und. × 0.26 Bs) y precinto termocontraíble (3 und. × 55.00 Bs). NIT emisor: 479770922.',
            'precio' => null,
            'tiempoEntrega' => null,
            'estado' => 1,
        ]);
    }
}

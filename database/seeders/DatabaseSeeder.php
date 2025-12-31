<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Empresa;
use App\Models\Personal;
use App\Models\Sucursal;
use App\Models\Cliente;
use App\Models\Proveedor;
use App\Models\Rol;
use App\Models\User;


class DatabaseSeeder extends Seeder
{

    public function run(): void
    {

        Rol::create(['id' => 1, 'nombre' => 'Super administrador', 'descripcion' => 'Acceso total al sistema']);
        Rol::create(['id' => 2, 'nombre' => 'Administrador', 'descripcion' => 'Acceso acceso a ventas']);
        Rol::create(['id' => 3, 'nombre' => 'Distribuidor', 'descripcion' => 'Acceso acceso a ventas']);
        Rol::create(['id' => 4, 'nombre' => 'Planta', 'descripcion' => 'Acceso acceso a ventas']);
        Rol::create(['id' => 5, 'nombre' => 'Cliente', 'descripcion' => 'Acceso la aplicacion móvil, catálogo y compras por internet.']);


        $adminUser = User::create([
            'email' => 'admin@mail.com',
            'password' => bcrypt(12345678),
            'estado' => 1,
            'rol_id' => 1,
        ]);
        Personal::factory()->create(['user_id' => $adminUser->id]);



        $empresa = Empresa::create([
            'id' => 1,
            'nombre' => 'Verzasca',
            'slogan' => 'En todo momento cuidamos tu salud',
            'mision' => 'Somos una empresa purificadora de agua, que contribuye a la mejora de la salud de las personas, purificando el agua con los estándares más altos de calidad, innovando en el proceso de producción de agua de mesa para todos nuestros clientes, cumpliendo con los requerimientos de una manera eficiente y eficaz, dando una excelente atención al cliente por todo el personal de la empresa.',
            'vision' => 'Ser una empresa competitiva y reconocida en el mercado de agua de mesa, por la calidad de agua purificada que producimos, y que las personas cuando piensen en mejorar su salud piensen en VERZASCA.',
            'nroContacto' => '591 4 4251234',
            'correo' => 'verzascacbba@gmail.com',
            'facebook' => 'https://facebook.com/verzasca',
            'instagram' => 'verzasca_bolivia',
            'tiktok' => 'verzasca BO',
        ]);


        Sucursal::create([
            'id' => 1,
            'nombre' => ' Central Cochabamba',
            'direccion' => 'Av. 23 de Septiembre entre calle Albert Einstein y Av. Sexta',
            'telefono' => '72989185',
            'zona' => 'Central',
            'empresa_id' => $empresa->id,
        ]);

        Sucursal::create([
            'id' => 2,
            'nombre' => 'Santa Cruz',
            'direccion' => 'Zona Palos Verdes entre 8vo. y 9no. anillo o entre Av. Alemana y Beni',
            'telefono' => '65405605',
            'zona' => 'Norte',
            'empresa_id' => $empresa->id,
        ]);
        $this->call(TapaSeeder::class);
        $this->call(EtiquetaSeeder::class);
        $this->call(PreformaSeeder::class);
        $this->call(BaseSeeder::class);
        $this->call(ProductoSeeder::class);
        $this->call(OtroSeeder::class);
        $this->call(PersonalSeeder::class);
        // $this->call(ClienteSeeder::class);
        $this->call(ProveedorSeeder::class);


    }
}

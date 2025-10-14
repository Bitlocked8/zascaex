<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asignacion;
use App\Models\Coche;
use App\Models\Distribucion;
use App\Models\Empresa;
use App\Models\Personal;
use App\Models\Sucursal;
use App\Models\Cliente;
use App\Models\Proveedor;
use App\Models\Rol;
use App\Models\User;
use App\Models\Promo;
use App\Models\ItemPromo;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ---------------------------
        // 1️⃣ Crear roles
        // ---------------------------
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
            'slogan' => 'Agua embotellada de mesa',
            'mision' => 'Proveer agua de calidad a nuestros clientes',
            'vision' => 'Ser líderes en agua embotellada en Bolivia',
            'nroContacto' => '591 4 4251234',
            'facebook' => 'https://facebook.com/verzasca',
            'instagram' => 'https://instagram.com/verzasca',
            'tiktok' => 'https://tiktok.com/@verzasca',
        ]);


        Sucursal::create([
            'id' => 1,
            'nombre' => 'Cochabamba Central',
            'direccion' => 'Av. Heroínas 123, Cochabamba, Bolivia',
            'telefono' => '591 4 4251234',
            'zona' => 'Centro',
            'empresa_id' => $empresa->id,
        ]);

        Sucursal::create([
            'id' => 2,
            'nombre' => 'Santa Cruz Norte',
            'direccion' => 'Av. Banzer 456, Santa Cruz, Bolivia',
            'telefono' => '591 3 3435678',
            'zona' => 'Norte',
            'empresa_id' => $empresa->id,
        ]);
        $this->call(TapaSeeder::class);
        $this->call(EtiquetaSeeder::class);
        $this->call(PreformaSeeder::class);
        $this->call(BaseSeeder::class);
        $this->call(ProductoSeeder::class);
         $this->call(ClienteSeeder::class);
        Proveedor::factory(10)->create();

        Coche::factory(5)->create()->each(function ($coche) {
            $asignacion = Asignacion::create([
                'fechaInicio' => now(),
                'fechaFinal' => now()->addMonths(6),
                'estado' => 1,
                'coche_id' => $coche->id,
                'personal_id' => Personal::inRandomOrder()->first()->id,
            ]);

            Distribucion::factory()->create([
                'asignacion_id' => $asignacion->id,
                'fecha' => now(),
                'estado' => 1,
                'observaciones' => "Distribución generada automáticamente.",
            ]);
        });
    }
}

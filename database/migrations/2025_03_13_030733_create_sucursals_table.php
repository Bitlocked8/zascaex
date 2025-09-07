<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sucursals', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->string('nombre'); // Nombre de la sucursal
            $table->string('direccion'); // Dirección de la sucursal
            $table->string('telefono', 15); // Teléfono de contacto
            $table->string('zona')->nullable(); // Zona (opcional)
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade'); // Relación con empresa
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursals');
    }
};

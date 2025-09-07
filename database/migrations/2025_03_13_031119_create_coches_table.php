<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coches', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->integer('movil'); // Marca del coche
            $table->string('marca'); // Marca del coche
            $table->string('modelo'); // Modelo del coche
            $table->integer('anio'); // Año del coche
            $table->string('color'); // Color del coche
            $table->string('placa')->unique(); // Placa única del coche
            $table->boolean('estado')->default(1); // Estado del coche (1: activo, 0: inactivo)
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coches');
    }
};

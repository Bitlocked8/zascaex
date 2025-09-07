<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('preformas', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->string('imagen')->nullable();
            $table->string('detalle')->nullable();
            $table->string('insumo'); // Insumo utilizado en la preforma
            $table->string('gramaje'); // Insumo utilizado en la preforma
            $table->string('cuello')->default('Bajo'); // Insumo utilizado en la preforma
            $table->text('descripcion')->nullable(); // Descripción opcional
            $table->integer('capacidad'); // Capacidad de la preforma
            $table->string('color'); // Color de la preforma
            $table->boolean('estado')->default(1); // Estado (1: activo, 0: inactivo)
            $table->text('observaciones')->nullable(); // Observaciones opcionales
            $table->timestamps(); // Campos created_at y updated_at
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preformas');
    }
};

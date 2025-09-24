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
            $table->text('descripcion')->nullable(); // Aquí puedes incluir detalle, insumo, gramaje, color, capacidad, etc.
            $table->boolean('estado')->default(1); // Estado (1: activo, 0: inactivo)
            $table->text('observaciones')->nullable(); // Observaciones adicionales
            $table->timestamps(); // created_at y updated_at
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

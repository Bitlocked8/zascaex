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
        Schema::create('productos', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->string('nombre');
            $table->string('imagen')->nullable();
            $table->tinyInteger('tipoContenido'); // Tipo de contenido (tiny integer)
            $table->boolean('tipoProducto'); // Tipo de producto (0: sin retorno, 1: con retorno)
            $table->integer('capacidad'); // Capacidad del producto
            $table->decimal('precioReferencia', 8, 2)->comment('Precio de referencia');
            $table->text('descripcion')->nullable(); // Descripción opcional
            $table->boolean('estado')->default(1); // Estado (1: activo, 0: inactivo)
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};

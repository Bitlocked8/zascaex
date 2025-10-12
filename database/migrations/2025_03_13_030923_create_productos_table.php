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
            $table->id();
            $table->string('imagen')->nullable();
            $table->string('unidad')->nullable();
            $table->string('descripcion'); // nombre del producto
            $table->string('tipoContenido')->nullable(); // agua normal, con gas, etc.
            $table->string('tipoProducto')->nullable(); // botella, botellón, etc.
            $table->decimal('capacidad', 8, 2)->nullable(); // capacidad numérica
            $table->decimal('precioReferencia', 8, 2)->comment('Precio de referencia');
            $table->string('paquete')->nullable(); // 10 unidades, etc.
            $table->text('observaciones')->nullable(); // comentarios u otros datos
            $table->boolean('estado')->default(1);
            $table->string('tipo')->nullable(); // (Plástico, Vidrio, etc.)
            $table->timestamps();
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('imagen')->nullable();
            $table->string('unidad')->nullable();
            $table->string('descripcion');
            $table->string('tipoContenido')->nullable();
            $table->string('tipoProducto')->nullable();
            $table->decimal('capacidad', 8, 2)->nullable();
            $table->decimal('precioReferencia', 8, 2)->comment('Precio de referencia');
            $table->integer('paquete')->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('estado')->default(1);
            $table->string('tipo')->nullable();
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

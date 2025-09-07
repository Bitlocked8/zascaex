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
        Schema::create('existencias', function (Blueprint $table) {
            $table->id();
            $table->morphs('existenciable'); // Relación polimórfica para Tapa, Base, Preforma, Etiqueta
            $table->integer('cantidad')->default(0); // Cantidad en existencia
            $table->integer('cantidadMinima')->default(0); // Cantidad en existencia
            $table->foreignId('sucursal_id')->constrained('sucursals')->onDelete('cascade'); // Relación con Sucursal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('existencias');
    }
};

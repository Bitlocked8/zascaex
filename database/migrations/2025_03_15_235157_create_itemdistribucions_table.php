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
        Schema::create('itemdistribucions', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidadNuevo');
            $table->integer('cantidadUsados');
            $table->foreignId('stock_id')->constrained('stocks')->onDelete('cascade'); // Relación con Asignación
            $table->foreignId('distribucion_id')->constrained('distribucions')->onDelete('cascade'); // Relación con Asignación
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itemdistribucions');
    }
};

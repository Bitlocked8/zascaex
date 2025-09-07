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
        Schema::create('retornos', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->integer('botellonesNuevos')->nullable(); // Cantidad de botellones nuevos
            $table->integer('llenos'); // no vendidos
            $table->integer('vacios'); // vendidos
            $table->integer('reportado'); // Cantidad de productos malos
            $table->integer('desechar'); // Cantidad de productos rechazados o dañado
            // $table->integer('recuperados'); // Cantidad de productos recuperados
            // $table->string('encargado'); // Encargado de procesar el retorno
            $table->text('observaciones')->nullable(); // Observaciones (opcional)
            $table->foreignId('distribucion_id')->constrained('distribucions')->onDelete('cascade'); // Relación con Distribución
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retornos');
    }
};

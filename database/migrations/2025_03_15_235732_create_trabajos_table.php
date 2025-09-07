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
        Schema::create('trabajos', function (Blueprint $table) {
            $table->id();
            $table->date('fechaInicio'); // Fecha de inicio de la asignación
            $table->date('fechaFinal')->nullable(); // Fecha final de la asignación
            $table->boolean('estado')->default(1); // Estado (1: activo, 0: inactivo)
            $table->foreignId('sucursal_id')->constrained('sucursals')->onDelete('cascade'); // Relación con Sucursal
            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade'); // Relación con Personal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabajos');
    }
};

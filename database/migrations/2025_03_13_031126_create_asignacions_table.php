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
        Schema::create('asignacions', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->date('fechaInicio'); // Fecha de inicio de la asignación
            $table->date('fechaFinal'); // Fecha final de la asignación
            $table->boolean('estado')->default(1); // Estado (1: activo, 0: inactivo)
            $table->foreignId('coche_id')->constrained('coches')->onDelete('cascade'); // Relación con Coche
            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade'); // Relación con Personal
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacions');
    }
};

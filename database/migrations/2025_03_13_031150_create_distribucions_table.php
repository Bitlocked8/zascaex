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
        Schema::create('distribucions', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->date('fecha'); // Fecha de la distribución
            $table->tinyInteger('estado'); // 1) En distribucion, 2) concluido
            $table->text('observaciones')->nullable(); // Observaciones (opcional)
            $table->foreignId('asignacion_id')->constrained('asignacions')->onDelete('cascade'); // Relación con Asignación
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribucions');
    }
};

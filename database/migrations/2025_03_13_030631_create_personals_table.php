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
        Schema::create('personals', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->string('nombres'); // Nombres del personal
            $table->string('apellidos'); // Apellidos del personal
            $table->string('direccion')->nullable(); // Dirección (opcional)
            $table->string('celular', 15); // Número de celular
            $table->boolean('estado')->default(1); // Estado (1: activo, 0: inactivo)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Relación con usuarios (nullable)
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personals');
    }
};

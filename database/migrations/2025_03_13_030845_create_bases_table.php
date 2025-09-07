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
        Schema::create('bases', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->string('imagen')->nullable();
            $table->string('descripcion')->nullable();
            $table->integer('capacidad'); // Capacidad de la base
            $table->boolean('estado')->default(1); // Estado (1: activo, 0: inactivo)
            $table->text('observaciones')->nullable(); // Observaciones (opcional)

            $table->foreignId('preforma_id')->constrained('preformas')->nullable()->onDelete('cascade');
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bases');
    }
};

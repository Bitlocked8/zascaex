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
        Schema::create('etiquetas', function (Blueprint $table) {
            $table->id();
            $table->string('imagen')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('capacidad');
            $table->string('unidad')->nullable(); // [L, ml, g, Kg, unidad]
            $table->tinyInteger('estado'); 
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete(); // Relación opcional con Preforma
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etiquetas');
    }
};

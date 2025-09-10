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
        Schema::create('item_promos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('promo_id')->constrained('promos')->onDelete('cascade');
            $table->integer('usos_realizados')->default(0);
            $table->integer('uso_maximo')->nullable();
            $table->enum('estado', ['activo', 'usado', 'expirado', 'cancelado'])->default('activo');
            $table->date('fecha_asignada')->nullable();
            $table->date('fecha_expiracion')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_promos');
    }
};

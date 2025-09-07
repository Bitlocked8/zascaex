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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->string('articulo'); // Botellones, Dispenser normal, Dispenser electronico
            $table->integer('cantidad');
            $table->tinyInteger('estado'); // 1) Debe, 2) Devolvio
            $table->integer('garantia')->nullable();
            $table->string('observaciones')->nullable();
            $table->integer('nroContrato')->nullable();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade'); // Relación con Cliente
            $table->foreignId('prestador')->nullable()->constrained('personals')->onDelete('cascade'); // Relación con Personal
            $table->foreignId('recuperador')->nullable()->constrained('personals')->onDelete('cascade'); // Relación con Personal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};

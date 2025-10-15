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
        Schema::create('sucursal_pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->constrained('sucursals')->onDelete('cascade'); // Relación con sucursal
            $table->string('nombre'); // Nombre del pago (ej: QR Banco X)
            $table->string('tipo')->nullable(); // Tipo de pago (ej: QR, Transferencia)
            $table->string('numero_cuenta')->nullable(); // Número de cuenta, si aplica
            $table->string('titular')->nullable(); // Titular de la cuenta, si aplica
            $table->string('imagen_qr')->nullable(); // Imagen del QR
            $table->boolean('estado')->default(true); // Activo / Inactivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursal_pagos');
    }
};

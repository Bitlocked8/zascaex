<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comprobante_pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reposicion_id')->constrained('reposicions')->onDelete('cascade'); // Relación con reposición
            $table->string('codigo')->nullable(); // Código del comprobante
            $table->decimal('monto', 10, 2)->nullable(); // Monto pagado
            $table->date('fecha')->nullable(); // Fecha del pago
            $table->string('imagen')->nullable(); // Imagen del comprobante
            $table->text('observaciones')->nullable(); // Observaciones opcionales
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comprobante_pagos');
    }
};

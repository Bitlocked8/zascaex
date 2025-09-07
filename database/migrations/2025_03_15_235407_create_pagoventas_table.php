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
        Schema::create('pagoventas', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->string('tipo'); // Tipo de pago: QR, contado, cheque
            $table->double('monto'); // Monto a cuenta
            $table->string('codigo')->nullable(); // Código asociado al crédito (opcional)
            $table->date('fechaPago'); // Fecha del pago
            $table->text('observaciones')->nullable(); // Observaciones (opcional)
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade'); // Relación con la tabla ventas
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagoventas');
    }
};
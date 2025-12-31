<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pago_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('sucursal_pago_id')->nullable()->constrained('sucursal_pagos')->onDelete('set null');

            $table->decimal('monto', 10, 2);
            $table->tinyInteger('metodo')->default(0)->comment('0=QR, 1=Efectivo, 2=Crédito');
            $table->boolean('estado')->default(false)->comment('false=pendiente, true=pagado');
            $table->string('codigo_pago')->nullable()->unique();
            $table->string('referencia')->nullable();
            $table->dateTime('fecha_pago')->nullable();
            $table->string('imagen_comprobante')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago_pedidos');
    }
};

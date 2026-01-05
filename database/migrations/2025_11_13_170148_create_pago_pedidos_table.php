<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pago_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')
                ->constrained('pedidos')
                ->onDelete('cascade');

            $table->foreignId('sucursal_pago_id')
                ->nullable()
                ->constrained('sucursal_pagos');
            $table->decimal('monto', 10, 2)->default(0);
            $table->tinyInteger('metodo')->default(0);
            $table->boolean('estado')->default(false);

            $table->string('referencia')->nullable();
            $table->string('codigo_factura')->nullable()->unique();
            $table->datetime('fecha')->nullable();
            $table->string('archivo_factura')->nullable();
            $table->string('archivo_comprobante')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago_pedidos');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pago_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->string('metodo')->nullable();
            $table->string('referencia')->nullable();
            $table->dateTime('fecha_pago')->nullable();
            $table->string('imagen_comprobante')->nullable();
            $table->tinyInteger('estado')->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago_pedidos');
    }
};

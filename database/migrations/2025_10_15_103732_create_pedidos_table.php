<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade');
            $table->tinyInteger('estado_pedido')->default(0); // 0: pendiente, 1: entregado, 2: cancelado
            $table->text('observaciones')->nullable();
            $table->dateTime('fecha_pedido')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};

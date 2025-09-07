<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->date('fechaPedido')->nullable(); // Fecha del pedido
            $table->date('fechaEntrega')->nullable(); // Fecha de entrega estimada
            $table->date('fechaMaxima')->nullable(); // Fecha máxima para crédito            
            $table->tinyInteger('estadoPedido')->default(1); // Estado (0: cancelado, 1: pedido, 2: vendido o entregado)
            $table->tinyInteger('estadoPago')->default(1); // Estado (0: parcial, 1: completo)
            $table->foreignId('sucursal_id')->constrained('sucursals')->onDelete('cascade'); // Relación con Cliente
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade'); // Relación con Cliente
            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade'); // Relación con Personal
            $table->foreignId('personalEntrega_id')->nullable()->constrained('personals')->onDelete('cascade'); // Relación con Personal
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};

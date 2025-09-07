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
        Schema::create('itemventas', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->integer('cantidad'); // Cantidad del ítem vendido
            $table->decimal('precio', 10, 2); // Precio unitario
            $table->foreignId('existencia_id')->constrained('existencias')->onDelete('cascade'); // Relación con Existencias (Stock)
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade'); // Relación con Venta
            $table->tinyInteger('estado')->default(1); // 0) cancelado, 1) pedido, 2)vendido, 4)rechasado
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itemventas');
    }
};

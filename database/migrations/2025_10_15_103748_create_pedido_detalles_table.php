<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('reposicion_id')->constrained('reposicions')->onDelete('cascade'); // lote usado
            $table->foreignId('existencia_id')->constrained('existencias')->onDelete('cascade'); // producto
            $table->decimal('cantidad', 10, 2)->default(0); // cantidad consumida del lote
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_detalles');
    }
};

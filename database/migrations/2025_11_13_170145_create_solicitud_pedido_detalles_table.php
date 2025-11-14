<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solicitud_pedido_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitud_pedido_id');
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->integer('cantidad')->default(1);
            $table->decimal('precio', 8, 2)->nullable();
            $table->timestamps();

            $table->foreign('solicitud_pedido_id')->references('id')->on('solicitud_pedidos')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_pedido_detalles');
    }
};

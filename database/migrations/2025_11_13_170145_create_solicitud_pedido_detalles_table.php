<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('solicitud_pedido_detalles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('solicitud_pedido_id');

            // Relación con los elementos solicitados
            $table->unsignedBigInteger('producto_id')->nullable(); 
            $table->unsignedBigInteger('otro_id')->nullable(); 
            $table->unsignedBigInteger('tapa_id')->nullable();
            $table->unsignedBigInteger('etiqueta_id')->nullable();

            // Cantidad solicitada
            $table->integer('cantidad')->default(1);

            $table->timestamps();

            $table->foreign('solicitud_pedido_id')
                ->references('id')
                ->on('solicitud_pedidos')
                ->onDelete('cascade');

            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('set null');
            $table->foreign('otro_id')->references('id')->on('otros')->onDelete('set null');
            $table->foreign('tapa_id')->references('id')->on('tapas')->onDelete('set null');
            $table->foreign('etiqueta_id')->references('id')->on('etiquetas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitud_pedido_detalles');
    }
};

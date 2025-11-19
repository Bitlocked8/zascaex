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

            // Guardamos datos descriptivos directamente
            $table->string('descripcion');
            $table->integer('cantidad')->default(1);
            $table->integer('paquete')->default(1);
            $table->decimal('precio_unitario', 10, 2)->nullable();
            $table->decimal('total', 12, 2)->nullable();
            $table->string('tapa_descripcion')->nullable();
            $table->string('tapa_imagen')->nullable();
            $table->string('etiqueta_descripcion')->nullable();
            $table->string('etiqueta_imagen')->nullable();

            $table->string('tipo_contenido')->nullable();

            $table->timestamps();

            $table->foreign('solicitud_pedido_id')
                ->references('id')
                ->on('solicitud_pedidos')
                ->onDelete('cascade');
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

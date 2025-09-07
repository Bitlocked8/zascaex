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
        Schema::create('itemcompras', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->integer('cantidad'); // Cantidad del ítem
            $table->decimal('precio', 10, 2); // Precio unitario

            // Relación con Existencia en lugar de referenciar múltiples tablas
            $table->foreignId('existencia_id')->constrained('existencias')->onDelete('cascade');

            // Relación con la Compra
            $table->foreignId('compra_id')->constrained('compras')->onDelete('cascade');

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itemcompras');
    }
};

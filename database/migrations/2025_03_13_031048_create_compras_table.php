<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->date('fecha'); // Fecha de la compra
            $table->text('observaciones')->nullable(); // Observaciones (opcional)
            $table->foreignId('proveedor_id')->constrained('proveedors')->onDelete('cascade'); // Relación con Proveedor
            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade'); // Relación con Personal
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};

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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre de la promoción
            $table->enum('tipo_descuento', ['porcentaje', 'monto']); // Tipo de descuento
            $table->decimal('valor_descuento', 10, 2); // Valor del descuento
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete(); // null = todos los clientes
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};

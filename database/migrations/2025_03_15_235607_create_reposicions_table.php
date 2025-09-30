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
        Schema::create('reposicions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha');
            $table->string('codigo')->unique();
            $table->integer('cantidad'); // Cantidad repuesta
            $table->integer('cantidad_inicial');
            $table->foreignId('existencia_id')->constrained('existencias')->onDelete('cascade'); // Lote/artículo repuesto
            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade'); // Quién repuso
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedors')->nullOnDelete(); // Proveedor opcional
            $table->text('observaciones')->nullable(); // Detalles opcionales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reposicions');
    }
};

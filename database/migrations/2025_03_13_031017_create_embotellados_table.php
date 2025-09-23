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
        Schema::create('embotellados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('existencia_base_id')->constrained('existencias')->onDelete('cascade');
            $table->foreignId('existencia_tapa_id')->constrained('existencias')->onDelete('cascade');
            $table->foreignId('existencia_producto_id')->nullable()->constrained('existencias')->nullOnDelete();
            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade');
            $table->integer('cantidad_base_usada');
            $table->integer('cantidad_tapa_usada');
            $table->integer('cantidad_generada')->nullable();
            $table->integer('mermaTapa')->default(0);
            $table->integer('mermaBase')->default(0);
            $table->date('fecha_embotellado');
            $table->text('observaciones')->nullable();
            $table->string('codigo')->unique();
            $table->string('estado')->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('embotellados');
    }
};

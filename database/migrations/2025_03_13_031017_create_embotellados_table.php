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
            $table->id(); // Clave primaria
            $table->foreignId('existencia_base_id')->constrained('existencias')->onDelete('cascade');
            $table->foreignId('existencia_tapa_id')->constrained('existencias')->onDelete('cascade');
            $table->foreignId('existencia_producto_id')->nullable()->constrained('existencias')->nullOnDelete(); // Producto generado
            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade'); // Encargado del proceso
            $table->integer('cantidad_base_usada');
            $table->integer('cantidad_tapa_usada');
            $table->integer('cantidad_generada')->nullable();
            $table->date('fecha_embotellado');
            $table->integer('mermaTapa')->default(0); // Cantidad de perdidos
            $table->integer('mermaBase')->default(0); // Cantidad de perdidos
            $table->text('observaciones')->nullable();

            $table->unsignedBigInteger('sucursal_id')->default(1);
            $table->foreign('sucursal_id')->references('id')->on('sucursals');
            $table->timestamps(); // created_at y updated_at
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

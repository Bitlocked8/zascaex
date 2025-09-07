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
        Schema::create('etiquetados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('existencia_producto_id')->constrained('existencias')->onDelete('cascade'); // Producto
            $table->foreignId('existencia_etiqueta_id')->constrained('existencias')->onDelete('cascade'); // Etiqueta
            $table->foreignId('existencia_stock_id')->nullable()->constrained('existencias')->nullOnDelete(); // Stock generado
            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade'); // Encargado del proceso
            $table->integer('cantidad_producto_usado');
            $table->integer('cantidad_etiqueta_usada');
            $table->integer('cantidad_generada')->nullable();
            $table->date('fecha_etiquetado');
            $table->integer('mermaProducto')->default(0); // Cantidad de perdidos
            $table->integer('mermaEtiqueta')->default(0); // Cantidad de perdidos
            $table->text('observaciones')->nullable();

            $table->unsignedBigInteger('sucursal_id')->default(1);
            $table->foreign('sucursal_id')->references('id')->on('sucursals');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etiquetados');
    }
};

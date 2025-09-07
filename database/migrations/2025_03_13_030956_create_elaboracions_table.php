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
        Schema::create('elaboracions', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->foreignId('existencia_entrada_id')->constrained('existencias')->onDelete('cascade'); // Preformas
            $table->foreignId('existencia_salida_id')->nullable()->constrained('existencias')->nullOnDelete(); // Bases Generadas
            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade'); // Encargado del proceso
            $table->integer('cantidad_entrada'); // Preformas usadas
            $table->integer('cantidad_salida')->nullable(); // Bases generadas
            $table->date('fecha_elaboracion');
            $table->integer('merma')->default(0); // Cantidad de perdidos
            $table->text('observaciones')->nullable();       

            $table->unsignedBigInteger('sucursal_id')->default(1);
            $table->foreign('sucursal_id')->references('id')->on('sucursals');
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elaboracions');
    }
};

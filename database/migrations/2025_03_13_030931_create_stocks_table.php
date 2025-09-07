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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->string('imagen')->nullable();
            $table->date('fechaElaboracion'); // Fecha de elaboración del lote
            $table->date('fechaVencimiento'); // Fecha de vencimiento del lote
            // $table->integer('tapas'); // Número de tapas en el lote
            // $table->integer('cantidad'); // Cantidad en el lote
            $table->text('observaciones')->nullable(); // Observaciones (opcional)
            $table->foreignId('etiqueta_id')->constrained('etiquetas')->onDelete('cascade'); // Eliminando nullable()
            // $table->foreignId('sucursal_id')->constrained('sucursals')->onDelete('cascade'); // Eliminando nullable()
            
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade'); // Relación con Producto
         

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};

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
        Schema::create('proveedors', function (Blueprint $table) {
            $table->id();
            $table->string('razonSocial');
            $table->string('nombreContacto')->nullable();
            $table->string('direccion');
            $table->integer('telefono');
            $table->string('correo');
            $table->string('tipo')->nullable(); // tapas, preformas, bases, etiquetas
            $table->string('servicio')->nullable(); // soplado, transporte
            $table->string('descripcion');
            $table->double('precio');
            $table->string('tiempoEntrega');
            $table->tinyInteger('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedors');
    }
};

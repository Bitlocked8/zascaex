<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->string('nombre'); // Nombre de la empresa
            $table->string('slogan')->nullable(); // Slogan de la empresa
            $table->text('mision')->nullable(); // Misión de la empresa
            $table->text('vision')->nullable(); // Visión de la empresa
            $table->string('nroContacto', 15); // Número de contacto
            $table->string('facebook')->nullable(); // URL de Facebook
            $table->string('instagram')->nullable(); // URL de Instagram
            $table->string('tiktok')->nullable(); // URL de TikTok
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};

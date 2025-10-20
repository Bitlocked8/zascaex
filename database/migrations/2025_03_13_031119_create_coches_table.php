<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coches', function (Blueprint $table) {
            $table->id();
            $table->string('movil');
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('anio')->nullable();
            $table->string('color')->nullable();
            $table->string('placa')->unique();
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coches');
    }
};

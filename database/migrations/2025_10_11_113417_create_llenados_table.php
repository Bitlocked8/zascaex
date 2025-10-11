<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('llenados', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('asignado_base_id')->constrained('asignados')->onDelete('cascade');
            $table->foreignId('asignado_tapa_id')->constrained('asignados')->onDelete('cascade');
            $table->foreignId('existencia_id')->constrained('existencias')->onDelete('cascade');
            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade');
            $table->foreignId('reposicion_id')->nullable()->constrained('reposicions')->onDelete('cascade');
            $table->decimal('cantidad', 10, 2)->nullable();  
            $table->decimal('merma_base', 10, 2)->default(0);    
            $table->decimal('merma_tapa', 10, 2)->default(0); 
            $table->tinyInteger('estado')->default(0);
            $table->text('observaciones')->nullable();
            $table->dateTime('fecha')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('llenados');
    }
};

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distribucions', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->dateTime('fecha_asignacion')->nullable();
            $table->dateTime('fecha_entrega')->nullable();
            $table->foreignId('coche_id')->nullable()->constrained('coches')->nullOnDelete();
            $table->foreignId('personal_id')->nullable()->constrained('personals')->nullOnDelete();
            $table->tinyInteger('estado')->default(1);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribucions');
    }
};

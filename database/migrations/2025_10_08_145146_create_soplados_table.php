<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soplados', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('asignado_id')->constrained('asignados')->onDelete('cascade');
            $table->foreignId('existencia_id')->constrained('existencias')->onDelete('cascade');
            $table->foreignId('reposicion_id')->constrained('reposicions')->onDelete('cascade');
            $table->decimal('cantidad', 10, 2);
            $table->decimal('merma', 10, 2)->default(0);
            $table->tinyInteger('estado')->default(0);
            $table->text('observaciones')->nullable();
            $table->dateTime('fecha')->default(now());

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soplados');
    }
};

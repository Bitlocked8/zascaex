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
        Schema::create('asignado_reposicions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asignado_id')->constrained('asignados')->onDelete('cascade');
            $table->foreignId('reposicion_id')->constrained('reposicions')->onDelete('cascade');
            $table->foreignId('existencia_id')->constrained('existencias')->onDelete('cascade');
            $table->integer('cantidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignado_reposicion');
    }
};

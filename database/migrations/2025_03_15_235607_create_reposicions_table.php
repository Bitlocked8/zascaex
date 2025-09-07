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
        Schema::create('reposicions', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->integer('cantidad');
            // $table->foreignId('base_id')->constrained('bases')->onDelete('cascade'); // Relación con Personal
            $table->foreignId('existencia_id')->constrained('existencias')->onDelete('cascade'); // Relación con Personal
            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade'); // Relación con Personal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reposicions');
    }
};

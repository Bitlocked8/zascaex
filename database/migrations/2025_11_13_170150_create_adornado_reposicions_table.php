<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('adornado_reposicions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adornado_id')->constrained('adornados')->onDelete('cascade');
            $table->foreignId('reposicion_id')->constrained('reposicions')->onDelete('cascade');
            $table->integer('cantidad_usada')->nullable()->default(0);
            $table->integer('merma')->nullable()->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adornado_reposicions');
    }
};

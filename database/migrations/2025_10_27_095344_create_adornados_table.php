<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('adornados', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('adornados');
    }
};

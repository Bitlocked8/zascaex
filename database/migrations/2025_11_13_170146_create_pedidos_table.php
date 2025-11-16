<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('solicitud_pedido_id')->nullable()->constrained('solicitud_pedidos')->onDelete('set null');
            $table->foreignId('personal_id')->nullable()->constrained('personals')->onDelete('cascade');
            $table->tinyInteger('estado_pedido')->default(0);
            $table->text('observaciones')->nullable();
            $table->dateTime('fecha_pedido')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};

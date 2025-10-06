<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('traspasos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();

            $table->foreignId('reposicion_destino_id')
                  ->constrained('reposicions')
                  ->onDelete('cascade');

            $table->foreignId('personal_id')
                  ->constrained('personals')
                  ->onDelete('cascade');

            $table->integer('cantidad');
            $table->dateTime('fecha_traspaso');
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traspasos');
    }
};

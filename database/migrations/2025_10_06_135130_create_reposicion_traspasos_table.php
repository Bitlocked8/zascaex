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
        Schema::create('reposicion_traspasos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('traspaso_id')
                ->constrained('traspasos')
                ->onDelete('cascade');

            $table->foreignId('reposicion_id')
                ->constrained('reposicions')
                ->onDelete('cascade');

            $table->integer('cantidad');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reposicion_traspasos');
    }
};

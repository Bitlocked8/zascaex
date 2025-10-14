<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->string('empresa')->nullable();
            $table->string('nitCi')->nullable();
            $table->string('razonSocial')->nullable();
            $table->string('direccion')->nullable();
            $table->string('establecimiento')->nullable();
            $table->boolean('disponible')->default(1);
            $table->string('bot')->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('celular', 50)->nullable();
            $table->string('ubicacion')->nullable();
            $table->string('movil')->nullable();
            $table->string('dias')->nullable();
            $table->string('departamento_localidad')->nullable();
            $table->tinyInteger('categoria')->default(1);
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->string('foto')->nullable();
            $table->boolean('estado')->default(1);
            $table->boolean('verificado')->default(1);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};

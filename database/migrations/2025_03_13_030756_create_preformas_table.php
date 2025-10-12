<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('preformas', function (Blueprint $table) {
            $table->id();
            $table->string('imagen')->nullable();
            $table->string('detalle')->nullable();    
            $table->string('insumo')->nullable();    
            $table->string('gramaje')->nullable();  
            $table->string('cuello')->nullable();   
            $table->string('descripcion')->nullable();
            $table->string('capacidad')->nullable(); 
            $table->string('color')->nullable(); 
            $table->boolean('estado')->default(1); 
            $table->text('observaciones')->nullable(); 
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preformas');
    }
};

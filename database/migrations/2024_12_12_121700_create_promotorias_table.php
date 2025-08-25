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
        Schema::create('promotorias', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('competencia')->nullable();
            $table->foreignId('promotor_id')->nullable()->references('id')->on('promotores');
            $table->date('titularidade_promotor_data_inicio')->nullable();
            $table->date('titularidade_promotor_data_final')->nullable();
            $table->date('vacancia_data_inicio')->nullable();
            $table->foreignId('grupo_promotoria_id')->references('id')->on('grupo_promotorias');                       
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotorias');
    }
};

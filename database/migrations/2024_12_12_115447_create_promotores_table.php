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
        Schema::create('promotores', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->json('cargos')->nullable();
            $table->boolean('zona_eleitoral')->default(false);
            $table->string('numero_da_zona_eleitoral')->nullable();
            $table->date('periodo_inicio')->nullable();
            $table->date('periodo_fim')->nullable();
            $table->string('tipo')->default('titular');
            $table->boolean('is_substituto')->default(false);
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotores');
    }
};

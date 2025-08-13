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
        Schema::create('espelhos', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('periodo_id')->constrained('periodos')->onDelete('cascade');
            
            $table->foreignId('plantao_atendimento_id')->constrained('plantao_atendimento')->onDelete('cascade');
            
            $table->foreignId('grupo_promotorias_id')->constrained('grupo_promotorias')->onDelete('cascade');
            
            $table->foreignId('municipio_id')->constrained('municipios')->onDelete('cascade');
            
            $table->string('nome')->nullable(); 
            $table->text('observacoes')->nullable(); 
            $table->enum('status', ['ativo', 'inativo', 'pendente'])->default('ativo');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('espelhos');
    }
};

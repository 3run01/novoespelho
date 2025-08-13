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
        Schema::create('plantao_promotor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plantao_atendimento_id')->constrained('plantao_atendimento')->onDelete('cascade');
            $table->foreignId('promotor_id')->constrained('promotores')->onDelete('cascade');
            
            $table->date('data_inicio_designacao');
            $table->date('data_fim_designacao');
            
            $table->integer('ordem')->default(1);
            
            $table->string('tipo_designacao')->default('titular');
            
            // Status da designação
            $table->enum('status', ['ativo', 'inativo', 'pendente'])->default('ativo');
            
            $table->timestamps();
            
            // Índices para performance
            $table->index(['plantao_atendimento_id', 'promotor_id']);
            $table->index(['data_inicio_designacao', 'data_fim_designacao']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantao_promotor');
    }
};

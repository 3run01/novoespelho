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
            
            $table->date('data_inicio_designacao')->nullable();
            $table->date('data_fim_designacao')->nullable();
            
            $table->integer('ordem')->nullable()->default(1);
            
            $table->string('tipo_designacao')->nullable()->default('titular');
            
            // Status da designação
            $table->enum('status', ['ativo', 'inativo', 'pendente'])->nullable()->default('ativo');
            
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

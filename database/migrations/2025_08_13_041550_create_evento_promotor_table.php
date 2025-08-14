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
        Schema::create('evento_promotor', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            
            $table->foreignId('promotor_id')->constrained('promotores')->onDelete('cascade');
            
            $table->string('tipo')->nullable()->default('titular');
            
            $table->date('data_inicio_designacao')->nullable();
            $table->date('data_fim_designacao')->nullable();
            
            $table->integer('ordem')->nullable()->default(0);
            
            $table->text('observacoes')->nullable();
            
            $table->timestamps();
            
            $table->index(['evento_id', 'promotor_id']);
            $table->index(['promotor_id', 'data_inicio_designacao']);
            $table->index(['evento_id', 'data_inicio_designacao']);
            
            // Removida a constraint unique que impedia flexibilidade
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_promotor');
    }
};

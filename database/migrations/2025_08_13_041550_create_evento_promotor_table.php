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
            
            $table->string('tipo')->default('titular');
            
            $table->date('data_inicio_designacao');
            $table->date('data_fim_designacao');
            
            $table->integer('ordem')->default(0);
            
            $table->text('observacoes')->nullable();
            
            $table->timestamps();
            
            $table->index(['evento_id', 'promotor_id']);
            $table->index(['promotor_id', 'data_inicio_designacao']);
            $table->index(['evento_id', 'data_inicio_designacao']);
            
            // Evita duplicatas do mesmo promotor no mesmo evento com sobreposição de datas
            $table->unique(['evento_id', 'promotor_id', 'data_inicio_designacao']);
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

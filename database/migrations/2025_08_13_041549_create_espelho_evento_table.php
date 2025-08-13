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
        Schema::create('espelho_evento', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('espelho_id')->constrained('espelhos')->onDelete('cascade');
            
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            
            $table->integer('ordem')->default(0); 
            $table->text('observacoes_evento')->nullable(); 
            
            $table->timestamps();
            
            $table->index(['espelho_id', 'evento_id']);
            $table->unique(['espelho_id', 'evento_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('espelho_evento');
    }
};

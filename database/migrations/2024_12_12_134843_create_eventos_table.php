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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotoria_id')->constrained();
            $table->string('titulo')->nullable();
            $table->string('tipo');
            $table->foreignId('periodo_id')->constrained('periodos');
            $table->date('periodo_inicio');
            $table->date('periodo_fim');
            $table->foreignId('promotor_titular_id')->constrained('promotores');
            $table->foreignId('promotor_designado_id')->constrained('promotores');
            $table->boolean('is_urgente')->default(false);        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropForeign(['promotoria_id']);
            $table->dropForeign(['periodo_id']);
            $table->dropForeign(['promotor_titular_id']);
            $table->dropForeign(['promotor_designado_id']);
        });
        
        Schema::dropIfExists('eventos');
    }
};


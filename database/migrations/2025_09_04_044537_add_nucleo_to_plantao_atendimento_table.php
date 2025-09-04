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
        Schema::table('plantao_atendimento', function (Blueprint $table) {
            $table->integer('nucleo')->nullable()->after('municipio_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plantao_atendimento', function (Blueprint $table) {
            $table->dropColumn('nucleo');
        });
    }
};

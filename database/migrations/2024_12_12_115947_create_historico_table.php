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
        Schema::create('historico', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id'); 
            $table->foreign('users_id')->references('id')->on('users');
            $table->string('detalhes');
            $table->datetime('modificado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico');
    }
};

class AddTimestampsToHistoricoTable extends Migration
{
    public function up()
    {
        Schema::table('historico', function (Blueprint $table) {
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::table('historico', function (Blueprint $table) {
            $table->dropTimestamps(); 
        });
    }
}

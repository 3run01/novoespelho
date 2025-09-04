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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            $table->string('level', 20)->index();
            $table->text('message'); // << antes era string(500)
            $table->json('context')->nullable();

            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('url', 500)->nullable();
            $table->string('method', 10)->nullable();

            $table->string('action', 100)->nullable()->index();
            $table->string('model_type', 100)->nullable();

            $table->unsignedBigInteger('model_id')->nullable();

            $table->unsignedBigInteger('periodo_id')->nullable()->index();

            $table->string('session_id')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['model_type', 'model_id']);
            $table->index(['level', 'created_at']);
            $table->index(['periodo_id', 'created_at']);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('periodo_id')->references('id')->on('periodos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

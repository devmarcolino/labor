<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('candidatos_curtidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vaga_id')->nullable();
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresa_tb')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('user_tb')->onDelete('cascade');
            $table->foreign('vaga_id')->references('id')->on('vagas_tb')->onDelete('cascade');
            $table->unique(['empresa_id', 'user_id', 'vaga_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidatos_curtidos');
    }
};

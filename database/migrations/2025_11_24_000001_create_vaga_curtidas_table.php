<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('vaga_curtidas', function (Blueprint $table) {
            $table->id();

            // FK para usuarios
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('user_tb')->onDelete('cascade');

            // FK para vagas
            $table->unsignedBigInteger('vaga_id');
            $table->foreign('vaga_id')->references('id')->on('vagas_tb')->onDelete('cascade');

            $table->timestamps();

            // Impede curtir a mesma vaga duas vezes
            $table->unique(['user_id', 'vaga_id']);
        });
    }

    public function down() {
        Schema::dropIfExists('vaga_curtidas');
    }
};

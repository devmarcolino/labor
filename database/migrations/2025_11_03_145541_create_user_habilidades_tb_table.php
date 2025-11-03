<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_habilidades_tb', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idUser')->nullable();
            $table->foreign('idUser')->references('id')->on('user_tb')->onDelete('cascade');
            $table->unsignedBigInteger('idHabilidade')->nullable();
            $table->foreign('idHabilidade')->references('id')->on('habilidades_tb')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_habilidades_tb');
    }
};

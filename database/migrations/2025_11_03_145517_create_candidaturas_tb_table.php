<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('candidaturas_tb', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idUser')->nullable();
            $table->foreign('idUser')->references('id')->on('user_tb')->onDelete('cascade');
            $table->unsignedBigInteger('idVaga')->nullable();
            $table->foreign('idVaga')->references('id')->on('vagas_tb')->onDelete('cascade');
            $table->date('dataCandidatura')->nullable();
            $table->string('status', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidaturas_tb');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('escala_tb', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idUser')->nullable();
            $table->foreign('idUser')->references('id')->on('user_tb')->onDelete('cascade');
            $table->unsignedBigInteger('idEmpresa')->nullable();
            $table->foreign('idEmpresa')->references('id')->on('empresa_tb')->onDelete('cascade');
            $table->unsignedBigInteger('idVaga')->nullable();
            $table->foreign('idVaga')->references('id')->on('vagas_tb')->onDelete('cascade');
            $table->date('dataDiaria');
            $table->time('horaDiaria');
            $table->decimal('gastoTotal', 10, 2)->nullable();
            $table->date('dataCriacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escala_tb');
    }
};

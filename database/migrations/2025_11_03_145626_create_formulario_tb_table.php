<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('formulario_tb', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idUser')->nullable();
            $table->foreign('idUser')->references('id')->on('user_tb')->onDelete('cascade');
            $table->unsignedBigInteger('idVaga')->nullable();
            $table->foreign('idVaga')->references('id')->on('vagas_tb')->onDelete('cascade');
            $table->text('p1')->nullable();
            $table->text('p2')->nullable();
            $table->text('p3')->nullable();
            $table->date('dataForm')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formulario_tb');
    }
};

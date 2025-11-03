<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vagas_tb', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idEmpresa')->nullable();
            $table->foreign('idEmpresa')->references('id')->on('empresa_tb')->onDelete('cascade');
            $table->string('tipoVaga', 100);
            $table->decimal('valor_vaga', 10, 2)->nullable();
            $table->date('dataVaga')->nullable();
            $table->text('descVaga')->nullable();
            $table->string('funcVaga', 100)->nullable();
            $table->string('imgVaga', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vagas_tb');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('empresa_tb', function (Blueprint $table) {
            $table->id();
            $table->string('nome_empresa', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('tel', 20)->unique();
            $table->string('cnpj', 18)->unique();
            $table->tinyInteger('status')->default(1);
            $table->string('fotoEmpresa')->nullable();
            $table->string('ramo', 100)->nullable();
            $table->text('desc_empresa')->nullable();
            $table->unsignedBigInteger('idEnd')->nullable();
            $table->foreign('idEnd')->references('id')->on('end_tb')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresa_tb');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_tb', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50);
            $table->string('email', 100)->unique();
            $table->string('cpf', 14)->unique();
            $table->date('datanasc')->nullable();
            $table->string('tel', 20)->nullable();
            $table->string('password');
            $table->string('fotoUser')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('nome_real', 100)->nullable();
            $table->unsignedBigInteger('idEnd')->nullable();
            $table->foreign('idEnd')->references('id')->on('end_tb')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_tb');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mensagem_tb', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idChat')->nullable();
            $table->foreign('idChat')->references('id')->on('chat_tb')->onDelete('cascade');
            $table->unsignedBigInteger('idRemetente');
            $table->unsignedBigInteger('idDestinatario');
            $table->enum('tipoRemetente', ['user', 'empresa']);
            $table->enum('tipoDestinatario', ['user', 'empresa']);
            $table->text('mensagem');
            $table->dateTime('horario')->default(now());
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensagem_tb');
    }
};

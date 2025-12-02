<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mensagem_tb', function (Blueprint $table) {
            $table->id();
            // ID do remetente (empresa ou user)
            $table->unsignedBigInteger('remetente_id')->index();
            // ID do destinatário (empresa ou user)
            $table->unsignedBigInteger('destinatario_id')->index();
            // Tipo do remetente: 'user' ou 'empresa'
            $table->enum('remetente_tipo', ['user', 'empresa']);
            // Tipo do destinatário: 'user' ou 'empresa'
            $table->enum('destinatario_tipo', ['user', 'empresa']);
            // Conteúdo da mensagem
            $table->text('mensagem')->nullable();
            // Caminho do arquivo (imagem ou vídeo)
            $table->string('arquivo')->nullable();
            // Data/hora da mensagem (default: agora)
            $table->dateTime('horario')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensagem_tb');
    }
};

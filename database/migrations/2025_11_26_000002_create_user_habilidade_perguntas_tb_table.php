<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_habilidade_perguntas_tb', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idUser');
            $table->unsignedBigInteger('idHabilidade');
            $table->unsignedBigInteger('idPergunta');
            $table->string('resposta', 255);
            $table->timestamps();

            $table->foreign('idUser')->references('id')->on('user_tb')->onDelete('cascade');
            $table->foreign('idHabilidade')->references('id')->on('habilidades_tb')->onDelete('cascade');
            $table->foreign('idPergunta')->references('id')->on('perguntas_tb')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_habilidade_perguntas_tb');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacoes_tb', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_avaliador')->constrained('user_tb')->onDelete('cascade');
            $table->foreignId('id_avaliado')->constrained('user_tb')->onDelete('cascade');

            // FK opcional para o trabalho; esse FK é seguro porque trabalhos_tb já existe (ordem das migrations)
            $table->unsignedBigInteger('id_trabalho')->nullable();
            $table->foreign('id_trabalho')->references('id')->on('trabalhos_tb')->onDelete('set null');

            $table->tinyInteger('nota')->comment('1 a 5');
            $table->text('comentario')->nullable();
            $table->enum('tipo_avaliacao', ['contratante', 'trabalhador']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes_tb');
    }
};

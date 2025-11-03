<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trabalhos_tb', function (Blueprint $table) {
            $table->id();

            // Relaciona o trabalhador (usuário)
            $table->unsignedBigInteger('idUser')->nullable();
            $table->foreign('idUser')->references('id')->on('user_tb')->onDelete('cascade');
            $table->unsignedBigInteger('idEmpresa')->nullable();
            $table->foreign('idEmpresa')->references('id')->on('empresa_tb')->onDelete('cascade');
            // Relaciona com a vaga (opcional)
            $table->unsignedBigInteger('id_vaga')->nullable();
            $table->foreign('id_vaga')->references('id')->on('vagas_tb')->onDelete('set null');

            // Datas do trabalho
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();

            // Valor combinado (se aplicável)
            $table->decimal('valor', 10, 2)->nullable();

            // Status do trabalho
            $table->enum('status', ['em_andamento', 'finalizado', 'cancelado'])->default('em_andamento');

            // Se quiser guardar os IDs das avaliações no trabalho, salve como unsignedBigInteger sem FK
            // (opcional — pode ser nulo até as avaliações existirem)
            $table->unsignedBigInteger('id_avaliacao_contratante')->nullable();
            $table->unsignedBigInteger('id_avaliacao_trabalhador')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trabalhos_tb');
    }
};

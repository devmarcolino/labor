<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_habilidade_perguntas_tb', function (Blueprint $table) {
            
            // 1. Remove a coluna 'resposta' SE ela existir
            if (Schema::hasColumn('user_habilidade_perguntas_tb', 'resposta')) {
                $table->dropColumn('resposta');
            }

            // 2. Adiciona 'idOpcao' SE ela NÃO existir
            if (!Schema::hasColumn('user_habilidade_perguntas_tb', 'idOpcao')) {
                $table->unsignedBigInteger('idOpcao');
                $table->foreign('idOpcao')->references('id')->on('opcoes_tb')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        // (Opcional: Reverter)
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_habilidade_perguntas_tb', function (Blueprint $table) {
            $table->integer('nota')->nullable(); // Pontos da opção selecionada
        });
    }

    public function down(): void
    {
        Schema::table('user_habilidade_perguntas_tb', function (Blueprint $table) {
            $table->dropColumn('nota');
        });
    }
};
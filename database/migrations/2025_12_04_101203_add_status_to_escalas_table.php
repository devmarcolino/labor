<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // CONFIRA SE O NOME DA TABELA É 'escalas' OU 'escala_tb' AQUI EMBAIXO 👇
        Schema::table('escala_tb', function (Blueprint $table) {
            
            // Adiciona a coluna status com valor padrão 'pendente'
            // O 'after' é só pra organizar visualmente no banco, pode tirar se der erro
            $table->string('status')->default('pendente'); 
            
        });
    }

    public function down()
    {
        Schema::table('escala_tb', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
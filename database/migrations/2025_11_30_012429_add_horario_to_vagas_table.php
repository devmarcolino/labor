<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adiciona a coluna.
     */
    public function up(): void
    {
        Schema::table('vagas_tb', function (Blueprint $table) {
            // Adiciona 'horario' depois de 'dataVaga'
            // Exemplo de dado: "18:00 às 23:00" ou "Comercial"
            $table->string('horario', 50)->nullable()->after('dataVaga');
        });
    }

    /**
     * Remove a coluna (caso precise desfazer).
     */
    public function down(): void
    {
        Schema::table('vagas_tb', function (Blueprint $table) {
            $table->dropColumn('horario');
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('vagas_tb', function (Blueprint $table) {
        // Garante que temos a coluna 'horario' do tipo string
        if (!Schema::hasColumn('vagas_tb', 'horario')) {
            $table->string('horario', 50)->nullable()->after('dataVaga');
        }
        
        // Se existirem as colunas separadas (lixo), removemos
        if (Schema::hasColumn('vagas_tb', 'hora_inicio')) {
            $table->dropColumn(['hora_inicio', 'hora_fim']);
        }
    });
}
};

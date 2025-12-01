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
        // 1 = Ativa (Padrão), 0 = Concluída/Fechada
        $table->tinyInteger('status')->default(1)->after('horario');
    });
}

public function down(): void
{
    Schema::table('vagas_tb', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mensagem_tb', function (Blueprint $table) {
            $table->boolean('lida')->default(false)->after('horario');
        });
    }

    public function down(): void
    {
        Schema::table('mensagem_tb', function (Blueprint $table) {
            $table->dropColumn('lida');
        });
    }
};

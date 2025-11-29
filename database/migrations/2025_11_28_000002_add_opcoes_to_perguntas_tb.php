<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('perguntas_tb', function (Blueprint $table) {
            $table->text('opcoes')->nullable()->after('tipo');
        });
    }

    public function down()
    {
        Schema::table('perguntas_tb', function (Blueprint $table) {
            $table->dropColumn('opcoes');
        });
    }
};

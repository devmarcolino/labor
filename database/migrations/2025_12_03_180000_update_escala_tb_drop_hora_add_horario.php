<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('escala_tb', function (Blueprint $table) {
            if (Schema::hasColumn('escala_tb', 'horaDiaria')) {
                $table->dropColumn('horaDiaria');
            }
            if (!Schema::hasColumn('escala_tb', 'horario')) {
                $table->string('horario', 50)->nullable()->after('dataDiaria');
            }
        });
    }

    public function down(): void
    {
        Schema::table('escala_tb', function (Blueprint $table) {
            if (Schema::hasColumn('escala_tb', 'horario')) {
                $table->dropColumn('horario');
            }
            if (!Schema::hasColumn('escala_tb', 'horaDiaria')) {
                $table->time('horaDiaria')->nullable()->after('dataDiaria');
            }
        });
    }
};

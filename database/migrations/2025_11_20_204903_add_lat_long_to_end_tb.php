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
        Schema::table('end_tb', function (Blueprint $table) {
            // Adiciona colunas para Latitude e Longitude
            // decimal(10, 8) é o padrão para coordenadas de GPS precisas
            $table->decimal('latitude', 10, 8)->nullable()->after('cep');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('end_tb', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
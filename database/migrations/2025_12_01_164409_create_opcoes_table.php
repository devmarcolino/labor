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
    Schema::create('opcoes_tb', function (Blueprint $table) {
        $table->id();
        $table->foreignId('idPergunta')->constrained('perguntas_tb')->onDelete('cascade');
        $table->string('texto'); // Ex: "Tenho experiência", "Não tenho"
        $table->integer('pontos'); // Ex: 100, 0
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opcoes_tb');
    }
};

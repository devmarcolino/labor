<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('avaliacoes_tb', function (Blueprint $table) {
        $table->id();

        // Quem avaliou (Empresa) e Quem foi avaliado (Freelancer)
        $table->foreignId('id_avaliador')->constrained('empresa_tb'); // Ajuste 'users' se sua tabela for 'user_tb'
        $table->foreignId('id_avaliado')->constrained('user_tb');

        // Link com a escala (para saber de qual trabalho foi essa nota)
        $table->foreignId('escala_id')->nullable()->constrained('escala_tb')->onDelete('cascade');

        $table->tinyInteger('nota')->comment('1 a 5');
        $table->text('comentario')->nullable();
        
        // Como só a empresa avalia por enquanto, deixamos default
        $table->enum('tipo_avaliacao', ['contratante', 'trabalhador'])->default('contratante');

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes_tb');
    }
};

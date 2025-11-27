<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('perguntas_tb', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idHabilidade'); // FK para habilidade
            $table->string('texto', 255);
            $table->enum('tipo', ['cotidiano', 'socioemocional']);
            $table->timestamps();

            $table->foreign('idHabilidade')->references('id')->on('habilidades_tb')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perguntas_tb');
    }
};

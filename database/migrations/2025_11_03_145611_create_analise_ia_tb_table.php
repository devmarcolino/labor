<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('analise_ia_tb', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idCandidatura')->nullable();
            $table->foreign('idCandidatura')->references('id')->on('candidaturas_tb')->onDelete('cascade');
            $table->string('statusSugestao', 100)->nullable();
            $table->decimal('notaIA', 3, 1)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analise_ia_tb');
    }
};

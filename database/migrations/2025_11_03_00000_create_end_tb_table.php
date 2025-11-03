<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('end_tb', function (Blueprint $table) {
            $table->id();
            $table->string('cep', 10)->nullable();
            $table->string('rua', 100)->nullable();
            $table->string('numero', 10)->nullable();
            $table->string('comp', 50)->nullable();
            $table->string('bairro', 50)->nullable();
            $table->string('cidade', 50)->nullable();
            $table->char('uf', 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('end_tb');
    }
};
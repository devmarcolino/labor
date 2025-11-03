<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('habilidades_tb', function (Blueprint $table) {
            $table->id();
            $table->string('nomeHabilidade', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habilidades_tb');
    }
};

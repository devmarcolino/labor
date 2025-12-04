<?php


    /**
     * Run the migrations.
     */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gastos_empresas_tb', function (Blueprint $table) {
            $table->id();
            
            // Chaves estrangeiras (assumindo que suas tabelas sejam 'users', 'vagas', 'escalas')
            // Ajuste os nomes das tabelas referenciadas ('on') se for necessário
            $table->foreignId('empresa_id')->constrained('empresa_tb'); 
            $table->foreignId('vaga_id')->constrained('vagas_tb'); 
            $table->foreignId('escala_id')->constrained('escala_tb')->onDelete('cascade'); // Se deletar a escala, apaga o gasto

            // Snapshot dos dados (Cópia fiel do momento da confirmação)
            $table->string('funcao');      // Ex: Garçom (Copiado de funcVaga)
            $table->decimal('valor', 10, 2); // Ex: 150.00 (Copiado de valorVaga)
            
            // Datas para filtros rápidos
            $table->timestamp('data_confirmacao'); 
            $table->timestamps();

            // Índices para performance extrema nos relatórios
            // Isso faz o select sum() ser quase instantâneo
            $table->index(['empresa_id', 'data_confirmacao']); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('gastos_empresas_tb');
    }
};

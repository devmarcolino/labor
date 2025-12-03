<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. TENTATIVA DE RENOMEAR (Para Salvar Dados Antigos)
        // Baseado no seu padrão antigo que era 'idNome'
        Schema::table('mensagem_tb', function (Blueprint $table) {
            
            // Se existir 'idRemetente', renomeia para 'remetente_id'
            if (Schema::hasColumn('mensagem_tb', 'idRemetente')) {
                $table->renameColumn('idRemetente', 'remetente_id');
            }

            // Se existir 'idDestinatario', renomeia para 'destinatario_id'
            if (Schema::hasColumn('mensagem_tb', 'idDestinatario')) {
                $table->renameColumn('idDestinatario', 'destinatario_id');
            }
        });

        // 2. CRIAÇÃO/AJUSTE DE COLUNAS (Garante que a estrutura final esteja certa)
        Schema::table('mensagem_tb', function (Blueprint $table) {
            
            // Se por algum motivo a renomeação falhou ou a coluna não existia, cria agora
            if (!Schema::hasColumn('mensagem_tb', 'remetente_id')) {
                $table->unsignedBigInteger('remetente_id')->index()->after('id');
            }

            if (!Schema::hasColumn('mensagem_tb', 'destinatario_id')) {
                $table->unsignedBigInteger('destinatario_id')->index()->after('remetente_id');
            }

            // Adiciona as colunas novas de TIPO se não existirem
            if (!Schema::hasColumn('mensagem_tb', 'remetente_tipo')) {
                $table->enum('remetente_tipo', ['user', 'empresa'])->after('destinatario_id');
            }

            if (!Schema::hasColumn('mensagem_tb', 'destinatario_tipo')) {
                $table->enum('destinatario_tipo', ['user', 'empresa'])->after('remetente_tipo');
            }

            // Adiciona coluna arquivo se não existir
            if (!Schema::hasColumn('mensagem_tb', 'arquivo')) {
                $table->string('arquivo')->nullable()->after('mensagem');
            }

            // Adiciona coluna horario se não existir
            if (!Schema::hasColumn('mensagem_tb', 'horario')) {
                $table->dateTime('horario')->useCurrent()->after('arquivo');
            }
            $table->boolean('lida')->default(false)->after('horario');
        });
    }

    public function down(): void
    {
        // Reverte as mudanças (opcional, tenta voltar ao padrão antigo)
        Schema::table('mensagem_tb', function (Blueprint $table) {
            if (Schema::hasColumn('mensagem_tb', 'remetente_id')) {
                $table->renameColumn('remetente_id', 'idRemetente');
            }
            if (Schema::hasColumn('mensagem_tb', 'destinatario_id')) {
                $table->renameColumn('destinatario_id', 'idDestinatario');
            }
            $table->dropColumn(['remetente_tipo', 'destinatario_tipo', 'arquivo', 'horario']);
        });
    }
};
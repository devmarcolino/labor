<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HabilidadesSeeder extends Seeder
{
    /**
     * Seed the habilidades table.
     */
    public function run(): void
    {
        // Criar 3 habilidades
        $habilidades = [
            ['nomeHabilidade' => 'Comunicação', 'created_at' => now(), 'updated_at' => now()],
            ['nomeHabilidade' => 'Resolução de Problemas', 'created_at' => now(), 'updated_at' => now()],
            ['nomeHabilidade' => 'Trabalho em Equipe', 'created_at' => now(), 'updated_at' => now()],
        ];

        $habilidadesIds = [];
        foreach ($habilidades as $hab) {
            $id = DB::table('habilidades_tb')->insertGetId($hab);
            $habilidadesIds[] = $id;
        }

        // Perguntas para Comunicação (idHabilidade = 1)
        $perguntas = [
            [
                'idHabilidade' => $habilidadesIds[0],
                'texto' => 'Você consegue expressar suas ideias claramente?',
                'tipo' => 'socioemocional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idHabilidade' => $habilidadesIds[0],
                'texto' => 'Como você lida com feedback construtivo?',
                'tipo' => 'cotidiano',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Perguntas para Resolução de Problemas
            [
                'idHabilidade' => $habilidadesIds[1],
                'texto' => 'Você consegue identificar a raiz de um problema?',
                'tipo' => 'cotidiano',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idHabilidade' => $habilidadesIds[1],
                'texto' => 'Como você aborda desafios complexos?',
                'tipo' => 'socioemocional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Perguntas para Trabalho em Equipe
            [
                'idHabilidade' => $habilidadesIds[2],
                'texto' => 'Você colabora bem com seus colegas?',
                'tipo' => 'socioemocional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idHabilidade' => $habilidadesIds[2],
                'texto' => 'Como você contribui para um ambiente de trabalho positivo?',
                'tipo' => 'cotidiano',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $perguntasIds = [];
        foreach ($perguntas as $pergunta) {
            $id = DB::table('perguntas_tb')->insertGetId($pergunta);
            $perguntasIds[] = $id;
        }

        // Opções para as perguntas
        $opcoes = [
            // Opções pergunta 1 (Comunicação)
            ['idPergunta' => $perguntasIds[0], 'texto' => 'Sempre', 'pontos' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[0], 'texto' => 'Frequentemente', 'pontos' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[0], 'texto' => 'Às vezes', 'pontos' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[0], 'texto' => 'Raramente', 'pontos' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[0], 'texto' => 'Nunca', 'pontos' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Opções pergunta 2 (Comunicação)
            ['idPergunta' => $perguntasIds[1], 'texto' => 'Sempre acolho bem', 'pontos' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[1], 'texto' => 'Geralmente acolho', 'pontos' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[1], 'texto' => 'Sou indiferente', 'pontos' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[1], 'texto' => 'Tenho dificuldade', 'pontos' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[1], 'texto' => 'Rejeito feedback', 'pontos' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Opções pergunta 3 (Resolução de Problemas)
            ['idPergunta' => $perguntasIds[2], 'texto' => 'Excelente', 'pontos' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[2], 'texto' => 'Boa', 'pontos' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[2], 'texto' => 'Média', 'pontos' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[2], 'texto' => 'Fraca', 'pontos' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[2], 'texto' => 'Muito fraca', 'pontos' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Opções pergunta 4 (Resolução de Problemas)
            ['idPergunta' => $perguntasIds[3], 'texto' => 'De forma sistemática', 'pontos' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[3], 'texto' => 'Com planejamento', 'pontos' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[3], 'texto' => 'Intuitivamente', 'pontos' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[3], 'texto' => 'De forma caótica', 'pontos' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[3], 'texto' => 'Evito desafios', 'pontos' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Opções pergunta 5 (Trabalho em Equipe)
            ['idPergunta' => $perguntasIds[4], 'texto' => 'Muito bem', 'pontos' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[4], 'texto' => 'Bem', 'pontos' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[4], 'texto' => 'Razoavelmente', 'pontos' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[4], 'texto' => 'Mal', 'pontos' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[4], 'texto' => 'Muito mal', 'pontos' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Opções pergunta 6 (Trabalho em Equipe)
            ['idPergunta' => $perguntasIds[5], 'texto' => 'Sempre', 'pontos' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[5], 'texto' => 'Frequentemente', 'pontos' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[5], 'texto' => 'Ocasionalmente', 'pontos' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[5], 'texto' => 'Raramente', 'pontos' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['idPergunta' => $perguntasIds[5], 'texto' => 'Nunca', 'pontos' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('opcoes_tb')->insert($opcoes);
    }
}

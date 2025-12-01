<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserHabilidadePergunta;

class WorkerRespostaController extends Controller
{
    /**
     * Salva as respostas do questionário de onboarding.
     */
    public function salvarRespostas(Request $request)
    {
        $user = Auth::user();
        
        // O front manda um array: [ pergunta_id => opcao_id ]
        $respostas = $request->input('respostas', []);
        $habilidades = $request->input('habilidades', []);

        // 1. Salva as Habilidades primeiro (Sync)
        if (!empty($habilidades)) {
            $user->skills()->sync($habilidades);
        }

        // 2. Salva as Respostas das Perguntas
        foreach ($respostas as $idPergunta => $idOpcao) {
            // Descobre a qual habilidade essa pergunta pertence (opcional, mas bom pra organização)
            // Aqui simplificamos salvando direto
            
            UserHabilidadePergunta::updateOrCreate(
                [
                    'idUser' => $user->id,
                    'idPergunta' => $idPergunta
                ],
                [
                    // 'idHabilidade' => ... (se precisar salvar, busque da pergunta)
                    'idOpcao' => $idOpcao // Salva o ID da opção (com os pontos)
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Perfil salvo com sucesso!']);
    }
}
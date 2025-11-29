<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidatura;
use Illuminate\Support\Facades\Auth;
// ...existing code...

class CandidaturaController extends Controller
{
    public function store(Request $request)
    {
        $user_id = Auth::id();
        $vaga_id = $request->vaga_id;

        if (!$user_id) {
            return response()->json(['success' => false, 'message' => 'Usuário não autenticado.'], 401);
        }
        if (!$vaga_id) {
            return response()->json(['success' => false, 'message' => 'ID da vaga não enviado.'], 400);
        }
        $request->validate([
            'vaga_id' => 'required|exists:vagas_tb,id',
        ]);

        // Busca usuário, vaga e dados
        $user = \App\Models\User::find($user_id);
        $vaga = \App\Models\Vaga::find($vaga_id);

        // Habilidades da vaga
        $skills_vaga = [];
        if ($vaga && $vaga->funcVaga) {
            $skills_vaga[] = $vaga->funcVaga;
        }

        // Habilidades do usuário
        $skills_user = $user->skills()->pluck('idHabilidade')->toArray();

        // Experiência (exemplo: pode ser ajustado para buscar de outro campo)
        $experiencia = 0;
        if (isset($user->experiencia)) {
            $experiencia = $user->experiencia;
        }

        // Respostas do usuário para as perguntas das habilidades que ele selecionou
        $respostas = \App\Models\UserHabilidadePergunta::where('idUser', $user_id)
            ->whereIn('idHabilidade', $skills_user)
            ->get();
        $respostas_json = [];
        foreach ($respostas as $resp) {
            $respostas_json[] = [
                'idPergunta' => $resp->idPergunta,
                'resposta' => $resp->resposta,
                'idHabilidade' => $resp->idHabilidade,
                'nota' => is_numeric($resp->resposta) ? floatval($resp->resposta) : 0
            ];
        }

        // Perfil
        $perfil_completo = !empty($user->nome_real) && !empty($user->fotoUser);


        // Calcula nota manualmente
        $valores = [
            'Ruim' => 20,
            'Regular' => 40,
            'Bom' => 60,
            'Ótimo' => 80,
            'Excelente' => 100
        ];
        $notas = [];
        foreach ($respostas_json as $r) {
            $resp = $r['resposta'];
            $notas[] = $valores[$resp] ?? 0;
        }
        $nota_ia = count($notas) ? intval(array_sum($notas) / count($notas)) : 0;
        $explicacao_ia = 'Nota calculada automaticamente pela média das respostas.';

        // Salva candidatura
        $candidatura = Candidatura::firstOrCreate([
            'idUser' => $user_id,
            'idVaga' => $vaga_id,
        ], [
            'dataCandidatura' => now(),
            'status' => 'ativa',
            'nota_ia' => $nota_ia,
        ]);

        // Se já existe, atualiza a nota
        if ($candidatura->nota_ia !== $nota_ia) {
            $candidatura->nota_ia = $nota_ia;
            $candidatura->save();
        }

        return response()->json([
            'success' => true,
            'candidatura' => $candidatura,
            'nota_ia' => $nota_ia,
            'explicacao_ia' => $explicacao_ia,
        ]);
    }
}

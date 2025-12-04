<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidatura;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
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
            $skills_vaga[] = $vaga->funcVaga; // funcVaga é o ID da habilidade
        }

        // Habilidades do usuário
        $skills_user = $user->skills()->pluck('idHabilidade')->toArray();

        // Habilidades em comum (usuário deve ter a habilidade da vaga para calcular nota)
        $common_skills = array_intersect($skills_user, $skills_vaga);

        // Experiência (exemplo: pode ser ajustado para buscar de outro campo)
        $experiencia = 0;
        if (isset($user->experiencia)) {
            $experiencia = $user->experiencia;
        }

        // Respostas do usuário para as perguntas das habilidades em comum
        $respostas = \App\Models\UserHabilidadePergunta::where('idUser', $user_id)
            ->whereIn('idHabilidade', $common_skills)
            ->with('opcao')
            ->get();
        $respostas_json = [];
        foreach ($respostas as $resp) {
            $pontos = $resp->nota ?? ($resp->opcao ? $resp->opcao->pontos : 0);
            $respostas_json[] = [
                'idPergunta' => $resp->idPergunta,
                'idOpcao' => $resp->idOpcao,
                'pontos' => $pontos,
                'idHabilidade' => $resp->idHabilidade,
            ];
        }

        // Perfil
        $perfil_completo = !empty($user->nome_real) && !empty($user->fotoUser);


        // Calcula nota manualmente
        $notas = [];
        foreach ($respostas_json as $r) {
            $notas[] = $r['pontos'];
        }
        $nota_ia = count($notas) ? intval(array_sum($notas) / count($notas)) : 0;
        $explicacao_ia = 'Nota calculada automaticamente pela média dos pontos das respostas.';

        // Salva candidatura
        $candidatura = Candidatura::firstOrCreate([
            'idUser' => $user_id,
            'idVaga' => $vaga_id,
        ], [
            'dataCandidatura' => now(),
            'status' => '1',
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

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidatura;
use Illuminate\Support\Facades\Auth;

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

        // Monta JSON para IA
        $input_ia = [
            'candidatos' => [[
                'nome' => $user->nome_real ?? $user->username,
                'skills' => $skills_user,
                'experiencia' => $experiencia,
                'respostas' => $respostas_json,
                'foto' => $user->fotoUser,
                'perfil_completo' => $perfil_completo,
            ]],
            'vaga' => [
                'skills' => $skills_vaga,
            ]
        ];

        // Executa script Python
        $python = base_path('python_ai/analisador_candidatos.py');
        $input_json = json_encode($input_ia, JSON_UNESCAPED_UNICODE);
        $cmd = "python \"$python\"";
        $process = proc_open($cmd, [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ], $pipes);
        $output = '';
        if (is_resource($process)) {
            fwrite($pipes[0], $input_json);
            fclose($pipes[0]);
            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);
        }

        $nota_ia = null;
        $explicacao_ia = null;
        // Debug: loga o output do Python para storage/logs/laravel.log
        \Log::info('Python IA output: ' . $output);
        if ($output) {
            $result = json_decode($output, true);
            if (is_array($result) && count($result) > 0) {
                $nota_ia = $result[0]['score'] ?? null;
                $explicacao_ia = $result[0]['explicacao'] ?? null;
            }
        }

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

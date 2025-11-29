<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserHabilidadePergunta;

class WorkerRespostaController extends Controller
{
    public function salvarRespostas(Request $request)
    {
        $user_id = Auth::id();
        $idHabilidade = $request->input('idHabilidade');
        $respostas = $request->input('respostas', []);

        foreach ($respostas as $idPergunta => $resposta) {
            UserHabilidadePergunta::updateOrCreate([
                'idUser' => $user_id,
                'idPergunta' => $idPergunta,
            ], [
                'idHabilidade' => $idHabilidade,
                'resposta' => $resposta,
            ]);
        }

        return redirect()->back()->with('success', 'Respostas salvas com sucesso!');
    }
}

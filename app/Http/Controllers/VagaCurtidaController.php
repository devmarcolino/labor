<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VagaCurtida;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class VagaCurtidaController extends Controller
{

    public function index()
    {
        $userId = auth()->id();
        $vagasCurtidas = \App\Models\VagaCurtida::where('user_id', $userId)
            ->with('vaga')
            ->get();
        return view('workers.vagas-curtidas', [
            'vagasCurtidas' => $vagasCurtidas
        ]);
    }

    public function interagir(Request $request)
    {
        $user_id = auth()->id();
        $vaga_id = $request->vaga_id;
        $tipo = $request->tipo; // 'curtida' ou 'rejeitada'
        if (!$user_id || !$vaga_id || !$tipo) {
            return response()->json(['success' => false, 'message' => 'Dados insuficientes.'], 400);
        }
        $key = 'interacoes_user_' . $user_id;
        $interacoes = Cache::get($key, []);
        $interacoes[$vaga_id] = $tipo;
        Cache::put($key, $interacoes, now()->addDays(7));
        return response()->json(['success' => true, 'interacoes' => $interacoes]);
    }
}

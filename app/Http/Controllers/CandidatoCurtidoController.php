<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CandidatoCurtido;
use Illuminate\Support\Facades\Cache;

class CandidatoCurtidoController extends Controller
{
        public function store(Request $request)
{
    \Log::info('CandidatoCurtidoController@store', [
        'auth_user' => auth()->user(),
        'request' => $request->all()
    ]);

    $validated = $request->validate([
        'vaga_id' => 'required|integer',
        'user_id' => 'required|integer',
    ]);

    $empresa_id = auth()->id();
    $vaga_id = $validated['vaga_id'];
    $user_id = $validated['user_id'];

    $cacheKey = "empresa:{$empresa_id}:vaga:{$vaga_id}:candidatos_vistos";

    try {
        CandidatoCurtido::create([
            'empresa_id' => $empresa_id,
            'user_id' => $user_id,
            'vaga_id' => $vaga_id,
        ]);

        // ADICIONA AO CACHE MESMO COM SUCESSO
        $vistos = Cache::get($cacheKey, []);
        $vistos[] = $user_id;
        Cache::put($cacheKey, array_unique($vistos), now()->addMonths(6));

        return response()->json(['success' => true]);

    } catch (\Exception $e) {

        \Log::error('Erro ao curtir candidato', [
            'exception' => $e->getMessage(),
        ]);

        // MESMA LÓGICA NO ERRO
        $vistos = Cache::get($cacheKey, []);
        $vistos[] = $user_id;
        Cache::put($cacheKey, array_unique($vistos), now()->addMonths(6));

        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
}


        public function rejeitar(Request $request)
{
    $validated = $request->validate([
        'vaga_id' => 'required|integer',
        'user_id' => 'required|integer'
    ]);

    $vaga_id = $validated['vaga_id'];
    $user_id = $validated['user_id'];
    $empresa_id = Auth::id(); // garante o id da empresa logada

    try {
        // ATENÇÃO: usa os nomes reais das colunas da sua tabela candidaturas_tb
        // Pelo que vi nas models, as colunas são idVaga e idUser e status
        \DB::table('candidaturas_tb')
            ->where('idVaga', $vaga_id)
            ->where('idUser', $user_id)
            ->update(['status' => 0]); // 0 = rejeitado / não aprovado

        // cache: marca como visto para não reaparecer
        $cacheKey = "empresa:{$empresa_id}:vaga:{$vaga_id}:candidatos_vistos";
        $vistos = Cache::get($cacheKey, []);
        $vistos[] = $user_id;
        Cache::put($cacheKey, array_values(array_unique($vistos)), now()->addMonths(6));

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        Log::error('Erro ao rejeitar candidatura', [
            'vaga_id' => $vaga_id,
            'user_id' => $user_id,
            'erro' => $e->getMessage()
        ]);

        // mesmo em erro, tenta colocar no cache para evitar repetição (opcional)
        $cacheKey = "empresa:{$empresa_id}:vaga:{$vaga_id}:candidatos_vistos";
        $vistos = Cache::get($cacheKey, []);
        $vistos[] = $user_id;
        Cache::put($cacheKey, array_values(array_unique($vistos)), now()->addMonths(6));

        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

}
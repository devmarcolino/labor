<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vaga;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CandidatoFeedController extends Controller
{
    /**
     * FEED PRINCIPAL — pega o melhor candidato de cada vaga
     */
    public function feed()
    {
        $empresaId = Auth::id();

        $vagas = Vaga::where('idEmpresa', $empresaId)
            ->with(['candidaturas.user.endereco'])
            ->get();

        $cards = [];

        foreach ($vagas as $vaga) {

            if ($vaga->candidaturas->count() === 0) {
                continue;
            }

            // 🔥 Carregando candidatos já vistos dessa vaga
            $cacheKey = "empresa:{$empresaId}:vaga:{$vaga->id}:candidatos_vistos";
            $vistos = Cache::get($cacheKey, []);

            // 🔥 Filtrar candidatos já vistos
            $naoVistos = $vaga->candidaturas->filter(function ($c) use ($vistos) {
                return !in_array($c->idUser, $vistos);
            });

            if ($naoVistos->count() === 0) {
                continue; // não tem ninguém novo para mostrar
            }

            // Ordena normalmente pela IA
            $melhor = $naoVistos->sortByDesc('nota_ia')->first();

            if (!$melhor || !$melhor->user) continue;

            $user = $melhor->user;

            // idade
            $idade = $user->datanasc 
                ? Carbon::parse($user->datanasc)->age . " anos"
                : null;

            $cards[] = [
                'id' => $vaga->id,
                'titulo_vaga' => $vaga->tipoVaga,
                'match_percent' => (int) $melhor->nota_ia,
                'total_candidatos' => $naoVistos->count(),

                'candidato' => [
                    'id' => $user->id,
                    'nome' => explode(" ", $user->nome_real)[0],
                    'idade' => $idade,
                    'cidade' => $user->endereco->cidade ?? "Não informado",
                    'foto' => $user->fotoUser ? asset("storage/".$user->fotoUser) : asset("img/default-avatar.png"),
                ],
            ];
        }

        return response()->json($cards);
    }

    /**
     * Lista de TODOS os candidatos (modal)
     */
    public function candidatosModal($vagaId)
    {
        $empresaId = Auth::id();

        $vaga = Vaga::with(['candidaturas.user.endereco'])
            ->findOrFail($vagaId);

        // 🔥 Puxando candidatos já vistos dessa vaga
        $cacheKey = "empresa:{$empresaId}:vaga:{$vaga->id}:candidatos_vistos";
        $vistos = Cache::get($cacheKey, []);

        // 🔥 Filtra da modal também
        $lista = $vaga->candidaturas
            ->filter(function ($c) use ($vistos) {
                return !in_array($c->idUser, $vistos);
            })
            ->sortByDesc('nota_ia')
            ->map(function ($c) {
                $user = $c->user;

                return [
                    'id' => $user->id,
                    'nome' => $user->nome_real,
                    'idade' => $user->datanasc ? Carbon::parse($user->datanasc)->age . " anos" : null,
                    'cidade' => $user->endereco->cidade ?? null,
                    'foto' => $user->fotoUser ? asset("storage/".$user->fotoUser) : asset("img/default-avatar.png"),
                    'match_percent' => (int) $c->nota_ia,
                ];
            })
            ->values();

        return response()->json([
            'vaga' => [
                'id' => $vaga->id,
                'titulo' => $vaga->tipoVaga,
            ],
            'candidatos' => $lista
        ]);
    }
}

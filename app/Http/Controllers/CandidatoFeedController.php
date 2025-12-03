<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vaga;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CandidatoFeedController extends Controller
{
    /**
     * FEED PRINCIPAL — pega o melhor candidato de cada vaga
     */
    public function feed()
    {
        $empresaId = Auth::id();

        // Corrigido: usa idEmpresa
        $vagas = Vaga::where('idEmpresa', $empresaId)
            ->with(['candidaturas.user.endereco'])
            ->get();

        $cards = [];

        foreach ($vagas as $vaga) {

            if ($vaga->candidaturas->count() === 0) {
                continue;
            }

            // Ordenando pela NOTA IA correta
            $melhor = $vaga->candidaturas
                ->sortByDesc('nota_ia')
                ->first();

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
                'total_candidatos' => $vaga->candidaturas->count(),

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
        $vaga = Vaga::with(['candidaturas.user.endereco'])
            ->findOrFail($vagaId);

        $lista = $vaga->candidaturas
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

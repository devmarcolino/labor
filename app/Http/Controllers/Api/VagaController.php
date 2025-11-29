<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vaga;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VagaController extends Controller
{
    /**
     * Recomendação IA: retorna melhor candidato e lista ordenada
     */
    public function melhorCandidato(int $id)
    {
        $vaga = Vaga::with(['candidaturas.user.skills', 'candidaturas.respostas'])->findOrFail($id);

        $candidatos = $vaga->candidaturas->map(function ($candidatura) use ($vaga) {
            $user = $candidatura->user;

            // 1. Compatibilidade de skills
            $skillsVaga = is_array($vaga->funcVaga) ? $vaga->funcVaga : [$vaga->funcVaga];
            $skillsVaga = array_filter($skillsVaga);

            $skillsUser = $user->skills->pluck('id')->toArray();
            $skillsMatch = count(array_intersect($skillsVaga, $skillsUser));
            $skillsScore = (count($skillsVaga) > 0)
                ? ($skillsMatch / count($skillsVaga)) * 40
                : 0;

            // 2. Experiência
            $expScore = 0;
            if (method_exists($user, 'experiencia') && is_countable($user->experiencia)) {
                $expScore = min(count($user->experiencia) * 10, 20);
            } elseif (property_exists($user, 'experiencia') && is_countable($user->experiencia)) {
                $expScore = min(count($user->experiencia) * 10, 20);
            }

            // 3. Qualidade das respostas
            $respostas = $candidatura->respostas ?? collect();
            $mediaNota = 0;

            if ($respostas->isNotEmpty() && $respostas->first()->offsetExists('nota')) {
                $mediaNota = $respostas->avg('nota') ?? 0;
            } else {
                $textoTotal = $respostas->pluck('texto')->implode(' ');
                $mediaNota = min(intval(strlen($textoTotal) / 20), 20);
            }

            $formScore = min($mediaNota, 20);

            // 4. Perfil
            $perfilScore = 0;
            $perfilScore += !empty($user->fotoUser) ? 10 : 0;
            $perfilScore += (isset($user->status) && $user->status == 1) ? 10 : 0;

            // Total
            $total = $skillsScore + $expScore + $formScore + $perfilScore;
            $total = min(round($total, 1), 100);

            // Explicação
            $explicacao = [];
            if ($skillsScore > 0) $explicacao[] = "Skills compatíveis ({$skillsMatch})";
            if ($expScore > 0) $explicacao[] = "Experiência relevante";
            if ($formScore > 0) $explicacao[] = "Boas respostas no formulário";
            if ($perfilScore > 0) $explicacao[] = "Perfil completo";

            // Salvando a nota da IA
            if ($candidatura->nota_ia !== $total) {
                $candidatura->nota_ia = $total;
                $candidatura->save();
            }

            return [
                'id' => $user->id,
                'nome' => $user->nome_real ?? $user->username ?? 'Candidato',
                'foto' => !empty($user->fotoUser)
                    ? asset('storage/' . ltrim($user->fotoUser, '/'))
                    : null,
                'porcentagem' => $total,
                'explicacao' => implode(', ', $explicacao),
            ];
        });

        $ordenados = $candidatos->sortByDesc('porcentagem')->values();
        $melhor = $ordenados->first();

        return response()->json([
            'melhor' => $melhor,
            'candidatos' => $ordenados,
        ]);
    }

    /**
     * Lista vagas recomendadas para o usuário
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Usuário não autenticado'], 401);
        }

        // Skills do usuário
        $skillIds = $user->skills()->pluck('habilidades_tb.id')->toArray();

        if (empty($skillIds)) {
            return response()->json([]);
        }

        $limit = (int) $request->query('limit', 20);
        $limit = $limit > 0 ? min($limit, 50) : 20;

        $vagas = Vaga::with(['empresa', 'candidaturas.user'])
            ->whereIn('funcVaga', $skillIds)
            ->latest('created_at')
            ->take($limit)
            ->get()
            ->map(function (Vaga $vaga) use ($user) {

                $empresa = $vaga->empresa;
                $companyName = $empresa->nome_empresa ?? 'Empresa confidencial';
                $nomeHabilidade = Skill::find($vaga->funcVaga)?->nome
                    ?? $vaga->tipoVaga
                    ?? 'Vaga';

                // Distância
                $userLat = $user->endereco->latitude ?? null;
                $userLon = $user->endereco->longitude ?? null;

                $empresaLat = $empresa->endereco->latitude ?? null;
                $empresaLon = $empresa->endereco->longitude ?? null;

                $distance = null;
                if ($userLat && $userLon && $empresaLat && $empresaLon) {
                    $distance = $this->calculateDistance($userLat, $userLon, $empresaLat, $empresaLon);
                }

                // Lista de candidatos da vaga
                $candidates = $vaga->candidaturas->map(function ($candidatura) {
                    $user = $candidatura->user;
                    return [
                        'id' => $user->id ?? null,
                        'nome' => $user->nome_real ?? $user->username ?? 'Candidato',
                        'foto' => !empty($user->fotoUser)
                            ? asset('storage/' . ltrim($user->fotoUser, '/'))
                            : null,
                    ];
                })->filter()->values();

                return [
                    'id' => $vaga->id,
                    'title' => $nomeHabilidade,
                    'company' => $companyName,
                    'ramo' => $empresa->ramo ?? 'Geral',
                    'logo_letters' => $this->makeLogoLetters($companyName),
                    'desc' => $vaga->descVaga ?? 'Descrição indisponível.',
                    'image' => $this->resolveImagePath($vaga->imgVaga),
                    'fotoEmpresa' => $this->resolveEmpresaImagePath($empresa->fotoEmpresa ?? null),
                    'salary' => !is_null($vaga->valor_vaga)
                        ? 'R$ ' . number_format($vaga->valor_vaga, 2, ',', '.')
                        : null,
                    'distance' => $distance,
                    'candidates' => $candidates,
                ];
            })
            ->filter(function ($vaga) {
                // Só exibe vagas com distância válida até 500km
                if (!$vaga['distance']) {
                    return false;
                }

                $distValue = floatval(str_replace(' km', '', $vaga['distance']));
                return $distValue <= 500;
            })
            ->values();

        return response()->json($vagas);
    }

    // --- MÉTODOS AUXILIARES ---

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return number_format(6371 * $c, 1) . ' km';
    }

    private function resolveEmpresaImagePath(?string $path): ?string
    {
        if (empty($path) || $path === 'null') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    private function makeLogoLetters(string $name): string
    {
        $words = collect(explode(' ', Str::upper($name)))
            ->filter()
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1));

        return $words->isEmpty() ? 'LB' : $words->implode('');
    }

    private function resolveImagePath(?string $path): string
    {
        if (empty($path) || $path === 'null') {
            return asset('img/match-example.png');
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return asset('storage/vagas_img/' . basename($path));
    }
}

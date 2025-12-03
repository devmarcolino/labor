<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vaga;
use App\Models\Skill;
use App\Models\C;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VagaController extends Controller
{
        public function melhorCandidato(int $id)
    {
        // 1. Carregamento Otimizado (Eager Loading)
        // Precisamos descer: Vaga -> Candidaturas -> User -> Respostas -> Opcao (para pegar os pontos)
        $vaga = Vaga::with([
            'candidaturas.user.skills', 
            'candidaturas.user.respostas.opcao' // <--- O PULO DO GATO: Carrega a opção escolhida e os pontos
        ])->findOrFail($id);

        $candidatos = $vaga->candidaturas->map(function ($candidatura) use ($vaga) {
            $user = $candidatura->user;

            // --- A. MATCH DE SKILLS (TAGS) - PESO 30% ---
            // Verifica se ele tem a etiqueta "Garçom", etc.
            $skillsVaga = is_array($vaga->funcVaga) ? $vaga->funcVaga : [$vaga->funcVaga];
            $skillsVaga = array_filter($skillsVaga); // Remove vazios
            
            $skillsUser = $user->skills->pluck('id')->toArray();
            $matchCount = count(array_intersect($skillsVaga, $skillsUser));
            
            // Se a vaga pede skills e ele tem, calcula proporção. Se a vaga não pede, dá nota cheia.
            $skillsScore = (count($skillsVaga) > 0) 
                ? ($matchCount / count($skillsVaga)) * 30 
                : 30;


            // --- B. SCORE DO FORMULÁRIO (IA / PONTOS) - PESO 50% ---
            // Aqui usamos a sua model Opcao com 'pontos'
            $somaPontos = 0;
            
            // Verifica se o user tem respostas carregadas
            if ($user->respostas && $user->respostas->isNotEmpty()) {
                foreach ($user->respostas as $resposta) {
                    // Se a resposta tem uma opção vinculada, soma os pontos dela
                    if ($resposta->opcao) {
                        $somaPontos += $resposta->opcao->pontos;
                    }
                }
            }

            // Normalização: Vamos supor que um candidato excelente faça uns 50 a 100 pontos.
            // Limitamos a 50 pontos para não estourar a porcentagem (ou ajustamos conforme sua regra de negócio)
            $formScore = min($somaPontos, 50);


            // --- C. PERFIL (FOTO E DADOS) - PESO 20% ---
            $perfilScore = 0;
            $perfilScore += !empty($user->fotoUser) ? 10 : 0; // Tem foto? +10
            $perfilScore += ($user->endereco) ? 10 : 0;       // Tem endereço? +10


            // --- TOTAL ---
            $total = $skillsScore + $formScore + $perfilScore;
            $total = min(round($total, 0), 100); // Arredonda e limita a 100%

            // --- EXPLICAÇÃO PARA A EMPRESA (FEEDBACK VISUAL) ---
            $explicacao = [];
            if ($matchCount > 0) $explicacao[] = "Possui as habilidades exigidas";
            if ($somaPontos > 20) $explicacao[] = "Alta pontuação no teste técnico";
            elseif ($somaPontos > 0) $explicacao[] = "Respondeu ao questionário";
            if (!empty($user->fotoUser)) $explicacao[] = "Perfil com foto";

            // Salva a nota na tabela pivô para ordenação futura sem recalcular
            if ($candidatura->nota_ia !== $total) {
                $candidatura->nota_ia = $total;
                $candidatura->save();
            }

            return [
                'id' => $user->id,
                'nome' => explode(' ', $user->nome_real)[0], // Primeiro nome
                'sobrenome' => explode(' ', $user->nome_real)[1] ?? '',
                'foto' => !empty($user->fotoUser) ? asset('storage/' . $user->fotoUser) : asset('img/default-avatar.png'),
                'porcentagem' => $total,
                'explicacao' => implode(' • ', $explicacao),
                
                // Dados extras para o card
                'idade' => $user->datanasc ? \Carbon\Carbon::parse($user->datanasc)->age . ' anos' : '--',
                'cidade' => $user->endereco->cidade ?? 'Localização não inf.',
                'pontos_teste' => $somaPontos // Útil para debug
            ];
        });

        // Ordena do maior para o menor
        $ordenados = $candidatos->sortByDesc('porcentagem')->values();
        $melhor = $ordenados->first();

        return response()->json([
            'melhor' => $melhor,
            'candidatos' => $ordenados,
        ]);
    }

    private function calcularMelhoresCandidatos(Vaga $vaga)
{
    $vaga->load([
        'candidaturas.user.skills',
        'candidaturas.user.respostas.opcao',
        'candidaturas.user.endereco'
    ]);

    $candidatos = $vaga->candidaturas->map(function ($candidatura) use ($vaga) {
        $user = $candidatura->user;

        $skillsVaga = is_array($vaga->funcVaga) ? $vaga->funcVaga : [$vaga->funcVaga];
        $skillsVaga = array_filter($skillsVaga);

        $skillsUser = $user->skills->pluck('id')->toArray();
        $matchCount = count(array_intersect($skillsVaga, $skillsUser));

        $skillsScore = (count($skillsVaga) > 0)
            ? ($matchCount / count($skillsVaga)) * 30
            : 30;

        $somaPontos = 0;
        if ($user->respostas) {
            foreach ($user->respostas as $resp) {
                if ($resp->opcao) {
                    $somaPontos += $resp->opcao->pontos;
                }
            }
        }

        $formScore = min($somaPontos, 50);

        $perfilScore  = !empty($user->fotoUser) ? 10 : 0;
        $perfilScore += $user->endereco ? 10 : 0;

        $total = min(round($skillsScore + $formScore + $perfilScore), 100);

        if ($candidatura->nota_ia != $total) {
            $candidatura->nota_ia = $total;
            $candidatura->save();
        }

        return (object)[
            'id' => $user->id,
            'user' => $user,
            'porcentagem' => $total,
            'idade' => $user->datanasc ? \Carbon\Carbon::parse($user->datanasc)->age.' anos' : '--',
            'cidade' => $user->endereco->cidade ?? 'Localização não inf.',
            'pontos_teste' => $somaPontos,
        ];
    });

    $ordenados = $candidatos->sortByDesc('porcentagem')->values();
    return [
        'melhor' => $ordenados->first(),
        'candidatosOrdenados' => $ordenados
    ];
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

                $duracao = '';
                if ($vaga->horario && str_contains($vaga->horario, ' - ')) {
                    try {
                        // Quebra "18:00 - 23:00" em duas partes
                        $partes = explode(' - ', $vaga->horario);
                        $inicio = \Carbon\Carbon::createFromFormat('H:i', trim($partes[0]));
                        $fim = \Carbon\Carbon::createFromFormat('H:i', trim($partes[1]));

                        // Se o fim for menor que o início (ex: 22:00 - 04:00), adiciona um dia
                        if ($fim->lessThan($inicio)) {
                            $fim->addDay();
                        }

                        // Calcula a diferença
                        $totalMinutos = $inicio->diffInMinutes($fim);
                        $horas = floor($totalMinutos / 60);
                        $minutos = $totalMinutos % 60;

                        // Formata: "5h" ou "5h 30m"
                        $duracao = $horas . 'h' . ($minutos > 0 ? ' ' . $minutos . 'm' : '');
                    } catch (\Exception $e) {
                        $duracao = ''; // Se der erro no formato, ignora
                    }
                }

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
                    'horario' => $vaga->horario,
                    'duracao' => $duracao,
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

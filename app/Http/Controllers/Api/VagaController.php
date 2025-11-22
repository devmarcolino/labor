<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vaga;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VagaController extends Controller
{
    public function index(Request $request)
    {
        // 1. Identificar o Usuário Logado
        // (Certifique-se que a rota api tem middleware 'auth:sanctum' ou 'auth:web')
        $user = auth()->user();

        // Se não tiver usuário logado (erro de segurança), retorna vazio ou erro
        if (!$user) {
            return response()->json(['message' => 'Usuário não autenticado'], 401);
        }

        // 2. Pegar as Habilidades do Usuário
        // O pluck('nome') pega só os textos. Ex: ['Garçom', 'Barman']
        // ATENÇÃO: Certifique-se que a coluna na tabela 'habilidades_tb' se chama 'nome'
        $skillNames = $user->skills()->pluck('nomeHabilidade')->toArray();

        // Se o usuário não tiver habilidades cadastradas, não mostra nada (ou mostraria tudo?)
        // Lógica atual: Sem skills = Sem vagas compatíveis.
        if (empty($skillNames)) {
            return response()->json([]); 
        }

        $limit = (int) $request->query('limit', 20);
        $limit = $limit > 0 ? min($limit, 50) : 20;

        // 3. Buscar Vagas com o Filtro (MATCH)
        $vagas = Vaga::with('empresa')
            ->whereIn('funcVaga', $skillNames)
            ->latest('created_at')
            ->take($limit)
            ->get()
            ->map(function (Vaga $vaga) {
                $empresa = $vaga->empresa;
                $companyName = $empresa->nome_empresa ?? 'Empresa confidencial';
                $ramoEmpresa = $empresa->ramo ?? 'Setor não informado';
                $fotoEmpresa = $empresa->fotoEmpresa ?? null;

                // Cálculo da distância
                $user = auth()->user();
                $userLat = $user && $user->endereco ? $user->endereco->latitude : null;
                $userLon = $user && $user->endereco ? $user->endereco->longitude : null;
                $empresaLat = $empresa && $empresa->endereco ? $empresa->endereco->latitude : null;
                $empresaLon = $empresa && $empresa->endereco ? $empresa->endereco->longitude : null;
                $distance = null;
                if ($userLat && $userLon && $empresaLat && $empresaLon) {
                    $distance = $this->calculateDistance($userLat, $userLon, $empresaLat, $empresaLon);
                }

                return [
                    'id' => $vaga->id,
                    'title' => $vaga->funcVaga ?? $vaga->tipoVaga,
                    'company' => $companyName,
                    'ramo' => $ramoEmpresa,
                    'logo' => $this->makeLogoLetters($companyName),
                    'desc' => $vaga->descVaga ?? 'Descrição indisponível no momento.',
                    'image' => $this->resolveImagePath($vaga->imgVaga),
                    'fotoEmpresa' => $this->resolveEmpresaImagePath($fotoEmpresa),
                    'distance' => $distance,
                    // Debug (Opcional: pra você ver porque deu match)
                    'match_skill' => $vaga->tipoVaga
                ];
            })
            ->filter(function ($vaga) {
                // Filtra vagas com distância <= 5 km
                if (!$vaga['distance']) return false;
                // Remove ' km' para comparar
                $distValue = floatval(str_replace(' km', '', $vaga['distance']));
                return $distValue <= 5;
            })
            ->values();

        return response()->json($vagas);
    }

    // --- MÉTODOS AUXILIARES (MANTIDOS IGUAIS) ---

    // Calcula a distância entre dois pontos (Haversine)
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;
        return number_format($distance, 1) . ' km';
    }

    private function resolveEmpresaImagePath(?string $path): string
    {
        if (empty($path) || $path === 'null' || $path === null) {
            return asset('img/match-example.png');
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
        if (empty($path)) {
            return asset('img/match-example.png');
        }
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        if (Str::startsWith($path, 'vagas_img/')) {
            return asset('storage/' . ltrim($path, '/'));
        }
        return asset(ltrim($path, '/'));
    }

     public function destaque(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user || !$user->endereco) {
            // Se o user não logou ou não tem endereço (não fez onboarding),
            // retornamos vazio ou erro 404.
            return response()->json(['message' => 'Endereço não encontrado'], 404);
        }

        // 1. Pegar coordenadas do User
        $lat = $user->endereco->latitude;
        $lon = $user->endereco->longitude;

        // 2. Pegar Skills do User
        $skillIds = $user->skills()->pluck('habilidades_tb.id')->toArray();

        if (empty($skillIds) || !$lat || !$lon) {
            return response()->json(null); // Sem match possível
        }

        // 3. A Query "Haversine"
                $vaga = Vaga::from('vagas_tb as vagas')
                        ->select('vagas.*')
                        // Junta com empresa para chegar no endereço dela
                        ->join('empresa_tb', 'vagas.idEmpresa', '=', 'empresa_tb.id')
                        ->join('end_tb', 'empresa_tb.idEnd', '=', 'end_tb.id')
                        // Filtra pelas habilidades (Match)
                        ->whereIn('vagas.funcVaga', $skillIds)
                        // CALCULA A DISTÂNCIA (Fórmula Mágica)
                        // 6371 é o raio da terra em KM
                        ->selectRaw("
                                ( 6371 * acos( cos( radians(?) ) *
                                    cos( radians( end_tb.latitude ) ) *
                                    cos( radians( end_tb.longitude ) - radians(?) ) +
                                    sin( radians(?) ) *
                                    sin( radians( end_tb.latitude ) ) )
                                ) AS distance", 
                                [$lat, $lon, $lat]
                        )
                            ->having('distance', '<=', 5)
                        // Ordena: Mais perto primeiro
                        ->orderBy('distance', 'asc')
                        // Pega só a campeã
                        ->first();

        if (!$vaga || !$vaga->id) {
            return response()->json(null);
        }

        // 4. Formata para o Front (igual ao index, mas com a distância)
        $empresa = $vaga->empresa; // O Laravel carrega automaticamente pelo model
        $companyName = $empresa->nome_empresa ?? 'Confidencial';

        $data = [
              'id' => $vaga->id ?? 0,
              'title' => $vaga->funcVaga ?? $vaga->tipoVaga ?? 'Vaga',
              'company' => $companyName ?? 'Confidencial',
              'logo' => $this->makeLogoLetters($companyName ?? 'Confidencial'),
              'image' => $this->resolveImagePath($vaga->imgVaga ?? null),
              'fotoEmpresa' => $this->resolveEmpresaImagePath($empresa->fotoEmpresa ?? null),
              'distance' => isset($vaga->distance) ? number_format($vaga->distance, 1) . ' km' : '',
              'location' => ($empresa->endereco->cidade ?? '') . ', ' . ($empresa->endereco->uf ?? ''),
        ];

        return response()->json($data);
    }
}

   
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vaga;
use App\Models\Skill; // Adicionei o Model Skill
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class VagaController extends Controller
{
    public function index(Request $request)
    {
        // 1. Identificar o Usuário
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Usuário não autenticado'], 401);
        }

        // 2. Pegar IDs das Habilidades
        $skillIds = $user->skills()->pluck('habilidades_tb.id')->toArray();

        if (empty($skillIds)) {
            return response()->json([]); 
        }

        $limit = (int) $request->query('limit', 20);
        $limit = $limit > 0 ? min($limit, 50) : 20;

        // 3. Buscar Vagas
        $vagas = Vaga::with('empresa')
            ->whereIn('funcVaga', $skillIds)
            ->latest('created_at')
            ->take($limit)
            ->get()
            ->map(function (Vaga $vaga) {
                $empresa = $vaga->empresa;
                $companyName = $empresa->nome_empresa ?? 'Empresa confidencial';
                
                // Busca o nome da habilidade para o título ficar bonito (Ex: "Garçom")
                $nomeHabilidade = Skill::find($vaga->funcVaga)?->nome ?? $vaga->tipoVaga ?? 'Vaga';

                // Cálculo da distância (Mantendo sua lógica PHP)
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
                    'title' => $nomeHabilidade, // Título corrigido
                    'company' => $companyName,
                    'ramo' => $empresa->ramo ?? 'Geral',
                    
                    // Gera as letras (Ex: MD para Madero)
                    'logo_letters' => $this->makeLogoLetters($companyName),
                    
                    'desc' => $vaga->descVaga ?? 'Descrição indisponível.',
                    
                    // Imagem da Vaga (Aceita Links)
                    'image' => $this->resolveImagePath($vaga->imgVaga),
                    
                    // Logo da Empresa (Retorna NULL se não tiver, para ativar as letras)
                    'fotoEmpresa' => $this->resolveEmpresaImagePath($empresa->fotoEmpresa ?? null),
                    
                    'salary' => 'R$ ' . number_format($vaga->valor_vaga, 2, ',', '.'),
                    'distance' => $distance,
                ];
            })
            ->filter(function ($vaga) {
                if (!$vaga['distance']) return false;
                $distValue = floatval(str_replace(' km', '', $vaga['distance']));
                return $distValue <= 500; // Aumentei o raio para 500km pro vídeo não falhar
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
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;
        return number_format($distance, 1) . ' km';
    }

    /**
     * AQUI ESTÁ O AJUSTE DA EMPRESA
     * Retorna NULL se não tiver foto. O Frontend vai ver o NULL e mostrar as letras.
     */
    private function resolveEmpresaImagePath(?string $path): ?string
    {
        // Se estiver vazio ou null, retorna null (ativa o visual de letras)
        if (empty($path) || $path === 'null' || $path === null) {
            return null; 
        }

        // Se for link da internet (http), retorna o link
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // Se for arquivo local, retorna o asset
        return asset('storage/' . ltrim($path, '/'));
    }

    private function makeLogoLetters(string $name): string
    {
        $words = collect(explode(' ', Str::upper($name)))
            ->filter()->take(2)->map(fn ($word) => Str::substr($word, 0, 1));
        return $words->isEmpty() ? 'LB' : $words->implode('');
    }

    private function resolveImagePath(?string $path): string
    {
        // Para vaga, se não tiver foto, retorna uma padrão bonita
        if (empty($path) || $path === 'null') {
            return asset('img/match-example.png');
        }
        
        // Aceita links da internet para o seu vídeo pitch
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        
        return asset(ltrim($path, '/')); // Tenta carregar da pasta public
    }
}
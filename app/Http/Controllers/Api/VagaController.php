<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vaga;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VagaController extends Controller
{
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 20);
        $limit = $limit > 0 ? min($limit, 50) : 20;

        $vagas = Vaga::with('empresa')
            ->latest('created_at')
            ->take($limit)
            ->get()
            ->map(function (Vaga $vaga) {
                $empresa = $vaga->empresa;
                $companyName = optional($empresa)->nome_empresa ?? 'Empresa confidencial';

                return [
                    'id' => $vaga->id,
                    'title' => $vaga->funcVaga ?? $vaga->tipoVaga,
                    'company' => $companyName,
                    'ramo' => optional($empresa)->ramo ?? 'Setor não informado',
                    'logo' => $this->makeLogoLetters($companyName),
                    'desc' => $vaga->descVaga ?? 'Descrição indisponível no momento.',
                    'image' => $this->resolveImagePath($vaga->imgVaga),
                ];
            });

        return response()->json($vagas);
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

        return asset(ltrim($path, '/'));
    }
}

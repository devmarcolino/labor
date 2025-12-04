<?php

namespace App\Http\Controllers;

use App\Models\GastosEmpresas;
use App\Models\Skill; // <--- Importante
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EnterpriseAnalyticsController extends Controller
{
    public function index()
    {
        $empresaId = Auth::id();

        // 1. Total Geral
        $totalGasto = GastosEmpresas::where('empresa_id', $empresaId)->sum('valor');

        // 2. Agrupamento
        $dados = GastosEmpresas::where('empresa_id', $empresaId)
            ->select('funcao', DB::raw('SUM(valor) as total'))
            ->groupBy('funcao')
            ->get();

        // 3. TRADUÇÃO DE ID PARA NOME (A MÁGICA ACONTECE AQUI)
        $labels = [];
        $series = [];

        foreach ($dados as $dado) {
            $nomeLabel = $dado->funcao;

            // Se for numérico (ex: "1"), buscamos o nome na tabela skills
            if (is_numeric($nomeLabel)) {
                $skill = Skill::find($nomeLabel);
                if ($skill) {
                    $nomeLabel = $skill->nomeHabilidade; // Ou o nome da coluna correta na sua tabela skills
                }
            }

            $labels[] = $nomeLabel;
            $series[] = floatval($dado->total); // Garante que é número pro gráfico
        }

        return view('enterprises.analytics', [
            'totalGasto'    => $totalGasto,
            'labelsGrafico' => $labels,
            'seriesGrafico' => $series
        ]);
    }
}
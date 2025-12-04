<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Escala;
use Illuminate\Support\Facades\Auth;

class EnterpriseScheduleController extends Controller
{
    public function index()
    {
        $empresa = Auth::guard('empresa')->user();

        if (!$empresa) {
            abort(403, 'Acesso negado.');
        }

        // Buscar escalas da empresa
        $escalas = Escala::where('idEmpresa', $empresa->id)
            ->with(['user', 'vaga'])
            ->orderBy('dataCriacao', 'desc')
            ->get();

        // Agrupar por vaga para mostrar usuários por vaga
        $escalasPorVaga = $escalas->groupBy('idVaga');

        return view('enterprises.schedule', compact('escalasPorVaga', 'empresa'));
    }

    public function removerEscala(Request $request)
    {
        $request->validate([
            'vaga_id' => 'required|integer',
            'empresa_id' => 'required|integer',
        ]);

        $empresa = Auth::guard('empresa')->user();
        if (!$empresa || $empresa->id != $request->empresa_id) {
            return response()->json(['success' => false, 'message' => 'Acesso negado.']);
        }

        try {
            // Buscar usuários escalados para remover candidaturas e curtidas
            $escalas = Escala::where('idEmpresa', $empresa->id)
                ->where('idVaga', $request->vaga_id)
                ->pluck('idUser');

            // Remover candidaturas
            \App\Models\Candidatura::whereIn('idUser', $escalas)
                ->where('idVaga', $request->vaga_id)
                ->delete();

            // Remover candidatos curtidos
            \App\Models\CandidatoCurtido::whereIn('user_id', $escalas)
                ->where('vaga_id', $request->vaga_id)
                ->delete();

            // Remover escalas
            $deleted = Escala::where('idEmpresa', $empresa->id)
                ->where('idVaga', $request->vaga_id)
                ->delete();

            \Log::info('Escalas removidas: ' . $deleted);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Erro ao remover escala: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro interno.']);
        }
    }

    public function removerUsuarioEscala(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'vaga_id' => 'required|integer',
        ]);

        $empresa = Auth::guard('empresa')->user();
        if (!$empresa) {
            return response()->json(['success' => false, 'message' => 'Acesso negado.']);
        }

        try {
            // Remover escala específica do usuário para a vaga
            $deleted = Escala::where('idEmpresa', $empresa->id)
                ->where('idVaga', $request->vaga_id)
                ->where('idUser', $request->user_id)
                ->delete();

            \Log::info('Escala de usuário removida: ' . $deleted);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Erro ao remover usuário da escala: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro interno.']);
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CandidatoCurtido;

class CandidatoCurtidoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:user_tb,id',
            'vaga_id' => 'required|exists:vagas_tb,id',
        ]);

        $empresaId = Auth::guard('empresa')->id();
        $userId = $request->user_id;
        $vagaId = $request->vaga_id;

        // Evita duplicidade
        $exists = CandidatoCurtido::where('empresa_id', $empresaId)
            ->where('user_id', $userId)
            ->where('vaga_id', $vagaId)
            ->exists();
        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Candidato já curtido para esta vaga.']);
        }

        $curtido = CandidatoCurtido::create([
            'empresa_id' => $empresaId,
            'user_id' => $userId,
            'vaga_id' => $vagaId,
        ]);

        return response()->json(['success' => true, 'id' => $curtido->id]);
    }
}

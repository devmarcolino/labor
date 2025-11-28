<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidatura;
use Illuminate\Support\Facades\Auth;

class CandidaturaController extends Controller
{
    public function store(Request $request)
    {
        $user_id = Auth::id();
        $vaga_id = $request->vaga_id;

        if (!$user_id) {
            return response()->json(['success' => false, 'message' => 'Usuário não autenticado.'], 401);
        }
        if (!$vaga_id) {
            return response()->json(['success' => false, 'message' => 'ID da vaga não enviado.'], 400);
        }
        $request->validate([
            'vaga_id' => 'required|exists:vagas_tb,id',
        ]);
        $candidatura = Candidatura::firstOrCreate([
            'idUser' => $user_id,
            'idVaga' => $vaga_id,
        ], [
            'dataCandidatura' => now(),
            'status' => 'ativa',
        ]);
        return response()->json(['success' => true, 'candidatura' => $candidatura]);
    }
}

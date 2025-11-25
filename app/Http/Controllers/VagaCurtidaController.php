<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VagaCurtida;
use Illuminate\Support\Facades\Auth;

class VagaCurtidaController extends Controller
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
        $curtida = VagaCurtida::firstOrCreate([
            'user_id' => $user_id,
            'vaga_id' => $vaga_id,
        ]);
        return response()->json(['success' => true, 'curtida' => $curtida]);
    }

    public function index()
    {
        $userId = auth()->id();
        $vagasCurtidas = \App\Models\VagaCurtida::where('user_id', $userId)
            ->with('vaga')
            ->get();
        return view('workers.vagas-curtidas', [
            'vagasCurtidas' => $vagasCurtidas
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ConversationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'vaga_id' => 'required|exists:vagas,id',
        ]);

        $empresaId = Auth::id(); // Empresa logada
        $userId = $request->user_id;

        // Verifica se já existe conversa
        $conversa = Conversation::where('user_id', $userId)
            ->where('empresa_id', $empresaId)
            ->first();

        if ($conversa) {
            return response()->json(['success' => false, 'message' => 'Conversa já existe.']);
        }

        $conversa = Conversation::create([
            'user_id' => $userId,
            'empresa_id' => $empresaId,
        ]);

        return response()->json(['success' => true, 'conversa_id' => $conversa->id]);
    }
}

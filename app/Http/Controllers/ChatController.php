<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Lista todas as conversas da empresa logada
    public function index()
    {
        $empresaId = auth('empresa')->id();
        $candidatosCurtidos = \App\Models\CandidatoCurtido::with('user', 'vaga')
            ->where('empresa_id', $empresaId)
            ->latest()
            ->get();
        return view('enterprises.chat', compact('candidatosCurtidos'));
    }

    // Cria ou busca conversa entre usuário logado e empresa
    public function startConversation(Request $request)
    {
        $empresaId = auth('empresa')->id();
        $userId = $request->input('user_id');

        if (!$empresaId) {
            return response()->json(['error' => 'Você precisa estar logado como empresa para iniciar uma conversa.'], 401);
        }
        if (!$userId) {
            return response()->json(['error' => 'ID do candidato não informado.'], 400);
        }

        $conversation = Conversation::firstOrCreate([
            'user_id' => $userId,
            'empresa_id' => $empresaId,
        ]);

        return response()->json(['conversation_id' => $conversation->id]);
    }

    public function show($conversationId)
    {
        $conversation = \App\Models\Conversation::findOrFail($conversationId);
        $worker = \App\Models\User::find($conversation->user_id);
        $enterprise = \App\Models\Empresa::find($conversation->empresa_id);
        $messages = $conversation->messages()->orderBy('created_at')->get();

        return view('enterprises.chat', compact('conversation', 'worker', 'enterprise', 'messages'));
    }
}

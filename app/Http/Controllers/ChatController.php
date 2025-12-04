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

    public function scaleUser(Request $request)
    {
        $empresaId = auth('empresa')->id();
        $userId = $request->input('user_id');
        $vagaId = $request->input('vaga_id');

        if (!$empresaId) {
            return response()->json(['success' => false, 'message' => 'Empresa não autenticada.'], 401);
        }

        if (!$vagaId) {
            return response()->json(['success' => false, 'message' => 'Vaga não especificada.'], 400);
        }

        // Buscar a vaga para obter o valor
        $vaga = \App\Models\Vaga::find($vagaId);
        if (!$vaga) {
            return response()->json(['success' => false, 'message' => 'Vaga não encontrada.'], 404);
        }

        // Verifica se já existe escala para evitar duplicatas
        $existing = \App\Models\Escala::where('idUser', $userId)->where('idEmpresa', $empresaId)->where('idVaga', $vagaId)->first();
        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Usuário já escalado para esta vaga.']);
        }

        \App\Models\Escala::create([
            'idUser' => $userId,
            'idEmpresa' => $empresaId,
            'idVaga' => $vagaId,
            'dataDiaria' => now()->toDateString(),
            'horaDiaria' => now()->toTimeString(),
            'gastoTotal' => $vaga->valor_vaga,
            'dataCriacao' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Usuário escalado com sucesso para a vaga.']);
    }
}

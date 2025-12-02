<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Mensagem;
use Illuminate\Support\Facades\Auth;

class EnterpriseChatController extends Controller
{
    /**
     * Exibe o chat entre a empresa logada e um usuário específico.
     */
    public function chatWithUser(Request $request, User $user)
    {
        // Empresa logada
        $empresa = Auth::guard('empresa')->user();

        if (!$empresa) {
            abort(403, 'Acesso negado. Você não está logado como empresa.');
        }

        // Se o formulário foi enviado, salva a mensagem
        if ($request->isMethod('post')) {
            $request->validate([
                'mensagem' => 'required|string|max:1000',
            ]);
            $novaMensagem = Mensagem::create([
                'remetente_id' => $empresa->id,
                'remetente_tipo' => 'empresa',
                'destinatario_id' => $user->id,
                'destinatario_tipo' => 'user',
                'mensagem' => $request->input('mensagem'),
                'horario' => now(),
            ]);
            event(new \App\Events\NovaMensagemEnviada($novaMensagem));
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true]);
            }
            return redirect()->route('enterprises.chat.user', $user->id);
        }

        // Busca todas as mensagens ENTRE a empresa e o usuário curtido
        $mensagens = Mensagem::where(function($q) use ($empresa, $user) {
            $q->where('remetente_id', $empresa->id)
              ->where('remetente_tipo', 'empresa')
              ->where('destinatario_id', $user->id)
              ->where('destinatario_tipo', 'user');
        })->orWhere(function($q) use ($empresa, $user) {
            $q->where('remetente_id', $user->id)
              ->where('remetente_tipo', 'user')
              ->where('destinatario_id', $empresa->id)
              ->where('destinatario_tipo', 'empresa');
        })
        ->orderBy('horario', 'asc')
        ->get();

        return view('enterprises.chat-user', compact('user', 'empresa', 'mensagens'));
    }
}

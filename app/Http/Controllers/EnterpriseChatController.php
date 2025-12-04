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
     * Exibe o chat entre a empresa logada e um usuário específico sobre uma vaga.
     */
    public function chatWithUser(Request $request, User $user, $vagaId)
    {
        // Empresa logada
        $empresa = Auth::guard('empresa')->user();

        if (!$empresa) {
            abort(403, 'Acesso negado. Você não está logado como empresa.');
        }

        // Buscar a vaga
        $vaga = \App\Models\Vaga::findOrFail($vagaId);

        // Se o formulário foi enviado, salva a mensagem
        if ($request->isMethod('post')) {
            $request->validate([
                'mensagem' => 'nullable|string|max:1000',
                'arquivo' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,wmv|max:20480',
            ]);

            $arquivoPath = null;
            if ($request->hasFile('arquivo')) {
                $arquivo = $request->file('arquivo');
                $arquivoPath = $arquivo->store('chat-midias', 'public');
            }

            // Só salva se houver mensagem ou arquivo
            if ($request->filled('mensagem') || $arquivoPath) {
                $novaMensagem = Mensagem::create([
                    'remetente_id' => $empresa->id,
                    'remetente_tipo' => 'empresa',
                    'destinatario_id' => $user->id,
                    'destinatario_tipo' => 'user',
                    'mensagem' => $request->input('mensagem'),
                    'arquivo' => $arquivoPath,
                    'horario' => now(),
                ]);
                // Dispara para ambos os canais
                event(new \App\Events\NovaMensagemEnviada($novaMensagem));
                // Canal inverso (user->empresa)
                $mensagemInvertida = clone $novaMensagem;
                $mensagemInvertida->remetente_id = $user->id;
                $mensagemInvertida->remetente_tipo = 'user';
                $mensagemInvertida->destinatario_id = $empresa->id;
                $mensagemInvertida->destinatario_tipo = 'empresa';
                event(new \App\Events\NovaMensagemEnviada($mensagemInvertida));
            }
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true]);
            }
            return redirect()->route('enterprises.chat.user', [$user->id, $vagaId]);
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

        return view('enterprises.chat-user', compact('user', 'empresa', 'mensagens', 'vaga'));
    }
}

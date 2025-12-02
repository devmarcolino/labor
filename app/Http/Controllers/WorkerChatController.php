<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Mensagem;
use Illuminate\Support\Facades\Auth;

class WorkerChatController extends Controller
{
    /**
     * Exibe o chat entre o worker logado e uma empresa específica.
     */
    public function chatWithEmpresa(Request $request, Empresa $empresa)
    {
        // Worker logado
        $worker = Auth::user();
        if (!$worker) {
            abort(403, 'Acesso negado. Você não está logado como trabalhador.');
        }

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
            if ($request->filled('mensagem') || $arquivoPath) {
                $novaMensagem = Mensagem::create([
                    'remetente_id' => $worker->id,
                    'remetente_tipo' => 'user',
                    'destinatario_id' => $empresa->id,
                    'destinatario_tipo' => 'empresa',
                    'mensagem' => $request->input('mensagem'),
                    'arquivo' => $arquivoPath,
                    'horario' => now(),
                ]);
                event(new \App\Events\NovaMensagemEnviada($novaMensagem));
            }
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true]);
            }
            return redirect()->route('workers.chat.empresa', $empresa->id);
        }

        // Busca todas as mensagens ENTRE o worker e a empresa
        $mensagens = Mensagem::where(function($q) use ($worker, $empresa) {
            $q->where('remetente_id', $worker->id)
              ->where('remetente_tipo', 'user')
              ->where('destinatario_id', $empresa->id)
              ->where('destinatario_tipo', 'empresa');
        })->orWhere(function($q) use ($worker, $empresa) {
            $q->where('remetente_id', $empresa->id)
              ->where('remetente_tipo', 'empresa')
              ->where('destinatario_id', $worker->id)
              ->where('destinatario_tipo', 'user');
        })
        ->orderBy('horario', 'asc')
        ->get();

        return view('workers.chat-empresa', compact('empresa', 'worker', 'mensagens'));
    }
}

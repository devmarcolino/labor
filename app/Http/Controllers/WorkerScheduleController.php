<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Escala;
use Illuminate\Support\Facades\Auth;

class WorkerScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Acesso negado.');
        }

        // Buscar escalas do usuário
        $escalas = Escala::where('idUser', $user->id)
            ->with(['empresa', 'vaga']) // Assumindo relationships
            ->orderBy('dataCriacao', 'desc')
            ->get();

        return view('workers.schedule', compact('escalas'));
    }

    public function desistirVaga(Request $request)
    {
        $request->validate([
            'escala_id' => 'required|integer',
            'vaga_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $user = Auth::user();
        if (!$user || $user->id != $request->user_id) {
            return response()->json(['success' => false, 'message' => 'Acesso negado.']);
        }

        \Log::info('Desistindo vaga', $request->all());

        try {
            // Remover candidatura
            $deletedCandidatura = \App\Models\Candidatura::where('idUser', $user->id)
                ->where('idVaga', $request->vaga_id)
                ->delete();
            \Log::info('Candidaturas deletadas: ' . $deletedCandidatura);

            // Remover candidato curtido
            $deletedCurtido = \App\Models\CandidatoCurtido::where('user_id', $user->id)
                ->where('vaga_id', $request->vaga_id)
                ->delete();
            \Log::info('Curtidos deletados: ' . $deletedCurtido);

            // Remover escala
            $deletedEscala = \App\Models\Escala::where('id', $request->escala_id)
                ->where('idUser', $user->id)
                ->delete();
            \Log::info('Escalas deletadas: ' . $deletedEscala);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Erro ao desistir vaga: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro interno.']);
        }
    }

    public function empresaInfo($empresaId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Acesso negado.'], 403);
        }

        $empresa = \App\Models\Empresa::with('endereco')->find($empresaId);
        if (!$empresa) {
            return response()->json(['success' => false, 'message' => 'Empresa não encontrada.'], 404);
        }

        return response()->json([
            'success' => true,
            'nome' => $empresa->nome_empresa,
            'ramo' => $empresa->ramo,
            'telefone' => $empresa->tel,
            'email' => $empresa->email,
            'foto' => $empresa->fotoEmpresa ? asset('storage/' . $empresa->fotoEmpresa) : asset('img/default-avatar.png'),
            'endereco' => $empresa->endereco ? [
                'rua' => $empresa->endereco->rua ?? null,
                'numero' => $empresa->endereco->numero ?? null,
                'cidade' => $empresa->endereco->cidade ?? null,
                'estado' => $empresa->endereco->estado ?? null,
                'completo' => trim(($empresa->endereco->rua ?? '') . ', ' . ($empresa->endereco->numero ?? '') . ' - ' . ($empresa->endereco->cidade ?? '') . '/' . ($empresa->endereco->estado ?? '')),
            ] : null,
        ]);
    }
}
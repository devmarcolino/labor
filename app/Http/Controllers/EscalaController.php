<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use App\Models\Escala;
use App\Models\Vaga;
use App\Models\GastosEmpresas;

class EscalaController extends Controller
{
    public function criar(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:user_tb,id',
            'empresa_id' => 'required|integer|exists:empresa_tb,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }


        $escala = DB::table('escala_tb')->insertGetId([
            'idUser' => $request->user_id,
            'idEmpresa' => $request->empresa_id,
            'dataCriacao' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Opcional: Broadcast para atualizar schedule
        // Broadcast::event(new NovaEscalaCriada($escala));

        return response()->json(['success' => true, 'escala_id' => $escala]);
    }

public function confirmarEscala($id)
    {
        try {
            // Traz a vaga junto para pegar os valores
            $escala = Escala::with('vaga')->findOrFail($id);

            // SEGURANÇA: Verifica se a vaga pertence à empresa logada
            if ($escala->vaga->idEmpresa !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Ação não autorizada.'], 403);
            }

            DB::transaction(function () use ($escala) {
                // CENÁRIO A: Confirmar (Cria o Gasto)
                if ($escala->status !== 'confirmada') {
                    $escala->status = 'confirmada';
                    $escala->save();

                    // SNAPSHOT: Salva o valor exato deste momento na tabela financeira
                    GastosEmpresas::create([
                        'empresa_id'       => $escala->vaga->idEmpresa,
                        'vaga_id'          => $escala->idVaga, // ou $escala->idVaga dependendo do seu model
                        'escala_id'        => $escala->id,
                        'funcao'           => $escala->vaga->funcVaga,
                        'valor'            => $escala->vaga->valor_vaga,
                        'data_confirmacao' => now(),
                    ]);
                } 
                // CENÁRIO B: Desfazer (Remove o Gasto)
                else {
                    $escala->status = 'pendente'; // Retorna ao status anterior
                    $escala->save();

                    // Estorna (apaga) o registro financeiro
                    GastosEmpresas::where('escala_id', $escala->id)->delete();
                }
            });

            return response()->json(['success' => true, 'status' => $escala->status]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro: ' . $e->getMessage()], 500);
        }
    }

    /**
     * 2. REMOVER ESCALA COMPLETA (Ação da Empresa)
     * Rota: POST /enterprises/remover-escala
     */
    public function removerEscala(Request $request)
    {
        try {
            $vagaId = $request->vaga_id;
            
            // SEGURANÇA: Verifica se a vaga é da empresa logada
            $vaga = Vaga::findOrFail($vagaId);
            if ($vaga->idEmpresa !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Você não é dono desta vaga.'], 403);
            }

            // Deleta TODAS as escalas vinculadas a esta vaga
            Escala::where('idVaga', $vagaId)->delete();
            // Opcional: Se quiser apagar gastos órfãos (caso tenha bug)
            // GastoEmpresa::where('vaga_id', $vagaId)->delete();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao remover.'], 500);
        }
    }

    /**
     * 3. REMOVER UM USUÁRIO ESPECÍFICO (Ação da Empresa na Modal)
     * Rota: POST /enterprises/remover-usuario-escala
     */
    public function removerUsuarioEscala(Request $request)
    {
        try {
            $userId = $request->user_id;
            $vagaId = $request->vaga_id;

            // Busca a escala específica
            $escala = Escala::where('idUser', $userId)
                            ->where('idVaga', $vagaId)
                            ->firstOrFail();

            // Valida se a empresa logada é dona da vaga dessa escala
            // (Assumindo relação: Escala -> Vaga -> idEmpresa)
            if ($escala->vaga->idEmpresa !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Não autorizado.'], 403);
            }

            $escala->delete();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao remover usuário.'], 500);
        }
    }

    /**
     * 4. DESISTIR DA VAGA (Ação do Trabalhador)
     * Rota: POST /workers/desistir-vaga
     */
    public function desistirVaga(Request $request)
    {
        try {
            $escalaId = $request->escala_id;
            
            // Busca a escala garantindo que pertence ao worker logado
            $escala = Escala::where('id', $escalaId)
                            ->where('idUser', Auth::id())
                            ->first();

            if (!$escala) {
                return response()->json(['success' => false, 'message' => 'Escala não encontrada ou não pertence a você.'], 404);
            }

            $escala->delete();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao desistir.'], 500);
        }
    }

}

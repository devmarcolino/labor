<?php

namespace App\Http\Controllers;

use App\Models\End;
use App\Http\Controllers\Password;
use App\Http\Controllers\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\UserHabilidadePergunta; 
use App\Models\Skill; 
use App\Models\Avaliacao; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Retorna perguntas de uma habilidade específica (AJAX).
     */

    public function editInfo()
{
    return view('workers.account-info', ['user' => auth()->user()]);
}


    public function perguntasPorHabilidade(Request $request)
    {
        $idHabilidade = $request->input('idHabilidade');
        $perguntas = \App\Models\Pergunta::where('idHabilidade', $idHabilidade)->get();
        return response()->json($perguntas);
    }

    /**
     * Salva respostas do usuário para perguntas de uma habilidade.
     */
    public function salvarRespostasPerguntas(Request $request)
    {
        $user = Auth::guard('web')->user();
        $idHabilidade = $request->input('idHabilidade');
        $respostas = $request->input('respostas'); // array: [idPergunta => resposta]

        foreach ($respostas as $idPergunta => $resposta) {
            UserHabilidadePergunta::updateOrCreate(
                [
                    'idUser' => $user->id,
                    'idHabilidade' => $idHabilidade,
                    'idPergunta' => $idPergunta,
                ],
                [
                    'resposta' => $resposta,
                ]
            );
        }
        return response()->json(['success' => true]);
    }
    public function rating() {
    $avaliacoes = \App\Models\Avaliacao::with(['avaliador', 'escala.vaga']) // Carregar quem avaliou e qual vaga
        ->where('id_avaliado', auth()->id())
        ->latest()
        ->get();
    return view('workers.rating', compact('avaliacoes'));
}
    public function edit()
{
    $user = Auth::guard('web')->user();
    $habilidades = \App\Models\Skill::all();
    $userSkills = $user->skills()->pluck('habilidades_tb.id')->toArray();

    // --- CÁLCULO DAS AVALIAÇÕES ---
    $totalAvaliacoes = \App\Models\Avaliacao::where('id_avaliado', $user->id)->count();
    $mediaNota = \App\Models\Avaliacao::where('id_avaliado', $user->id)->avg('nota') ?? 0;

    return view('workers.account', [
        'user' => $user,
        'habilidades' => $habilidades,
        'userSkills' => $userSkills,
        'totalAvaliacoes' => $totalAvaliacoes, // Enviando pro Blade
        'mediaNota' => $mediaNota,             // Enviando pro Blade
    ]);
}

    /**
     * Atualiza o perfil completo.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('web')->user();

        // Validação do usuário (Mantive a sua)
        $validatedUser = $request->validate([
            'nome_real' => 'sometimes|required|string|max:100',
            'tel'       => 'sometimes|required|string|max:20',
            'email'     => ['sometimes', 'required', 'email', 'max:100', Rule::unique('user_tb')->ignore($user->id)],
            'cpf'       => ['sometimes', 'required', 'string', 'max:14', Rule::unique('user_tb')->ignore($user->id)],
            'fotoUser'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'habilidades' => 'nullable|array',
            'habilidades.*' => 'exists:habilidades_tb,id',
            
            // ADICIONEI: Validação das respostas (opcional, mas bom ter)
            'respostas' => 'nullable|array',
        ]);

        // Validação do endereço (Mantive a sua)
        $validatedEnd = $request->validate([
            'cep'    => 'required|string|max:10',
            'rua'    => 'required|string|max:100',
            'numero' => 'required|string|max:10',
            'bairro' => 'required|string|max:50',
            'cidade' => 'required|string|max:50',
            'uf'     => 'required|string|max:2',
        ]);

        try {
            DB::transaction(function () use ($user, $request, $validatedUser, $validatedEnd) {

                // 1. Endereço
                if ($user->idEnd) {
                    $user->endereco->update($validatedEnd);
                    $validatedUser['status'] = 2; 
                } else {
                    $newEnd = End::create($validatedEnd);
                    $validatedUser['idEnd'] = $newEnd->id;
                    $validatedUser['status'] = 2;
                }

                // 2. Foto
                if ($request->hasFile('fotoUser')) {
                    if ($user->fotoUser) {
                        Storage::disk('public')->delete($user->fotoUser);
                    }
                    $path = $request->file('fotoUser')->store('fotos_perfil', 'public');
                    $validatedUser['fotoUser'] = $path;
                }

                // 3. Atualiza user
                $user->update($validatedUser);

                // 4. Habilidades (Sync)
                if ($request->has('habilidades')) {
                    $user->skills()->sync($request->habilidades);
                }

                // 5. RESPOSTAS DO QUESTIONÁRIO (NOVO!)
                if ($request->has('respostas')) {
                    foreach ($request->respostas as $idPergunta => $idOpcao) {
                        \App\Models\UserHabilidadePergunta::updateOrCreate(
                            [
                                'idUser' => $user->id,
                                'idPergunta' => $idPergunta
                            ],
                            [
                                'idOpcao' => $idOpcao // Salva o ID da opção escolhida
                            ]
                        );
                    }
                }
            });

        } catch (\Exception $e) {
            return back()->withErrors(['db_error' => 'Erro ao salvar o perfil: ' . $e->getMessage()]);
        }

        Auth::guard('web')->setUser($user->fresh());

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate(['fotoUser' => 'required|image|max:5120']); // Aumentei pra 5MB por garantia
        
        $user = auth()->user();
        if ($user->fotoUser) Storage::disk('public')->delete($user->fotoUser);
        
        $path = $request->file('fotoUser')->store('fotos_perfil', 'public');
        $user->fotoUser = $path;
        $user->save();

        return back()->with('success', 'Foto atualizada com sucesso!');
    }

    public function editSkills()
    {
        $user = auth()->user();

        // [IMPORTANTE] Pega APENAS os IDs em um array simples. 
        // O JavaScript precisa disso assim: [1, 5, 10] para marcar os checkboxes.
        $mySkillsIds = $user->skills()->pluck('habilidades_tb.id')->toArray();

        // Pega os objetos completos apenas para mostrar na lista visual (atrás do modal)
        $mySkills = $user->skills;

        // [IMPORTANTE] Carrega TODAS as skills + Perguntas + Opções.
        // Sem o 'with', o modal não consegue montar o passo-a-passo.
        $allSkills = Skill::with(['perguntas.opcoes'])->get();

        return view('workers.skills', compact('mySkills', 'mySkillsIds', 'allSkills'));
    }

    // =========================================================================
    // 2. SALVAR TUDO (POST/PATCH)
    // =========================================================================
    public function updateSkills(Request $request)
    {
        $user = auth()->user();
        
        try {
            DB::beginTransaction();

            // 1. Array de IDs das Skills (checkboxes)
            $habilidadesIds = $request->input('habilidades', []); 
            // 2. Array de Respostas (radios) [pergunta_id => opcao_id]
            $respostasForm = $request->input('respostas', []); 

            // --- PASSO 1: Atualiza a tabela pivô de skills ---
            // O sync garante que o banco fique igual ao checkbox da tela
            $user->skills()->sync($habilidadesIds);

            // --- PASSO 2: Salva as respostas ---
            if (count($respostasForm) > 0) {
                foreach ($respostasForm as $idPergunta => $idOpcao) {
                    
                    // BUSCA A PERGUNTA NO BANCO PARA DESCOBRIR A HABILIDADE DELA
                    $pergunta = \App\Models\Pergunta::find($idPergunta);
                    
                    // Só tenta salvar se a pergunta e a opção existirem
                    if ($pergunta && $idOpcao) {
                        
                        // updateOrCreate evita duplicidade de respostas para a mesma pergunta
                        \App\Models\UserHabilidadePergunta::updateOrCreate(
                            [
                                'idUser' => $user->id, 
                                'idPergunta' => $idPergunta
                            ],
                            [
                                // AQUI ESTÁ A CORREÇÃO DO ERRO:
                                // Pegamos o idHabilidade direto da pergunta que buscamos acima
                                'idHabilidade' => $pergunta->idHabilidade, 
                                'idOpcao' => $idOpcao
                            ]
                        );
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // DICA DE OURO: Isso vai fazer o erro aparecer no console do navegador (Network > Preview)
            // Em produção você tiraria isso, mas agora vai te salvar.
            return response()->json([
                'success' => false, 
                'message' => 'Erro interno', 
                'debug_error' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    // =========================================================================
    // 3. REMOVER PELO "X" (DELETE)
    // =========================================================================
    public function removeSkill($id)
    {
        $user = auth()->user();
        
        // Remove a habilidade
        $user->skills()->detach($id);
        
        // Limpa as respostas antigas dessa habilidade (opcional, mas recomendado para limpeza)
        UserHabilidadePergunta::where('idUser', $user->id)
            ->where('idHabilidade', $id)
            ->delete();

        return response()->json(['success' => true]);
    }

    public function editAddress()
    {
        // CARREGA A RELAÇÃO: O Eloquent agora busca o endereço na tabela Endereco.
        $user = auth()->user()->load('endereco'); 

        // Passa apenas os dados do usuário. A View acessa o endereço via $user->endereco.
        return view('workers.address', compact('user'));
    }

    public function updateAddress(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'cep' => 'required|string',
            'rua' => 'required|string',
            'numero' => 'required|string',
            'bairro' => 'required|string',
            'cidade' => 'required|string',
            'uf' => 'required|string|max:2',
        ]);

        try {
            // 1. CRIA/ATUALIZA o registro na tabela de ENDEREÇOS (enderecos_tb)
            $endereco = \App\Models\End::updateOrCreate(
                // Se o endereço existe, atualiza ele. Se não, cria um novo.
                // Usamos as FKs para tentar encontrar um registro existente, mas como é 1:1,
                // vamos buscar o endereço atual ou criar um.
                
                // Opção mais segura para 1:1 REVERSO:
                // Se já tem, atualiza. Se não, cria e vincula.
                $user->idEnd ? ['id' => $user->idEnd] : ['id' => null],
                $validated
            );
            
            // 2. VINCULA o ID do endereço na tabela de USUÁRIOS
            if ($user->idEnd !== $endereco->id) {
                 $user->update(['idEndereco' => $endereco->id]);
            }
            
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            // Se der erro, confira se as colunas estão no fillable do model Endereco!
            return response()->json(['success' => false, 'message' => 'Erro ao salvar endereço.'], 500);
        }
    }

    public function settings()
    {
        // Passamos o user para verificar configurações salvas no banco futuramente
        return view('workers.settings', ['user' => auth()->user()]);
    }
    public function updateInfo(Request $request)
{
    $user = auth()->user();

    $validated = $request->validate([
        'username' => 'required|string|max:50|unique:user_tb,username,'.$user->id,
        'email'    => 'required|email|max:100|unique:user_tb,email,'.$user->id,
        'tel'      => 'required|string|max:20',
    ]);

    $user->update([
        'username' => $validated['username'],
        'email'    => $validated['email'],
        'tel'      => $validated['tel'],
    ]);

    return redirect()
            ->route('workers.account')
            ->with('sucess', 'Dados atualizados com sucesso!');
}

public function updatePassword(Request $request)
{
    $validated = $request->validate([
        'current_password' => 'required|current_password',
        'password' => ['required', 'confirmed', Password::defaults()],
    ]);

    auth()->user()->update([
        'password' => Hash::make($validated['password']),
    ]);

    return redirect()
            ->route('workers.account')
            ->with('sucess', 'Senha atualizada com sucesso!');
}
}

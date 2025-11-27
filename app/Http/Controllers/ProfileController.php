<?php

namespace App\Http\Controllers;

use App\Models\End;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Retorna perguntas de uma habilidade específica (AJAX).
     */
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
            \App\Models\UserHabilidadePergunta::updateOrCreate(
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

    /**
     * Atualiza as habilidades do trabalhador (User).
     */
    public function updateSkills(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('web')->user();

        $validated = $request->validate([
            'habilidades' => 'nullable|array',
            'habilidades.*' => 'exists:habilidades_tb,id',
        ]);

        $user->skills()->sync($validated['habilidades'] ?? []);

        return redirect()->route('workers.account')
            ->with('success', 'Habilidades atualizadas com sucesso!');
    }

    /**
     * Exibe o formulário de edição.
     */
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('web')->user();
        $habilidades = \App\Models\Skill::all();
        $userSkills = $user->skills()->pluck('habilidades_tb.id')->toArray();

        return view('workers.account', [
            'user' => $user,
            'habilidades' => $habilidades,
            'userSkills' => $userSkills,
        ]);
    }

    /**
     * Atualiza o perfil completo.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('web')->user();

        // Validação do usuário
        $validatedUser = $request->validate([
            'nome_real' => 'sometimes|required|string|max:100',
            'tel'       => 'sometimes|required|string|max:20',
            'email'     => [
                'sometimes', 'required', 'email', 'max:100',
                Rule::unique('user_tb')->ignore($user->id)
            ],
            'cpf'       => [
                'sometimes', 'required', 'string', 'max:14',
                Rule::unique('user_tb')->ignore($user->id)
            ],

            'fotoUser'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'habilidades' => 'nullable|array',
            'habilidades.*' => 'exists:habilidades_tb,id'
        ]);

        // Validação do endereço
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

                // Endereço
                if ($user->idEnd) {
                    $endereco = $user->endereco;
                    $endereco->fill($validatedEnd);
                    $endereco->save();
                    $validatedUser['status'] = 2;

                } else {
                    $newEnd = End::create($validatedEnd);
                    $validatedUser['idEnd'] = $newEnd->id;
                    $validatedUser['status'] = 2;
                }

                // Foto
                if ($request->hasFile('fotoUser')) {
                    if ($user->fotoUser) {
                        Storage::disk('public')->delete($user->fotoUser);
                    }

                    $path = $request->file('fotoUser')->store('fotos_perfil', 'public');
                    $validatedUser['fotoUser'] = $path;
                }

                // Atualiza user
                $user->update($validatedUser);

                // Habilidades
                if ($request->has('habilidades')) {
                    $user->skills()->sync($request->habilidades);
                }
            });

        } catch (\Exception $e) {
            return back()->withErrors([
                'db_error' => 'Erro ao salvar o perfil: ' . $e->getMessage()
            ]);
        }

        Auth::guard('web')->setUser($user->fresh());

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }
}

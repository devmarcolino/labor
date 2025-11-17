<?php

namespace App\Http\Controllers;

use App\Models\End;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage; // <-- IMPORTE O STORAGE

class ProfileController extends Controller
{
    // ... O método edit() continua o mesmo ...
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('web')->user();

        // Para a modal de habilidades, você precisa buscar
        // todas as habilidades cadastradas no seu banco
        // $habilidades = \App\Models\Skill::all(); // (Exemplo)

        return view('workers.account', [
            'user' => $user,
            // 'habilidades' => $habilidades // Envia para a view
        ]);
    }


    /**
     * Atualiza o perfil do trabalhador (User).
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('web')->user();

        // 2. Valida os dados do PERFIL
        $validatedUser = $request->validate([
            'nome_real' => 'required|string|max:100',
            'tel'       => 'required|string|max:20',
            'email'     => ['required', 'email', 'max:100', Rule::unique('user_tb')->ignore($user->id)],
            'cpf'       => ['required', 'string', 'max:14', Rule::unique('user_tb')->ignore($user->id)],
            // NOVAS VALIDAÇÕES
            'fotoUser'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB max
            'habilidades' => 'nullable|array', // Espera um array de IDs
            'habilidades.*' => 'exists:skills,id' // Valida se cada ID existe na tabela 'skills'
        ]);

        // 3. Valida os dados do ENDEREÇO
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
                
                // LÓGICA DO ENDEREÇO (continua a mesma)
                if ($user->idEnd) {
                    $user->endereco->update($validatedEnd);
                } else {
                    $newEnd = End::create($validatedEnd);
                    $validatedUser['idEnd'] = $newEnd->id;
                    $validatedUser['status'] = 2; 
                }

                // NOVA LÓGICA DE UPLOAD DE FOTO
                if ($request->hasFile('fotoUser')) {
                    // 1. Apaga a foto antiga (se existir)
                    if ($user->fotoUser) {
                        Storage::disk('public')->delete($user->fotoUser);
                    }
                    // 2. Salva a nova foto em 'storage/app/public/fotos_perfil'
                    $path = $request->file('fotoUser')->store('fotos_perfil', 'public');
                    // 3. Adiciona o caminho no array de save
                    $validatedUser['fotoUser'] = $path;
                }

                // 5. Salva as alterações no PERFIL do usuário
                $user->update($validatedUser);

                // NOVA LÓGICA DE HABILIDADES (após salvar o user)
                if ($request->has('habilidades')) {
                    // Sincroniza as habilidades na tabela pivo (ex: skill_user)
                    // Isso adiciona os novos, remove os que não estão na lista, e mantém os que já estavam.
                    $user->skills()->sync($request->habilidades);
                }
            });

        } catch (\Exception $e) {
            return back()->withErrors(['db_error' => 'Erro ao salvar o perfil: ' . $e->getMessage()]);
        }

        return back()->with('status', 'Perfil atualizado com sucesso!');
    }
}
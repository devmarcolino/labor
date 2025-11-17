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
    // A regra 'sometimes' diz: "Só valide se o campo estiver no formulário"
    $validatedUser = $request->validate([
        'nome_real' => 'sometimes|required|string|max:100',
        'tel'       => 'sometimes|required|string|max:20',
        'email'     => ['sometimes', 'required', 'email', 'max:100', Rule::unique('user_tb')->ignore($user->id)],
        'cpf'       => ['sometimes', 'required', 'string', 'max:14', Rule::unique('user_tb')->ignore($user->id)],
        
        // Estes campos sempre vêm da modal
        'fotoUser'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'habilidades' => 'nullable|array',
        'habilidades.*' => 'exists:habilidades_tb,id' // Tabela correta
    ]);

    // 3. Valida os dados do ENDEREÇO (Esses são obrigatórios na modal)
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
            
            // LÓGICA DO ENDEREÇO
            if ($user->idEnd) {
                $user->endereco->update($validatedEnd);
            } else {
                $newEnd = End::create($validatedEnd);
                // Adiciona o ID e STATUS manualmente ao array de update
                $validatedUser['idEnd'] = $newEnd->id;
                $validatedUser['status'] = 2; 
            }

            // LÓGICA DE UPLOAD DE FOTO
            if ($request->hasFile('fotoUser')) {
                if ($user->fotoUser) {
                    Storage::disk('public')->delete($user->fotoUser);
                }
                $path = $request->file('fotoUser')->store('fotos_perfil', 'public');
                $validatedUser['fotoUser'] = $path;
            }

            // SALVA O USER
            // (O Laravel vai ignorar nome/cpf/email se eles não estiverem no validatedUser)
            $user->update($validatedUser);

            // HABILIDADES
            if ($request->has('habilidades')) {
                $user->skills()->sync($request->habilidades);
            }
        });

    } catch (\Exception $e) {
        return back()->withErrors(['db_error' => 'Erro ao salvar o perfil: ' . $e->getMessage()]);
    }

    return back()->with('status', 'Perfil atualizado com sucesso!');
}
}
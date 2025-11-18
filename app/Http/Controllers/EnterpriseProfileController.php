<?php

namespace App\Http\Controllers;

use App\Models\End;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage; // <-- IMPORTE O STORAGE

class EnterpriseProfileController extends Controller
{
    // ... O método edit() continua o mesmo ...
    public function edit()
    {
        /** @var \App\Models\Empresa $empresa */
        $empresa = Auth::guard('empresa')->user();

        return view('enterprises.account', [
            'empresa' => $empresa
        ]);
    }

    /**
     * Atualiza o perfil da Empresa.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\Empresa $empresa */
        $empresa = Auth::guard('empresa')->user();

        // 2. Valida os dados do PERFIL
        $validatedEmpresa = $request->validate([
            'nome_empresa' => 'sometimes|required|string|max:100', 
            'ramo'         => 'sometimes|nullable|string|max:100', 
            'desc_empresa' => 'nullable|string|max:255', 
            'tel'          => 'sometimes|nullable|string|max:20',
            'email'        => ['sometimes', 'required', 'email', 'max:100', Rule::unique('empresa_tb')->ignore($empresa->id)],
            'cnpj'         => ['sometimes', 'required', 'string', 'max:18', Rule::unique('empresa_tb')->ignore($empresa->id)],
            'fotoEmpresa'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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
            DB::transaction(function () use ($empresa, $request, $validatedEmpresa, $validatedEnd) {
                
                // LÓGICA DO ENDEREÇO (continua a mesma)
                if ($empresa->idEnd) {
                    $empresa->endereco->update($validatedEnd);
                } else {
                    $newEnd = End::create($validatedEnd);
                    $validatedEmpresa['idEnd'] = $newEnd->id;
                    $validatedEmpresa['status'] = 2; 
                }

                // NOVA LÓGICA DE UPLOAD DE FOTO (LOGO)
                if ($request->hasFile('fotoEmpresa')) {
                    // 1. Apaga a foto antiga (se existir)
                    // (assumindo que sua coluna de foto na empresa se chama 'foto')
                    if ($empresa->fotoEmpresa) { 
                        Storage::disk('public')->delete($empresa->fotoEmpresa);
                    }
                    // 2. Salva a nova foto em 'storage/app/public/fotos_empresa'
                    $path = $request->file('fotoEmpresa')->store('fotos_empresa', 'public');
                    // 3. Adiciona o caminho no array de save
                    $validatedEmpresa['fotoEmpresa'] = $path; // <-- Salva na coluna 'foto'
                }

                // 5. Salva as alterações no PERFIL da empresa
                $empresa->update($validatedEmpresa);
            });

        } catch (\Exception $e) {
            return back()->withErrors(['db_error' => 'Erro ao salvar o perfil: ' . $e->getMessage()]);
        }

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }
}
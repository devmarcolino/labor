<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Empresa; // Certifique-se que este é o nome do seu Model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class RegisteredEnterpriseController extends Controller
{
    public function create()
    {
        // Você precisa criar esta view
        return view('enterprises.register'); 
    }

    public function store(Request $request)
    {
        // 1. Validação (Exemplo)
       $validated = $request->validate([
            'nome_empresa' => 'required|string|max:100',
            'cnpj'         => 'required|string|max:22|unique:empresa_tb,cnpj',
            'email'        => 'required|email|max:100|unique:empresa_tb,email',
            'telefone'     => 'required|string|max:20|unique:empresa_tb,tel', // Valida o campo 'telefone'
            'ramo'         => 'required|string|max:100', 
            'password'     => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Criar a Empresa (Forma Correta)
        $empresa = Empresa::create([
            'nome_empresa' => $validated['nome_empresa'],
            'cnpj'         => $validated['cnpj'],
            'email'        => $validated['email'],
            'ramo'         => $validated['ramo'],
            'password'     => Hash::make($validated['password']),
            
            // Mapeamento explícito (campo do form -> coluna do banco)
            'tel'          => $validated['telefone'], 
            
            // Campos do Onboarding
            'idEnd'        => null, 
            'status'       => 1, // <-- ADICIONADO (para a modal funcionar)
        ]);

        // 3. Logar a Empresa
        Auth::guard('empresa')->login($empresa);

        // 4. Redirecionar para o Dashboard da Empresa
        return redirect()->route('enterprises.dashboard');
    }
}
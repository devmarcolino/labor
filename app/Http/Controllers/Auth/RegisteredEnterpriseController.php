<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Empresa; // Certifique-se que este é o nome do seu Model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use App\Rules\ValidaCnpj;

class RegisteredEnterpriseController extends Controller
{
    public function create()
    {
        // Você precisa criar esta view
        return view('enterprises.register'); 
    }

    public function store(Request $request)
    {
        // Normaliza entradas que chegam com máscara (., /, -)

        // 1. Validação (Exemplo)
        $validated = $request->validate([
            'nome_empresa' => 'required|string|max:100',
            'email'        => 'required|email:rfc,dns|max:100|unique:empresa_tb,email',
            'cnpj' => ['required', 'string', 'max:18', 'unique:empresa_tb,cnpj', new ValidaCnpj], // Adicione new ValidaCnpj
            'telefone' => ['required', 'string', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'], // Adicione Regex // Valida o campo 'telefone'
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

        // 3. Redirecionar para login corporativo
        Auth::guard('empresa')->login($empresa);
        return redirect()->route('enterprises.dashboard')->with('success', 'Conta criada com sucesso!');
    }
}
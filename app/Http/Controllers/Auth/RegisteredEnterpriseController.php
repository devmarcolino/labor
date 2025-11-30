<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use App\Rules\ValidaCnpj;

class RegisteredEnterpriseController extends Controller
{
    public function create()
    {
        return view('enterprises.register'); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome_empresa' => 'required|string|max:100',
            'email'        => 'required|email:rfc,dns|max:100|unique:empresa_tb,email',
            'cnpj' => ['required', 'string', 'max:18', 'unique:empresa_tb,cnpj', new ValidaCnpj],
            'telefone' => ['required', 'string', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],
            'ramo'         => 'required|string|max:100', 
            'password'     => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $empresa = Empresa::create([
            'nome_empresa' => $validated['nome_empresa'],
            'cnpj'         => $validated['cnpj'],
            'email'        => $validated['email'],
            'ramo'         => $validated['ramo'],
            'password'     => Hash::make($validated['password']),
            'tel'          => $validated['telefone'], 
            'idEnd'        => null, 
            'status'       => 1, 
        ]);

        Auth::guard('empresa')->login($empresa);
        return redirect()->route('enterprises.dashboard')->with('success', 'Conta criada com sucesso!');
    }
}
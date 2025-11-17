<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EnterpriseLoginController extends Controller
{
    /**
     * Exibe a view do formulário de login da empresa.
     */
    public function create()
    {
        // Certifique-se que esta view exista
        return view('enterprises.login');
    }

    /**
     * Processa a tentativa de login da empresa.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // 1. Tenta o login USANDO O GUARD 'empresa'
        if (! Auth::guard('empresa')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            
            // 2. Se falhar, retorna com erro
            throw ValidationException::withMessages([
                'email' => __('auth.failed'), // Pega a mensagem de erro padrão
            ]);
        }

        // 3. Se funcionar, regenera a sessão
        $request->session()->regenerate();

        // 4. Redireciona para o dashboard da EMPRESA
        return redirect()->intended(route('enterprises.dashboard'));
    }

    /**
     * Processa o logout da empresa.
     */
    public function destroy(Request $request)
    {
        // 5. Faz o logout USANDO O GUARD 'empresa'
        Auth::guard('empresa')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Redireciona para a home
    }
}
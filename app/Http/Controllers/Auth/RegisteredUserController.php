<?php 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
// use App\Models\Empresa; // Não precisa mais aqui
use App\Models\End; // Não precisa mais aqui
use Illuminate\Http\Request; // <<< VAMOS USAR A REQUEST PADRÃO
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // <<< PARA LOGAR O USUÁRIO
use Illuminate\Validation\Rules; // <<< Para regras de senha

class RegisteredUserController extends Controller
{
    // ... seu método create() continua aqui ...
    public function create()
    {
        return view('workers.register');
    }

    /**
     * Handle an incoming registration request.
     * Este é o NOVO método que recebe o POST do formulário.
     */
    public function store(Request $request)
    {
        // 1. Validação (SÓ dos campos do formulário)
        $validated = $request->validate([
            'nome_real' => 'required|string|max:100',
            'username'  => 'required|string|max:50|unique:user_tb,username',
            'email'     => 'required|email|max:100|unique:user_tb,email',
            'cpf'       => 'required|string|max:14|unique:user_tb,cpf',
            'datanasc'  => 'required|date_format:d/m/Y', // Recebe 'd/m/Y'
            'telefone'  => 'required|string|max:20', // O form manda 'telefone'
            'password'  => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Criar o usuário no banco
        $user = User::create([
            'nome_real' => $validated['nome_real'],
            'username'  => $validated['username'],
            'email'     => $validated['email'],
            'cpf'       => $validated['cpf'],
            // Formatar a data para o banco (de 'd/m/Y' para 'Y-m-d')
            'datanasc'  => \Carbon\Carbon::createFromFormat('d/m/Y', $validated['datanasc'])->format('Y-m-d'),
            'tel'       => $validated['telefone'], // Mapeando 'telefone' (form) para 'tel' (banco)
            'password'  => Hash::make($validated['password']),
            'idEnd'     => null, // <<< O ENDEREÇO FICA NULO! (Obrigatório)
            'status'    => 1, // Definimos como '1' (Pendente de Perfil)
        ]);

        // 3. Logar o usuário recém-criado
        Auth::login($user);

        // 4. Redirecionar para o Dashboard
        // (Aqui a sua modal obrigatória vai entrar em ação)
        return redirect()->route('workers.dashboard');
    }

    // ... (Apague os métodos registerEndereco e registerUser antigos)
}
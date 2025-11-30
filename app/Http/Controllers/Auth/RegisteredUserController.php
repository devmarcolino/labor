<?php 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use App\Rules\ValidaCpf;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('workers.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome_real' => 'required|string|max:100',
            'username'  => 'required|string|max:50|unique:user_tb,username',
            'email'        => 'required|email:rfc,dns|max:100|unique:empresa_tb,email',
            'cpf' => ['required', 'string', 'max:14', 'unique:user_tb,cpf', new ValidaCpf], // Adicione new ValidaCpf
            'telefone' => ['required', 'string', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'], // Adicione Regex
            'datanasc'  => 'required|date_format:d/m/Y', // Recebe 'd/m/Y'
            'password'  => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'nome_real' => $validated['nome_real'],
            'username'  => $validated['username'],
            'email'     => $validated['email'],
            'cpf'       => $validated['cpf'],
            'datanasc'  => \Carbon\Carbon::createFromFormat('d/m/Y', $validated['datanasc'])->format('Y-m-d'),
            'tel'       => $validated['telefone'],
            'password'  => Hash::make($validated['password']),
            'idEnd'     => null,
            'status'    => 1,
        ]);

        Auth::login($user);

        return redirect()->route('workers.dashboard')->with('success', 'Conta criada com sucesso!');
    }
}
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Http\Requests\StoreUserRequest;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreUserRequest $request)
{
    $dadosValidados = $request->validated();
    $dadosValidados['datanasc'] = \Carbon\Carbon::createFromFormat('d/m/Y', $dadosValidados['datanasc'])->format('Y-m-d');

    $user = User::create([
        'nome' => $dadosValidados['nome'],
        'user' => $dadosValidados['user'],
        'datanasc' => $dadosValidados['datanasc'],
        'telefone' => $dadosValidados['telefone'],
        'email' => $dadosValidados['email'],
        'cpf' => $dadosValidados['cpf'],
        'cidade' => $dadosValidados['cidade'],
        'estado' => $dadosValidados['estado'],
        'password' => Hash::make($dadosValidados['password']),
    ]);

    event(new Registered($user));
    return redirect('/login');
}
}
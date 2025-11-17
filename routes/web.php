<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\RegisteredEnterpriseController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EnterpriseLoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EnterpriseProfileController;

Route::get('/', function () {
    return view('index');
})->middleware('guest');

Route::get('/choose', function () {
    return view('choose');
})->middleware('guest');

Route::get('/workers/auth', function () {
    return view('/workers/auth');
})->middleware('guest');

Route::get('/workers/login', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('workers.login');
Route::post('/workers/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');

Route::get('/workers/register', [RegisteredUserController::class, 'create'])->middleware('guest')->name('workers.register');
Route::post('/workers/register', [RegisteredUserController::class, 'store'])->middleware('guest');

Route::get('/workers/dashboard', function () {
    return view('/workers/dashboard');
})->middleware('auth')->name('workers.dashboard');

Route::get('/workers/chat', function () {
    return view('/workers/chat');
})->middleware('auth')->name('workers.chat');



Route::get('/enterprises/auth', fn() => view('enterprises.auth'))->middleware('guest');

// MUDADO: Aponta para o novo EnterpriseLoginController
Route::get('/enterprises/login', [EnterpriseLoginController::class, 'create'])
       ->middleware('guest')
       ->name('enterprises.login');

// MUDADO: Aponta para o novo EnterpriseLoginController
Route::post('/enterprises/login', [EnterpriseLoginController::class, 'store'])
       ->middleware('guest');

// Rota de registro (como você já fez, apontando para o controller de registro separado)
Route::get('/enterprises/register', [RegisteredEnterpriseController::class, 'create'])
       ->middleware('guest')
       ->name('enterprises.register');

Route::post('/enterprises/register', [RegisteredEnterpriseController::class, 'store'])
       ->middleware('guest');

Route::get('/enterprises/dashboard', fn() => view('enterprises.dashboard'))
       ->middleware('auth:empresa') // <-- MUITO IMPORTANTE!
       ->name('enterprises.dashboard');

Route::get('/enterprises/chat', function () {
    return view('/enterprises/chat');
})->middleware('auth:empresa')->name('enterprises.chat');


Route::get('/workers/account', [ProfileController::class, 'edit'])
       ->middleware('auth:web') // 'auth' ou 'auth:web'
       ->name('workers.account');

// Rota para SALVAR o formulário (note o 'patch')
Route::patch('/workers/account', [ProfileController::class, 'update'])
       ->middleware('auth:web')
       ->name('workers.profile.update');

// ===================== ROTAS DE PERFIL (ENTERPRISE) =====================
// Rota para MOSTRAR o formulário de perfil
Route::get('/enterprises/account', [EnterpriseProfileController::class, 'edit'])
       ->middleware('auth:empresa')
       ->name('enterprises.account');

// Rota para SALVAR o formulário (note o 'patch')
Route::patch('/enterprises/account', [EnterpriseProfileController::class, 'update'])
       ->middleware('auth:empresa')
       ->name('enterprises.profile.update');
// ===================== LOGOUT =====================

// Rota de Logout para TRABALHADORES
Route::post('/workers/logout', [AuthenticatedSessionController::class, 'destroy'])
       ->middleware('auth') // usa o guard padrão 'web'
       ->name('logout'); // MANTENHA o name 'logout' se seu layout já usa

// Rota de Logout para EMPRESAS
Route::post('/enterprises/logout', [EnterpriseLoginController::class, 'destroy'])
       ->middleware('auth:empresa') // usa o guard 'empresa'
       ->name('enterprises.logout');
<?php

use Illuminate\Support\Facades\Route;

// Controllers de Autenticação
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\RegisteredEnterpriseController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EnterpriseLoginController;

// Controllers de Perfil (Onboarding)
use App\Http\Controllers\ProfileController; // Perfil User
use App\Http\Controllers\EnterpriseProfileController; // Perfil Empresa

// Models
use App\Models\Skill;
use App\Http\Controllers\VagaCurtidaController;

/*
|--------------------------------------------------------------------------
| 🏠 PÚBLICO
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('index'))->name('home');
Route::get('/choose', fn() => view('choose'))->name('choose');

/*
|--------------------------------------------------------------------------
| 👷 TRABALHADORES (Workers) - Guard: 'web'
|--------------------------------------------------------------------------
*/

// --- GUEST (Visitante) ---
Route::middleware('guest')->group(function () {
    Route::get('/workers/auth', fn() => view('workers.auth'))->name('workers.auth');

    // Login & Registro
    Route::get('/workers/login', [AuthenticatedSessionController::class, 'create'])->name('workers.login');
    Route::post('/workers/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/workers/register', [RegisteredUserController::class, 'create'])->name('workers.register');
    Route::post('/workers/register', [RegisteredUserController::class, 'store']);
});

// --- AUTH (Logado) ---
Route::middleware('auth:web')->group(function () {

    // Dashboard (+ Habilidades para a Modal de Onboarding)
    Route::get('/workers/dashboard', function () {
        $habilidades = Skill::all();
        return view('workers.dashboard', ['habilidades' => $habilidades]);
    })->name('workers.dashboard');

    // Chat & Perfil
    Route::get('/workers/chat', fn() => view('workers.chat'))->name('workers.chat');

    // Conta/Perfil (Onboarding usa isso)
    Route::get('/workers/account', [ProfileController::class, 'edit'])->name('workers.account');
    Route::patch('/workers/account', [ProfileController::class, 'update'])->name('workers.profile.update');

    // Logout
    Route::post('/workers/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // === OUTRAS PÁGINAS ===
    Route::view('/workers/settings', 'workers.settings')->name('workers.settings');
    Route::view('/workers/schedule', 'workers.schedule')->name('workers.schedule');
    Route::view('/workers/other_jobs', 'workers.other_jobs')->name('workers.jobs'); // nome correto do arquivo é 'other_jobs'
    Route::view('/workers/rating', 'workers.rating')->name('workers.rating');
    Route::view('/workers/skills', 'workers.skills')->name('workers.skills');
    Route::view('/workers/address', 'workers.adress')->name('workers.address'); // 'adress' com um D só

    /*
    |--------------------------------------------------------------------------
    | 🚨 ROTA DE REGISTRO DE VISUALIZAÇÃO
    | SOMENTE O WORKER DEVE TER ACESSO
    |--------------------------------------------------------------------------
    */
    Route::post(
        '/vagas/visualizar',
        [\App\Http\Controllers\VisualizacaoVagaController::class, 'registrar']
    )->name('vagas.visualizar');

    // Rota para curtir vaga
    Route::post('/vagas/curtir', [VagaCurtidaController::class, 'store'])->name('vagas.curtir');

    // Página de Vagas Curtidas
    Route::get('/workers/vagas-curtidas', [VagaCurtidaController::class, 'index'])->name('workers.vagasCurtidas');

    // Rota para retornar vagas curtidas em JSON
    Route::get('/workers/vagas-curtidas-json', function() {
        $userId = auth()->id();
        return \App\Models\VagaCurtida::where('user_id', $userId)->get(['vaga_id']);
    })->middleware('auth:web');

    // Rota para registrar interações do usuário com a vaga (curtir ou rejeitar) no cache.
    Route::post('/vagas/interagir', [VagaCurtidaController::class, 'interagir'])->middleware('auth:web');

    // Rota para retornar interações do usuário em JSON
    Route::get('/workers/vagas-interacoes-json', function() {
        $userId = auth()->id();
        return \Illuminate\Support\Facades\Cache::get('interacoes_user_' . $userId, []);
    })->middleware('auth:web');
});

/*
|--------------------------------------------------------------------------
| 🏢 EMPRESAS (Enterprises) - Guard: 'empresa'
|--------------------------------------------------------------------------
*/

// --- GUEST (Visitante) ---
Route::middleware('guest')->group(function () {
    Route::get('/enterprises/auth', fn() => view('enterprises.auth'))->name('enterprises.auth');

    // Login & Registro
    Route::get('/enterprises/login', [EnterpriseLoginController::class, 'create'])->name('enterprises.login');
    Route::post('/enterprises/login', [EnterpriseLoginController::class, 'store']);

    Route::get('/enterprises/register', [RegisteredEnterpriseController::class, 'create'])->name('enterprises.register');
    Route::post('/enterprises/register', [RegisteredEnterpriseController::class, 'store']);
});

// --- AUTH (Logado como Empresa) ---
Route::middleware('auth:empresa')->group(function () {

    // Minhas vagas (lista)
    Route::get('/enterprises/vagas', [\App\Http\Controllers\EnterpriseVagaController::class, 'list'])
        ->name('enterprises.vagas.list');

    // ⚠️ IMPORTANTE: rota de visualizar vaga NÃO fica aqui!

    // Dashboard
    Route::get('/enterprises/dashboard', fn() => view('enterprises.dashboard'))
        ->name('enterprises.dashboard');

    // Criar vaga
    Route::get('/enterprises/vagas/create', [\App\Http\Controllers\EnterpriseVagaController::class, 'create'])
        ->name('enterprises.vagas.create');

    Route::post('/enterprises/vagas', [\App\Http\Controllers\EnterpriseVagaController::class, 'store'])
        ->name('enterprises.vagas.store');

    // Excluir vaga
    Route::delete('/enterprises/vagas/delete/{id}', [\App\Http\Controllers\EnterpriseVagaController::class, 'destroy'])
        ->name('enterprises.vagas.delete');

    // Chat & Perfil
    Route::get('/enterprises/chat', fn() => view('enterprises.chat'))->name('enterprises.chat');

    Route::get('/enterprises/account', [EnterpriseProfileController::class, 'edit'])->name('enterprises.account');
    Route::patch('/enterprises/account', [EnterpriseProfileController::class, 'update'])->name('enterprises.profile.update');

    // Logout
    Route::post('/enterprises/logout', [EnterpriseLoginController::class, 'destroy'])->name('enterprises.logout');

    // === OUTRAS PÁGINAS ===
    Route::view('/enterprises/settings', 'enterprises.settings')->name('enterprises.settings');
    Route::view('/enterprises/schedule', 'enterprises.schedule')->name('enterprises.schedule');
    Route::view('/enterprises/jobs', 'enterprises.jobs')->name('enterprises.jobs');
    Route::view('/enterprises/rating', 'enterprises.rating')->name('enterprises.rating');
    Route::view('/enterprises/address', 'enterprises.adress')->name('enterprises.address');
    Route::view('/enterprises/analytics', 'enterprises.analytics')->name('enterprises.analytics');
});

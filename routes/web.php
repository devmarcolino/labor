<?php

use Illuminate\Support\Facades\Route;

// Controllers de Autenticação
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\RegisteredEnterpriseController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EnterpriseLoginController;

// Controllers de Perfil (Onboarding)
use App\Http\Controllers\ProfileController; // Perfil User
use App\Http\Controllers\EnterpriseProfileController;
use App\Http\Controllers\EnterpriseVagaController; // Perfil Empresa

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
            // Formulário para responder perguntas de uma habilidade
            Route::get('/workers/responder-perguntas', function() {
                $habilidade_id = request('habilidade_id');
                return view('workers.responder-perguntas', ['habilidade_id' => $habilidade_id]);
            })->name('workers.responderPerguntas');

            // Salvar respostas do formulário
            Route::post('/workers/salvar-respostas', [\App\Http\Controllers\WorkerRespostaController::class, 'salvarRespostas'])->name('workers.salvarRespostas');
        // Página de configurações do worker
        Route::get('/workers/settings', function() {
            return view('workers.settings');
        })->name('workers.settings');
    // Perguntas por habilidade (AJAX)
    Route::post('/workers/perguntas-habilidade', [ProfileController::class, 'perguntasPorHabilidade'])->name('workers.perguntas.habilidade');
    // Salvar respostas das perguntas
    Route::post('/workers/salvar-respostas-perguntas', [ProfileController::class, 'salvarRespostasPerguntas'])->name('workers.salvar.respostas.perguntas');

    // Dashboard (+ Habilidades para a Modal de Onboarding)
    Route::get('/workers/dashboard', function () {
    // Eager Loading: Já traz as perguntas e as opções de resposta de uma vez
    $habilidades = Skill::with('perguntas.opcoes')->get();
    
    return view('workers.dashboard', ['habilidades' => $habilidades]);
})->name('workers.dashboard');

    // Chat & Perfil
    Route::get('/workers/chat', fn() => view('workers.chat'))->name('workers.chat');

    // Conta/Perfil (Onboarding usa isso)
    Route::get('/workers/account', [ProfileController::class, 'edit'])->name('workers.account');
    Route::patch('/workers/account', [ProfileController::class, 'update'])->name('workers.profile.update');
    Route::post('/workers/account/photo', [ProfileController::class, 'updatePhoto'])
    ->name('workers.profile.photo.update');
    // Logout

    Route::post('/workers/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/workers/settings', [ProfileController::class, 'settings'])->name('workers.settings');

    Route::get('/workers/address', [ProfileController::class, 'editAddress'])->name('workers.edit.address');
    Route::patch('/workers/update-address', [ProfileController::class, 'updateAddress'])->name('workers.update.address');
    // === OUTRAS PÁGINAS ===
    Route::view('/workers/schedule', 'workers.schedule')->name('workers.schedule');
    Route::view('/workers/rating', 'workers.rating')->name('workers.rating');

    Route::get('/workers/skills', [ProfileController::class, 'editSkills'])
        ->name('workers.skills');

    // 2. Rota para SALVAR/ATUALIZAR as habilidades do Modal (PATCH)
    // O JavaScript manda para cá. Note que é PATCH porque seu JS usa _method: PATCH
    Route::patch('/workers/update-skills', [ProfileController::class, 'updateSkills'])
        ->name('workers.update.skills');

    // 3. Rota para DELETAR uma habilidade específica (DELETE)
    // O botão "X" manda para cá
    Route::delete('/workers/skills/{id}/remove', [ProfileController::class, 'removeSkill'])
        ->name('workers.skills.remove');

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
    Route::post('/vagas/curtir', [\App\Http\Controllers\CandidaturaController::class, 'store'])->name('vagas.curtir');

    // Página de Vagas Curtidas
    Route::get('/workers/vagas-curtidas', [VagaCurtidaController::class, 'index'])->name('workers.vagasCurtidas');

    Route::get('/workers/account/info', [ProfileController::class, 'editInfo'])
        ->name('workers.account.info');

    // 2. Ação de Salvar Informações
    Route::patch('/workers/account/info', [ProfileController::class, 'updateInfo'])
        ->name('workers.account.info.update');

    // 3. Ação de Trocar Senha
    Route::put('/workers/password', [ProfileController::class, 'updatePassword'])
        ->name('workers.password.update');
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

    Route::post('/enterprises/account/photo', [EnterpriseProfileController::class, 'updatePhoto'])
    ->name('enterprises.profile.photo.update');
    Route::get('/enterprises/register', [RegisteredEnterpriseController::class, 'create'])->name('enterprises.register');
    Route::post('/enterprises/register', [RegisteredEnterpriseController::class, 'store']);
});

// --- AUTH (Logado como Empresa) ---
Route::middleware('auth:empresa')->group(function () {

    // Minhas vagas (lista)
    Route::get('/enterprises/vagas', [EnterpriseVagaController::class, 'list'])
        ->name('enterprises.vagas.list');

    // ⚠️ IMPORTANTE: rota de visualizar vaga NÃO fica aqu
    // Dashboard
    Route::get('/enterprises/dashboard', fn() => view('enterprises.dashboard'))
        ->name('enterprises.dashboard');

    // Criar vaga
    Route::get('/enterprises/vagas/create', [EnterpriseVagaController::class, 'create'])
        ->name('enterprises.vagas.create');

    Route::post('/enterprises/vagas', [EnterpriseVagaController::class, 'store'])
        ->name('enterprises.vagas.store');

    // Excluir vaga
    Route::delete('/enterprises/vagas/delete/{id}', [EnterpriseVagaController::class, 'destroy'])
        ->name('enterprises.vagas.delete');

    Route::patch('/enterprises/vagas/concluir/{id}', [EnterpriseVagaController::class, 'concluir'])
    ->name('enterprises.vagas.concluir');

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

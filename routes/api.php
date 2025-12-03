<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VagaController;
use App\Http\Controllers\EnterpriseVagaController;
use App\Http\Controllers\Api\ValidationController;
use App\Http\Controllers\Api\CandidatoFeedController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rota de Validação (Pública)
Route::post('/validate-field', [ValidationController::class, 'check']);

// Rota de Vagas (PROTEGIDA e usando sessão WEB)
// Se não tiver 'auth:web', o auth()->user() retorna null e quebra tudo.
// CERTO: Carregamos a sessão 'web' E DEPOIS verificamos o 'auth'

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/vagas', [VagaController::class, 'index']);
    Route::get('/vagas/destaque', [VagaController::class, 'destaque']);
    Route::get('/vaga/{id}/melhor-candidato', [VagaController::class, 'melhorCandidato']);
    // Rota que alimenta o FEED (Tinder)

});

Route::get('/vaga/{id}/melhor-candidato-ia', [VagaController::class, 'melhorCandidatoIA']);
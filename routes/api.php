<?php

use App\Http\Controllers\Api\ValidationController;
use App\Http\Controllers\Api\VagaController;
use Illuminate\Support\Facades\Route;

Route::post('/validate-field', [ValidationController::class, 'check']);
Route::get('/vagas', [VagaController::class, 'index']);
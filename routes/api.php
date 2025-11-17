<?php

use App\Http\Controllers\Api\ValidationController;
use App\Http\Controllers\Api\VagaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('/validate-field', [ValidationController::class, 'check']);
Route::get('/vagas', [VagaController::class, 'index']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
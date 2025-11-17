<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ValidationController;

// Adicione esta linha
Route::post('/validate-field', [ValidationController::class, 'check']);
<?php

use App\Http\Controllers\Api\ValidationController;
use Illuminate\Support\Facades\Route;

Route::post('/validate-field', [ValidationController::class, 'check']);
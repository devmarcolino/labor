<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
})->middleware('guest');

Route::get('/choose', function () {
    return view('choose');
})->middleware('guest');

Route::get('/workers/auth', function () {
    return view('/workers/auth');
})->middleware('guest');

Route::get('/workers/login', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('login');
Route::post('/workers/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');

Route::get('/workers/register', [RegisteredUserController::class, 'create'])->middleware('guest')->name('register');
Route::post('/workers/register', [RegisteredUserController::class, 'store'])->middleware('guest');

Route::get('/workers/dashboard', function () {
    return view('/workers/dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/enterprises/auth', function () {
    return view('/enterprises/auth');
})->middleware('guest');

Route::get('/enterprises/login', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('login');
Route::post('/enterprises/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');

Route::get('/enterprises/register', [RegisteredUserController::class, 'create'])->middleware('guest')->name('register');
Route::post('/enterprises/register', [RegisteredUserController::class, 'store'])->middleware('guest');

Route::get('/enterprises/dashboard', function () {
    return view('/enterprises/dashboard');
})->middleware('auth')->name('dashboard');


Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');


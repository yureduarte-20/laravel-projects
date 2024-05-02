<?php

use App\Http\Controllers\Api\AgendamentoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::resource('especialidade', \App\Http\Controllers\Api\EspecialidadeController::class)
        ->except(['create', 'update', 'edit', 'destroy', 'store']);
    Route::post('/agendar', [AgendamentoController::class, 'store'])->name('agendamento.store');
});
Route::post('/login', [\App\Http\Controllers\Api\UserController::class, 'login']);
Route::post('/signup', [\App\Http\Controllers\Api\UserController::class, 'signup']);

<?php

use App\Http\Controllers\Api\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', [UserController::class,'login']);
Route::post('signup', [UserController::class,'signup']);
Route::middleware('auth:sanctum')->group(function(){
    Route::controller(\App\Http\Controllers\Api\RentalsController::class)->group(function(){
        Route::get('/rentals', 'index');
        Route::post('/rentals', 'store');
        Route::get('/rentals/{id}', 'show');
        Route::delete('/rentals/{id}', 'cancel');
    });
    Route::controller(\App\Http\Controllers\Api\CarController::class)->group(function(){
        Route::get('/cars', 'index');
    });
});

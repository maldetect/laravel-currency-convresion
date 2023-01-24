<?php

use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::prefix('currencies')->as('currencies.')->middleware('auth:sanctum')->group(function(){
    Route::get('/',[CurrencyController::class ,'index'])->name('index');

    Route::post('/convert',[CurrencyController::class ,'convert'])->name('convert');
});

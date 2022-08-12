<?php

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

Route::group(['prefix' => 'user'], function ($router) {
    Route::post('/register',[\App\Http\Controllers\api\UserController::class, 'register']);
    Route::post('/login',[\App\Http\Controllers\api\UserController::class, 'login']);
    Route::post('/logout',[\App\Http\Controllers\api\UserController::class, 'logout']);
});

Route::group(['prefix' => 'currency','middleware' => 'auth:api'], function ($router) {
    Route::get('/list',[\App\Http\Controllers\api\CurrencyController::class, 'getAvailableCurrencies']);
    Route::get('/live_rate',[\App\Http\Controllers\api\CurrencyController::class, 'getCurrenciesLiveRate']);
    Route::get('/period_statis',[\App\Http\Controllers\api\CurrencyController::class, 'getPeriodStatis']);
});

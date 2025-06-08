<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\WeatherDataController;
use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractDataController;
use App\Http\Controllers\ContractAuthController;
use App\Http\Controllers\ContractUserController;
use App\Http\Middleware\CheckSubscriptionToken;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\UserController;

//AUTHENTICATIE
Route::post('/contracten/login', [ContractAuthController::class, 'login']);
Route::post('/contract/logout', [ContractAuthController::class, 'logout']);

//GEBRUIKERS-BEHEER MET JWT

    Route::get('/contracten/{identifier}/users', [ContractUserController::class, 'list']);
    Route::get('/contracten/{identifier}/user/{useridentifier}', [ContractUserController::class, 'show']);
    Route::post('/contracten/{identifier}/user', [ContractUserController::class, 'store']);
    Route::put('/contracten/{identifier}/user/{useridentifier}', [ContractUserController::class, 'update']);
    Route::delete('/contracten/{identifier}/user/{useridentifier}', [ContractUserController::class, 'destroy']);


//ABONNEMENTEN MET TOKEN-CHECK
Route::middleware([CheckSubscriptionToken::class])->group(function () {
    Route::get('/abonnement/{identifier}', [AbonnementController::class, 'showWeather']);
    Route::get('/abonnement/{identifier}/stations', [AbonnementController::class, 'listStations']);
    Route::get('/abonnement/{identifier}/station/{naam}', [AbonnementController::class, 'stationDetails']);
});

//CONTRACT-QUERIES
Route::get('/contracten/{identifier}/{queryID}', [ContractDataController::class, 'getQueryData']);
Route::get('/contracten/{identifier}/{queryID}/stations', [ContractDataController::class, 'getStationsForQuery']);
Route::get('/contracten/{identifier}/station/{name}', [ContractDataController::class, 'getStationDetails']);

//OSAKA AUTHENTICATIE
Route::post('/login', [ApiAuthController::class, 'login']);
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);


//DEBUG
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

//TEST WEATHER UPLOAD
Route::any('/postWeatherData', [WeatherDataController::class, 'receiveWeatherData']);

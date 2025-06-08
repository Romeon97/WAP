<?php

use App\Http\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NearestLocationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SubscriptionManagerController;
use App\Http\Controllers\CompanyController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckRegisterPermission;
use App\Http\Middleware\CompanyMiddleware;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\CriteriumController;
use App\Http\Controllers\CriteriumGroupController;
use App\Http\Controllers\ContractUserController;
use App\Http\Controllers\ContractAuthController;




//Homepagina route
Route::get('/', fn() => view('index'))->name('index');

//Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Gebruikersregistratie
Route::middleware(['auth', CheckRegisterPermission::class])->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');
});

//Weerdata
Route::get('/nearestlocation', [NearestLocationController::class, 'index'])->name('nearestlocation.index');
Route::get('/station/{stationName}', [NearestLocationController::class, 'showMeasurements'])->name('station.measurements');
Route::get('/stations/map-data', [NearestLocationController::class, 'mapData']);
Route::get('/stations/load-more', [NearestLocationController::class, 'loadMore'])->name('stations.loadMore');

//Admin-only gebruikersbeheer
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/admin/manage-users', [AdminController::class, 'manageUsers'])->name('admin.manageUsers');
    Route::put('/admin/manage-users', [AdminController::class, 'updateUsers'])->name('admin.updateUsers');
    Route::delete('/admin/manage-users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
});

//Dashboard + beheer routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Logs
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

    // Overzicht en CRUD op abonnementen
    Route::get('/manage-subscriptions', [SubscriptionManagerController::class, 'index'])->name('manage-subscriptions.index');
    Route::post('/manage-subscriptions', [SubscriptionManagerController::class, 'store'])->name('manage-subscriptions.store');

//route voor het bewerken  van één abonnement
    Route::get('/manage-subscriptions/{id}/edit', [SubscriptionManagerController::class, 'edit'])->name('manage-subscriptions.edit');
    Route::put('/manage-subscriptions/{id}', [SubscriptionManagerController::class, 'update'])->name('manage-subscriptions.update');

    Route::delete('/manage-subscriptions/{id}', [SubscriptionManagerController::class, 'destroy'])->name('manage-subscriptions.destroy');

//Stations beheren
    Route::get('/manage-subscriptions/{id}/stations', [SubscriptionManagerController::class, 'editStations'])->name('manage-subscriptions.editStations');
    Route::post('/manage-subscriptions/{id}/stations', [SubscriptionManagerController::class, 'updateStations'])->name('manage-subscriptions.updateStations');
});

//CRUD bedrijvenbeheer

Route::middleware(['auth', CompanyMiddleware::class])->group(function () {
    Route::resource('bedrijven', CompanyController::class);
});

//monitoring stations
Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
Route::get('/monitoring/active-stations', [MonitoringController::class, 'activeStations'])->name('monitoring.active');
Route::get('/monitoring/errors', [MonitoringController::class, 'activeErrors'])->name('monitoring.errors');
Route::get('/monitoring/errors/station/{stationName}', [MonitoringController::class, 'showStationErrors'])->name('monitoring.errors.detail');
Route::put('/monitoring/errors/update/{id}', [MonitoringController::class, 'updateError'])->name('monitoring.errors.update');

//Resource routes voor Contract:
Route::resource('contracts', ContractController::class);

//Nested resource routes voor Query binnen Contract
Route::resource('contracts.queries', QueryController::class);

//Groups (genest onder queries)
Route::resource('contracts.queries.groups', CriteriumGroupController::class);

//Criteria (genest onder groups)
Route::resource('contracts.queries.groups.criteria', CriteriumController::class);

Route::post('/criteria/store', [CriteriumGroupController::class, 'store'])->name('contracts.queries.criteria.store');
Route::post('/criteria/index', [CriteriumGroupController::class, 'index'])->name('contracts.queries.criteria.index');

//Test route
Route::get('/session-test', function () {
    session(['test' => 'Sessie werkt!']);
    return session('test', 'Sessie werkt niet!');
});


Route::get('/contract/register', function () {
    //Alleen als je ingelogd bent én admin bent
    if (!session()->has('contract_user_identifier')) {
        return redirect('/contract/login')->withErrors(['Je moet eerst inloggen.']);
    }

    if (session('contract_user_is_admin') !== 1) {
        return redirect('/contract/dashboard')->withErrors(['Je hebt geen toegang tot deze pagina.']);
    }

    return view('contract.register');
});

Route::get('/contract/users', function () {
    if (!session()->has('contract_user_identifier')) {
        return redirect('/contract/login')->withErrors(['Je moet eerst inloggen.']);
    }

    if (session('contract_user_is_admin') !== 1) {
        return redirect('/contract/dashboard')->withErrors(['Je hebt geen toegang tot deze pagina.']);
    }

    return app(\App\Http\Controllers\ContractUserController::class)->beheer();
});

Route::post('/contract/users', [ContractUserController::class, 'storeFromWeb'])->name('contractusers.store');
Route::get('/contract/login', [ContractAuthController::class, 'showLoginForm'])->name('contract.login');
Route::post('/contract/login', [ContractAuthController::class, 'login'])->name('contract.login.submit');
Route::post('/contract/logout', [ContractAuthController::class, 'logout'])->name('contract.logout');
Route::get('/contract/dashboard', [ContractUserController::class, 'dashboard']);

<?php

use Illuminate\Support\Facades\Route;

/*
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'create'])->name('login');
    Route::post('/login',   [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register',[RegisterController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
});
*/


//Barebones preview routes


//Auth pages (guest screens)
Route::view('/login',    'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

//Fake login — submitting the login form lands on the MFA screen.
Route::post('/login', fn () => redirect()->route('mfa.show'))->name('login.attempt');

//MFA screen — fake data passed directly as view data (no session, no controller)
Route::view('/mfa', 'auth.mfa', [
    'email' => 'demo@housing-raajje.test',
    'code'  => '123456',
])->name('mfa.show');

//MFA submit — just bounces back to the MFA page so the button doesn't 404
Route::post('/mfa', fn () => redirect('/mfa'))->name('mfa.verify');

//Fake logout so the layout's form doesn't error on submit
Route::post('/logout', fn () => redirect('/login'))->name('logout');

//Client portal
Route::view('/client',            'client.home')->name('client.home');
Route::view('/client/properties', 'client.properties.index')->name('client.properties');

//Owner portal
Route::view('/owner', 'owner.dashboard')->name('owner.dashboard');

//Admin portal
Route::view('/admin', 'admin.dashboard')->name('admin.dashboard');
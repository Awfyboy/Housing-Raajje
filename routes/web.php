<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\MfaController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'create'])->name('login');
    Route::post('/login',   [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register',[RegisterController::class, 'store']);

    Route::get('/mfa',  [MfaController::class, 'show'])->name('mfa.show');
    Route::post('/mfa', [MfaController::class, 'verify'])->name('mfa.verify');

    //PASSWORD RESET ROUTES
    Route::view('/forgot-password', 'auth.login')->name('password.request');
    Route::post('/forgot-password', [LoginController::class, 'sendResetLink'])->name('password.email');
    
    Route::get('/reset-password/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', fn () => view('auth.mfa'))->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('login')->with('status', 'Email verified successfully! You can now log in.'); //extra fix
    })->middleware('signed')->name('verification.verify');
    
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () { // temp
        return '<div style="text-align:center; margin-top:50px; font-family:sans-serif;">' .
               '<h1>You are logged in successfully!</h1>' .
               '<form method="POST" action="' . route('logout') . '" style="margin-top:20px;">' .
               csrf_field() .
               '<button type="submit" style="padding: 10px 20px; cursor:pointer;">Click here to Log out</button>' .
               '</form>' .
               '</div>';
    })->name('dashboard'); //temp
});

// Portals (Public for now)
//clients
Route::view('/client/home', 'client.home')->name('client.home');
Route::view('/client/properties', 'client.properties.index')->name('client.properties');
//owner
Route::view('/owner/dashboard', 'owner.dashboard')->name('owner.dashboard');
//admin
Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');

//auth protected later
/*
Route::middleware('auth')->group(function () {
    Route::view('/client',            'client.home')->name('client.home');
    Route::view('/client/properties', 'client.properties.index')->name('client.properties');
    Route::view('/owner', 'owner.dashboard')->name('owner.dashboard');
    Route::view('/admin', 'admin.dashboard')->name('admin.dashboard');
});
*/
<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\MfaController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

//Home
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route(Auth::user()->dashboardRoute());
    }
    return view('client.home');
})->name('home');

//Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'create'])->name('login');
    Route::post('/login',   [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register',[RegisterController::class, 'store']);

    Route::get('/mfa',  [MfaController::class, 'show'])->name('mfa.show');
    Route::post('/mfa', [MfaController::class, 'verify'])->name('mfa.verify');

    // PASSWORD RESET ROUTES
    Route::view('/forgot-password', 'auth.login')->name('password.request');
    Route::post('/forgot-password', [LoginController::class, 'sendResetLink'])->name('password.email');
    
    Route::get('/reset-password/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.store');
}); 

//Logout
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

//Auth & Email Verification 
// Public so the Success page is visible after logging out
Route::get('/email/verify', fn () => view('auth.verify-email'))->name('verification.notice');

// Resend (now handled by the controller with rate limiting)
Route::post('/email/verification-notification', [RegisterController::class, 'resendVerification'])
    ->middleware('auth')
    ->name('verification.send');

// The signed link that marks the email as verified
Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    $user = \App\Models\User::find($request->route('id'));

    if (! $user) {
        return redirect()->route('login')
            ->withErrors('status', 'This verification link has expired. The account no longer exists. Please register again.');
    }

    if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
        abort(403, 'Invalid verification link.');
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));
    }

    if (Auth::check()) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    return redirect()->route('verification.notice')->with('verified_success', true);
    
})->middleware('signed')->name('verification.verify');

//Authenticated/Protected Routes (only logged in AND verified users)
Route::middleware(['auth', 'verified'])->group(function () {
    // Client
    Route::view('/client/home', 'client.home')->name('client.dashboard'); 
    Route::view('/client/properties', 'client.properties.index')->name('client.properties');

    // Owner
    Route::view('/owner/dashboard', 'owner.dashboard')->name('owner.dashboard');

    // Admin
    Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');

    // Staff
    Route::view('/staff/dashboard', 'staff.dashboard')->name('staff.dashboard');

    // Receptionist
    Route::view('/receptionist/dashboard', 'receptionist.dashboard')->name('receptionist.dashboard');

    // Branch Manager
    Route::view('/branch-manager/dashboard', 'branch-manager.dashboard')->name('branch-manager.dashboard');
});
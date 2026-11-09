<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\MfaController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\AuditLogController;   
use App\Http\Controllers\Admin\DashboardController;
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
            ->withErrors(['email' => 'This verification link has expired. The account no longer exists. Please register again.']);
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
    // Universal Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::put('/profile/mfa', [ProfileController::class, 'toggleMfa'])->name('profile.mfa.toggle');
    Route::put('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.email.update');
    Route::get('/profile/email/verify/{token}', [ProfileController::class, 'verifyEmailChange'])->name('profile.email.verify');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Client
    Route::view('/client/home', 'client.home')->name('client.dashboard'); 
    Route::view('/client/properties', 'client.properties.index')->name('client.properties');

    // Owner
    Route::view('/owner/dashboard', 'owner.dashboard')->name('owner.dashboard');

    // Staff
    Route::view('/staff/dashboard', 'staff.dashboard')->name('staff.dashboard');

    // Receptionist
    Route::view('/receptionist/dashboard', 'receptionist.dashboard')->name('receptionist.dashboard');

    // Branch Manager
    Route::view('/branch-manager/dashboard', 'branch-manager.dashboard')->name('branch-manager.dashboard');

    // Admin Dashboard
    Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard'); 
    // Admin User Management Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        //User Management Routes
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
        Route::post('/users/{user}/reactivate', [AdminUserController::class, 'reactivate'])->name('users.reactivate');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        
        //Requests (Must go ABOVE the wildcard /users/{user} routes)
        Route::get('/requests', [AdminUserController::class, 'requests'])->name('users.requests');

        //Request Approvals
        Route::post('/users/{user}/approve', [AdminUserController::class, 'approveRequest'])->name('users.approve');
        Route::post('/users/{user}/reject', [AdminUserController::class, 'rejectRequest'])->name('users.reject');

        //branch management
        Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
        Route::get('/branches/create', [BranchController::class, 'create'])->name('branches.create');
        Route::post('/branches', [BranchController::class, 'store'])->name('branches.store');
        Route::get('/branches/{branch}/edit', [BranchController::class, 'edit'])->name('branches.edit');
        Route::put('/branches/{branch}', [BranchController::class, 'update'])->name('branches.update');
        Route::delete('/branches/{branch}', [BranchController::class, 'destroy'])->name('branches.destroy');

        //Audit Logs
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });
});
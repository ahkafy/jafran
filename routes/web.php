<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\MLMController;


Route::get('/', function () {
    return redirect('/app');
});

Auth::routes();

// Dashboard routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/app', [DashboardController::class, 'index'])->name('home');

    // Profile routes
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');

    // Wallet routes
    Route::get('/wallet', [DashboardController::class, 'wallet'])->name('wallet');

    // Investment routes
    Route::prefix('investments')->name('investments.')->group(function () {
        Route::get('/', [InvestmentController::class, 'index'])->name('index');
        Route::get('/history', [InvestmentController::class, 'history'])->name('history');
        Route::get('/daily-returns', [InvestmentController::class, 'dailyReturns'])->name('daily-returns');
        Route::get('/package/{package}', [InvestmentController::class, 'show'])->name('show');
        Route::post('/invest', [InvestmentController::class, 'invest'])->name('invest');
        Route::get('/details/{investment}', [InvestmentController::class, 'investmentDetails'])->name('details');
    });

    // MLM routes
    Route::prefix('mlm')->name('mlm.')->group(function () {
        Route::get('/', [MLMController::class, 'index'])->name('index');
        Route::get('/genealogy', [MLMController::class, 'genealogy'])->name('genealogy');
        Route::get('/referrals', [MLMController::class, 'referrals'])->name('referrals');
        Route::get('/commissions', [MLMController::class, 'commissions'])->name('commissions');
        Route::get('/team', [MLMController::class, 'team'])->name('team');
        Route::get('/referral-link', [MLMController::class, 'referralLink'])->name('referral-link');
    });
});

// Public routes
Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');

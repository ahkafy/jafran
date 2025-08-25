<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\MLMController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AccessController;

// Access blocked route (no middleware)
Route::get('/access-blocked', [AccessController::class, 'blocked'])->name('access.blocked');

// Apply geolocation middleware to all routes except access blocked
Route::middleware(['geolocation'])->group(function () {
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
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::get('/wallet/add-funds', [WalletController::class, 'addFunds'])->name('wallet.add-funds');
    Route::post('/wallet/stripe-payment', [WalletController::class, 'processStripePayment'])->name('wallet.stripe-payment');
    Route::post('/wallet/paypal-payment', [WalletController::class, 'processPayPalPayment'])->name('wallet.paypal-payment');
    Route::post('/wallet/bank-transfer', [WalletController::class, 'submitBankTransfer'])->name('wallet.bank-transfer');

    Route::get('/wallet/gift-cards', [WalletController::class, 'giftCards'])->name('wallet.gift-cards');
    Route::post('/wallet/create-gift-card', [WalletController::class, 'createGiftCard'])->name('wallet.create-gift-card');
    Route::post('/wallet/redeem-gift-card', [WalletController::class, 'redeemGiftCard'])->name('wallet.redeem-gift-card');

    Route::get('/wallet/withdrawal', [WalletController::class, 'withdrawal'])->name('wallet.withdrawal');
    Route::post('/wallet/withdrawal', [WalletController::class, 'submitWithdrawal'])->name('wallet.submit-withdrawal');

    Route::get('/wallet/transactions', [WalletController::class, 'transactions'])->name('wallet.transactions');

    // Wallet index alias for backwards compatibility
    Route::get('/wallet/index', [WalletController::class, 'index'])->name('wallet.index');

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
});

<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Dashboard Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transactions
    Route::get('/transactions', [DashboardController::class, 'transactions'])->name('transactions.index');
    Route::get('/transactions/export', [DashboardController::class, 'exportTransactions'])->name('transactions.export');
    Route::post('/transactions', [DashboardController::class, 'storeTransaction'])->name('transactions.store');
    Route::delete('/transactions/{transaction}', [DashboardController::class, 'destroyTransaction'])->name('transactions.destroy');

    // Wallets / Portfolios
    Route::get('/wallets', [DashboardController::class, 'wallets'])->name('wallets.index');
    Route::post('/wallets', [DashboardController::class, 'storeWallet'])->name('wallets.store');

    // Budgets
    Route::get('/budgets', [DashboardController::class, 'budgets'])->name('budgets.index');
    Route::post('/budgets', [DashboardController::class, 'storeBudget'])->name('budgets.store');

    // Investments
    Route::get('/investments', [DashboardController::class, 'investments'])->name('investments.index');

    // Settings
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings.index');
    Route::post('/settings/clear', [DashboardController::class, 'clearAllData'])->name('settings.clear');
});

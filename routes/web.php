<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TrialController;
use App\Http\Controllers\VerifyController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('landing');
})->name('landing');

Route::get('/verify/{token}', [VerifyController::class, 'show'])->name('coa.verify');

// Guest
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Auth - trial expired page (auth but no subscription check)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/trial-expired', [TrialController::class, 'expired'])->name('trial.expired');
});

// ===== ADMIN PANEL =====
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/companies', [AdminController::class, 'companies'])->name('companies');
    Route::get('/companies/{company}', [AdminController::class, 'show'])->name('companies.show');
    Route::post('/companies/{company}/activate', [AdminController::class, 'activate'])->name('companies.activate');
    Route::post('/companies/{company}/extend-trial', [AdminController::class, 'extendTrial'])->name('companies.extend');
    Route::post('/companies/{company}/deactivate', [AdminController::class, 'deactivate'])->name('companies.deactivate');
    Route::post('/companies/{company}/notes', [AdminController::class, 'saveNotes'])->name('companies.notes');
});

// ===== APP (auth + subscription check) =====
Route::middleware(['auth', 'subscription'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::post('/products/{product}/params', [ProductController::class, 'storeParam'])->name('products.params.store');
    Route::delete('/products/{product}/params/{param}', [ProductController::class, 'destroyParam'])->name('products.params.destroy');

    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store');
    Route::post('/materials/lots', [MaterialController::class, 'storeLot'])->name('materials.lots.store');

    Route::get('/batches', [BatchController::class, 'index'])->name('batches.index');
    Route::get('/batches/new', [BatchController::class, 'create'])->name('batches.create');
    Route::post('/batches', [BatchController::class, 'store'])->name('batches.store');
    Route::get('/batches/{batch}', [BatchController::class, 'show'])->name('batches.show');
    Route::post('/batches/{batch}/results', [BatchController::class, 'saveResults'])->name('batches.results');
    Route::get('/batches/{batch}/coa', [BatchController::class, 'coa'])->name('batches.coa');

    Route::get('/bills', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/bills/new', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/bills', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/bills/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::post('/bills/{sale}/payment', [SaleController::class, 'addPayment'])->name('sales.payment');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/logo', [SettingsController::class, 'uploadLogo'])->name('settings.logo');
    Route::delete('/settings/logo', [SettingsController::class, 'removeLogo'])->name('settings.logo.remove');
});

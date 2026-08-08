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
use App\Http\Controllers\DeclarationController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Auth;

// Public
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : view('landing');
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
    Route::patch('/products/{product}/params/{param}', [ProductController::class, 'updateParam'])->name('products.params.update');

    Route::delete('/products/{product}/params/{param}', [ProductController::class, 'destroyParam'])->name('products.params.destroy');

    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store');
    Route::post('/materials/lots', [MaterialController::class, 'storeLot'])->name('materials.lots.store');
    Route::patch('/materials/{rawMaterial}', [MaterialController::class, 'update'])->name('materials.update');
    Route::patch('/materials/lots/{lot}', [MaterialController::class, 'updateLot'])->name('materials.lots.update');

    Route::get('/batches', [BatchController::class, 'index'])->name('batches.index');
    Route::get('/batches/new', [BatchController::class, 'create'])->name('batches.create');
    Route::post('/batches', [BatchController::class, 'store'])->name('batches.store');
    Route::get('/batches/{batch}', [BatchController::class, 'show'])->name('batches.show');
    Route::post('/batches/{batch}/results', [BatchController::class, 'saveResults'])->name('batches.results');
    Route::get('/batches/{batch}/coa', [BatchController::class, 'coa'])->name('batches.coa');
    Route::patch('/batches/{batch}', [BatchController::class, 'update'])->name('batches.update');

    Route::get('/bills', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/bills/new', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/bills', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/bills/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::post('/bills/{sale}/payment', [SaleController::class, 'addPayment'])->name('sales.payment');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::patch('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');


    Route::get('/bills/{sale}/declaration', [DeclarationController::class, 'show'])->name('declarations.show');
    Route::post('/bills/{sale}/declaration', [DeclarationController::class, 'store'])->name('declarations.store');

    Route::get('/quotations', [QuotationController::class, 'index'])->name('quotations.index');
    Route::get('/quotations/create', [QuotationController::class, 'create'])->name('quotations.create');
    Route::post('/quotations', [QuotationController::class, 'store'])->name('quotations.store');
    Route::get('/quotations/{quotation}', [QuotationController::class, 'show'])->name('quotations.show');
    Route::patch('/quotations/{quotation}/status', [QuotationController::class, 'updateStatus'])->name('quotations.status');
    Route::delete('/quotations/{quotation}', [QuotationController::class, 'destroy'])->name('quotations.destroy');

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::patch('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');

    // Supplier Products
    Route::post('/suppliers/{supplier}/products', [SupplierController::class, 'storeProduct'])->name('suppliers.products.store');
    Route::patch('/suppliers/{supplier}/products/{product}', [SupplierController::class, 'updateProduct'])->name('suppliers.products.update');

    // Supplier COAs
    Route::post('/suppliers/{supplier}/products/{product}/coas', [SupplierController::class, 'storeCoa'])->name('suppliers.coas.store');
    Route::patch('/suppliers/coas/{coa}', [SupplierController::class, 'updateCoa'])->name('suppliers.coas.update');
});

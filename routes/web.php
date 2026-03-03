<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CashierDashboardController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;

// Landing Page
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role ?? 'Cashier';
        return $role === 'Admin'
            ? redirect()->route('dashboard')
            : redirect()->route('cashier.dashboard');
    }
    return view('landing');
})->name('landing');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes 
Route::middleware('auth')->group(function () {

    // Invoice 
    Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');

    // ── Cashier Routes
    Route::middleware('role:Cashier,Admin')->group(function () {
        Route::get('/cashier/dashboard', [CashierDashboardController::class, 'index'])->name('cashier.dashboard');
        Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
        Route::get('/pos/search', [POSController::class, 'searchProduct'])->name('pos.search');
        Route::post('/pos/store', [POSController::class, 'store'])->name('pos.store');
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    });

    // ── Admin-only Routes ─────────────────────────────────────────
    Route::middleware('role:Admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Products & Actions
        Route::get('/products/export', [\App\Http\Controllers\ProductActionController::class, 'exportProducts'])->name('products.export');
        Route::get('/stock/export', [\App\Http\Controllers\ProductActionController::class, 'exportStock'])->name('stock.export');
        Route::post('/products/import', [\App\Http\Controllers\ProductActionController::class, 'importProducts'])->name('products.import');
        Route::post('/stock/import', [\App\Http\Controllers\ProductActionController::class, 'importStock'])->name('stock.import');
        Route::get('/products/barcodes/print', [\App\Http\Controllers\ProductActionController::class, 'generateBarcodes'])->name('products.barcodes.print');
        Route::resource('products', ProductController::class);

        // Inventory
        Route::resource('categories', CategoryController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('purchases', PurchaseController::class);

        // Customers full CRUD (Admin only for create/edit/delete)
        Route::resource('customers', CustomerController::class)->except(['index', 'show']);

        // Reports
        Route::get('/reports/sales', [ReportController::class, 'salesSummary'])->name('reports.sales');
        Route::get('/reports/inventory', [ReportController::class, 'inventoryReport'])->name('reports.inventory');

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

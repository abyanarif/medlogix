<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public Landing Page
Route::get('/', function () {
    return view('landing_page');
})->name('landing');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Billing Flow (Pharmacist Side)
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing/upload', [BillingController::class, 'upload'])->name('billing.upload');

    // Subscribed Pharmacists only (Dashboard & Inventory)
    Route::middleware('subscribed')->group(function () {
        // Dashboard Home (Dashboard Apoteker Branch)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Kelola Inventory Branch (Form & Notification Config)
        Route::get('/inventory', [DashboardController::class, 'inventory'])->name('inventory');
        Route::post('/inventory/store', [DashboardController::class, 'storeMedicine'])->name('inventory.store');
        Route::post('/notifications/update', [DashboardController::class, 'updateNotifications'])->name('notifications.update');

        // Data Stock & Reminder Branch
        Route::get('/stock-reminder', [DashboardController::class, 'stockReminder'])->name('stock-reminder');

        // Medicine CRUD Edit & Update
        Route::get('/medicines/{id}/edit', [MedicineController::class, 'edit'])->name('medicines.edit');
        Route::put('/medicines/{id}', [MedicineController::class, 'update'])->name('medicines.update');

        // Dispense Medicine
        Route::get('/inventory/{id}/dispense', [MedicineController::class, 'showDispenseForm'])->name('medicines.dispense');
        Route::post('/inventory/{id}/dispense', [MedicineController::class, 'dispense'])->name('medicines.dispense.submit');

        // Audit Trail / Transaction Log
        Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    });

    // Admin Dashboard Flow (Superadmin Side)
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/users/{id}/approve', [AdminController::class, 'approve'])->name('admin.users.approve');
        Route::post('/users/{id}/reject', [AdminController::class, 'reject'])->name('admin.users.reject');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    });
});

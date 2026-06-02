<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

    // Dashboard Home (Dashboard Apoteker Branch)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

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

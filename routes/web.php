<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuditPointController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/tasks/{task}/attachment/{media}', [TaskController::class, 'attachment'])->name('tasks.attachment');
    
    // Sadece Admin ve Manager
    Route::middleware(['role:admin|manager'])->group(function () {
        Route::resource('tasks', TaskController::class)->only(['create', 'store', 'destroy']);
        Route::resource('audit-points', AuditPointController::class)->except(['index', 'show']);
    });

    // Herkesin erişebileceği sayfalar (İlgili filtrelemeler controller/policy'de yapılmalı)
    Route::resource('tasks', TaskController::class)->only(['index', 'show', 'edit', 'update']);
    Route::resource('audit-points', AuditPointController::class)->only(['index', 'show']);
    Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Sadece Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
    });
});

require __DIR__.'/auth.php';

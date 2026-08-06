<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuditPointController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
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
    Route::post('/tasks/{task}/claim', [TaskController::class, 'claimTask'])->name('tasks.claim');
    Route::post('/tasks/{task}/complete', [TaskController::class, 'completeTask'])->name('tasks.complete');
    
    Route::post('/feedbacks', [\App\Http\Controllers\FeedbackController::class, 'store'])->name('feedbacks.store');
    
    // Sadece Admin ve Manager
    Route::middleware(['role:admin|manager'])->group(function () {
        Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');
        Route::resource('tasks', TaskController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('audit-points', AuditPointController::class)->except(['index', 'show']);
    });

    // Herkesin erişebileceği sayfalar (İlgili filtrelemeler controller/policy'de yapılmalı)
    Route::resource('tasks', TaskController::class)->only(['index', 'show']);
    Route::resource('audit-points', AuditPointController::class)->only(['index', 'show']);
    Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Sadece Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });
    
    // Notifications
    Route::get('/notifications/{notification}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

require __DIR__.'/auth.php';

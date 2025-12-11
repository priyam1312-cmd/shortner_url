<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;

// Root route
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Public routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Short URL redirect (public)
Route::get('/s/{shortCode}', [ShortUrlController::class, 'redirect'])->name('shorturl.redirect');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // URL Shortener routes
    Route::get('/urls', [ShortUrlController::class, 'index'])->name('urls.index');
    Route::post('/urls', [ShortUrlController::class, 'store'])->name('urls.store');
    Route::get('/urls/download', [ShortUrlController::class, 'download'])->name('urls.download');
    
    // Member routes
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/urls', [ShortUrlController::class, 'index'])->name('urls.index');
        Route::post('/urls', [ShortUrlController::class, 'store'])->name('urls.store');
    });
    
    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/urls', [ShortUrlController::class, 'index'])->name('urls.index');
        Route::get('/team-members', [AdminController::class, 'teamMembers'])->name('team-members');
        Route::get('/invite', [AdminController::class, 'showInviteForm'])->name('invite');
        Route::post('/invite', [AdminController::class, 'inviteMember'])->name('invite.store');
    });
    
    // SuperAdmin routes
    Route::prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/clients', [SuperAdminController::class, 'clients'])->name('clients');
        Route::get('/urls', [ShortUrlController::class, 'index'])->name('urls.index');
        Route::get('/invite-client', [SuperAdminController::class, 'showInviteClientForm'])->name('invite-client');
        Route::post('/invite-client', [SuperAdminController::class, 'inviteClient'])->name('invite-client.store');
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuperAdmin\UserManagementController;
use App\Http\Controllers\SuperAdmin\DocumentTypeController;
use App\Http\Controllers\SuperAdmin\TemplateController;
use App\Http\Controllers\SuperAdmin\NumberFormatController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Super Admin Routes
    Route::prefix('superadmin')->middleware(['role:super_admin'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.superadmin');
        })->name('superadmin.dashboard');

        Route::resource('users', UserManagementController::class);

        // Document Type Management
        Route::resource('document-types', DocumentTypeController::class)->except('show', 'destroy');
        Route::get('document-types/{documentType}/history', [DocumentTypeController::class, 'history'])->name('document-types.history');
        Route::patch('document-types/{documentType}/rollback/{version}', [DocumentTypeController::class, 'rollback'])->name('document-types.rollback');

        // Template Management
        Route::resource('templates', TemplateController::class)->except('show', 'destroy');
        Route::get('templates/{template}/history', [TemplateController::class, 'history'])->name('templates.history');
        Route::get('templates/versions/{version}/preview', [TemplateController::class, 'preview'])->name('templates.preview');
        Route::patch('templates/{template}/rollback/{version}', [TemplateController::class, 'rollback'])->name('templates.rollback');

        // Number Format Management
        Route::resource('number-formats', NumberFormatController::class)->except('show', 'destroy');
        Route::get('number-formats/{numberFormat}/history', [NumberFormatController::class, 'history'])->name('number-formats.history');
        Route::patch('number-formats/{numberFormat}/rollback/{version}', [NumberFormatController::class, 'rollback'])->name('number-formats.rollback');

        Route::view('logs', 'superadmin.logs.index')->name('logs.index');
    });

    // Admin Routes
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.admin', [
                'pendingCount' => 0,
                'processingCount' => 0,
                'completedCount' => 0,
                'rejectedCount' => 0,
                'recentDocuments' => [],
                'overdueDocuments' => []
            ]);
        })->name('admin.dashboard');

        // Document routes will be implemented in Phase 4
        Route::view('documents', 'admin.documents.index')->name('admin.documents.index');
        Route::get('documents/{document}', function () {
            return redirect()->back(); })->name('admin.documents.show');
    });

    // Client Routes
    Route::prefix('client')->middleware(['role:client'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard.client', [
                'recentDocuments' => [],
                'unreadCount' => 0
            ]);
        })->name('client.dashboard');

        // Document routes will be implemented in Phase 4
        Route::view('documents/create', 'client.documents.create')->name('client.documents.create');
        Route::view('documents', 'client.documents.index')->name('client.documents.index');
        Route::get('documents/{document}', function () {
            return redirect()->back(); })->name('client.documents.show');
        Route::get('documents/{document}/download', function () {
            return redirect()->back(); })->name('client.documents.download');
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuperAdmin\UserManagementController;
use App\Http\Controllers\SuperAdmin\DocumentTypeController;
use App\Http\Controllers\SuperAdmin\TemplateController;
use App\Http\Controllers\SuperAdmin\NumberFormatController;
use App\Http\Controllers\SuperAdmin\LogController;
use App\Http\Controllers\Admin\DocumentApprovalController;
use App\Http\Controllers\Client\NotificationsController;

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

        // Log Management
        Route::get('logs', [LogController::class, 'index'])->name('logs.index');
        Route::get('logs/{log}', [LogController::class, 'show'])->name('logs.show');
    });

    // Admin Routes
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', function () {
            $pendingCount = \App\Models\Document::where('status', 'pending')->count();
            $processingCount = \App\Models\Document::where('status', 'processing')->count();
            $completedCount = \App\Models\Document::where('status', 'completed')->count();
            $rejectedCount = \App\Models\Document::where('status', 'rejected')->count();

            $recentDocuments = \App\Models\Document::with('documentType', 'user')
                ->latest()
                ->take(5)
                ->get();

            $overdueDocuments = \App\Models\Document::with('documentType', 'user')
                ->where('status', 'pending')
                ->where('created_at', '<=', now()->subDays(3))
                ->get();

            return view('dashboard.admin', compact(
                'pendingCount',
                'processingCount',
                'completedCount',
                'rejectedCount',
                'recentDocuments',
                'overdueDocuments'
            ));
        })->name('admin.dashboard');

        // Document Approval & Request Routes
        Route::get('documents/create', [App\Http\Controllers\Admin\DocumentApprovalController::class, 'create'])->name('admin.documents.create');
        Route::post('documents', [App\Http\Controllers\Admin\DocumentApprovalController::class, 'store'])->name('admin.documents.store');
        Route::get('documents', [App\Http\Controllers\Admin\DocumentApprovalController::class, 'index'])->name('admin.documents.index');
        Route::get('documents/{document}', [App\Http\Controllers\Admin\DocumentApprovalController::class, 'show'])->name('admin.documents.show');
        Route::get('documents/{document}/preview', [App\Http\Controllers\Admin\DocumentApprovalController::class, 'preview'])->name('admin.documents.preview');
        Route::post('documents/{document}/preview', [App\Http\Controllers\Admin\DocumentApprovalController::class, 'preview']);
        Route::post('documents/{document}/approve', [App\Http\Controllers\Admin\DocumentApprovalController::class, 'approve'])->name('admin.documents.approve');
        Route::post('documents/{document}/reject', [App\Http\Controllers\Admin\DocumentApprovalController::class, 'reject'])->name('admin.documents.reject');
        Route::get('documents/{document}/download', [App\Http\Controllers\Admin\DocumentApprovalController::class, 'download'])->name('admin.documents.download');

        // Admin Notifications Routes
        Route::get('notifications', [App\Http\Controllers\Admin\NotificationsController::class, 'index'])->name('admin.notifications.index');
        Route::post('notifications/mark-all-read', [App\Http\Controllers\Admin\NotificationsController::class, 'markAllAsRead'])->name('admin.notifications.mark-all-read');
        Route::post('notifications/{notification}/mark-read', [App\Http\Controllers\Admin\NotificationsController::class, 'markAsRead'])->name('admin.notifications.mark-read');
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuperAdmin\UserManagementController;
use App\Http\Controllers\SuperAdmin\DocumentTypeController;
use App\Http\Controllers\SuperAdmin\TemplateController;
use App\Http\Controllers\SuperAdmin\NumberFormatController;
use App\Http\Controllers\Admin\DocumentApprovalController;
use App\Http\Controllers\Client\DocumentRequestController;
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

        Route::view('logs', 'superadmin.logs.index')->name('logs.index');
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

        // Document Approval Routes
        Route::get('documents', [App\Http\Controllers\Admin\DocumentApprovalController::class, 'index'])->name('admin.documents.index');
        Route::get('documents/{document}', [App\Http\Controllers\Admin\DocumentApprovalController::class, 'show'])->name('admin.documents.show');
        Route::get('documents/{document}/preview', [App\Http\Controllers\Admin\DocumentApprovalController::class, 'preview'])->name('admin.documents.preview');
        Route::post('documents/{document}/approve', [App\Http\Controllers\Admin\DocumentApprovalController::class, 'approve'])->name('admin.documents.approve');
        Route::post('documents/{document}/reject', [App\Http\Controllers\Admin\DocumentApprovalController::class, 'reject'])->name('admin.documents.reject');
    });    // Client Routes
    Route::prefix('client')->middleware(['role:client'])->group(function () {
        Route::get('/dashboard', function () {
            $recentDocuments = \App\Models\Document::where('client_id', auth()->id())
                ->with('documentType')
                ->latest()
                ->take(5)
                ->get();

            $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count();

            return view('dashboard.client', compact('recentDocuments', 'unreadCount'));
        })->name('client.dashboard');

        // Document Request Routes
        Route::get('documents/create', [App\Http\Controllers\Client\DocumentRequestController::class, 'create'])->name('client.documents.create');
        Route::post('documents', [App\Http\Controllers\Client\DocumentRequestController::class, 'store'])->name('client.documents.store');
        Route::get('documents', [App\Http\Controllers\Client\DocumentRequestController::class, 'index'])->name('client.documents.index');
        Route::get('documents/{document}', [App\Http\Controllers\Client\DocumentRequestController::class, 'show'])->name('client.documents.show');
        Route::get('documents/{document}/download', [App\Http\Controllers\Client\DocumentRequestController::class, 'download'])->name('client.documents.download');

        // Notifications Routes
        Route::get('notifications', [App\Http\Controllers\Client\NotificationsController::class, 'index'])->name('client.notifications.index');
        Route::post('notifications/{notification}/read', [App\Http\Controllers\Client\NotificationsController::class, 'markAsRead'])->name('client.notifications.mark-read');
        Route::post('notifications/read-all', [App\Http\Controllers\Client\NotificationsController::class, 'markAllAsRead'])->name('client.notifications.mark-all-read');
    });
});

<?php

use App\Http\Controllers\AdminProdi\UnitController;
use App\Http\Controllers\AdminProdi\UserController;
use App\Http\Controllers\Asesor\AuditController;
use App\Http\Controllers\Auditi\TemuanController;
use App\Http\Controllers\AdminProdi\PeriodeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auditi\HasilAuditorController;
use App\Http\Controllers\Asesor\DashboardController as AsesorDashboardController;
use App\Http\Controllers\Auditi\DashboardController as AuditiDashboardController;

Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role->nama) {
            'ASESOR' => redirect()->route('asesor.dashboard'),
            default => redirect()->route('auditi.dashboard'),
        };
    }

    return redirect()->route('login');
});

Route::post('/periode', function (\Illuminate\Http\Request $request) {
    session(['periode' => $request->periode]);
    return back();
})->name('periode.set');

Route::post('/notifications/read', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return response()->json(['success' => true]);
})->middleware('auth');

Route::get('/notifications/count', function () {
    return response()->json([
        'count' => auth()->user()
            ->unreadNotifications()
            ->count()
    ]);
})->middleware('auth');

Route::get('/notification/{id}', function ($id) {
    $notification = auth()
        ->user()
        ->notifications()
        ->findOrFail($id);
    if (! $notification->read_at) {
        $notification->markAsRead();
    }
    return redirect(
        $notification->data['url'] ?? '/'
    );
})->middleware('auth')
    ->name('notification.read');

Route::middleware(['auth', 'nocache',])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware([
    'auth', 'nocache',
    'role:ADMIN_PRODI,KPS,DOSEN,TEKNISI'
])->group(function () {
    Route::get('/dashboard-auditi', [AuditiDashboardController::class, 'index'])
        ->name('auditi.dashboard');
});

Route::prefix('temuan')->name('temuan.')->middleware(['auth', 'nocache', 'role:ADMIN_PRODI,KPS,DOSEN,TEKNISI'])
    ->group(function () {
        Route::get('/', [TemuanController::class, 'index'])->name('index');
        Route::get('/{audit}/distribusi', [TemuanController::class, 'distribusi'])->name('distribusi');
        Route::post('/{temuan}', [TemuanController::class, 'update'])->name('update');
        Route::post('/{temuan}/assign', [TemuanController::class, 'assign'])->name('assign');
    });

Route::prefix('auditi')->name('auditi.')->middleware(['auth', 'nocache', 'role:KPS,TEKNISI'])
    ->group(function () {
        Route::get('/hasil-auditor', [HasilAuditorController::class, 'index'])->name('hasil-auditor.index');
        Route::get('/hasil-auditor/{audit}/download', [HasilAuditorController::class, 'download'])
            ->name('hasil-auditor.download');
    });

Route::prefix('kps')->name('kps.')->middleware(['auth', 'nocache', 'role:KPS'])->group(function () {
    // Periode
    Route::get('/periode', [PeriodeController::class, 'index'])->name('periode.index');
    Route::post('/periode', [PeriodeController::class, 'store'])->name('periode.store');
    Route::delete('/periode/{periode}', [PeriodeController::class, 'destroy'])->name('periode.destroy');
    Route::post('/periode/{id}/activate', [PeriodeController::class, 'activate'])->name('periode.activate');

    // User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/template', [UserController::class, 'exportTemplate'])->name('users.template');
    Route::post('/users/import', [UserController::class, 'import'])->name('users.import');

    // Unit
    Route::resource('units', UnitController::class)->except(['show']);
});

// ASESOR
Route::prefix('asesor')->name('asesor.')->middleware(['auth', 'nocache', 'role:ASESOR'])->group(function () {
    Route::get('/dashboard', [AsesorDashboardController::class, 'index'])->name('dashboard');
    // AUDIT
    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
    Route::post('/audit', [AuditController::class, 'store'])->name('audit.store');
    Route::get('/audit/{id}', [AuditController::class, 'show'])->name('audit.show');
    Route::get('/audit/{id}/template', [AuditController::class, 'downloadTemplate'])
        ->name('audit.template.download');
    Route::post('/audit/{id}/import', [AuditController::class, 'import'])->name('audit.import');
    Route::get('/audit/{id}/export', [AuditController::class, 'export'])->name('audit.export');
    Route::post('/audit/{id}/upload-final-pdf', [AuditController::class, 'uploadFinalPdf'])
        ->name('audit.uploadFinalPdf');
    Route::delete('/audit/{id}', [AuditController::class, 'destroy'])->name('audit.destroy');
    // TEMUAN REVIEW
    Route::get('/temuan', [AuditController::class, 'temuan'])->name('temuan.index');
    Route::post('/review/{id}', [AuditController::class, 'review'])->name('review');
    Route::post('/validasi/{id}', [AuditController::class, 'validasi'])->name('validasi');
});

require __DIR__.'/auth.php';

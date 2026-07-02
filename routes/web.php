<?php

use App\Http\Controllers\AdminProdi\InstrumentTemplateController;
use App\Http\Controllers\AdminProdi\UnitController;
use App\Http\Controllers\AdminProdi\UserController;
use App\Http\Controllers\Asesor\AuditController;
use App\Http\Controllers\Auditi\TemuanController;
use App\Http\Controllers\AdminProdi\PeriodeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asesor\DashboardController as AsesorDashboardController;
use App\Http\Controllers\Auditi\DashboardController as AuditiDashboardController;

Route::get('/', function () {
    return view('auth.login');
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware([
    'auth',
    'role:ADMIN_PRODI,KPS,DOSEN,TEKNISI'
])->group(function () {
    Route::get('/dashboard-auditi', [AuditiDashboardController::class, 'index'])
        ->name('auditi.dashboard');
});

Route::prefix('temuan')
    ->name('temuan.')
    ->middleware([
        'auth',
        'role:ADMIN_PRODI,KPS,DOSEN,TEKNISI'
    ])
    ->group(function () {
        Route::get('/', [TemuanController::class, 'index'])->name('index');
        Route::post('/{id}', [TemuanController::class, 'update'])
            ->name('update');
    });

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:ADMIN_PRODI'])->group(function () {
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

    // Template Instrumen
    Route::get('/templates', [InstrumentTemplateController::class, 'index'])->name('templates.index');
    Route::post('/templates', [InstrumentTemplateController::class, 'store'])->name('templates.store');
    Route::post('/templates/{template}/activate', [InstrumentTemplateController::class, 'activate'])->name('templates.activate');
});

// ASESOR
Route::prefix('asesor')->name('asesor.')->middleware(['auth', 'role:ASESOR'])->group(function () {
    Route::get('/dashboard', [AsesorDashboardController::class, 'index'])->name('dashboard');
    // AUDIT
    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
    Route::get('/audit/create', [AuditController::class, 'create'])->name('audit.create');
    Route::post('/audit', [AuditController::class, 'store'])->name('audit.store');
    Route::get('/audit/{id}', [AuditController::class, 'show'])->name('audit.show');
    Route::get('/audit/{id}/template', [AuditController::class, 'downloadTemplate'])
        ->name('audit.template.download');
    Route::post('/audit/{id}/import', [AuditController::class, 'import'])->name('audit.import');
    Route::get('/audit/{id}/export', [AuditController::class, 'export'])->name('audit.export');
    Route::delete('/audit/{id}', [AuditController::class, 'destroy'])->name('audit.destroy');
    // TEMUAN REVIEW
    Route::get('/temuan', [AuditController::class, 'temuan'])->name('temuan.index');
    Route::post('/review/{id}', [AuditController::class, 'review'])->name('review');
    Route::post('/validasi/{id}', [AuditController::class, 'validasi'])->name('validasi');
});

require __DIR__.'/auth.php';

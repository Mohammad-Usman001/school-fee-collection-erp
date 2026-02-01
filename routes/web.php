<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FeeStructureController;
use App\Http\Controllers\FeeCollectionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\AcademicSessionController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeeHeadController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('students', StudentController::class);
    Route::resource('classes', SchoolClassController::class)->parameters([
        'classes' => 'class'
    ]);
    Route::resource('sessions', AcademicSessionController::class)->parameters([
        'sessions' => 'session'
    ]);
    Route::resource('sections', SectionController::class);
    // Recycle Bin
    Route::get('students-recycle-bin', [StudentController::class, 'recycleBin'])->name('students.recycleBin');
    Route::post('students/{id}/restore', [StudentController::class, 'restore'])->name('students.restore');
    Route::delete('students/{id}/force-delete', [StudentController::class, 'forceDelete'])->name('students.forceDelete');
    Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');
    Route::post('/promotions/promote', [PromotionController::class, 'promote'])->name('promotions.promote');
    Route::post('/promotions/preview', [PromotionController::class, 'preview'])->name('promotions.preview');
    Route::post('/promotions/confirm', [PromotionController::class, 'confirm'])->name('promotions.confirm');
Route::resource('fee-heads', FeeHeadController::class);
    Route::resource('fee-structures', FeeStructureController::class);

    // recycle bin optional
    Route::get('fee-structures-recycle-bin', [FeeStructureController::class, 'recycleBin'])->name('fee-structures.recycleBin');
    Route::post('fee-structures/{id}/restore', [FeeStructureController::class, 'restore'])->name('fee-structures.restore');
    Route::delete('fee-structures/{id}/force-delete', [FeeStructureController::class, 'forceDelete'])->name('fee-structures.forceDelete');

    // Fee Collection (New Professional)
    Route::get('fees', [FeeCollectionController::class, 'index'])->name('fees.index');
    Route::get('fees/create', [FeeCollectionController::class, 'create'])->name('fees.create');
    Route::post('fees/store', [FeeCollectionController::class, 'store'])->name('fees.store');
Route::delete('fees/{id}/delete', [FeeCollectionController::class, 'delete'])->name('fees.delete');
    // AJAX
    Route::get('fees/student-search', [FeeCollectionController::class, 'studentSearch'])->name('fees.studentSearch');
    Route::get('fees/month-payments', [FeeCollectionController::class, 'monthPayments'])->name('fees.monthPayments');
// routes/web.php
Route::get('fees/load-pending-invoices', [FeeCollectionController::class, 'loadPendingInvoices'])
    ->name('fees.loadPendingInvoices');
Route::post('fees/load-invoice', [FeeCollectionController::class, 'loadInvoice'])
    ->name('fees.loadInvoice');

    // Receipt
    Route::get('fees/payment/{payment}/receipt', [FeeCollectionController::class, 'receipt'])->name('fees.receipt');

    Route::get('ledger', [LedgerController::class, 'index'])->name('ledger.index');
    Route::get('ledger/{student}', [LedgerController::class, 'show'])->name('ledger.show');

    // Export optional
    Route::get('ledger/{student}/pdf', [LedgerController::class, 'pdf'])->name('ledger.pdf');
    // Recycle bin


    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/reports/monthly', [ReportController::class, 'monthlyReport'])
        ->name('reports.monthly');

    Route::get('/reports/class-wise', [ReportController::class, 'classWiseReport'])
        ->name('reports.classWise');

    Route::get('/reports/dues', [ReportController::class, 'dueReport'])
        ->name('reports.dues');

    Route::get('/reports/monthly-pdf', [ReportController::class, 'monthlyPdf'])
        ->name('reports.monthlyPdf');

    Route::get('backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('backup/create', [BackupController::class, 'create'])->name('backup.create');
    Route::get('backup/download/{file}', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('backup/delete/{file}', [BackupController::class, 'delete'])->name('backup.delete');

    // Restore
    Route::get('backup/restore', [BackupController::class, 'restoreForm'])->name('backup.restoreForm');
    Route::post('backup/restore', [BackupController::class, 'restore'])->name('backup.restore');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
});

require __DIR__ . '/auth.php';

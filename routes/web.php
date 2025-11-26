<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\VerifyController;

Route::get('/', [VerifyController::class, 'index'])->name('verify.index');
Route::post('/verify', [VerifyController::class, 'verify'])->name('verify.check');
Route::post('/mark-used', [VerifyController::class, 'markUsed'])->name('verify.mark-used');

Route::prefix('admin')->group(function () {
    Route::get('/codes', [CodeController::class, 'index'])->name('codes.index');
    Route::get('/codes/dashboard', [CodeController::class, 'dashboard'])->name('codes.dashboard');
    Route::get('/codes/anonymous', [CodeController::class, 'anonymous'])->name('codes.anonymous');
    Route::get('/codes/named', [CodeController::class, 'named'])->name('codes.named');
    Route::post('/codes/generate', [CodeController::class, 'generate'])->name('codes.generate');
    Route::post('/codes/generate-named', [CodeController::class, 'generateNamed'])->name('codes.generate-named');
    Route::get('/codes/export', [CodeController::class, 'export'])->name('codes.export');
    Route::get('/codes/export-pdf', [CodeController::class, 'exportPdf'])->name('codes.export-pdf');
    Route::get('/codes/{code}/qr', [CodeController::class, 'showQR'])->name('codes.qr');
});


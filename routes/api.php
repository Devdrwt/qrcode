<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerifyController;

Route::get('/verify/{code}', [VerifyController::class, 'apiVerify'])->name('api.verify');
Route::post('/mark-used/{code}', [VerifyController::class, 'apiMarkUsed'])->name('api.mark-used');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\DashboardController;
Route::get('/', [SystemController::class, 'index'])->name('home');

Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {
    Route::get('/sheet-data', [DashboardController::class, 'showSheetData'])->name('sheet.data');
    Route::get('/check-latest-data', [DashboardController::class, 'checkLatestData'])->name('check.latest.data');
    Route::get('/get-latest-data', [DashboardController::class, 'getLatestData'])->name('get.latest.data');
});

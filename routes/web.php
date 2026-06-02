<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonationExportController;
use App\Http\Controllers\PrintReportController;

Route::get('/', function () {
    return redirect('/admin/donation-dashboard');
});

Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

Route::middleware('auth')->group(function (): void {
    Route::get('/donations/export', DonationExportController::class)->name('donations.export');
    Route::get('/reports/print', PrintReportController::class)->name('reports.print');
});

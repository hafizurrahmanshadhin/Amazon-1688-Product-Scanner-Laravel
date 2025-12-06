<?php

use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\ProductMatchController;
use Illuminate\Support\Facades\Route;

// Route for Admin Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::controller(ProductMatchController::class)
    ->prefix('product/matches')
    ->name('product.matches.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });

<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/test', [CategoryController::class, 'breadcrumbs']);
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('home');

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/{id}/breadcrumbs', [CategoryController::class, 'breadcrumbs'])->name('categories.breadcrumbs');
    });
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\KlantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BehandelingController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::get('/klanten', [KlantController::class, 'index'])->name('klanten.index');
    Route::get('/klanten/create', [KlantController::class, 'create'])->name('klanten.create');
    Route::post('/klanten', [KlantController::class, 'store'])->name('klanten.store');
    Route::get('/klanten/{id}/edit', [KlantController::class, 'edit'])->name('klanten.edit');
    Route::put('/klanten/{id}', [KlantController::class, 'update'])->name('klanten.update');
    Route::delete('/klanten/{id}', [KlantController::class, 'destroy'])->name('klanten.destroy');

    Route::get('/behandelingen', [BehandelingController::class, 'index'])->name('behandelingen.index');
    Route::get('/behandelingen/create', [BehandelingController::class, 'create'])->name('behandelingen.create');
    Route::post('/behandelingen', [BehandelingController::class, 'store'])->name('behandelingen.store');
    Route::get('/behandelingen/{id}/edit', [BehandelingController::class, 'edit'])->name('behandelingen.edit');
    Route::put('/behandelingen/{id}', [BehandelingController::class, 'update'])->name('behandelingen.update');
    Route::delete('/behandelingen/{id}', [BehandelingController::class, 'destroy'])->name('behandelingen.destroy');
});

require __DIR__.'/settings.php';

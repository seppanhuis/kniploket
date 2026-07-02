<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::get('/producten', [ProductController::class, 'index'])->name('producten.index');
    Route::get('/producten/create', [ProductController::class, 'create'])->name('producten.create');
    Route::post('/producten', [ProductController::class, 'store'])->name('producten.store');
    Route::get('/producten/{id}/edit', [ProductController::class, 'edit'])->name('producten.edit');
    Route::put('/producten/{id}', [ProductController::class, 'update'])->name('producten.update');
    Route::delete('/producten/{id}', [ProductController::class, 'destroy'])->name('producten.destroy');
});

require __DIR__.'/settings.php';

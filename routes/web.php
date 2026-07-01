<?php
use App\Http\Controllers\AfspraakController;
use App\Http\Controllers\KlantController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::get('/klanten', [KlantController::class, 'index'])->name('klanten.index');
    Route::get('/klanten/create', [KlantController::class, 'create'])->name('klanten.create');
    Route::post('/klanten', [KlantController::class, 'store'])->name('klanten.store');
    Route::get('/klanten/{id}/edit', [KlantController::class, 'edit'])->name('klanten.edit');
    Route::put('/klanten/{id}', [KlantController::class, 'update'])->name('klanten.update');
    Route::delete('/klanten/{id}', [KlantController::class, 'destroy'])->name('klanten.destroy');

    // Afspraken
    Route::get('/afspraken', [AfspraakController::class, 'index'])->name('afspraken.index');
    Route::get('/afspraken/create', [AfspraakController::class, 'create'])->name('afspraken.create');
    Route::post('/afspraken', [AfspraakController::class, 'store'])->name('afspraken.store');
    Route::get('/afspraken/{id}/edit', [AfspraakController::class, 'edit'])->name('afspraken.edit');
    Route::put('/afspraken/{id}', [AfspraakController::class, 'update'])->name('afspraken.update');
    Route::delete('/afspraken/{id}', [AfspraakController::class, 'destroy'])->name('afspraken.destroy');
});

require __DIR__.'/settings.php';

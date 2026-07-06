<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JenisPelanggaranController;
use App\Http\Controllers\JenisHukumanController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\TingkatKeterlibatanController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



Route::redirect('/', '/login');
    
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
    Route::resource('siswas', SiswaController::class)->except(['create', 'show', 'edit']);
    Route::resource('alternatifs', AlternatifController::class)->except(['create', 'show', 'edit']);
    Route::resource('kriterias', KriteriaController::class)->except(['create', 'show', 'edit']);
    Route::resource('pelanggarans', JenisPelanggaranController::class)->except(['create', 'show', 'edit']);
    Route::resource('hukumans', JenisHukumanController::class)->except(['create', 'show', 'edit']);
    Route::resource('keterlibatans', TingkatKeterlibatanController::class)->except(['create', 'show', 'edit']);
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JenisPelanggaranController;
use App\Http\Controllers\JenisHukumanController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\TingkatKeterlibatanController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SpkController;
use App\Models\Alternatif;
use App\Models\JenisHukuman;
use App\Models\JenisPelanggaran;
use App\Models\Kriteria;
use App\Models\Spk;
use App\Models\Siswa;
use App\Models\TingkatKeterlibatan;
use App\Models\User;
use Illuminate\Support\Facades\Route;



Route::redirect('/', '/login');
    
Route::get('/dashboard', function () {
    return view('dashboard', [
        'dashboardStats' => [
            'users' => User::query()->count(),
            'siswas' => Siswa::query()->count(),
            'alternatifs' => Alternatif::query()->count(),
            'kriterias' => Kriteria::query()->count(),
            'pelanggarans' => JenisPelanggaran::query()->count(),
            'keterlibatans' => TingkatKeterlibatan::query()->count(),
            'hukumans' => JenisHukuman::query()->count(),
            'spks' => Spk::query()->count(),
        ],
        'recentSpks' => Spk::query()
            ->with(['siswa', 'jenisHukuman'])
            ->latest('tanggal_spk')
            ->latest('id')
            ->limit(5)
            ->get(),
        'latestSpk' => Spk::query()->with(['siswa', 'jenisHukuman'])->latest('id')->first(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware('role.access:admin,guru_bk')->group(function () {
        Route::resource('siswas', SiswaController::class)->except(['create', 'show', 'edit']);
        Route::resource('alternatifs', AlternatifController::class)->except(['create', 'show', 'edit']);
        Route::get('/spk', [SpkController::class, 'index'])->name('spk.index');
        Route::post('/spk', [SpkController::class, 'store'])->name('spk.store');
        Route::delete('/spk/{spk}', [SpkController::class, 'destroy'])->name('spk.destroy');
        Route::get('/spk/pdf', [SpkController::class, 'exportPdf'])->name('spk.pdf');
    });

    Route::middleware('role.access:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
        Route::resource('kriterias', KriteriaController::class)->except(['create', 'show', 'edit']);
        Route::resource('pelanggarans', JenisPelanggaranController::class)->except(['create', 'show', 'edit']);
        Route::resource('hukumans', JenisHukumanController::class)->except(['create', 'show', 'edit']);
        Route::resource('keterlibatans', TingkatKeterlibatanController::class)->except(['create', 'show', 'edit']);
    });
});

require __DIR__.'/auth.php';

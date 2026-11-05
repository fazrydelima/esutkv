<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KejohananController;
use App\Http\Controllers\MerentasDesaController;
use App\Http\Controllers\KeputusanController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\SukantaraController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\GuruKeputusanController;
use App\Http\Controllers\GuruAkaunController;
use App\Http\Controllers\ScoreboardController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('esutkv.logout');

Route::get('/scoreboard', [ScoreboardController::class, 'index'])->name('scoreboard');
Route::get('/scoreboard/data', [ScoreboardController::class, 'data'])->name('scoreboard.data');

Route::get('/', [KejohananController::class, 'dashboard'])
    ->middleware(['auth', 'role:admin'])
    ->name('dashboard');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // Peserta routes
    Route::get('/peserta/senarai', [PesertaController::class, 'senarai'])->name('peserta.senarai');
    Route::get('/peserta', [PesertaController::class, 'index'])->name('peserta.index');
    Route::post('/peserta', [PesertaController::class, 'store'])->name('peserta.store');
    Route::delete('/peserta/{id}', [PesertaController::class, 'destroy'])->name('peserta.destroy');

    // Merentas Desa route
    Route::get('/merentas-desa', [MerentasDesaController::class, 'index'])->name('merentasdesa.index');
    Route::redirect('/acara', '/admin/merentas-desa');

    // Keputusan routes (sedia ada)
    Route::get('/keputusan', [KeputusanController::class, 'index'])->name('keputusan.index');
    Route::post('/keputusan', [KeputusanController::class, 'store'])->name('keputusan.store');
    Route::get('/guru/akaun', [GuruAkaunController::class, 'index'])->name('admin.guru-accounts.index');
    Route::put('/guru/akaun/{user}/kata-laluan', [GuruAkaunController::class, 'updatePassword'])->name('admin.guru-accounts.password');
    Route::get('/guru/semakan', [GuruKeputusanController::class, 'index'])->name('admin.guru-keputusan.index');
    Route::put('/guru/semakan/{keputusanAcara}', [GuruKeputusanController::class, 'update'])->name('admin.guru-keputusan.update');

    // === TAMBAHAN: Laluan (Routes) untuk Kejohanan Baru ===
    Route::get('/kejohanan', [KejohananController::class, 'show'])->name('kejohanan.index');
    Route::get('/kejohanan/tambah', [KejohananController::class, 'create'])->name('kejohanan.create');
    Route::post('/kejohanan', [KejohananController::class, 'store'])->name('kejohanan.store');
    
    Route::get('/kejohanan/kemaskini', [KejohananController::class, 'showupdate'])->name('kejohanan.showupdate');
    Route::get('/kejohanan/{id}/edit', [KejohananController::class, 'edit'])->name('kejohanan.edit');
    Route::put('/kejohanan/{id}', [KejohananController::class, 'update'])->name('kejohanan.update');
    Route::delete('/kejohanan/{id}', [KejohananController::class, 'delete'])->name('kejohanan.delete');
    
    Route::get('/kejohanan/mata', [KejohananController::class, 'jumlahMata'])->name('kejohanan.mata');

    Route::get('/sukantara', [SukantaraController::class, 'index'])->name('sukantara.index');
    Route::get('/sukantara/create', [SukantaraController::class, 'create'])->name('sukantara.create');
    Route::post('/sukantara', [SukantaraController::class, 'store'])->name('sukantara.store');
    Route::get('/sukantara/{acaraId}/edit', [SukantaraController::class, 'edit'])->name('sukantara.edit');
    Route::put('/sukantara/{acaraId}', [SukantaraController::class, 'update'])->name('sukantara.update');
    Route::delete('/sukantara/{acaraId}', [SukantaraController::class, 'destroy'])->name('sukantara.destroy');
});

Route::prefix('guru')->middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/', [GuruController::class, 'index'])->name('guru.dashboard');
    Route::post('/keputusan', [GuruController::class, 'store'])->name('guru.keputusan.store');
});
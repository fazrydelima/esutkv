
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScoreboardController;

use App\Http\Controllers\GuruController;
use App\Http\Controllers\PesertaController;

use App\Http\Controllers\KejohananController;
use App\Http\Controllers\SukantaraController;
use App\Http\Controllers\MerentasDesaController;
use App\Http\Controllers\KeputusanController;

use App\Http\Controllers\GuruKeputusanController;
use App\Http\Controllers\GuruAkaunController;


/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.authenticate');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('esutkv.logout');


/*
|--------------------------------------------------------------------------
| PUBLIC SCOREBOARD
|--------------------------------------------------------------------------
*/

Route::get('/scoreboard', [ScoreboardController::class, 'index'])
    ->name('scoreboard');

Route::get('/scoreboard/data', [ScoreboardController::class, 'data'])
    ->name('scoreboard.data');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // Dashboard Admin
        Route::get('/', [KejohananController::class, 'dashboard'])
            ->name('dashboard');


        /*
        |------------------------------------------------------------------
        | KEJOHANAN
        |------------------------------------------------------------------
        */

        Route::get('/kejohanan', [KejohananController::class, 'show'])
            ->name('kejohanan.index');

        Route::get('/kejohanan/tambah', [KejohananController::class, 'create'])
            ->name('kejohanan.create');

        Route::post('/kejohanan', [KejohananController::class, 'store'])
            ->name('kejohanan.store');

        Route::get('/kejohanan/kemaskini', [KejohananController::class, 'showupdate'])
            ->name('kejohanan.showupdate');

        Route::get('/kejohanan/{id}/edit', [KejohananController::class, 'edit'])
            ->name('kejohanan.edit');

        Route::put('/kejohanan/{id}', [KejohananController::class, 'update'])
            ->name('kejohanan.update');

        Route::delete('/kejohanan/{id}', [KejohananController::class, 'delete'])
            ->name('kejohanan.delete');

        Route::get('/kejohanan/mata', [KejohananController::class, 'jumlahMata'])
            ->name('kejohanan.mata');

        Route::delete('/kejohanan/delete-all', [KejohananController::class, 'deleteAll'])
            ->name('kejohanan.deleteAll');


        /*
        |------------------------------------------------------------------
        | SUKANTARA
        |------------------------------------------------------------------
        */

        Route::get('/sukantara', [SukantaraController::class, 'index'])
            ->name('sukantara.index');

        Route::get('/sukantara/create', [SukantaraController::class, 'create'])
            ->name('sukantara.create');

        Route::post('/sukantara', [SukantaraController::class, 'store'])
            ->name('sukantara.store');

        Route::get('/sukantara/{acaraId}/edit', [SukantaraController::class, 'edit'])
            ->name('sukantara.edit');

        Route::put('/sukantara/{acaraId}', [SukantaraController::class, 'update'])
            ->name('sukantara.update');

        Route::delete('/sukantara/{acaraId}', [SukantaraController::class, 'destroy'])
            ->name('sukantara.destroy');

        Route::delete('/sukantara/delete-all', [SukantaraController::class, 'deleteAll'])
            ->name('sukantara.deleteAll');


        /*
        |------------------------------------------------------------------
        | MERENTAS DESA
        |------------------------------------------------------------------
        */

        Route::prefix('merentas-desa')
            ->name('merentas_desa.')
            ->group(function () {

                Route::get('/', [MerentasDesaController::class, 'index'])
                    ->name('index');

                Route::get('/create', [MerentasDesaController::class, 'create'])
                    ->name('create');

                Route::post('/', [MerentasDesaController::class, 'store'])
                    ->name('store');

                Route::get('/show', [MerentasDesaController::class, 'show'])
                    ->name('show');

                Route::get('/edit/{id}', [MerentasDesaController::class, 'edit'])
                    ->name('edit');

                Route::put('/update/{id}', [MerentasDesaController::class, 'update'])
                    ->name('update');

                Route::delete('/delete/{id}', [MerentasDesaController::class, 'destroy'])
                    ->name('destroy');

                Route::delete('/delete-all', [MerentasDesaController::class, 'deleteAll'])
                    ->name('deleteAll');
            });


        /*
        |------------------------------------------------------------------
        | KEPUTUSAN
        |------------------------------------------------------------------
        */

        Route::get('/keputusan', [KeputusanController::class, 'index'])
            ->name('keputusan.index');

        Route::post('/keputusan', [KeputusanController::class, 'store'])
            ->name('keputusan.store');


        /*
        |------------------------------------------------------------------
        | PENGURUSAN AKAUN GURU
        |------------------------------------------------------------------
        */

        Route::get('/guru/akaun', [GuruAkaunController::class, 'index'])
            ->name('guru-accounts.index');

        Route::put('/guru/akaun/{user}/kata-laluan', [GuruAkaunController::class, 'updatePassword'])
            ->name('guru-accounts.password');
        
        Route::get('/guru-accounts', [GuruAkaunController::class, 'index'])
        ->name('guru-accounts.index');

        Route::post('/guru-accounts', [GuruAkaunController::class, 'store'])
            ->name('guru-accounts.store');

        Route::put('/guru-accounts/{guru}/password', [GuruAkaunController::class, 'updatePassword'])
            ->name('guru-accounts.password');



    });


/*
|--------------------------------------------------------------------------
| GURU ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('guru')
    ->middleware(['auth', 'role:guru'])
    ->group(function () {

        // Dashboard Guru
        Route::get('/', [GuruController::class, 'index'])
            ->name('guru.dashboard');


        /*
        |------------------------------------------------------------------
        | PENGURUSAN PESERTA
        |------------------------------------------------------------------
        */

        Route::get('/peserta', [PesertaController::class, 'index'])
            ->name('peserta.index');

        Route::get('/peserta/senarai', [PesertaController::class, 'senarai'])
            ->name('peserta.senarai');

        Route::post('/peserta', [PesertaController::class, 'store'])
            ->name('peserta.store');

        Route::get('/peserta/{id}/edit', [PesertaController::class, 'edit'])
            ->name('peserta.edit');

        Route::put('/peserta/{id}', [PesertaController::class, 'update'])
            ->name('peserta.update');

        Route::delete('/peserta/{id}', [PesertaController::class, 'destroy'])
            ->name('peserta.destroy');

    });

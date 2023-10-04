<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\SOPController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('login', fn () => Inertia::render('Auth/Login'))->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/', [Controller::class, 'dashboard']);
    Route::get('dashboard', [Controller::class, 'dashboard'])->name('dashboard');

    Route::get('pelanggaran', [PelanggaranController::class, 'index'])->middleware(['can:viewAny,App\Models\Pelanggaran']);
    Route::post('pelanggaran-create', [PelanggaranController::class, 'create'])->middleware(['can:create,App\Models\Pelanggaran']);
    Route::post('pelanggaran-update', [PelanggaranController::class, 'update'])->middleware(['can:create,App\Models\Pelanggaran']);
    Route::delete('pelanggaran-destroy', [PelanggaranController::class, 'destroy'])->middleware(['can:create,App\Models\Pelanggaran']);

    Route::get('sop', [SOPController::class, 'index'])->middleware(['can:viewAny,App\Models\SOP']);
    Route::post('sop-create', [SOPController::class, 'create'])->middleware(['can:create,App\Models\SOP']);
    Route::post('sop-update', [SOPController::class, 'update'])->middleware(['can:create,App\Models\SOP']);
    Route::delete('sop-destroy', [SOPController::class, 'destroy'])->middleware(['can:create,App\Models\SOP']);

    Route::get('siswa', [SiswaController::class, 'index'])->middleware(['can:viewAny,App\Models\Siswa']);
    Route::post('siswa-create', [SiswaController::class, 'create'])->middleware(['can:create,App\Models\Siswa']);
    Route::post('siswa-update', [SiswaController::class, 'update'])->middleware(['can:create,App\Models\Siswa']);
    Route::delete('siswa-destroy', [SiswaController::class, 'destroy'])->middleware(['can:create,App\Models\Siswa']);

    Route::get('guru', [GuruController::class, 'index'])->middleware(['can:viewAny,App\Models\Guru']);
    Route::post('guru-create', [GuruController::class, 'create'])->middleware(['can:create,App\Models\Guru']);
    Route::post('guru-update', [GuruController::class, 'update'])->middleware(['can:create,App\Models\Guru']);
    Route::delete('guru-destroy', [GuruController::class, 'destroy'])->middleware(['can:create,App\Models\Guru']);

    Route::get('matapelajaran', [MataPelajaranController::class, 'index'])->middleware(['can:viewAny,App\Models\MataPelajaran']);
    Route::post('matapelajaran-create', [MataPelajaranController::class, 'create'])->middleware(['can:create,App\Models\MataPelajaran']);
    Route::post('matapelajaran-update', [MataPelajaranController::class, 'update'])->middleware(['can:create,App\Models\MataPelajaran']);
    Route::delete('matapelajaran-destroy', [MataPelajaranController::class, 'destroy'])->middleware(['can:create,App\Models\MataPelajaran']);

    Route::middleware(['can:viewAny,App\Models\User'])->group(function () {
        Route::get('user', [UserController::class, 'index']);
        Route::post('user-create', [UserController::class, 'create']);
        Route::post('user-update', [UserController::class, 'update']);
        Route::delete('user-destroy', [UserController::class, 'destroy']);
    });    

    Route::get('profile', [ProfileController::class, 'index']);
    Route::post('profile-update', [ProfileController::class, 'update']);
});



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

    Route::get('pelanggaran', [PelanggaranController::class, 'index']);
    Route::post('pelanggaran-create', [PelanggaranController::class, 'create']);
    Route::post('pelanggaran-update', [PelanggaranController::class, 'update']);
    Route::delete('pelanggaran-destroy', [PelanggaranController::class, 'destroy']);

    Route::get('sop', [SOPController::class, 'index']);
    Route::post('sop-create', [SOPController::class, 'create']);
    Route::post('sop-update', [SOPController::class, 'update']);
    Route::delete('sop-destroy', [SOPController::class, 'destroy']);

    Route::get('siswa', [SiswaController::class, 'index']);
    Route::post('siswa-create', [SiswaController::class, 'create']);
    Route::post('siswa-update', [SiswaController::class, 'update']);
    Route::delete('siswa-destroy', [SiswaController::class, 'destroy']);

    Route::get('guru', [GuruController::class, 'index']);
    Route::post('guru-create', [GuruController::class, 'create']);
    Route::post('guru-update', [GuruController::class, 'update']);
    Route::delete('guru-destroy', [GuruController::class, 'destroy']);

    Route::get('matapelajaran', [MataPelajaranController::class, 'index']);
    Route::post('matapelajaran-create', [MataPelajaranController::class, 'create']);
    Route::post('matapelajaran-update', [MataPelajaranController::class, 'update']);
    Route::delete('matapelajaran-destroy', [MataPelajaranController::class, 'destroy']);

    Route::get('user', [UserController::class, 'index']);
    Route::post('user-create', [UserController::class, 'create']);
    Route::post('user-update', [UserController::class, 'update']);
    Route::delete('user-destroy', [UserController::class, 'destroy']);

    Route::get('profile', [ProfileController::class, 'index']);
    Route::post('profile-update', [ProfileController::class, 'update']);
});



<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
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
    Route::get('/', fn () => Inertia::render('Dashboard'));
    Route::get('home', fn () => Inertia::render('Dashboard'))->name('home');;

    Route::get('user', [UserController::class, 'index']);
    Route::post('user-create', [UserController::class, 'create']);
    Route::post('user-update', [UserController::class, 'update']);
    Route::delete('user-destroy', [UserController::class, 'destroy']);
});



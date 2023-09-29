<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;

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

Route::get('/', fn () => Inertia::render('Dashboard'));
Route::get('home', fn () => Inertia::render('Dashboard'));


Route::get('user', [UserController::class, 'index']);
Route::post('user-create', [UserController::class, 'create']);
Route::post('user-update', [UserController::class, 'update']);
Route::delete('user-destroy', [UserController::class, 'destroy']);

<?php

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [UsuarioController::class, 'login']);
Route::post('/register', [UsuarioController::class, 'register']);
Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->get('/index', [UsuarioController::class, 'index'])->name('index');
Route::middleware(['auth'])->get('/statistics', [UsuarioController::class, 'statistics'])->name('statistics');

Route::get('/usuario', [UsuarioController::class, 'getUsuario']);

Route::get('/products', [ProductController::class, 'getProducts']);
Route::get('/product/{productId}', [ProductController::class, 'getProduct']);
Route::get('/statistic', [ProductController::class, 'getStatistics']);
Route::post('/save', [ProductController::class, 'save']);
Route::post('/search', [ProductController::class, 'search']);
Route::put('/update/{productId}', [ProductController::class, 'update']);
Route::delete('/delete/{productId}', [ProductController::class, 'delete']);

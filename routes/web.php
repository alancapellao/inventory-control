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

Route::get('/index', function () {
    return view('index');
})->name('index');

Route::post('/login', [UsuarioController::class, 'login']);
Route::post('/register', [UsuarioController::class, 'register']);
Route::post('/logout', [UsuarioController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->get('/dashboard', [UsuarioController::class, 'dashboard'])->name('dashboard');

Route::post('/save', [ProductController::class, 'save']);
Route::get('/usuario', [ProductController::class, 'usuario']);
Route::get('/products', [ProductController::class, 'products']);
Route::get('/product/{productId}', [ProductController::class, 'product']);
Route::put('/update/{productId}', [ProductController::class, 'update']);
Route::delete('/delete/{productId}', [ProductController::class, 'delete']);
Route::post('/search', [ProductController::class, 'search']);

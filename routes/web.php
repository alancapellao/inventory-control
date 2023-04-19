<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

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
});

Route::get('/usuario', [UserController::class, 'getUsuario']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::middleware(['auth'])->get('/index', [UserController::class, 'index'])->name('index');
Route::middleware(['auth'])->get('/statistics', [UserController::class, 'statistics'])->name('statistics');

Route::get('/products', [ProductController::class, 'getProducts']);
Route::get('/product/{productId}', [ProductController::class, 'getProduct']);
Route::get('/statistic', [ProductController::class, 'getStatistics']);
Route::post('/save', [ProductController::class, 'save']);
Route::post('/search', [ProductController::class, 'search']);
Route::put('/update/{productId}', [ProductController::class, 'update']);
Route::delete('/delete/{productId}', [ProductController::class, 'delete']);

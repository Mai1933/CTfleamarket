<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/register', [ProductController::class, 'register']);

Route::get('/login', [ProductController::class, 'login']);

Route::post('/login', [ProductController::class, 'loginStore']);

Route::get('/', [ProductController::class, 'list']);

Route::get('/item/{item_id}', [ProductController::class, 'detail']);

Route::get('/purchase/{item_id}', [ProductController::class, 'buy']);

Route::get('/purchase/address', [ProductController::class, 'address']);

Route::get('/sell', [ProductController::class, 'sell']);

Route::get('/mypage', [ProductController::class, 'profile']);

Route::get('/mypage/profile', [ProductController::class, 'edit']);




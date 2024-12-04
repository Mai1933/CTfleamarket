<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [ProductController::class, 'register']);

Route::get('/login', [ProductController::class, 'login']);

Route::get('/', [ProductController::class, 'list']);

Route::get('/item', [ProductController::class, 'detail']);

Route::get('/purchase', [ProductController::class, 'buy']);

Route::get('/purchase/address', [ProductController::class, 'address']);

Route::get('/sell', [ProductController::class, 'sell']);

Route::get('/mypage', [ProductController::class, 'profile']);

Route::get('/mypage/profile', [ProductController::class, 'edit']);
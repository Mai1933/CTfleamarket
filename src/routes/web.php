<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FavoriteController;
use App\Http\Requests\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Fortify\RoutePath;
use App\Http\Controllers\CheckoutController;

Route::get('/register', [ProductController::class, 'register']);

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/login');
})->middleware(['signed'])->name('verification.verify');
if (Features::enabled(Features::registration())) {
    Route::post(RoutePath::for('register', '/register'), [ProductController::class, 'registerStore'])
        ->middleware(['guest:' . config('fortify.guard')])
        ->name('register.store');
}

Route::get('/login', [ProductController::class, 'login'])->name('login');

Route::post('/login', [ProductController::class, 'loginStore']);

Route::get('/', [ProductController::class, 'list']);

Route::post('/', [ProductController::class, 'search']);

Route::get('/item/{item_id}', [ProductController::class, 'detail']);

Route::post('/comment', [CommentController::class, 'store']);

Route::get('/item/like/{item_id}', [FavoriteController::class, 'like'])->where('item_id', '[0-9]+')->middleware('auth');

Route::get('/item/unlike/{item_id}', [FavoriteController::class, 'unlike'])->where('item_id', '[0-9]+');

Route::get('/purchase/{item_id}', [ProductController::class, 'buy'])->where('item_id', '[0-9]+');

Route::get('/purchase/address/{item_id}', [ProductController::class, 'address'])->where('item_id', '[0-9]+');

Route::post('/purchase/address/{item_id}', [ProductController::class, 'addressStore'])->where('item_id', '[0-9]+')->name('addressStore');

Route::post('/purchase', [ProductController::class, 'purchaseStore']);

Route::get('/sell', [ProductController::class, 'sell']);

Route::post('/sell', [ProductController::class, 'sellItem'])->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')]);

Route::get('/mypage', [ProductController::class, 'profile']);

Route::get('/mypage/profile', [ProductController::class, 'edit']);

Route::put('/mypage/profile', [ProductController::class, 'profileUpdate'])->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
    ->name('user-profile-information.update');

Route::get('/checkout', [CheckoutController::class, 'index']);

Route::post('/checkout', [CheckoutController::class, 'checkout']);

Route::post('/create-checkout-session', [CheckoutController::class, 'createCheckoutSession']);

Route::get('/chat/{item_id}', [ProductController::class, 'chat'])->where('item_id', '[0-9]+');

Route::post('/chat/{item_id}', [MessageController::class, 'messageStore'])->where('item_id', '[0-9]+')->name('chat');

Route::get('/chat/edit/{item_id}', [MessageController::class, 'chatEditView'])->where('item_id', '[0-9]+')->name('chat.edit');

Route::post('/chat/edit/{item_id}', [MessageController::class, 'chatEdit'])->where('item_id', '[0-9]+');

Route::post('/chat/delete/{item_id}', [MessageController::class, 'chatDelete'])->where('item_id', '[0-9]+');

Route::post('/evaluate/{item_id}', [MessageController::class, 'evaluationStore'])->where('item_id', '[0-9]+');
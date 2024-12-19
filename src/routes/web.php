<?php

use App\Http\Controllers\ProductController;
use App\Http\Requests\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Fortify\RoutePath;


Route::get('/register', [ProductController::class, 'register']);

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/login');
})->middleware(['signed'])->name('verification.verify');


// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();

//     return redirect('/login');
// })->middleware(['auth', 'signed'])->name('verification.verify');

if (Features::enabled(Features::registration())) {
    // if ($enableViews) {
    //     Route::get(RoutePath::for('register', '/register'), [RegisteredUserController::class, 'create'])
    //         ->middleware(['guest:' . config('fortify.guard')])
    //         ->name('register');
    // }

    Route::post(RoutePath::for('register', '/register'), [ProductController::class, 'registerStore'])
        ->middleware(['guest:' . config('fortify.guard')])
        ->name('register.store');
}

Route::get('/login', [ProductController::class, 'login'])->name('login');

Route::post('/login', [ProductController::class, 'loginStore']);

Route::get('/', [ProductController::class, 'list']);

Route::get('/item/{item_id}', [ProductController::class, 'detail']);

Route::get('/purchase/{item_id}', [ProductController::class, 'buy']);

Route::get('/purchase/address', [ProductController::class, 'address']);

Route::get('/sell', [ProductController::class, 'sell']);

Route::put('/sell', [ProductController::class, 'sellItem'])->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')]);

Route::get('/mypage', [ProductController::class, 'profile']);

Route::get('/mypage/profile', [ProductController::class, 'edit']);

Route::put('/mypage/profile', [ProductController::class, 'profileUpdate'])->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
    ->name('user-profile-information.update');




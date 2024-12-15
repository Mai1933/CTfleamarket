<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Routing\Controller;
use Laravel\Fortify\Contracts\LoginResponse;
use Illuminate\Routing\Pipeline;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\CanonicalizeUsername;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;




class ProductController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginStore(LoginRequest $request)
    {
        return $this->loginPipeline($request)->then(function ($request) {
            return app(LoginResponse::class);
        });
    }

    protected function loginPipeline(LoginRequest $request)
    {
        if (Fortify::$authenticateThroughCallback) {
            return (new Pipeline(app()))->send($request)->through(array_filter(
                call_user_func(Fortify::$authenticateThroughCallback, $request)
            ));
        }

        if (is_array(config('fortify.pipelines.login'))) {
            return (new Pipeline(app()))->send($request)->through(array_filter(
                config('fortify.pipelines.login')
            ));
        }

        return (new Pipeline(app()))->send($request)->through(array_filter([
            config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
            config('fortify.lowercase_usernames') ? CanonicalizeUsername::class : null,
            Features::enabled(Features::twoFactorAuthentication()) ? RedirectIfTwoFactorAuthenticatable::class : null,
            AttemptToAuthenticate::class,
            PrepareAuthenticatedSession::class,
        ]));
    }
    public function list()
    {
        $items = Item::all();
        return view('list', compact('items'));
    }

    public function detail($item_id)
    {
        $item = Item::find($item_id);
        $categories = Category::all();
        return view('detail', compact('item', 'categories'));
    }

    public function buy($item_id)
    {
        $item = Item::find($item_id);
        return view('buy', compact('item'));
    }

    public function address()
    {
        return view('address');
    }

    public function sell()
    {
        return view('sell');
    }

    public function profile()
    {
        return view('profile');
    }

    public function edit()
    {
        return view('edit');
    }

    protected $createNewUser;

    // public function __construct(CreateNewUser $createNewUser)
    // {
    //     $this->createNewUser = $createNewUser;
    // }

    // public function store(RegisterRequest $request) {
    //     $user = $this->createNewUser->create($request->validated());

    //     return response()->json($user); }
}

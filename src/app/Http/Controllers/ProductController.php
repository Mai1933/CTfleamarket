<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckEmailVerified;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Http\Responses\RegisterResponse;
use App\Http\Requests\RegisterRequest;
use Illuminate\Contracts\Auth\StatefulGuard;



class ProductController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    protected $guard;

    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    public function registerStore(RegisterRequest $request, CreatesNewUsers $creator): RegisterResponse
    {
        if (config('fortify.lowercase_usernames')) {
            $request->merge([
                Fortify::username() => Str::lower($request->{Fortify::username()}),
            ]);
        }

        event(new Registered($user = $creator->create($request->all())));

        $this->guard->login($user, $request->boolean('remember'));

        return new RegisterResponse();
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginStore(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->route('login')->withErrors(['login' => 'ログイン情報が登録されていません']);
        }

        if ($user && !$user->hasVerifiedEmail()) {
            return redirect()->route('login')->withErrors(['login' => 'メール認証が必要です。メールを確認してください。']);
        }

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
            CheckEmailVerified::class,
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
        $user = Auth::user();
        return view('edit', compact('user'));
    }

}

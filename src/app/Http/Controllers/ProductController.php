<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Message;
use App\Models\Message_Viewed_At;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
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
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Http\Responses\RegisterResponse;
use App\Http\Requests\RegisterRequest;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;


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

        return $this->loginPipeline($request)->then(function ($request) use ($user) {
            if ($user->first_login) {
                $user->update(['first_login' => false]);
                return redirect('/mypage/profile');
            }
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
        $user = Auth::user();
        if (!$user) {
            $favorites = collect();
            return view('list', compact('items', 'favorites'));
        } else {
            // if (empty($user->postcode) || empty($user->address) || empty($user->building)) {
            //     return redirect('/mypage/profile');
            // } else {
            //出品したアイテムを除外
            $filteredItems = $items->filter(function ($item) use ($user) {
                return $item->user_id != $user->id;
            });
            $items = $filteredItems;

            //マイリスト処理
            $favoritesData = $user->favorites;
            $favoriteItemIds = $favoritesData->pluck('item_id');
            $favoriteItems = Item::whereIn('id', $favoriteItemIds)->get() ?? collect();
            $filteredFavoriteItems = $favoriteItems->filter(function ($favoriteItems) use ($user) {
                return $favoriteItems->user_id != $user->id;
            });
            $favorites = $filteredFavoriteItems;
            // }
        }
        return view('list', compact('items', 'favorites'));
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $keyword = $request->input('keyword');
        $items = Item::where('item_name', 'like', '%' . $keyword . '%')->get();

        if (!$user) {
            $favorites = collect();
            return view('list', compact('items', 'favorites'));
        } else {
            //出品したアイテムを除外
            $filterdItems = $items->filter(function ($item) use ($user) {
                return $item->user_id != $user->id;
            });

            //マイリスト処理
            $favoritesData = $user->favorites;
            $favoriteItemIds = $favoritesData->pluck('item_id');
            $favoriteItems = $filterdItems->filter(function ($item) use ($favoriteItemIds) {
                return $favoriteItemIds->contains($item->id);
            });

            $items = $filterdItems->all();
            $favorites = $favoriteItems;
        }
        return view('list', compact('items', 'favorites'));
    }

    public function detail($item_id)
    {
        $item = Item::find($item_id);
        $categories = $item->categories;

        $commentData = Comment::where('item_id', $item_id)->get();
        $comments = collect();
        $commentNumber = count($commentData);

        foreach ($commentData as $comment) {
            $commentUser = User::find($comment->user_id);

            if ($commentUser) {
                $comments[] = [
                    'user_name' => $commentUser->name,
                    'user_image' => $commentUser->user_image,
                    'content' => $comment->comment_content,
                ];
            }
        }
        return view('detail', compact('item', 'categories', 'commentNumber', 'comments'));
    }


    public function buy($item_id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors(['login' => 'ユーザーが認証されていません。']);
        }
        $item = Item::find($item_id);
        return view('buy', compact('user', 'item'));
    }

    public function address($item_id)
    {
        $user = Auth::user();
        $item = Item::find($item_id);
        return view('address', compact('user', 'item'));
    }

    public function addressStore($item_id, Request $request)
    {
        $user = Auth::user();
        $item_id = $item_id;
        if (!$user) {
            return redirect()->route('login')->withErrors(['login' => 'ユーザーが認証されていません。']);
        }

        $user->postcode = $request->postcode;
        $user->address = $request->address;
        $user->building = $request->building;

        $user->save();
        return redirect('/purchase/' . $item_id);
    }

    public function purchaseStore(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors(['login' => 'ユーザーが認証されていません。']);
        }
        $purchase = new Purchase();
        $purchase->user_id = $user->id;
        $purchase->item_id = $request->item_id;
        $purchase->payment = $request->payment;
        $purchase->postcode = $user->postcode;
        $purchase->address = $user->address;
        $purchase->building = $user->building;
        $purchase->save();

        $item = Item::find($request->item_id);
        $item->status = 'sold';
        $item->update();

        $url = $item->url;
        return redirect()->to($url);
    }

    public function sell()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors(['login' => 'ユーザーが認証されていません。']);
        }
        $categories = Category::all();
        return view('sell', compact('categories'));
    }

    public function sellItem(ExhibitionRequest $request)
    {
        $user = Auth::user();
        $item = new Item();

        $image = $request->file('item_image');
        $imageName = $image->getClientOriginalName();
        $image->storeAs('item_image', $imageName, 'public');

        $item->item_image = $imageName;
        $item->item_name = $request->item_name;
        $item->brand = $request->brand;
        $item->color = $request->color;
        $item->description = $request->description;
        $item->condition = $request->condition;
        $item->price = $request->price;
        $item->status = 'stock';
        $item->user_id = $user->id;

        $item->save();
        $item->categories()->sync($request->category);

        return redirect('/');

    }


    public function profile()
    {
        //出品商品、購入商品及び取引中の商品の取得
        //取引メッセージがついている商品を取引中の商品とみなす
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        } else {
            $sellItems = Item::where('user_id', $user->id)->get() ?? collect();
            $buyItemsData = Purchase::where('user_id', $user->id)->get();
            if ($buyItemsData->isEmpty()) {
                $buyItems = collect();
            } else {
                $itemIds = $buyItemsData->pluck('item_id');
                $buyItems = Item::whereIn('id', $itemIds)->get();
            }
            $soldTransactionItems = $sellItems->filter(function ($item) {
                return $item->messages()->exists();
            });
            $myMessages = Message::where('user_id', $user->id)->get();
            $messagedItemsId = $myMessages->pluck('item_id');
            $messagedTransactionItems = Item::whereIn('id', $messagedItemsId)->where('user_id', '!=', $user->id)->get();
            $transactionItems = $soldTransactionItems->concat($messagedTransactionItems)->unique('id')->values();

            $transactionItems->each(function ($item) use ($user) {
                $viewedAtRecord = Message_Viewed_At::where('item_id', $item->id)->where('user_id', $user->id)->first();
                if ($viewedAtRecord) {
                    $viewedAt = $viewedAtRecord->created_at;
                    $count = $item->messages()->where('user_id', '!=', $user->id)->where('created_at', '>', $viewedAt)->count();
                } else {
                    $count = $item->messages()->where('user_id', '!=', $user->id)->count();
                }

                $item->messagesCount = $count;
            });
            $transactionItems = $transactionItems->sortByDesc(function ($item) {
                $lateMessage = $item->messages()->orderBy('created_at', 'desc')->first();
                return $lateMessage ? $lateMessage->created_at : null;
            })->values();
            session(['transactionItems' => $transactionItems]);

            $totalNewMessages = $transactionItems->sum('messagesCount');
        }
        return view('profile', compact('user', 'sellItems', 'buyItems', 'transactionItems', 'totalNewMessages'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('edit', compact('user'));
    }

    public function profileUpdate(ProfileRequest $profileRequest, AddressRequest $addressRequest)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors(['login' => 'ユーザーが認証されていません。']);
        }

        if ($profileRequest->hasFile('user_image')) {
            $image = $profileRequest->file('user_image');
            $imageName = $image->getClientOriginalName();
            $image->storeAs('user_image', $imageName, 'public');
            $user->user_image = $imageName;
        }

        $user->name = $addressRequest->name;
        $user->postcode = $addressRequest->postcode;
        $user->address = $addressRequest->address;
        $user->building = $addressRequest->building;

        $user->save();

        return redirect('/mypage');
    }

    public function chat($item_id)
    {
        $item = Item::find($item_id);
        $user = Auth::user();
        $seller = User::find($item->user_id);
        if (!$user) {
            return redirect('/login');
        }

        $transactionItems = session('transactionItems', collect());
        $otherTransactionItems = $transactionItems->where('id', '!=', $item_id);
        if (!$otherTransactionItems) {
            $otherTransactionItems = collect();
        }

        $checkedTime = Message_Viewed_At::where('item_id', $item_id)->where('user_id', $user->id)->first();
        if (!$checkedTime) {
            $checkedTime = new Message_Viewed_At();
            $checkedTime->user_id = $user->id;
            $checkedTime->item_id = $item_id;
        }
        $checkedTime->created_at = now();
        $checkedTime->save();

        $messages = Message::where('item_id', $item_id)->orderBy('created_at', 'asc')->get();
        $chattingId = $messages->pluck('user_id');
        $partner = User::whereIn('id', $chattingId)->where('id', '!=', $user->id)->first();

        if ($item->status === 'stock') {
            $isStock = 'true';
        } else {
            $isStock = null;
        }

        return view('chat', compact('item', 'user', 'seller', 'partner', 'otherTransactionItems', 'messages', 'isStock'));
    }

}

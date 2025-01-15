<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use App\Models\Favorite;



class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->only(['like', 'unlike']);
    }

    public function like($item_id)
    {
        Favorite::create([
            'item_id' => $item_id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back();
    }

    public function unlike($item_id)
    {
        $like = Favorite::where('item_id', $item_id)->where('user_id', Auth::id())->first();
        $like->delete();

        return redirect()->back();
    }
}



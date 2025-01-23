<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use App\Models\Favorite;
use App\Models\Item;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category_Item;
use App\Models\Category;



class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->only(['like', 'unlike']);
    }

    public function like($item_id)
    {
        $item = Item::find($item_id);
        $user = Auth::user();
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
        // Favorite::create([
        //     'item_id' => $item_id,
        //     'user_id' => Auth::id(),
        // ]);

        $favorite = new Favorite();
        $favorite->item_id = $item->id;
        $favorite->user_id = $user->id;
        $favorite->save();

        // return redirect()->back();
        return view('detail', compact('item', 'categories', 'commentNumber', 'comments'));
    }

    public function unlike($item_id)
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
        $like = Favorite::where('item_id', $item_id)->where('user_id', Auth::id())->first();
        $like->delete();

        return view('detail', compact('item', 'categories', 'commentNumber', 'comments'));
    }
}



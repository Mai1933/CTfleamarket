<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        $item = Item::find($request->item_id);
        $categories = $item->categories;
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors(['login' => 'ログインしてください']);
        } else {
            $comment = new Comment();
            $comment->user_id = $user->id;
            $comment->item_id = $request->item_id;
            $comment->comment_content = $request->comment;
            $comment->save();

            $commentData = Comment::where('item_id', $request->item_id)->get();
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
            // return redirect('/')->with('success', 'コメントが投稿されました');
            return view('detail', compact('item', 'categories', 'commentNumber', 'comments'));
        }
    }
}


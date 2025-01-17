<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors(['login' => 'ログインしてください']);
        } else {
            $comment = new Comment();
            $comment->user_id = $user->id;
            $comment->item_id = $request->item_id;
            $comment->comment_content = $request->comment;
            $comment->save();

            return redirect('/')->with('success', 'コメントが投稿されました');
        }
    }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors(['login' => 'ログインしてください']);
        } else {
            $request->validate([
                'comment' => 'required|max:255',
            ], [
                'comment.required' => 'コメントを入力してください。',
                'comment.max' => '255文字以内で入力してください。',
            ]);

            $comment = new Comment();
            $comment->user_id = $user->id;
            $comment->item_id = $request->item_id;
            $comment->comment_content = $request->comment;
            $comment->save();

            return redirect('/')->with('success', 'コメントが投稿されました');
        }
    }
}


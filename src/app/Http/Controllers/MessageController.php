<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Message;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\MessageRequest;
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


class MessageController extends Controller
{
    public function messageStore($item_id, MessageRequest $request)
    {
        $user = Auth::user();

        $message = new Message();
        $message->user_id = $user->id;
        $message->item_id = $item_id;
        $message->message_content = $request->message;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $image->storeAs('item_image', $imageName, 'public');
            $message->image = $imageName;
        }

        $message->save();
        return redirect('/chat/' . $item_id);

    }
}

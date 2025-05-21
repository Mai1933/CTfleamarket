<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;
use App\Models\Message;
use App\Models\Message_Viewed_At;
use App\Models\Evaluation;
use Mail;
use App\Mail\CompleteNotification;


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

    public function chatEditView($item_id)
    {
        $item = Item::find($item_id);
        $user = Auth::user();
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

        return view('chat_edit', compact('item', 'user', 'partner', 'otherTransactionItems', 'messages'));
    }

    public function chatEdit($item_id, Request $request)
    {
        $message = Message::find($request->message_id);
        $message->message_content = $request->new_message;

        $message->save();
        return redirect()->route('chat', ['item_id' => $item_id]);
    }

    public function chatDelete($item_id, Request $request)
    {
        $message = Message::find($request->message_id);
        $message->delete();
        return redirect()->route('chat', ['item_id' => $item_id]);
    }

    public function evaluationStore($item_id, Request $request)
    {
        $user = Auth::user();
        $item = Item::find($item_id);

        $messages = Message::where('item_id', $item_id)->orderBy('created_at', 'asc')->get();
        $chattingId = $messages->pluck('user_id');
        $partner = User::whereIn('id', $chattingId)->where('id', '!=', $user->id)->first();
        if ($partner === null) {
            if ($user->id === $item->user_id) {
                return redirect()->route('chat', ['item_id' => $item_id]);
            } else {
                $partner = User::find($item->user_id);
            }
        }

        $evaluation = new Evaluation;
        $evaluation->user_id = $partner->id;

        $evaluation->stars = $request->rating;
        if ($request->rating === null) {
            $evaluation->stars = '0';
        }

        if ($item->user_id === $partner->id) {
            $evaluation->evaluated_as = 'seller';
        } else {
            $evaluation->evaluated_as = 'buyer';
        }
        $evaluation->save();

        if ($item->user_id === $partner->id) {
            $seller = User::find($item->user_id);
            Mail::to($seller->email)->send(new CompleteNotification($item, $evaluation));
        }

        return redirect('/');
    }
}

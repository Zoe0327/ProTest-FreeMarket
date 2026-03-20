<?php

namespace App\Http\Controllers;

use App\Models\SoldItem;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Requests\MessageStoreRequest;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(MessageStoreRequest $request)
    {
        $soldItem = SoldItem::with('item')->findOrFail($request->sold_item_id);

        if (
            $soldItem->user_id !== Auth::id() &&
            $soldItem->item->user_id !== Auth::id()
        ) {
            abort(403);
        }

        if ($soldItem->status === 'completed') {
            abort(403, '取引完了後はメッセージを送信できません。');
        }

        $messageImgUrl = null;

        if ($request->hasFile('message_img_url')) {
            $file = $request->file('message_img_url');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/message_images', $filename);
            $messageImgUrl = $filename;
        }

        Message::create([
            'sold_item_id' => $soldItem->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'message_img_url' => $messageImgUrl,
            'is_read' => false,
        ]);

        return redirect()->route('transactions.show', $soldItem->id);
    }

    public function update(Request $request, Message $message)
    {
        if ($message->user_id !== Auth::id()) {
            abort(403);
        }

        if ($message->soldItem->status === 'completed') {
            abort(403, '取引完了後はメッセージを編集できません。');
        }

        $request->validate([
            'message' => ['required', 'string', 'max:400'],
        ]);

        $message->update([
            'message' => $request->message,
        ]);

        return redirect()->route('transactions.show', $message->sold_item_id);
    }

    public function destroy(Message $message)
    {
        if ($message->user_id !== Auth::id()) {
            abort(403);
        }

        if ($message->soldItem->status === 'completed') {
            abort(403, '取引完了後はメッセージを削除できません。');
        }

        $message->delete();

        return back();
    }
}
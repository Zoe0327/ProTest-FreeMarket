<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->item_id = $item_id;
        $comment->comment = $request->comment;
        $comment->save();

        // コメント送信後に商品詳細ページにリダイレクト
        return redirect()->route('items.show', ['item_id' => $item_id]);
    }
}

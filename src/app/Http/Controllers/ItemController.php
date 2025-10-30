<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;


class ItemController extends Controller
{ 
    // 商品一覧
    public function index(Request $request)
    {

        $userId = Auth::id();
        // 自分の出品商品を除外して取得
        $recommendedItems = Item::where('user_id', '!=', $userId)->get();

        // マイリストはユーザーが「いいね」した商品だけ取得
        $mylistItems = [];
        if ($userId) {
            $mylistItems = Item::whereHas('likes', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->get();
        }

        return view('index', compact('recommendedItems', 'mylistItems'));
    }

    // 商品詳細
    public function show($item_id)
    {
        $item = Item::with(['categories', 'condition', 'comments.user.profile', 'user', 'likes'])->findOrFail($item_id);

        $liked = false;
        if (Auth::check()) {
            $liked = $item->likes->contains('user_id', Auth::id());
        }

        return view('items.show', compact('item', 'liked'));
    }

    public function toggleLike(Item $item)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $like = $item->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // 既存のいいねを解除
            $like->delete();
            $liked = false;
        } else {
            // 新規のいいね作成
            $item->likes()->create(['user_id'=> $user->id]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $item->likes()->count(),
        ]);
    }
}

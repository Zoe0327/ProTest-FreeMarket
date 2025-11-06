<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;
use App\Models\Comment;


class ItemController extends Controller
{
    // 商品一覧
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $userId = Auth::id();

        // ベースとなるクエリ: 検索キーワードを適用
        $baseQuery = Item::query();
        if ($keyword) {
            // 商品名(name)を部分一致で検索
            $baseQuery->where('name', 'like', '%' . $keyword . '%');
        }

        //1. おすすめ商品リスト

        if ($userId) {
            // ログインユーザー: 自分の出品商品を除外
            $recommendedQuery = (clone $baseQuery)
                ->where('user_id', '!=', $userId);
        } else {
            // ゲストユーザー: すべての商品
            $recommendedQuery = (clone $baseQuery);
        }

        $recommendedItems = $recommendedQuery->withCount('likes')->latest()->get();


        //2. マイリスト

        $mylistItems = collect(); // 初期化
        if ($userId) {
            // ログインユーザーのみマイリストを取得
            $mylistQuery = (clone $baseQuery)
                ->whereHas('likes', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                });

            $mylistItems = $mylistQuery->withCount('likes')->get();
        }

        // 検索の有無に関わらず、すべてのデータをビューに渡す
        return view('index', compact('recommendedItems', 'mylistItems', 'keyword'));
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
            $item->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $item->likes()->count(),
        ]);
    }
}
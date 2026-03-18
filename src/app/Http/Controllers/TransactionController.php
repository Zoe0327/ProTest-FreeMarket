<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoldItem;
use App\Models\Review;

use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function show($soldItemId)
    {
        $soldItem = SoldItem::with([
            'item.user.profile',
            'messages.user.profile',
            'user.profile',
        ])->findOrFail($soldItemId);

        // 関係者以外は閲覧不可
        if (
            $soldItem->user_id !== Auth::id() &&
            $soldItem->item->user_id !== Auth::id()
        ) {
            abort(403);
        }

        // 取引相手を取得
        if (Auth::id() === $soldItem->user_id) {
            $partner = $soldItem->item->user;
        } else {
            $partner = $soldItem->user;
        }

        // 自分以外の未読メッセージを既読化
        $soldItem->messages()
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // サイドバー用：自分が関係する他の取引一覧
        $transactions = SoldItem::with([
            'item.user.profile',
            'user.profile',
        ])
            ->where('id', '!=', $soldItem->id)
            ->where(function ($query) {
                $query->where('user_id', Auth::id())
                    ->orWhereHas('item', function ($q) {
                        $q->where('user_id', Auth::id());
                    });
            })
            ->latest()
            ->get();


            // すでに評価済みか
            $alreadyReviewed = Review::where('sold_item_id', $soldItem->id)
                ->where('reviewer_id', Auth::id())
                ->exists();

            // モーダル表示条件
            $shouldShowModal = !$alreadyReviewed && $soldItem->status === 'completed';

        return view('transactions.show', compact('soldItem', 'partner', 'transactions', 'shouldShowModal'));
    }

    public function complete($soldItemId)
    {
        $soldItem = SoldItem::findOrFail($soldItemId);
        if (Auth::id() !== $soldItem->user_id) {
            abort(403);
        }

        $soldItem->update([
            'status' => 'completed',
        ]);

        return redirect()->route('transactions.show', $soldItem->id);
    }
}

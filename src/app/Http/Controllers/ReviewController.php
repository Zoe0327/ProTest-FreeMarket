<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\SoldItem;



class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'sold_item_id' => ['required', 'exists:sold_items,id'],
            'reviewee_id' => ['required', 'exists:users,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
        ]);

        $soldItem = SoldItem::with('item')->findOrFail($request->sold_item_id);

        if (
            Auth::id() !== $soldItem->user_id &&
            Auth::id() !== $soldItem->item->user_id
        ) {
            abort(403);
        }

        if (Auth::id() == $request->reviewee_id) {
            abort(403);
        }

        $alreadyReviewed = Review::where('sold_item_id', $soldItem->id)
            ->where('reviewer_id', Auth::id())
            ->exists();

        if ($alreadyReviewed) {
            return redirect()
                ->route('transactions.show', $soldItem->id)
                ->with('error', 'この取引は既に評価済みです。');
        }

        Review::create([
            'sold_item_id' => $soldItem->id,
            'reviewer_id' => Auth::id(),
            'reviewed_user_id' => $request->reviewee_id,
            'rating' => $request->rating,
        ]);

        return redirect()->route('items.index');
    }
}

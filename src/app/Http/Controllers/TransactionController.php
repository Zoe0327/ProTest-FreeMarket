<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoldItem;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function show($soldItemId)
    {
        $soldItem = SoldItem::with([
            'item.user',
            'messages.user',
        ])->findOrFail($soldItemId);

        if (
            $soldItem->user_id !== Auth::id() &&
            $soldItem->item->user_id !== Auth::id()
        ) {
            abort(403);
        }

        if (Auth::id() === $soldItem->user_id) {
            $partner = $soldItem->item->user;
        } else {
            $partner = $soldItem->user;
        }

        $soldItem->messages()
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('transactions.show', compact('soldItem', 'partner'));
    }
}

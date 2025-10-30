<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Profile;
use App\Models\SoldItem;

class PurchaseController extends Controller
{
    // 購入画面表示
    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('purchases.create', compact('item'));
    }

    // 購入処理
    public function store(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        if (SoldItem::where('item_id', $item_id)->exists()) {
            return redirect()->route('item.show', ['item_id' => $item_id])
                ->withErrors(['sold' => 'この商品はすでに購入されています。']);
        }


        // sold_items テーブルに保存
        $profile = $user->profile;

        SoldItem::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'sending_post_code' => $profile->post_code,
            'sending_address' => $profile->address,
            'sending_building' => $profile->building,
            'payment_method' => $request->payment_method,
        ]);

        return redirect()->route('items.index')->with('success', '購入が完了しました！');
    }

    //住所変更フォームを表示
    public function editAddress($item_id)
    {
        $user = Auth::user();
        $profile = $user->profile;
        $item = Item::findOrFail($item_id);

        return view('purchases.address', compact('profile', 'item'));
    }

    //住所変更を更新して購入ページに戻る
    public function updateAddress(Request $request, $item_id)
    {
        $request->validate([
            'post_code' => ['required', 'string', 'max:8'],
            'address' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
        ]);

        $user = Auth::user();
        // profile が存在しない場合は新規作成
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        $profile->post_code = $request->post_code;
        $profile->address = $request->address;
        $profile->building = $request->building;
        $profile->save();

        // 更新後に購入ページへ戻す
        return redirect()->route('purchase.create', ['item_id' => $item_id])->with('success', '住所を変更しました。');
    }

    // 購入完了ページ
    public function thanks()
    {
        return view('purchases.thanks');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Profile;
use App\Models\SoldItem;

class PurchaseController extends Controller
{
    // 購入画面表示
    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        // 出品者自身が購入画面にアクセスしようとした場合のチェック
        if ($item->user_id === $user->id) {
            return redirect()->route('items.show', ['item_id' => $item_id])
                ->withErrors(['purchase' => '自分の出品商品は購入できません。']);
        }

        // 配送先住所の取得
        $deliveryAddress = session('purchase_address') ?? [
            'post_code' => $user->profile->post_code,
            'address' => $user->profile->address,
            'building' => $user->profile->building,
        ];

        return view('purchases.create', compact('item', 'deliveryAddress'));
    }

    // 購入処理
    public function store(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        // 出品者自身が購入しようとした場合のチェック
        if ($item->user_id === $user->id) {
            return redirect()->route('items.show', ['item_id' => $item_id])
                ->withErrors(['purchase' => '自分の出品商品は購入できません。']);
        }

        // 売り切れの商品を購入しようとした場合のチェック
        if (SoldItem::where('item_id', $item_id)->exists()) {
            return redirect()->route('items.show', ['item_id' => $item_id])
                ->withErrors(['sold' => 'この商品はすでに購入されています。']);
        }

        // 住所情報の取得
        $address = session('purchase_address') ?? [
            'post_code' => $user->profile->post_code,
            'address' => $user->profile->address,
            'building' => $user->profile->building,
        ];

        // 購入処理
        SoldItem::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'sending_post_code' => $address['post_code'],
            'sending_address' => $address['address'],
            'sending_building' => $address['building'],
            'payment_method' => $request->payment_method,
        ]);

        // 購入後、セッションをクリア
        session()->forget('purchase_address');

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

        session([
            'purchase_address' => [
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building' => $request->building,
            ],
        ]);

        return redirect()->route('purchase.create', ['item_id' => $item_id])
            ->with('success', '配送先住所を変更しました。');
    }

    // 購入完了ページ
    public function thanks()
    {
        return view('purchases.thanks');
    }
}

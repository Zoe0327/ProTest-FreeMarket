<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Profile;
use App\Models\SoldItem;

class PurchaseController extends Controller
{
    /**
     * セッションまたはプロフィールから配送先住所を取得
     */
    private function getDeliveryAddress($user)
    {
        return session('purchase_address') ?? [
            'postcode' => $user->profile->postcode ?? '',
            'address'   => $user->profile->address ?? '',
            'building'  => $user->profile->building ?? '',
        ];
    }

    /**
     * 購入画面表示
     */
    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        // 出品者自身が購入画面にアクセスしようとした場合
        if ($item->user_id === $user->id) {
            return redirect()->route('items.show', ['item_id' => $item_id])
                ->withErrors(['purchase' => '自分の出品商品は購入できません。']);
        }

        // 配送先住所（セッション優先）
        $deliveryAddress = $this->getDeliveryAddress($user);

        return view('purchases.create', compact('item', 'deliveryAddress'));
    }

    /**
     * 購入処理
     */
    public function store(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        // 出品者購入チェック
        if ($item->user_id === $user->id) {
            return redirect()->route('items.show', ['item_id' => $item_id])
                ->withErrors(['purchase' => '自分の出品商品は購入できません。']);
        }

        // 売り切れチェック
        if (SoldItem::where('item_id', $item_id)->exists()) {
            return redirect()->route('items.show', ['item_id' => $item_id])
                ->withErrors(['sold' => 'この商品はすでに購入されています。']);
        }

        // 住所取得
        $address = $this->getDeliveryAddress($user);

        // 購入処理
        SoldItem::create([
            'user_id'           => $user->id,
            'item_id'           => $item->id,
            'sending_postcode' => $address['postcode'],
            'sending_address'   => $address['address'],
            'sending_building'  => $address['building'],
            'payment_method'    => $request->payment_method,
        ]);

        // セッションの配送先をクリア
        session()->forget('purchase_address');

        return redirect()->route('items.index')->with('success', '購入が完了しました！');
    }

    /**
     * 住所変更フォームを表示
     */
    public function editAddress($item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        // 既存住所（プロフィール）をフォームに渡す
        $profile = $user->profile;

        return view('purchases.address', compact('profile', 'item'));
    }

    /**
     * 住所変更を更新して購入ページに戻る
     */
    public function updateAddress(Request $request, $item_id)
    {
        $request->validate([
            'postcode' => ['required', 'string', 'max:8'],
            'address'   => ['required', 'string', 'max:255'],
            'building'  => ['nullable', 'string', 'max:255'],
        ]);

        // セッションに住所を保存
        session([
            'purchase_address' => [
                'postcode' => $request->postcode,
                'address'   => $request->address,
                'building'  => $request->building,
            ],
        ]);

        return redirect()->route('purchases.create', ['item_id' => $item_id])
            ->with('success', '配送先住所を変更しました。');
    }

    /**
     * 購入完了ページ
     */
    public function thanks()
    {
        return view('purchases.thanks');
    }
}

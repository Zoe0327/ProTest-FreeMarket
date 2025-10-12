<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function create()
    {
        // 仮の商品データ（レイアウト確認用）
        $product = (object) [
            'id' => 1,
            'name' => 'テスト商品',
            'price' => 47000,
            'image_url' => 'https://placehold.co/300x300?text=商品画像'
        ];

        return view('purchases.create', compact('product'));
    }
}

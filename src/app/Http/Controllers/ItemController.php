<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Item;

class ItemController extends Controller
{
    // 商品一覧
    public function index(Request $request)
    {
        // ダミーデータ
        $items = [
            ['id' => 1, 'name' => 'サンプル商品1', 'price' => 1000],
            ['id' => 2, 'name' => 'サンプル商品2', 'price' => 2000],
        ];

        if ($request->query('tab') === 'mylist') {
            // 今は同じデータを返す（本来はMyListデータを取得）
            $items = $items;
        }

        return view('index', compact('items'));
    }

    // 商品詳細
    public function show($item_id)
    {
        // いったん仮データを直接定義（DBなしでOK）
        $item = [
            'id' => $item_id,
            'name' => 'テスト商品',
            'price' => 1980,
            'description' => 'こちらはダミー商品の説明です。実際のデータ登録前の表示テストです。',
            'image' => asset('images/sample.png'), // 仮画像パス
        ];

        return view('items.show', compact('item'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellController extends Controller
{
    // 出品フォームを表示
    public function create()
    {
        return view('exhibitions.create');
    }

    // 出品データを保存
    public function store(Request $request)
    {
        // バリデーション・保存処理を書く
        // 例: Item::create($request->all());

        return redirect()->route('items.index');
    }
}

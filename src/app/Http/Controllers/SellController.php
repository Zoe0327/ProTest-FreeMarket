<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;

class SellController extends Controller
{
    // 出品フォーム表示
    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view('exhibitions.create',compact('categories', 'conditions'));
    }

    // 出品データ登録処理
    public function store(ExhibitionRequest $request)
    {
        $user = Auth::user();

        // 画像アップロード処理
        if ($request->hasFile('item_img_url')) {
            $path = $request->file('item_img_url')->store('public/item_images');
            $item_img_url = basename($path);
        } else {
            return back()->withErrors(['item_img_url' => '画像が選択されていません']);
        }
        // DB登録
        $item = Item::create([
            'user_id' => $user->id,
            'condition_id' => $request->condition_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'brand_name' => $request->brand_name,
            'item_img_url' => $item_img_url,
        ]);

        if ($request->filled('category_id')) {
            $categoryIds = explode(',', $request->category_id);
            $item->categories()->attach($categoryIds);
        }
        return redirect()->route('items.index')->with('success', '商品を出品しました！');
    }
}

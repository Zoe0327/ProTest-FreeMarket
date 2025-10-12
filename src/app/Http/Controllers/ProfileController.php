<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if($request->query('page') === 'buy') {
            $items = $user->purchases ?? collect();
            return view('profile.buy', compact('user', 'items'));
        } elseif ($request->query('page') === 'sell') {
            $items = $user->sells ?? collect();
            return view('profile.sell', compact('user', 'items'));
        }

        return view('profile.show', compact('user'));
    }
    // 編集フォーム
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    // プロフィールの更新処理
    public function update(Request $request)
    {
        $user = auth()->user();

        // 1. バリデーション
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'post_code' => 'nullable|string|size:7', // 7桁の数字（ハイフンなし）を想定
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'profile_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 画像ファイルはオプション
        ]);

        // 2. 画像ファイルのアップロード処理
        if ($request->hasFile('profile_img')) {
            // 古い画像があれば削除
            if ($user->profile_img_path) {
                //Storage::delete($user->profile_img_path);
            }
            // 新しい画像を保存し、パスをDBに保存
            $path = $request->file('profile_img')->store('public/profile_images');
            $user->profile_img_path = str_replace('public/', '', $path);
        }

        // 3. ユーザー情報の更新
        $user->name = $validatedData['name'];
        $user->post_code = $validatedData['post_code'];
        $user->address = $validatedData['address'];
        $user->building = $validatedData['building'];

        //$user->save();

        // 更新後、マイページまたは編集画面にリダイレクトして成功メッセージを表示
        return redirect()->route('mypage.edit')->with('success', 'プロフィールが更新されました！');
    }
    //住所変更画面の作成用
    public function editAddress()
    {
        return view('profile.address');
    }
}

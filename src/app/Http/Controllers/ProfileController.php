<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // マイページ表示
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($request->query('page') === 'buy') {
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
        $user = Auth::user();
        $profile = $user->profile;
        return view('profile.edit', compact('user', 'profile'));
    }

    // プロフィールの更新処理
    public function update(Request $request)
    {
        /** @var User $user */ //
        $user = Auth::user();

        // 1. バリデーション
        $validatedData = $request->validate([
            'name' => 'required|string|max:20',
            'post_code' => 'required|string|size:8', // ハイフンあり8文字
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'profile_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Profile が存在しなければ作成（NOT NULL カラムの値を必ず渡す）
        $profile = $user->profile;
        if (!$profile) {
            $profile = $user->profile()->create([
                'post_code' => $validatedData['post_code'],
                'address'   => $validatedData['address'],
                'building'  => $validatedData['building'] ?? '',
            ]);
        }

        // 3. 画像アップロード処理
        if ($request->hasFile('profile_img')) {
            $path = $request->file('profile_img')->store('public/profile_images');
            $profile->profile_img_url = str_replace('public/', '', $path);
        }

        // 4. その他情報を保存
        $profile->post_code = $validatedData['post_code'];
        $profile->address = $validatedData['address'];
        $profile->building = $validatedData['building'];
        $profile->save();

        // User 名は Users テーブルに保存
        $user->name = $validatedData['name'];
        $user->save();

        return redirect()->route('mypage.index')->with('success', 'プロフィールが更新されました！');
    }

    // 住所変更画面
    public function editAddress()
    {
        $user = auth::user();
        $profile = $user->profile;
        return view('profile.address', compact('profile'));
    }

    public function updateAddress(Request $request)
    {
        // バリデーション
        $request->validate([
            'post_code' => ['required', 'regex:/^\d{3}-\d{4}$/'], // 例：123-4567形式
            'address' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
        ]);

        // ログイン中のユーザーを取得
        $user = auth()->user();

        // プロフィールを取得 or 新規作成
        $profile = $user->profile ?? new \App\Models\Profile();
        $profile->user_id = $user->id;
        $profile->post_code = $request->post_code;
        $profile->address = $request->address;
        $profile->building = $request->building;
        $profile->save();

        // 成功メッセージ付きでリダイレクト
        return redirect()->route('purchase.create')->with('success', '住所を更新しました。');
    }

    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;
        $items = $user->items;
        $purchasedItems = $user->soldItems;

        return view('profile.show', compact('user', 'profile', 'items', 'purchasedItems'));
    }
}

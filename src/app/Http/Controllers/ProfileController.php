<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;
use App\Models\Review;
use App\Models\SoldItem;

use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    // マイページ表示
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->profile;

        if ($request->query('page') === 'buy') {
            $items = $user->soldItems ?? collect();
            return view('profile.buy', compact('user', 'profile', 'items'));
        }

        if ($request->query('page') === 'sell') {
            $items = $user->items ?? collect();
            return view('profile.sell', compact('user', 'profile', 'items'));
        }

        $averageRating = Review::where('reviewed_user_id', $user->id)->avg('rating');
        $reviewCount = Review::where('reviewed_user_id', $user->id)->count();

        $authId = Auth::id();

        $inProgressTransactions = SoldItem::with(['item'])
            ->withCount(['messages as unread_count' => function ($query) use ($authId) {
                $query->where('user_id', '!=', $authId)
                    ->where('is_read', false);
            }])
            ->withMax('messages', 'created_at')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('item', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
            })
            ->where(function ($query) use ($user) {
                $query->where('status', 'in_progress')
                    ->orWhere(function ($q) use ($user) {
                        $q->where('status', 'completed')
                        ->whereDoesntHave('reviews', function ($reviewQuery) use ($user) {
                            $reviewQuery->where('reviewer_id', $user->id);
                        });
                    });
            })
            ->orderByDesc('messages_max_created_at')
            ->get();

        $totalUnreadCount = $inProgressTransactions->sum('unread_count');

        return view('profile.show', compact(
            'user',
            'profile',
            'averageRating',
            'reviewCount',
            'inProgressTransactions',
            'totalUnreadCount'
        ));
    }

    // 編集フォーム
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;
        return view('profile.edit', compact('user', 'profile'));
    }

    // プロフィールの更新処理
    public function update(ProfileRequest $request)
    {
        /** @var User $user */ //
        $user = Auth::user();

        // 1. バリデーション
        $validatedData = $request->validated();
        $profileImageFile = $request->file('profile_img_url');

        // 2. Profile が存在しなければ作成（NOT NULL カラムの値を必ず渡す）
        $profile = $user->profile ?? new Profile();
        if (!$profile->exists) {
            $profile->user_id = $user->id;
            $profile->postcode = $validatedData['postcode'] ?? '';
            $profile->address = $validatedData['address'] ?? '';
            $profile->building = $validatedData['building'] ?? '';
        }

        // 3. 画像アップロード処理
        if ($profileImageFile) {
            $path = $profileImageFile->store('public/profile_images');
            $profile->profile_img_url = str_replace('public/', '', $path);
        }

        // 4. その他情報を保存
        $profile->postcode = $validatedData['postcode'];
        $profile->address = $validatedData['address'];
        $profile->building = $validatedData['building'] ?? null;
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
            'postcode' => ['required', 'regex:/^\d{3}-\d{4}$/'], // 例：123-4567形式
            'address' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
        ]);

        // ログイン中のユーザーを取得
        $user = auth()->user();

        // プロフィールを取得 or 新規作成
        $profile = $user->profile ?? new \App\Models\Profile();
        $profile->user_id = $user->id;
        $profile->postcode = $request->postcode;
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

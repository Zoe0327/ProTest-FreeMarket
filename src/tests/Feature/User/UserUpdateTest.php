<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ユーザー情報変更ページに初期値が正しく表示されるか確認
     */
    public function test_profile_edit_page_displays_initial_values()
    {
        // ① ユーザー作成（メール認証済みにする）
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'email_verified_at' => now(),
        ]);

        // ② プロフィール作成（初期値）
        Profile::create([
            'user_id' => $user->id,
            'profile_img_url' => 'test_profile.jpg',
            'post_code' => '1234567',
            'address' => '東京都テスト',
            'building' => 'テストビル',
        ]);

        /** @var \App\Models\User $user */
        // ③ ログイン
        $this->actingAs($user);

        // ④ プロフィール編集ページへアクセス
        $response = $this->get(route('mypage.edit'));

        // ⑤ ステータスコード確認
        $response->assertStatus(200);

        // ⑥ 初期値がページに表示されているか確認
        $response->assertSee('テストユーザー');       // ユーザー名
        $response->assertSee('1234567');             // 郵便番号
        $response->assertSee('東京都テスト');        // 住所
        $response->assertSee('テストビル');          // 建物名
        $response->assertSee('test_profile.jpg');    // プロフィール画像
    }
}

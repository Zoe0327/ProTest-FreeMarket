<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserInfoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 認証済みユーザーがプロフィール編集ページにアクセスできるか確認
     */
    public function test_authenticated_user_can_access_profile_edit_page()
    {
        // ① ユーザー作成（メール認証済みにする）
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'email_verified_at' => now(), // メール認証済み
        ]);

        /** @var \App\Models\User $user */
        // ② ログイン
        $this->actingAs($user);

        // ③ プロフィール編集ページへアクセス
        $response = $this->get(route('mypage.edit'));

        // ④ ステータスコード確認
        $response->assertStatus(200);

        // ⑤ ユーザー名がページ内に表示されているか確認（任意）
        $response->assertSee('テストユーザー');
    }
}

<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_logout()
    {
        // 1. 認証済みユーザーを作る
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        // 2. actingAs() でログイン状態にする
        $response = $this->actingAs($user)
            ->post('/logout'); // ルートに合わせて変更

        // 3. リダイレクト先の確認 (デフォルトは /)
        $response->assertRedirect('/');

        // 4. ログアウトされていることを確認
        $this->assertGuest();
    }

    /** @test */
    public function guest_user_cannot_logout()
    {
        // 未ログインで logout 叩いた時の挙動
        $response = $this->post('/logout');

        // 403 か 302 リダイレクトなど、アプリに合わせて調整
        $response->assertStatus(302);
    }
}

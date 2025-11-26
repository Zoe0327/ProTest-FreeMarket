<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 正しく入力された場合はプロフィール設定画面にリダイレクトされる()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // DBにユーザーが作成されているか確認
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        // プロフィール設定画面へ遷移（メール認証ページ）
        $response->assertRedirect(route('verification.notice'));
    }

    /** @test */
    public function メールアドレスが未入力の場合はバリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function パスワードが未入力の場合はバリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function パスワード確認が一致していない場合はエラーになる()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'wrong',
        ]);

        $response->assertSessionHasErrors(['password_confirmation']);
    }
}

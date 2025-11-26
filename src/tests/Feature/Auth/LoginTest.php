<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function メールアドレス未入力の場合はバリデーションエラーが出る()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function パスワード未入力の場合はバリデーションエラーが出る()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => ''
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function 入力情報が正しくない場合はエラーが表示される()
    {
        // 実在しないユーザー
        $response = $this->post('/login', [
            'email' => 'notfound@example.com',
            'password' => 'wrongpass'
        ]);

        // Laravelの標準エラーは "These credentials do not match our records."
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function 正しい情報の場合はログイン処理が成功する()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $this->assertAuthenticatedAs($user);

        // ログイン後のリダイレクト先が dashboard 等であれば適宜調整
        $response->assertRedirect('/');
    }
}

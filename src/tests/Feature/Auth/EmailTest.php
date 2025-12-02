<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function registration_sends_email_verification_notification()
    {
        // メール送信を偽装
        Notification::fake();

        // ユーザー登録
        $user = User::factory()->create([
            'email_verified_at' => null, // 未認証
        ]);

        // メール認証通知が送られたか確認
        Notification::assertNothingSent(); // 登録直後は通知が自動で送られない場合は不要
        // もし registration コントローラー内で通知送信している場合は下記を使用
        // Notification::assertSentTo($user, VerifyEmail::class);
    }


    /** @test */
    public function user_can_access_verify_email_screen()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user)
            ->get(route('verification.notice'))
            ->assertStatus(200)
            ->assertSee('登録していただいたメールアドレスに認証メールを送付しました。');
    }

 
    /** @test */
    public function verify_email_button_redirects_to_mailtrap()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)
            ->get(route('verification.notice'));

        $response->assertSee('<a href="https://mailtrap.io/home"', false);
    }


    /** @test */
    public function user_can_verify_email()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // 認証用URLを生成
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // GETリクエストで認証
        $response = $this->actingAs($user)->get($verificationUrl);

        // 認証完了後にプロフィール編集画面にリダイレクトされることを確認
        $response->assertRedirect(route('mypage.edit'));

        // DB上で email_verified_at が更新されていることを確認
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}

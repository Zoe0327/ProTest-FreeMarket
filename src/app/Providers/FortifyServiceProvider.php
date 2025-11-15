<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Http\Middleware\ValidateLoginRequest;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Http\Responses\LoginResponse;


class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::loginView(function () {
            return view('auth.login');
        });
        Fortify::authenticateThrough(function (Request $request) {
            return array_filter([
                // ログイン制限（RateLimiter）のミドルウェア
                config('fortify.limiters.login') ? \Illuminate\Routing\Middleware\ThrottleRequests::class : null,

                // ★ここに追加: カスタムバリデーションを実行
                ValidateLoginRequest::class,

                // Fortifyのデフォルト認証アクション
                \Laravel\Fortify\Actions\AttemptToAuthenticate::class,
                \Laravel\Fortify\Actions\PrepareAuthenticatedSession::class,
            ]);
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });


        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(10)->by($email . $request->ip());
        });
        $this->app->singleton(
            \Laravel\Fortify\Contracts\RegisterResponse::class,
            \App\Http\Responses\RegisterResponse::class
        );
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
    }
}

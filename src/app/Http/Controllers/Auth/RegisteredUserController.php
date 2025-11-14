<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\RegisterRequest;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // パスワードはハッシュ化
        ]);

        Auth::login($user);

        // 登録と同時にメール認証通知を送信
        event(new Registered($user));

        // メール認証画面へリダイレクト
        return redirect()->route('verification.notice')->with('success', '会員登録が完了しました。');
    }
}

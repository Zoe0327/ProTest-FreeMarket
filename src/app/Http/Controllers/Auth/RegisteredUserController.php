<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        $user = User::latest()->first();

        Auth::login($user);

        // プロフィール設定画面へ
        return redirect('/profile/setup');
    }
}

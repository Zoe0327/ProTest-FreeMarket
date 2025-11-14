<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        if (!$request->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }
        
        return redirect('/');
    }
}
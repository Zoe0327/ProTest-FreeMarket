<?php

namespace App\Http\Middleware;

use App\Http\Requests\LoginRequest;
use Closure;
use Illuminate\Http\Request;

class ValidateLoginRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        app(LoginRequest::class);

        return $next($request);
    }
}

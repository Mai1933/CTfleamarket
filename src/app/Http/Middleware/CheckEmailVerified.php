<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckEmailVerified
{
/**
* Handle an incoming request.
*
* @param \Illuminate\Http\Request $request
* @param \Closure $next
* @return mixed
*/
public function handle($request, Closure $next)
{

if (!Auth::user()->hasVerifiedEmail()) {

Auth::logout();
return redirect()->route('login')->withErrors(['login' => 'メール認証が必要です。メールを確認してください。']);
}

return $next($request);
}
}
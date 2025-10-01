<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserBlacklist
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->blacklist) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda telah di-blacklist. Hubungi admin untuk informasi lebih lanjut.'
            ]);
        }

        return $next($request);
    }
}
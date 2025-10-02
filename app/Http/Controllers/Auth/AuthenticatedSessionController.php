<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
    
        $user = Auth::user(); // Ganti dari auth()->user() ke Auth::user()
    
        // Redirect otomatis berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        } elseif ($user->role === 'petugas') {
            return redirect('/dashboard-petugas');
        } elseif ($user->role === 'siswa') {
            return redirect()->route('dashboard.siswa');
        } else {
            Auth::logout();
            return redirect('/login')->withErrors(['email' => 'Role tidak dikenali.']);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        // Coba login sebagai Admin
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            return redirect()->intended('/admin/dashboard');
        }

        // Coba login sebagai Staff
        if (Auth::guard('staff')->attempt($credentials, $request->filled('remember'))) {
            return redirect()->intended('/staff/dashboard');
        }

        // Coba login sebagai User biasa
        if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
            return redirect()->intended('/dashboard');
        }

        // Kalau semua gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } elseif (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        } else {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

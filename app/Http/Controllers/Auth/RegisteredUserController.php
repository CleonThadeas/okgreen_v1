<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan form register
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Proses register user baru
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    // buat user baru
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // 🔴 JANGAN auto-login
    // event(new Registered($user)); ❌
    // Auth::login($user); ❌

    // pastikan logout semua guard
    Auth::guard('web')->logout();
    Auth::guard('admin')->logout();
    Auth::guard('staff')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // redirect ke login, BUKAN dashboard
    return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Silakan login.');
}

}

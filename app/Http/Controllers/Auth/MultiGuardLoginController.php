<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MultiGuardLoginController extends Controller
{
    public function create()
    {
        return view('auth.login'); // view login
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $email = $request->email;
        $password = $request->password;
        $remember = $request->filled('remember');

        // 1) coba login sebagai admin
        if (Auth::guard('admin')->attempt(['email' => $email, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        // 2) coba login sebagai staff
        if (Auth::guard('staff')->attempt(['email' => $email, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('staff.dashboard'));
        }

        // 3) coba login sebagai user (web)
        if (Auth::guard('web')->attempt(['email' => $email, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        // Jika semua gagal, lempar error (biasa)
        throw ValidationException::withMessages([
            'email' => ['Credensial tidak cocok dengan data kami.'],
        ]);
    }

    public function destroy(Request $request)
    {
        // logout dari semua guard supaya aman
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();
        Auth::guard('staff')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

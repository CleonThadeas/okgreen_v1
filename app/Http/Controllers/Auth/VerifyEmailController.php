<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    public function __construct()
    {
        // pastikan controller memakai middleware yang benar
        $this->middleware('auth');
        $this->middleware('signed')->only('verify'); // jika route verify memakai signed URLs
        $this->middleware('throttle:6,1')->only('verify', 'resend'); // batasi percobaan
    }

    /**
     * Handle email verification link.
     *
     * Route signature usually: /email/verify/{id}/{hash}
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        // EmailVerificationRequest->fulfill() akan menandai user sebagai verified
        $request->fulfill();

        return redirect()->route('dashboard')->with('status', 'Email berhasil diverifikasi.');
    }

    /**
     * Resend verification email.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Link verifikasi telah dikirim ulang.');
    }
}

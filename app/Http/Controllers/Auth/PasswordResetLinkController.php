<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
public function store(Request $request): RedirectResponse
{
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->post('http://presence.guestallow.com/api/auth/forgot-password', [
        'email' => $request->email,
    ]);

    if ($response->successful()) {
        return back()->with('status', $response->json('message'));
    } elseif ($response->status() == 422) {
        return back()->withInput()->withErrors([
            'email' => 'Email tidak valid atau tidak ditemukan.',
        ]);
    } else {
        return back()->withInput()->withErrors([
            'email' => 'Terjadi kesalahan saat mengirim link reset password.',
        ]);
    }
}

}

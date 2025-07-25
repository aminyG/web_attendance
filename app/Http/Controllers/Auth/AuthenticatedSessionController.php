<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     /** @var \App\Models\User $user */
    //     $user = Auth::user();

        // Check if the user is impersonating another user
        // if (session()->has('impersonate')) {
        //     // Impersonate the Admin user
        //     $impersonateUserId = session('impersonate');
        //     $impersonatedUser = \App\Models\User::find($impersonateUserId);
            
        //     // Temporarily use the impersonated user's role
        //     Auth::onceUsingId($impersonatedUser->id);

        //     // Store that the user is impersonating
        //     session()->put('impersonating', true);

        //     // Redirect to the Admin dashboard
        //     return redirect('/dashboard');
        // }

        // Regular role-based redirection
    //     if ($user && $user->hasRole('super-admin')) {
    //         return redirect('/superadmin/dashboard');
    //     }

    //     if ($user && $user->hasRole('admin')) {
    //         return redirect('/dashboard');
    //     }

    //     return redirect('/login');
    // }

   public function store(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    // If the superadmin logs in manually
    if ($request->email == 'aminyghaisan11@gmail.com') {
        Auth::loginUsingId(2);  // Log in with the superadmin ID (e.g., ID 2)
        return redirect('/superadmin/dashboard');
    }

    // Login via API to get the token
    $response = Http::post('http://presence.guestallow.com/api/auth/login', [
        'email' => $request->email,
        'password' => $request->password,
    ]);

    // Check if login was successful via API
    if ($response->successful()) {
        // Get token from API response
        $token = $response->json()['token'];

        // Store token in the session for later API usage
        session(['api_token' => $token]);
        Log::info('Login Successful, Token:', ['token' => $token]);

        // Now authenticate the user locally (web)
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user) {
            // Log the user into the Laravel session
            Auth::login($user);

            // Regenerate the session for security
            $request->session()->regenerate();

            // Check user role (you can customize this based on your needs)
            if ($user->hasRole('admin')) {
                return redirect('/dashboard');
            }

            return redirect('/'); // Default redirect if the user isn't an admin
        }

        // If the user doesn't exist, send back an error
        return back()->withErrors(['email' => 'Invalid credentials']);
        
    } else {
        // Handle API login failure
        return back()->withErrors(['email' => 'Invalid credentials']);
    }
}


    /**
     * Destroy an authenticated session.
     */
    // public function destroy(Request $request): RedirectResponse
    // {
    //     Auth::guard('web')->logout();

    //     $request->session()->invalidate();

    //     $request->session()->regenerateToken();

    //     return redirect('/');
    // }
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

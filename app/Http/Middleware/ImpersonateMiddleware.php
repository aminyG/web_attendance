<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ImpersonateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Check if the user is impersonating
        if (session()->has('impersonate')) {
            // Get the impersonated user
            $impersonateUserId = session('impersonate');
            
            // Log in as the impersonated user for the current request
            $impersonatedUser = \App\Models\User::find($impersonateUserId);
            
            // Temporarily use the impersonated user's roles
            Auth::onceUsingId($impersonatedUser->id);

            // Optionally set a flag that indicates the user is impersonating
            session()->put('impersonating', true);
        }

        return $next($request);
    }
}

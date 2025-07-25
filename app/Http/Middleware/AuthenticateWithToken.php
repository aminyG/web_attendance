<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthenticateWithToken
{
    public function handle(Request $request, Closure $next)
    {
        // Ambil token dari sesi
        $token = session('api_token');
        
        // Jika ada token di sesi
        if ($token) {
            // Kirim permintaan ke API untuk memverifikasi token dan mendapatkan data pengguna
            $response = Http::withHeaders(['Authorization' => 'Bearer ' . $token])
                            ->get('http://presence.guestallow.com/api/users/all'); // Ganti dengan endpoint yang sesuai

            // Jika respons API berhasil dan data pengguna ada
            if ($response->successful()) {
                $userData = $response->json()['user'];
                
                // Temukan atau buat pengguna di database lokal berdasarkan data API
                $user = \App\Models\User::firstOrCreate([
                    'email' => $userData['email'],
                ]);

                // Login pengguna dengan data yang diterima
                Auth::login($user);
            }
        }

        return $next($request);
    }
}

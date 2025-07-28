<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewAdminMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AdminManagementController extends Controller
{
    public function create()
{
    return view('superadmin.admins.create');
}

public function store(Request $request)
{
    // 1️⃣ Validasi input
    $validated = $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
    ]);

    // 2️⃣ Generate password default '123456'
    $passwordPlain = '123456';  // Default password for admin

    // 3️⃣ Simpan ke database (hash)
    $user = \App\Models\User::create([
        'name'     => $validated['name'],
        'email'    => $validated['email'],
        'password' => Hash::make($passwordPlain),
    ]);

    // 4️⃣ Assign Role sebagai Admin
    $user->assignRole('admin');

    // 5️⃣ Kirim Email dengan Password Default
    Mail::to($user->email)->send(new NewAdminMail($user, $passwordPlain));

    // 6️⃣ Daftarkan Admin di API (Register dengan API yang sama)
    $response = Http::post('http://presence.guestallow.com/api/auth/register', [
        'name' => $user->name,
        'email' => $user->email,
        'password' => $passwordPlain,  // Password default
        'password_confirmation' => $passwordPlain, // Password confirmation
    ]);

    // 7️⃣ Tangani Respons API untuk pendaftaran admin (optional, jika perlu)
    if ($response->successful()) {
        Log::info('Admin Registered in Presence API:', $response->json());
    } else {
        Log::error('Failed to Register Admin in Presence API:', $response->json());
    }

    // 8️⃣ Redirect dengan flash message
    return redirect()->route('superadmin.dashboard')
        ->with('success', "Admin berhasil dibuat.\nEmail: {$validated['email']}\nPassword: {$passwordPlain}");
}

public function destroy(User $user)
{
        $user->delete();
        return redirect()->route('superadmin.dashboard')->with('success', 'Admin berhasil dihapus.');
}
}

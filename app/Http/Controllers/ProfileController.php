<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
    // public function updatePassword(Request $request)
    // {
    //     $request->validate([
    //         'current_password' => 'required',
    //         'password' => 'required|string|min:8|confirmed',
    //     ]);

    //     $user = User::find(Auth::id());

    //     if (!$user) {
    //         return redirect()->route('profile.edit')->with('error', 'User tidak ditemukan.');
    //     }

    //     if (!Hash::check($request->current_password, $user->password)) {
    //         return redirect()->route('profile.edit')->with('error', 'Password lama tidak valid.');
    //     }

    //     $user->password = Hash::make($request->password);
    //     $user->save();

    //     return redirect()->route('profile.edit')->with('success', 'Password berhasil diperbarui.');
    // }
public function updatePassword(Request $request)
{
    // Validasi input
    $request->validate([
        'current_password' => 'required',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::find(Auth::id());

    if (!$user) {
        return redirect()->route('profile.edit')->with('error', 'User tidak ditemukan.');
    }

    $currentPassword = trim($request->current_password);
    $newPassword = $request->password;
    $newPasswordConfirmation = $request->password_confirmation;

    Log::info('Password lama yang dimasukkan (trimmed): ' . $currentPassword);
    Log::info('Password baru yang dimasukkan: ' . $newPassword);
    Log::info('Konfirmasi password baru yang dimasukkan: ' . $newPasswordConfirmation);

    $apiData = [
        'old_password' => $currentPassword,
        'new_password' => $newPassword,
        'new_password_confirmation' => $newPasswordConfirmation,
    ];

    Log::info('Request ke API untuk mengganti password: ' . json_encode($apiData));

    $apiToken = session('api_token');

    if (!$apiToken) {
        return redirect()->route('profile.edit')->with('error', 'Token autentikasi tidak ditemukan.');
    }

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiToken,
        'Accept' => 'application/json',
    ])->post('http://presence.guestallow.com/api/users/change-password', $apiData);

    Log::info('Response dari API: ' . $response->body());

    if ($response->successful()) {
        return redirect()->route('profile.edit')->with('success', 'Password berhasil diperbarui.');
    } elseif ($response->status() == 401) {
        return redirect()->route('profile.edit')->with('error', 'Password lama tidak valid.');
    } else {
        return redirect()->route('profile.edit')->with('error', 'Gagal mengganti password, coba lagi nanti.');
    }
}

}
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
//     // Validasi
//     $request->validate([
//         'current_password' => 'required',
//         'password' => 'required|string|min:8|confirmed', // Password baru dan konfirmasi harus cocok
//     ]);

//     $user = User::find(Auth::id());

//     if (!$user) {
//         return redirect()->route('profile.edit')->with('error', 'User tidak ditemukan.');
//     }

//     // Verifikasi password lama
//     if (!Hash::check($request->current_password, $user->password)) {
//         return redirect()->route('profile.edit')->with('error', 'Password lama tidak valid.');
//     }

//     // Update password
//     $user->password = Hash::make($request->password);  // Pastikan password terenkripsi
//     $user->save();

//     // Debugging: Memastikan password baru sudah diubah
//     dd($user->password); // Uncomment untuk memastikan password baru telah terenkripsi

//     return redirect()->route('profile.edit')->with('success', 'Password berhasil diperbarui.');
// }

public function updatePassword(Request $request)
{
    // Validasi password
    $request->validate([
        'current_password' => 'required',
        'password' => 'required|string|min:8|confirmed', // Password baru dan konfirmasi harus cocok
    ]);

    $user = User::find(Auth::id());

    // Pastikan user ditemukan
    if (!$user) {
        return redirect()->route('profile.edit')->with('error', 'User tidak ditemukan.');
    }

    // Verifikasi password lama
    if (!Hash::check($request->current_password, $user->password)) {
        return redirect()->route('profile.edit')->with('error', 'Password lama tidak valid.');
    }

    // Update password baru
    $user->password = Hash::make($request->password);  // Pastikan password terenkripsi
    $user->save();

    return redirect()->route('profile.edit')->with('success', 'Password berhasil diperbarui.');
}

// public function destroy(Request $request)
// {
//     $user = User::find(Auth::id());

//     if (!Hash::check($request->password, $user->password)) {
//         return redirect()->route('profile.edit')->with('error', 'Password tidak valid.');
//     }

//     $user->delete();

//     return redirect('/')->with('success', 'Akun Anda telah dihapus.');
// }


}

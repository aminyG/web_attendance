<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewAdminMail;

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

    // 2️⃣ Generate password random
    $passwordPlain = Str::random(10);

    // 3️⃣ Simpan ke database (hash)
    $user = \App\Models\User::create([
        'name'     => $validated['name'],
        'email'    => $validated['email'],
        'password' => Hash::make($passwordPlain),
    ]);

    // 4️⃣ Assign Role
    $user->assignRole('admin');

    // 5️⃣ Kirim Email
    Mail::to($user->email)->send(new NewAdminMail($user, $passwordPlain));

    // 6️⃣ Redirect dengan flash message
    return redirect()->route('superadmin.dashboard')
        ->with('success', "Admin berhasil dibuat.\nEmail: {$validated['email']}\nPassword: {$passwordPlain}\n(PASSWORD JUGA DIKIRIM KE EMAIL ADMIN)");
}
}

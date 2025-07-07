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
    $validated = $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
    ]);

    $password = Str::random(8);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($password),
    ]);

    $user->assignRole('admin');

    // Kirim email ke admin baru
    Mail::to($user->email)->send(new NewAdminMail($user, $password));

    return redirect()->route('superadmin.dashboard')->with('success', 'Admin berhasil dibuat!');
}

}

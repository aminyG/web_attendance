<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SuperAdminDashboardController extends Controller
{
    public function index()
{
    $admins = User::role('admin')->get();
    return view('superadmin.dashboard', compact('admins'));
}
}

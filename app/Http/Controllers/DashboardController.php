<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Category;
use App\Models\Location;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     return view('dashboard.index', [
    //         'employeeCount' => Employee::count(),
    //         'categoryCount' => Category::count(),
    //         'locationCount' => Location::count(),
    //         'todayAttendanceCount' => Attendance::whereDate('date', today())->count(),
    //         'todayAttendanceStatus' => Attendance::whereDate('date', today())
    //             ->select('status', DB::raw('count(*) as total'))
    //             ->groupBy('status')
    //             ->pluck('total', 'status'),
    //         'latestAttendances' => Attendance::with('employee.category')
    //             ->latest('date')
    //             ->latest('time')
    //             ->take(5)
    //             ->get(),
    //     ]);
    // }
    public function index()
{
    // Mendapatkan user yang sedang autentikasi
    $user = auth()->user();

    // Pastikan user ada dan terautentikasi
    if (!$user) {
        Log::error('User tidak terautentikasi.');
        return redirect()->route('login')->with('error', 'User not authenticated');
    }

    // Log informasi user yang sedang autentikasi
    Log::info('User yang mengakses dashboard:', ['user' => $user->toArray()]);

    // Hitung jumlah Employee, hanya yang terkait dengan user
    $employeeCount = Employee::where('user_id', $user->id)->count();

    // Hitung jumlah Category, hanya yang terkait dengan user
    $categoryCount = Category::where('user_id', $user->id)->count();

    // Hitung jumlah Location, hanya yang terkait dengan user
    $locationCount = Location::where('user_id', $user->id)->count();

    // Hitung jumlah Attendance hari ini, hanya yang terkait dengan user melalui Employee
    $todayAttendanceCount = Attendance::whereHas('employee', function ($query) use ($user) {
        $query->where('user_id', $user->id);
    })
    ->whereDate('date', today())
    ->count();

    // Hitung status Attendance hari ini, hanya yang terkait dengan user melalui Employee
    $todayAttendanceStatus = Attendance::whereHas('employee', function ($query) use ($user) {
        $query->where('user_id', $user->id);
    })
    ->whereDate('date', today())
    ->select('status', DB::raw('count(*) as total'))
    ->groupBy('status')
    ->pluck('total', 'status');

    // Ambil 5 Attendance terbaru, hanya yang terkait dengan user melalui Employee
    $latestAttendances = Attendance::with('employee.category')
        ->whereHas('employee', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->latest('date')
        ->latest('time')
        ->take(5)
        ->get();

    return view('dashboard.index', compact(
        'employeeCount',
        'categoryCount',
        'locationCount',
        'todayAttendanceCount',
        'todayAttendanceStatus',
        'latestAttendances'
    ));
}

}
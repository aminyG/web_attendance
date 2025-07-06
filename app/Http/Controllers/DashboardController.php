<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Category;
use App\Models\Location;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'employeeCount' => Employee::count(),
            'categoryCount' => Category::count(),
            'locationCount' => Location::count(),
            'todayAttendanceCount' => Attendance::whereDate('date', today())->count(),
            'todayAttendanceStatus' => Attendance::whereDate('date', today())
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status'),
            'latestAttendances' => Attendance::with('employee.category')
                ->latest('date')
                ->latest('time')
                ->take(5)
                ->get(),
        ]);
    }
}
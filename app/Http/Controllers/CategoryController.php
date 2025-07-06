<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Employee;

class CategoryController extends Controller
{

    public function index()
    {
        $users = Employee::all(); // asumsi User model dipakai untuk admin/karyawan
        // return view('category.index', compact('users'));
        return view('category.index', compact('categories'));

    }

    public function update(Request $request)
    {
        // $categories = $request->input('categories');
        $categories = Employee::select('category')->distinct()->pluck('category');

        foreach ($categories as $userId => $category) {
            $user = Employee::find($userId);
            if ($user) {
                $user->category = $category;
                $user->save();
            }
        }

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui');
    }
    public function showAttendanceSettings()
    {
        $categories = Category::all();
        return view('category.attendance_settings', compact('categories'));
    }
    public function updateAttendance(Request $request)
    {
        foreach ($request->attendances as $categoryId => $attendanceCount) {
            $category = Category::find($categoryId);
            if ($category) {
                $category->required_attendance_per_day = $attendanceCount;
                $category->save();
            }
        }

        return redirect()->route('categories.attendanceSettings')->with('success', 'Pengaturan absensi berhasil disimpan.');
    }


}
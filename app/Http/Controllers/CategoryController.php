<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{

    public function index()
    {
        $users = Employee::all();
        // return view('category.index', compact('users'));
        return view('category.index', compact('categories'));

    }
//     public function index(Request $request)
// {
//     // Mendapatkan user yang sedang autentikasi
//     $user = auth()->user();

//     // Pastikan user ada
//     if (!$user) {
//         return redirect()->route('login')->with('error', 'User not authenticated');
//     }

//     Log::info('Current Auth User:', ['user' => $user->toArray()]);

//     // Ambil kategori berdasarkan user yang sedang login
//     $categories = Category::where('user_id', $user->id)->get(); // Filter kategori berdasarkan admin yang login

//     return view('category.index', compact('categories'));
// }

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
    // public function showAttendanceSettings()
    // {
    //     $categories = Category::all();
    //     return view('category.attendance_settings', compact('categories'));
    // }
    public function showAttendanceSettings()
{
    // Mendapatkan user yang sedang autentikasi
    $user = auth()->user();

    // Pastikan user ada dan terautentikasi
    if (!$user) {
        Log::error('User tidak terautentikasi.');
        return redirect()->route('login')->with('error', 'User not authenticated');
    }

    // Log informasi user yang sedang autentikasi
    Log::info('User yang mengakses pengaturan absensi:', ['user' => $user->toArray()]);

    // Ambil kategori berdasarkan user yang sedang autentikasi
    $categories = Category::where('user_id', $user->id)->get();

    // Log hasil kategori yang ditemukan untuk user
    Log::info('Kategori yang ditemukan untuk user ini:', ['categories' => $categories->toArray()]);

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

//     public function updateAttendance(Request $request)
// {
//     // Mendapatkan user yang sedang autentikasi
//     $user = auth()->user();

//     // Pastikan user ada dan terautentikasi
//     if (!$user) {
//         Log::error('User tidak terautentikasi.');
//         return redirect()->route('login')->with('error', 'User not authenticated');
//     }

//     // Log informasi user yang sedang autentikasi
//     Log::info('User yang melakukan update attendance:', ['user' => $user->toArray()]);

//     // Proses update attendance untuk setiap kategori
//     foreach ($request->attendances as $categoryId => $attendanceCount) {
//         // Log ID kategori dan jumlah absensi yang diupdate
//         Log::info('Mencoba mengupdate kategori:', ['category_id' => $categoryId, 'attendance_count' => $attendanceCount]);

//         $category = Category::find($categoryId);

//         if ($category) {
//             // Log sebelum perubahan data
//             Log::info('Kategori ditemukan. Data sebelum update:', ['category' => $category->toArray()]);

//             // Pastikan kategori milik user yang sedang autentikasi
//             if ($category->user_id !== $user->id) {
//                 Log::warning('User mencoba mengubah kategori yang bukan miliknya.', ['category_id' => $categoryId, 'user_id' => $user->id]);
//                 return redirect()->route('categories.attendanceSettings')->with('error', 'Anda tidak memiliki izin untuk mengubah kategori ini');
//             }

//             // Update jumlah absensi per hari
//             $category->required_attendance_per_day = $attendanceCount;
//             $category->save();

//             // Log setelah perubahan data
//             Log::info('Kategori berhasil diupdate:', ['category' => $category->toArray()]);
//         } else {
//             Log::error('Kategori tidak ditemukan:', ['category_id' => $categoryId]);
//         }
//     }

//     Log::info('Pengaturan absensi berhasil disimpan untuk user:', ['user_id' => $user->id]);
//     return redirect()->route('categories.attendanceSettings')->with('success', 'Pengaturan absensi berhasil disimpan.');
// }


public function getCategoryForUser($userId)
{
    // Fetch the employee by ID
    $employee = Employee::find($userId);

    if (!$employee) {
        return response()->json([
            'message' => 'Employee not found'
        ], 404);
    }

    // Fetch the category associated with the employee
    $category = $employee->category;

    if (!$category) {
        return response()->json([
            'message' => 'Category not found'
        ], 404);
    }

    // Return the category info
    return response()->json([
        'category' => $category
    ]);
}
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Schedule;

class ScheduleController extends Controller
{
public function index()
{
    // Ambil semua kategori beserta jadwal mereka
    $categories = Category::with('schedules')->get();

    // Cek apakah ada jadwal global (yang tidak terkait dengan kategori)
    $allSchedules = Schedule::whereNull('category_id')->get();  // Ambil jadwal yang berlaku untuk semua kategori

    return view('schedule.index', compact('categories', 'allSchedules'));
}
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'order' => 'nullable|integer',
        ]);

        Schedule::create($request->all());

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    }
public function storeAll(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i',
        'order' => 'nullable|integer',
    ]);

    // Menyimpan jadwal untuk semua kategori
    $categories = Category::all();
    foreach ($categories as $category) {
        Schedule::create([
            'category_id' => $category->id,
            'name' => $validated['name'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'order' => $validated['order'] ?? 0,
        ]);
    }
    
    Schedule::create([
        'category_id' => null, 
        'name' => $validated['name'],
        'start_time' => $validated['start_time'],
        'end_time' => $validated['end_time'],
        'order' => $validated['order'] ?? 0,
    ]);

    return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan ke semua kategori dan ditampilkan di tab Semua Kategori.');
}

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }
}

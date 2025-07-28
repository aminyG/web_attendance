<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Schedule;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
// public function index()
// {
//     // Ambil semua kategori beserta jadwal mereka
//     $categories = Category::with('schedules')->get();

//     // Cek apakah ada jadwal global (yang tidak terkait dengan kategori)
//     $allSchedules = Schedule::whereNull('category_id')->get();  // Ambil jadwal yang berlaku untuk semua kategori

//     return view('schedule.index', compact('categories', 'allSchedules'));
// }

public function index()
{
    // Mendapatkan user yang sedang autentikasi
    $user = auth()->user();

    // Pastikan user ada dan terautentikasi
    if (!$user) {
        return redirect()->route('login')->with('error', 'User not authenticated');
    }

    // Log informasi user yang sedang autentikasi
    Log::info('User yang mengakses jadwal:', ['user' => $user->toArray()]);

    // Ambil kategori berdasarkan user yang sedang autentikasi
    $categories = Category::where('user_id', $user->id)
                            ->with('schedules')  // Memuat jadwal yang terkait dengan kategori
                            ->get();

    // Log kategori yang ditemukan untuk user
    Log::info('Kategori yang ditemukan untuk user ini beserta jadwalnya:', ['categories' => $categories->toArray()]);

    // Cek jadwal global (yang tidak terkait dengan kategori)
    $allSchedules = Schedule::whereNull('category_id')->get();  // Ambil jadwal yang berlaku untuk semua kategori

    // Log jadwal global yang ditemukan
    Log::info('Jadwal global ditemukan:', ['allSchedules' => $allSchedules->toArray()]);

    return view('schedule.index', compact('categories', 'allSchedules'));
}


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'category_id' => 'required|exists:categories,id',
    //         'name' => 'required|string',
    //         'start_time' => 'required',
    //         'end_time' => 'required',
    //         'order' => 'nullable|integer',
    //     ]);

    //     Schedule::create($request->all());

    //     return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    // }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'category_id' => 'required|exists:categories,id',
    //         'name' => 'required|string',
    //         'start_time' => 'required|date_format:H:i',
    //         'end_time' => 'required|date_format:H:i',
    //         'order' => 'nullable|integer',
    //     ]);

    //     // Simpan ke database lokal dulu
    //     $schedule = Schedule::create($request->all());

    //     // Kirim ke Presence API
    //     try {
    //         $apiResponse = \Illuminate\Support\Facades\Http::withHeaders([
    //             'Authorization' => 'Bearer ' . session('api_token'),
    //             'Accept' => 'application/json',
    //         ])->post('http://presence.guestallow.com/api/schedules', [
    //             'schedule_name' => $request->name,
    //             'schedule_time_enter' => $request->start_time,
    //             'schedule_time_out' => $request->end_time,
    //             'schedule_type' => 'daily',
    //             'schedule_day' => null,
    //         ]);

    //         if ($apiResponse->successful()) {
    //             Log::info('Schedule created successfully in Presence API', $apiResponse->json());
    //         } else {
    //             Log::error('Failed to create schedule in Presence API', [
    //                 'response' => $apiResponse->json(),
    //                 'status' => $apiResponse->status()
    //             ]);
    //         }

    //     } catch (\Exception $e) {
    //         Log::error('Exception during creating schedule in Presence API', [
    //             'message' => $e->getMessage(),
    //             'schedule_id' => $schedule->id ?? null,
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    // }
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'order' => 'nullable|integer',
        ]);

        // Simpan dulu data lokal TANPA presence_id
        $schedule = Schedule::create($request->only(['category_id', 'name', 'start_time', 'end_time', 'order']));

        // Kirim ke Presence API
        try {
            $apiResponse = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . session('api_token'),
                'Accept' => 'application/json',
            ])->post('http://presence.guestallow.com/api/schedules', [
                'name' => $request->name,
                'schedule_time_enter' => $request->start_time,
                'schedule_time_out' => $request->end_time,
                'schedule_type' => 'daily',
                'schedule_day' => null,
            ]);

            if ($apiResponse->successful()) {
                $presenceId = $apiResponse->json('data.id'); // Pastikan struktur responsenya sesuai

                // Update presence_id di database lokal
                $schedule->update([
                    'presence_id' => $presenceId
                ]);

                Log::info('Schedule created successfully in Presence API', $apiResponse->json());
            } else {
                Log::error('Failed to create schedule in Presence API', [
                    'response' => $apiResponse->json(),
                    'status' => $apiResponse->status()
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Exception during creating schedule in Presence API', [
                'message' => $e->getMessage(),
                'schedule_id' => $schedule->id ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    }


// public function storeAll(Request $request)
// {
//     $validated = $request->validate([
//         'name' => 'required|string|max:255',
//         'start_time' => 'required|date_format:H:i',
//         'end_time' => 'required|date_format:H:i',
//         'order' => 'nullable|integer',
//     ]);

//     // Menyimpan jadwal untuk semua kategori
//     $categories = Category::all();
//     foreach ($categories as $category) {
//         Schedule::create([
//             'category_id' => $category->id,
//             'name' => $validated['name'],
//             'start_time' => $validated['start_time'],
//             'end_time' => $validated['end_time'],
//             'order' => $validated['order'] ?? 0,
//         ]);
//     }
    
//     Schedule::create([
//         'category_id' => null, 
//         'name' => $validated['name'],
//         'start_time' => $validated['start_time'],
//         'end_time' => $validated['end_time'],
//         'order' => $validated['order'] ?? 0,
//     ]);

//     return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan ke semua kategori dan ditampilkan di tab Semua Kategori.');
// }

    // public function destroy($id)
    // {
    //     $schedule = Schedule::findOrFail($id);
    //     $schedule->delete();

    //     return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    // }
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);

        // Coba hapus dari Presence API jika presence_id ada
        if ($schedule->presence_id) {
            try {
                $apiResponse = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'Bearer ' . session('api_token'),
                    'Accept' => 'application/json',
                ])->delete('http://presence.guestallow.com/api/schedules/' . $schedule->presence_id);

                if ($apiResponse->successful()) {
                    Log::info('Schedule deleted from Presence API', ['presence_id' => $schedule->presence_id]);
                } else {
                    Log::warning('Failed to delete schedule in Presence API', [
                        'response' => $apiResponse->json(),
                        'status' => $apiResponse->status()
                    ]);
                }

            } catch (\Exception $e) {
                Log::error('Exception during deleting schedule in Presence API', [
                    'message' => $e->getMessage(),
                    'presence_id' => $schedule->presence_id,
                ]);
            }
        }

        // Hapus dari database lokal
        $schedule->delete();

        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }

}
